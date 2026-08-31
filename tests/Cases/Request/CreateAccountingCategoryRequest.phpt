<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Tests\Cases\Request;

use Tester\Assert;
use Znojil\RevolutBusiness\Request\CreateAccountingCategoryRequest;

require __DIR__ . '/../../bootstrap.php';

/**
 * @testCase
 */
final class CreateAccountingCategoryRequestTest extends \Tester\TestCase{

	public function testConfiguration(): void{
		$request = new CreateAccountingCategoryRequest('Transportation', '400', 'e6b84ddb-a127-466b-9a0a-501bda07773f');
		Assert::same('POST', $request->getMethod());
		Assert::same('accounting-categories', $request->getUrn());
		Assert::same(['Content-Type' => 'application/json'], $request->getHeaders());
		Assert::same([
			'name' => 'Transportation',
			'code' => '400',
			'default_tax_rate_id' => 'e6b84ddb-a127-466b-9a0a-501bda07773f'
		], $request->getData());

		// default properties
		Assert::same([
			'name' => 'Office supplies',
			'code' => '410'
		], (new CreateAccountingCategoryRequest('Office supplies', '410'))->getData());
	}

	public function testCreateResponse(): void{
		Assert::same(
			'6a37383e-cfd3-4a2f-aa81-e3a6e6939efa',
			(new CreateAccountingCategoryRequest('Office supplies', '410'))
				->createResponse(new \Znojil\Http\Message\Response(201, body: '{"id":"6a37383e-cfd3-4a2f-aa81-e3a6e6939efa"}'))
		);
	}

}

(new CreateAccountingCategoryRequestTest)->run();
