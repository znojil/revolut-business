<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Tests\Cases\Request;

use Tester\Assert;
use Znojil\RevolutBusiness\Enum;
use Znojil\RevolutBusiness\Request\GetAccountRequest;

require __DIR__ . '/../../bootstrap.php';

/**
 * @testCase
 */
final class GetAccountRequestTest extends \Tester\TestCase{

	public function testConfiguration(): void{
		$request = new GetAccountRequest('b7ec67d3-5af1-42c8-bece-3d28nlmo894d');

		Assert::same('GET', $request->getMethod());
		Assert::same('accounts/b7ec67d3-5af1-42c8-bece-3d28nlmo894d', $request->getUrn());
	}

	public function testCreateResponse(): void{
		$result = (new GetAccountRequest('b7ec67d3-5af1-42c8-bece-3d28nlmo894d'))
			->createResponse(\Znojil\RevolutBusiness\Tests\Fixtures\ResponseFactory::create('accounts/account'));

		Assert::same('b7ec67d3-5af1-42c8-bece-3d28nlmo894d', $result->id);
		Assert::same('International account', $result->name);
		Assert::same(3171.89, $result->balance);
		Assert::same(Enum\Currency::Gbp, $result->currency);
		Assert::same(Enum\AccountState::Active, $result->state);
		Assert::false($result->public);
		Assert::same('2022-08-05T14:29:22+00:00', $result->createdAt->format('c'));
		Assert::same('2022-08-05T14:29:22+00:00', $result->updatedAt->format('c'));
	}

}

(new GetAccountRequestTest)->run();
