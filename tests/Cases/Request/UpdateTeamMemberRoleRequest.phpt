<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Tests\Cases\Request;

use Tester\Assert;

require __DIR__ . '/../../bootstrap.php';

/**
 * @testCase
 */
final class UpdateTeamMemberRoleRequestTest extends \Tester\TestCase{

	public function testConfiguration(): void{
		$request = new \Znojil\RevolutBusiness\Request\UpdateTeamMemberRoleRequest('123e4567-e89b-12d3-a456-426614174000', 'a40b6121-350b-4d2e-9ba1-9dab61a18d46');

		// a PUT, unlike the PATCH used for other updates
		Assert::same('PUT', $request->getMethod());
		Assert::same('team-members/123e4567-e89b-12d3-a456-426614174000/role', $request->getUrn());
		Assert::same(['Content-Type' => 'application/json'], $request->getHeaders());
		Assert::same(['role_id' => 'a40b6121-350b-4d2e-9ba1-9dab61a18d46'], $request->getData());
	}

}

(new UpdateTeamMemberRoleRequestTest)->run();
