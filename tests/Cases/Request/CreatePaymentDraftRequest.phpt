<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Tests\Cases\Request;

use Tester\Assert;
use Znojil\RevolutBusiness\DTO;
use Znojil\RevolutBusiness\Enum;
use Znojil\RevolutBusiness\Request\CreatePaymentDraftRequest;

require __DIR__ . '/../../bootstrap.php';

/**
 * @testCase
 */
final class CreatePaymentDraftRequestTest extends \Tester\TestCase{

	public function testConfiguration(): void{
		$request = new CreatePaymentDraftRequest(
			[$this->getPayment(Enum\ChargeBearer::Debtor, Enum\TransferReasonCode::Services)],
			'Draft Payment through API',
			new \DateTimeImmutable('2026-06-24 08:00:00')
		);
		Assert::same('POST', $request->getMethod());
		Assert::same('payment-drafts', $request->getUrn());
		Assert::same(['Content-Type' => 'application/json'], $request->getHeaders());
		Assert::same([
			'title' => 'Draft Payment through API',
			'schedule_for' => '2026-06-24', // a date, not a date-time
			'payments' => [[
				'account_id' => '05018b0d-e67c-4fec-bea6-415e9da9432c',
				'receiver' => ['counterparty_id' => '7e18625a-3e6c-4d4f-8429-216c25309a5f'],
				'amount' => 123.45,
				'currency' => 'GBP',
				'reference' => 'Invoice 2026/03',
				'charge_bearer' => 'debtor',
				'transfer_reason_code' => 'services'
			]]
		], $request->getData());

		// default properties
		Assert::same([
			'payments' => [[
				'account_id' => '05018b0d-e67c-4fec-bea6-415e9da9432c',
				'receiver' => ['counterparty_id' => '7e18625a-3e6c-4d4f-8429-216c25309a5f'],
				'amount' => 123.45,
				'currency' => 'GBP',
				'reference' => 'Invoice 2026/03'
			]]
		], (new CreatePaymentDraftRequest([$this->getPayment()]))->getData());
	}

	public function testCreateResponse(): void{
		Assert::same(
			'e7e54cb2-861a-aaaa-80e9-3e6600f3db10',
			(new CreatePaymentDraftRequest([$this->getPayment()]))
				->createResponse(new \Znojil\Http\Message\Response(201, body: '{"id":"e7e54cb2-861a-aaaa-80e9-3e6600f3db10"}'))
		);
	}

	private function getPayment(?Enum\ChargeBearer $chargeBearer = null, ?Enum\TransferReasonCode $transferReasonCode = null): DTO\DraftPaymentDTO{
		return new DTO\DraftPaymentDTO(
			'05018b0d-e67c-4fec-bea6-415e9da9432c',
			new DTO\PaymentReceiverDTO('7e18625a-3e6c-4d4f-8429-216c25309a5f', null, null),
			123.45,
			Enum\Currency::Gbp,
			'Invoice 2026/03',
			$chargeBearer,
			$transferReasonCode
		);
	}

}

(new CreatePaymentDraftRequestTest)->run();
