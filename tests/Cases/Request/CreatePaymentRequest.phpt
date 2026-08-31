<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Tests\Cases\Request;

use Tester\Assert;
use Znojil\RevolutBusiness\DTO\PaymentReceiverDTO;
use Znojil\RevolutBusiness\Enum;
use Znojil\RevolutBusiness\Request\CreatePaymentRequest;

require __DIR__ . '/../../bootstrap.php';

/**
 * @testCase
 */
final class CreatePaymentRequestTest extends \Tester\TestCase{

	public function testConfiguration(): void{
		$request = new CreatePaymentRequest(
			'af98333c-ea53-482b-93c2-1fa5e4eae671',
			new PaymentReceiverDTO('49c6a48b-6b58-40a0-b974-0b8c4888c8a7', '9116f03a-c074-4585-b261-18a706b3768b', 'card-id'),
			10.0,
			Enum\Currency::Eur,
			'A1pH4num3ric',
			'To John Doe',
			Enum\ChargeBearer::Debtor,
			Enum\TransferReasonCode::Services,
			Enum\ExchangeReasonCode::Payroll,
			'ABCDEF12G34H567I',
			'a1b2c3d4-e5f6-7890-abcd-ef1234567890'
		);
		Assert::same('POST', $request->getMethod());
		Assert::same('pay', $request->getUrn());
		Assert::same(['Content-Type' => 'application/json'], $request->getHeaders());
		Assert::same([
			'request_id' => 'A1pH4num3ric',
			'account_id' => 'af98333c-ea53-482b-93c2-1fa5e4eae671',
			'receiver' => [
				'counterparty_id' => '49c6a48b-6b58-40a0-b974-0b8c4888c8a7',
				'account_id' => '9116f03a-c074-4585-b261-18a706b3768b',
				'card_id' => 'card-id'
			],
			'amount' => 10.0,
			'currency' => 'EUR',
			'reference' => 'To John Doe',
			'charge_bearer' => 'debtor',
			'transfer_reason_code' => 'services',
			'exchange_reason_code' => 'payroll',
			'fiscal_code' => 'ABCDEF12G34H567I',
			'name_validation_id' => 'a1b2c3d4-e5f6-7890-abcd-ef1234567890'
		], $request->getData());

		// default properties
		Assert::same([
			'request_id' => 'A1pH4num3ric',
			'account_id' => 'af98333c-ea53-482b-93c2-1fa5e4eae671',
			'receiver' => ['counterparty_id' => '49c6a48b-6b58-40a0-b974-0b8c4888c8a7'],
			'amount' => 15.0,
			'currency' => 'GBP'
		], (new CreatePaymentRequest(
			'af98333c-ea53-482b-93c2-1fa5e4eae671',
			new PaymentReceiverDTO('49c6a48b-6b58-40a0-b974-0b8c4888c8a7', null, null),
			15,
			Enum\Currency::Gbp,
			'A1pH4num3ric'
		))->getData());
	}

	public function testCreateResponse(): void{
		$result = (new CreatePaymentRequest(
			'05018b0d-e67c-4fec-bea6-415e9da9432c',
			new PaymentReceiverDTO('7e18625a-3e6c-4d4f-8429-216c25309a5f', null, null),
			100.0,
			Enum\Currency::Gbp,
			'request-id'
		))->createResponse(\Znojil\RevolutBusiness\Tests\Fixtures\ResponseFactory::create('transfers/transfer-result'));

		Assert::same('9a6434d8-3581-4faa-988b-48875e785be7', $result->id);
		Assert::same(Enum\TransactionState::Pending, $result->state);
		Assert::same('2023-04-06T12:21:49+00:00', $result->createdAt->format('c'));
		Assert::null($result->completedAt); // still pending
	}

}

(new CreatePaymentRequestTest)->run();
