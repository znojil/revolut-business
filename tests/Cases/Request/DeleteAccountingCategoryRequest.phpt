<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Tests\Cases\Request;

use Tester\Assert;

require __DIR__ . '/../../bootstrap.php';

/**
 * @testCase
 */
final class DeleteAccountingCategoryRequestTest extends \Tester\TestCase{

	public function testConfiguration(): void{
		$request = new \Znojil\RevolutBusiness\Request\DeleteAccountingCategoryRequest('category-id');

		Assert::same('DELETE', $request->getMethod());
		Assert::same('accounting-categories/category-id', $request->getUrn());
	}

}

(new DeleteAccountingCategoryRequestTest)->run();
