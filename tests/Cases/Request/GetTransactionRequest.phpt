<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Tests\Cases\Request;

use Tester\Assert;
use Znojil\RevolutBusiness\Enum;
use Znojil\RevolutBusiness\Request\GetTransactionRequest;

require __DIR__ . '/../../bootstrap.php';

/**
 * @testCase
 */
final class GetTransactionRequestTest extends \Tester\TestCase{

	public function testConfiguration(): void{
		$request = new GetTransactionRequest('640dc0b7-1234-aaaa-1234-92ac4de1dacd');

		Assert::same('GET', $request->getMethod());
		Assert::same('transaction/640dc0b7-1234-aaaa-1234-92ac4de1dacd', $request->getUrn());
	}

	public function testCreateResponse(): void{
		$result = (new GetTransactionRequest('63d2a8bd-8b67-a2de-b1d2-b58ee21d7073'))
			->createResponse(\Znojil\RevolutBusiness\Tests\Fixtures\ResponseFactory::create('transactions/transaction'));

		Assert::same('63d2a8bd-8b67-a2de-b1d2-b58ee21d7073', $result->id);
		Assert::same(Enum\TransactionType::CardPayment, $result->type);
		Assert::same(Enum\TransactionState::Completed, $result->state);
		Assert::same('6a8b2ad9-d8b9-4348-9207-1c5737ccf11b', $result->requestId);
		Assert::same('To John Doe', $result->reference);
		Assert::same('9a6434d8-3581-4faa-988b-48875e785be7', $result->relatedTransactionId);
		Assert::same('2023-01-26T16:22:25+00:00', $result->completedAt?->format('c'));
		Assert::null($result->scheduledFor);
		Assert::null($result->reasonCode);

		Assert::same('Best Printers Company', $result->merchant?->name);
		Assert::same('BPC*1234MN1HA2', $result->merchant?->fullName);
		Assert::same('London', $result->merchant?->city);
		Assert::same('5943', $result->merchant?->categoryCode);
		Assert::same('GBR', $result->merchant?->country); // three letters here, unlike everywhere else

		Assert::count(1, $result->legs);
		$leg = $result->legs[0];
		Assert::same(-10.0, $leg->amount);
		Assert::same(0.25, $leg->fee);
		Assert::same(-11.5, $leg->billAmount);
		Assert::same(Enum\Currency::Eur, $leg->billCurrency);
		Assert::same(3161.89, $leg->balance);
		Assert::same(Enum\TransactionCounterpartyAccountType::External, $leg->counterparty?->accountType);
		Assert::same('ff29e658-f07f-4d81-bc0f-7ad0ff141357', $leg->counterparty?->accountId);
		Assert::same('To John Doe', $leg->description);

		Assert::same('424242******4242', $result->card?->cardNumber);
		Assert::same('John', $result->card?->firstName);
		Assert::count(1, $result->card->references ?? []);
		Assert::same('Cost centre', $result->card?->references[0]->name);
		Assert::same('HR', $result->card?->references[0]->value);

		Assert::same('224466', $result->authCode);
	}

}

(new GetTransactionRequestTest)->run();
