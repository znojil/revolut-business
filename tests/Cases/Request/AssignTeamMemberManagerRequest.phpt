<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Tests\Cases\Request;

use Tester\Assert;

require __DIR__ . '/../../bootstrap.php';

/**
 * @testCase
 */
final class AssignTeamMemberManagerRequestTest extends \Tester\TestCase{

	public function testConfiguration(): void{
		$request = new \Znojil\RevolutBusiness\Request\AssignTeamMemberManagerRequest('7e18625a-3e6c-4d4f-8429-216c25309a5f', '0e1a8d4b-1d1e-457d-9f10-3e7007a82ea8');

		Assert::same('PUT', $request->getMethod());
		Assert::same('team-members/7e18625a-3e6c-4d4f-8429-216c25309a5f/manager', $request->getUrn());
		Assert::same(['Content-Type' => 'application/json'], $request->getHeaders());
		Assert::same(['manager_id' => '0e1a8d4b-1d1e-457d-9f10-3e7007a82ea8'], $request->getData());
	}

}

(new AssignTeamMemberManagerRequestTest)->run();
