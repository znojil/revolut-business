<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Tests\Cases\Request;

use Tester\Assert;
use Znojil\Http\Message\Response;
use Znojil\RevolutBusiness\Request\CreateDepartmentRequest;

require __DIR__ . '/../../bootstrap.php';

/**
 * @testCase
 */
final class CreateDepartmentRequestTest extends \Tester\TestCase{

	public function testConfiguration(): void{
		$request = new CreateDepartmentRequest('Engineering');

		Assert::same('POST', $request->getMethod());
		Assert::same('departments', $request->getUrn());
		Assert::same(['Content-Type' => 'application/json'], $request->getHeaders());
		Assert::same(['name' => 'Engineering'], $request->getData());
	}

	public function testCreateResponse(): void{
		Assert::same(
			'a1b2c3d4-e5f6-4a5b-bc6d-7e8f9a0b1c2d',
			(new CreateDepartmentRequest('Engineering'))
				->createResponse(new Response(201, body: '{"id":"a1b2c3d4-e5f6-4a5b-bc6d-7e8f9a0b1c2d"}'))
		);
	}

}

(new CreateDepartmentRequestTest)->run();
