<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Tests\Cases\Request;

use Tester\Assert;
use Znojil\RevolutBusiness\Enum;
use Znojil\RevolutBusiness\Request\GetTransactionsRequest;

require __DIR__ . '/../../bootstrap.php';

/**
 * @testCase
 */
final class GetTransactionsRequestTest extends \Tester\TestCase{

	public function testConfiguration(): void{
		$request = new GetTransactionsRequest(
			new \DateTimeImmutable('2023-01-01 00:00:00.000000', new \DateTimeZone('UTC')),
			new \DateTimeImmutable('2023-01-31 23:59:59.999999', new \DateTimeZone('UTC')),
			'05018b0d-e67c-4fec-bea6-415e9da9432c',
			1000,
			Enum\TransactionType::CardPayment,
			'6a8b2ad9-d8b9-4348-9207-1c5737ccf11b',
			[Enum\TransactionState::Completed, Enum\TransactionState::Pending]
		);
		Assert::same('GET', $request->getMethod());
		Assert::same('transactions?from=2023-01-01T00%3A00%3A00.000000Z&to=2023-01-31T23%3A59%3A59.999999Z'
			. '&account=05018b0d-e67c-4fec-bea6-415e9da9432c&count=1000&type=card_payment'
			. '&request_id=6a8b2ad9-d8b9-4348-9207-1c5737ccf11b&state=completed&state=pending', $request->getUrn());

		// default properties
		Assert::same('transactions', (new GetTransactionsRequest)->getUrn());
	}

	public function testCreateResponse(): void{
		$result = (new GetTransactionsRequest)
			->createResponse(\Znojil\RevolutBusiness\Tests\Fixtures\ResponseFactory::create('transactions/transactions'));

		Assert::count(2, $result);

		$transfer = $result[0];
		Assert::same(Enum\TransactionType::Transfer, $transfer->type);
		Assert::same(Enum\TransactionState::Pending, $transfer->state);
		Assert::same('2023-02-01', $transfer->scheduledFor?->format('Y-m-d'));
		Assert::null($transfer->requestId);
		Assert::null($transfer->completedAt);
		Assert::null($transfer->merchant);
		Assert::null($transfer->card);

		// an internal transfer has two legs, one out and one in
		Assert::count(2, $transfer->legs);
		Assert::same(-10.0, $transfer->legs[0]->amount);
		Assert::same(Enum\Currency::Gbp, $transfer->legs[0]->currency);
		Assert::same(11.5, $transfer->legs[1]->amount);
		Assert::same(Enum\Currency::Eur, $transfer->legs[1]->currency);
		// only the required properties on the leg
		Assert::null($transfer->legs[0]->fee);
		Assert::null($transfer->legs[0]->billAmount);
		Assert::null($transfer->legs[0]->billCurrency);
		Assert::null($transfer->legs[0]->balance);
		Assert::null($transfer->legs[0]->counterparty);

		// temp_block is undocumented in the specification but returned by the API
		Assert::same(Enum\TransactionType::TempBlock, $result[1]->type);
		Assert::same(Enum\TransactionState::Declined, $result[1]->state);
		Assert::same('insufficient_balance', $result[1]->reasonCode);
	}

}

(new GetTransactionsRequestTest)->run();
