<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Tests\Cases\Request;

use Tester\Assert;
use Znojil\RevolutBusiness\Request\GetAccountingCategoriesRequest;

require __DIR__ . '/../../bootstrap.php';

/**
 * @testCase
 */
final class GetAccountingCategoriesRequestTest extends \Tester\TestCase{

	public function testConfiguration(): void{
		$request = new GetAccountingCategoriesRequest(50, 'page-token');
		Assert::same('GET', $request->getMethod());
		Assert::same('accounting-categories?limit=50&page_token=page-token', $request->getUrn());

		// default properties
		Assert::same('accounting-categories', (new GetAccountingCategoriesRequest)->getUrn());
	}

	public function testCreateResponse(): void{
		$result = (new GetAccountingCategoriesRequest)
			->createResponse(\Znojil\RevolutBusiness\Tests\Fixtures\ResponseFactory::create('accounting/accounting-categories'));

		Assert::same('MjAyNi0wMS0wMVQwOToxMzoxNS40MDZaN2RhNTFjZGY0LTZiYjctNDRkNi04OWU1LTc2OWEzZGYxZDc5ZA', $result->nextPageToken);
		Assert::count(2, $result->items);

		Assert::same('8d831292-f950-4c5a-83f9-030df8cacd3f', $result->items[0]->id);
		Assert::same('Transportation', $result->items[0]->name);
		Assert::same('400', $result->items[0]->code);
		Assert::same('2026-03-11T17:03:25+00:00', $result->items[0]->createdAt->format('c'));
		Assert::same('2026-03-11T17:03:25+00:00', $result->items[0]->updatedAt->format('c'));
		Assert::same('d8f6bf6c-5b70-4c16-9549-5943e67ab009', $result->items[0]->defaultTaxRateId);

		// only the required properties
		Assert::same('8d749b93-4a2e-4f28-8b5b-9d64fe2e1d62', $result->items[1]->id);
		Assert::null($result->items[1]->code);
		Assert::null($result->items[1]->defaultTaxRateId);
	}

}

(new GetAccountingCategoriesRequestTest)->run();
