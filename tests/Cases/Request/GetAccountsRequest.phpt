<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Tests\Cases\Request;

use Tester\Assert;
use Znojil\RevolutBusiness\Enum;
use Znojil\RevolutBusiness\Request\GetAccountsRequest;

require __DIR__ . '/../../bootstrap.php';

/**
 * @testCase
 */
final class GetAccountsRequestTest extends \Tester\TestCase{

	public function testConfiguration(): void{
		$request = new GetAccountsRequest;

		Assert::same('GET', $request->getMethod());
		Assert::same('accounts', $request->getUrn());
	}

	public function testCreateResponse(): void{
		$result = (new GetAccountsRequest)
			->createResponse(\Znojil\RevolutBusiness\Tests\Fixtures\ResponseFactory::create('accounts/accounts'));

		Assert::count(2, $result);

		Assert::same('b7ec67d3-5af1-42c8-bece-3d28nlmo894d', $result[0]->id);
		Assert::same('International account', $result[0]->name);
		Assert::same(3171.89, $result[0]->balance);
		Assert::same(Enum\Currency::Gbp, $result[0]->currency);
		Assert::same(Enum\AccountState::Active, $result[0]->state);
		Assert::false($result[0]->public);
		Assert::same('2022-08-05T14:29:22+00:00', $result[0]->createdAt->format('c'));
		Assert::same('2022-08-05T14:29:22+00:00', $result[0]->updatedAt->format('c'));
		Assert::same(Enum\AccountType::Current, $result[0]->accountType);

		Assert::null($result[1]->name); // only the required properties
		Assert::same(0.0, $result[1]->balance); // a zero balance must survive
		Assert::same('XYZ', $result[1]->currency); // an unknown currency falls back to the raw string
		Assert::same(Enum\AccountState::Inactive, $result[1]->state);
		Assert::same(Enum\AccountType::Savings, $result[1]->accountType);
	}

}

(new GetAccountsRequestTest)->run();
