<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Tests\Cases\Request;

use Tester\Assert;
use Znojil\RevolutBusiness\Request\GetDepartmentRequest;

require __DIR__ . '/../../bootstrap.php';

/**
 * @testCase
 */
final class GetDepartmentRequestTest extends \Tester\TestCase{

	public function testConfiguration(): void{
		$request = new GetDepartmentRequest('a1b2c3d4-e5f6-4a5b-bc6d-7e8f9a0b1c2d');

		Assert::same('GET', $request->getMethod());
		Assert::same('departments/a1b2c3d4-e5f6-4a5b-bc6d-7e8f9a0b1c2d', $request->getUrn());
	}

	public function testCreateResponse(): void{
		$result = (new GetDepartmentRequest('a1b2c3d4-e5f6-4a5b-bc6d-7e8f9a0b1c2d'))
			->createResponse(\Znojil\RevolutBusiness\Tests\Fixtures\ResponseFactory::create('teams/department'));

		Assert::same('a1b2c3d4-e5f6-4a5b-bc6d-7e8f9a0b1c2d', $result->id);
		Assert::same('Engineering', $result->name);
		Assert::same('2026-01-15T10:20:30+00:00', $result->createdAt->format('c'));
		Assert::same('2026-03-10T14:45:12+00:00', $result->updatedAt->format('c'));
	}

}

(new GetDepartmentRequestTest)->run();
