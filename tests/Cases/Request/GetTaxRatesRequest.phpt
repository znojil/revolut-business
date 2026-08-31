<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Tests\Cases\Request;

use Tester\Assert;
use Znojil\RevolutBusiness\Request\GetTaxRatesRequest;

require __DIR__ . '/../../bootstrap.php';

/**
 * @testCase
 */
final class GetTaxRatesRequestTest extends \Tester\TestCase{

	public function testConfiguration(): void{
		$request = new GetTaxRatesRequest(100, 'page-token');
		Assert::same('GET', $request->getMethod());
		Assert::same('tax-rates?limit=100&page_token=page-token', $request->getUrn());

		// default properties
		Assert::same('tax-rates', (new GetTaxRatesRequest)->getUrn());
	}

	public function testCreateResponse(): void{
		$result = (new GetTaxRatesRequest)
			->createResponse(\Znojil\RevolutBusiness\Tests\Fixtures\ResponseFactory::create('accounting/tax-rates'));

		Assert::same('MjAyNi0wMS0wMVQwOToxMzoxNS40MDZaN2RhNTFjZGY0LTZiYjctNDRkNi04OWU1LTc2OWEzZGYxZDc5ZA', $result->nextPageToken);
		Assert::count(2, $result->items);

		Assert::same('f09ad1ad3-a1c0-4c09-b8f8-24b439e72690', $result->items[0]->id);
		Assert::same('20% (VAT on Expenses)', $result->items[0]->name);
		Assert::same(20.0, $result->items[0]->percentage); // the API sends a whole number, the DTO widens it to float
		Assert::same('2026-03-11T10:33:51+00:00', $result->items[0]->createdAt->format('c'));

		// only the required properties
		Assert::same('Exempt Financial Services', $result->items[1]->name);
		Assert::null($result->items[1]->percentage);
	}

}

(new GetTaxRatesRequestTest)->run();
