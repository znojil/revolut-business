<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Tests\Cases\Request;

use Tester\Assert;
use Znojil\RevolutBusiness\Request\GetAccountingCategoryRequest;

require __DIR__ . '/../../bootstrap.php';

/**
 * @testCase
 */
final class GetAccountingCategoryRequestTest extends \Tester\TestCase{

	public function testConfiguration(): void{
		$request = new GetAccountingCategoryRequest('8d831292-f950-4c5a-83f9-030df8cacd3f');

		Assert::same('GET', $request->getMethod());
		Assert::same('accounting-categories/8d831292-f950-4c5a-83f9-030df8cacd3f', $request->getUrn());
	}

	public function testCreateResponse(): void{
		$result = (new GetAccountingCategoryRequest('8d831292-f950-4c5a-83f9-030df8cacd3f'))
			->createResponse(\Znojil\RevolutBusiness\Tests\Fixtures\ResponseFactory::create('accounting/accounting-category'));

		Assert::same('8d831292-f950-4c5a-83f9-030df8cacd3f', $result->id);
		Assert::same('Transportation', $result->name);
		Assert::same('400', $result->code);
		Assert::same('2026-03-11T17:03:25+00:00', $result->createdAt->format('c'));
		Assert::same('2026-03-11T17:03:25+00:00', $result->updatedAt->format('c'));
		Assert::same('e6b84ddb-a127-466b-9a0a-501bda07773f', $result->defaultTaxRateId);
	}

}

(new GetAccountingCategoryRequestTest)->run();
