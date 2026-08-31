<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Tests\Cases\Request;

use Tester\Assert;
use Znojil\RevolutBusiness\Request\UpdateAccountingCategoryRequest;

require __DIR__ . '/../../bootstrap.php';

/**
 * @testCase
 */
final class UpdateAccountingCategoryRequestTest extends \Tester\TestCase{

	public function testConfiguration(): void{
		$request = new UpdateAccountingCategoryRequest('category-id', 'Custom with tax rate', '998', 'e6b84ddb-a127-466b-9a0a-501bda07773f');
		Assert::same('PATCH', $request->getMethod());
		Assert::same('accounting-categories/category-id', $request->getUrn());
		Assert::same(['Content-Type' => 'application/json'], $request->getHeaders());
		Assert::same([
			'name' => 'Custom with tax rate',
			'code' => '998',
			'default_tax_rate_id' => 'e6b84ddb-a127-466b-9a0a-501bda07773f'
		], $request->getData());

		// optional property
		Assert::same(['code' => '400'], (new UpdateAccountingCategoryRequest('category-id', code: '400'))->getData());
		Assert::same([
			'name' => 'Transportation',
			'code' => '400',
			'default_tax_rate_id' => 'tax-rate-id'
		], (new UpdateAccountingCategoryRequest('category-id', 'Transportation', '400', 'tax-rate-id'))->getData());

		// at least one property must be provided
		Assert::exception(
			fn() => (new UpdateAccountingCategoryRequest('category-id'))->getData(),
			\Znojil\RevolutBusiness\Exception\InvalidArgumentException::class
		);
	}

}

(new UpdateAccountingCategoryRequestTest)->run();
