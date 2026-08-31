<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Tests\Cases\Request;

use Tester\Assert;

require __DIR__ . '/../../bootstrap.php';

/**
 * @testCase
 */
final class AssignTeamMemberDepartmentRequestTest extends \Tester\TestCase{

	public function testConfiguration(): void{
		$request = new \Znojil\RevolutBusiness\Request\AssignTeamMemberDepartmentRequest('7e18625a-3e6c-4d4f-8429-216c25309a5f', 'a1b2c3d4-e5f6-4a5b-bc6d-7e8f9a0b1c2d');

		Assert::same('PUT', $request->getMethod());
		Assert::same('team-members/7e18625a-3e6c-4d4f-8429-216c25309a5f/department', $request->getUrn());
		Assert::same(['Content-Type' => 'application/json'], $request->getHeaders());
		Assert::same(['department_id' => 'a1b2c3d4-e5f6-4a5b-bc6d-7e8f9a0b1c2d'], $request->getData());
	}

}

(new AssignTeamMemberDepartmentRequestTest)->run();
