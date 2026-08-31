<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Tests\Cases\Request;

use Tester\Assert;
use Znojil\RevolutBusiness\Enum;
use Znojil\RevolutBusiness\Request\UpdatePaymentDraftPaymentRequest;

require __DIR__ . '/../../bootstrap.php';

/**
 * @testCase
 */
final class UpdatePaymentDraftPaymentRequestTest extends \Tester\TestCase{

	public function testConfiguration(): void{
		$request = new UpdatePaymentDraftPaymentRequest(
			'draft-id',
			'payment-id',
			new \Znojil\RevolutBusiness\DTO\PaymentReceiverDTO('counterparty-id', 'account-id', null),
			200.0,
			Enum\Currency::Eur,
			'Invoice 2026/04',
			Enum\ChargeBearer::Shared,
			Enum\TransferReasonCode::Services
		);
		Assert::same('PATCH', $request->getMethod());
		Assert::same('payment-drafts/draft-id/payments/payment-id', $request->getUrn());
		Assert::same(['Content-Type' => 'application/json'], $request->getHeaders());
		Assert::same([
			'receiver' => ['counterparty_id' => 'counterparty-id', 'account_id' => 'account-id'],
			'amount' => 200.0,
			'currency' => 'EUR',
			'reference' => 'Invoice 2026/04',
			'charge_bearer' => 'shared',
			'transfer_reason_code' => 'services'
		], $request->getData());

		// Clear::Value & default properties
		Assert::same(
			['charge_bearer' => null],
			(new UpdatePaymentDraftPaymentRequest('draft-id', 'payment-id', chargeBearer: \Znojil\RevolutBusiness\Clear::Value))->getData()
		);

		// at least one property must be provided
		Assert::exception(
			fn() => (new UpdatePaymentDraftPaymentRequest('draft-id', 'payment-id'))->getData(),
			\Znojil\RevolutBusiness\Exception\InvalidArgumentException::class
		);
	}

}

(new UpdatePaymentDraftPaymentRequestTest)->run();
