<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Tests\Cases\Request;

use Tester\Assert;
use Znojil\RevolutBusiness\DTO;
use Znojil\RevolutBusiness\Enum;
use Znojil\RevolutBusiness\Request\CreatePaymentDraftPaymentRequest;

require __DIR__ . '/../../bootstrap.php';

/**
 * @testCase
 */
final class CreatePaymentDraftPaymentRequestTest extends \Tester\TestCase{

	public function testConfiguration(): void{
		$request = new CreatePaymentDraftPaymentRequest('e7e54cb2-861a-aaaa-80e9-3e6600f3db10', $this->getPayment());

		Assert::same('POST', $request->getMethod());
		Assert::same('payment-drafts/e7e54cb2-861a-aaaa-80e9-3e6600f3db10/payments', $request->getUrn());
		Assert::same(['Content-Type' => 'application/json'], $request->getHeaders());
		Assert::same([
			'account_id' => '05018b0d-e67c-4fec-bea6-415e9da9432c',
			'receiver' => [
				'counterparty_id' => '7e18625a-3e6c-4d4f-8429-216c25309a5f',
				'card_id' => '8a7b6c5d-4e3f-4a1b-8c9d-0e1f2a3b4c5d'
			],
			'amount' => 123.45,
			'currency' => 'GBP',
			'reference' => 'Invoice 2026/03'
		], $request->getData());
	}

	public function testCreateResponse(): void{
		Assert::same(
			'645a7696-22f3-aa47-9c74-cbae0449cc46',
			(new CreatePaymentDraftPaymentRequest('draft-id', $this->getPayment()))
				->createResponse(new \Znojil\Http\Message\Response(201, body: '{"id":"645a7696-22f3-aa47-9c74-cbae0449cc46"}'))
		);
	}

	private function getPayment(): DTO\DraftPaymentDTO{
		return new DTO\DraftPaymentDTO(
			'05018b0d-e67c-4fec-bea6-415e9da9432c',
			new DTO\PaymentReceiverDTO('7e18625a-3e6c-4d4f-8429-216c25309a5f', null, '8a7b6c5d-4e3f-4a1b-8c9d-0e1f2a3b4c5d'),
			123.45,
			Enum\Currency::Gbp,
			'Invoice 2026/03'
		);
	}

}

(new CreatePaymentDraftPaymentRequestTest)->run();
