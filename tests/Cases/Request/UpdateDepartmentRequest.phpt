<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Tests\Cases\Request;

use Tester\Assert;

require __DIR__ . '/../../bootstrap.php';

/**
 * @testCase
 */
final class UpdateDepartmentRequestTest extends \Tester\TestCase{

	public function testConfiguration(): void{
		$request = new \Znojil\RevolutBusiness\Request\UpdateDepartmentRequest('123e4567-e89b-12d3-a456-426614174000', 'Platform Engineering');

		Assert::same('PATCH', $request->getMethod());
		Assert::same('departments/123e4567-e89b-12d3-a456-426614174000', $request->getUrn());
		Assert::same(['Content-Type' => 'application/json'], $request->getHeaders());
		Assert::same(['name' => 'Platform Engineering'], $request->getData());
	}

}

(new UpdateDepartmentRequestTest)->run();
