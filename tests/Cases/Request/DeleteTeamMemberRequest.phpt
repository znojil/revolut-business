<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Tests\Cases\Request;

use Tester\Assert;

require __DIR__ . '/../../bootstrap.php';

/**
 * @testCase
 */
final class DeleteTeamMemberRequestTest extends \Tester\TestCase{

	public function testConfiguration(): void{
		$request = new \Znojil\RevolutBusiness\Request\DeleteTeamMemberRequest('7e18625a-3e6c-4d4f-8429-216c25309a5f');

		Assert::same('DELETE', $request->getMethod());
		Assert::same('team-members/7e18625a-3e6c-4d4f-8429-216c25309a5f', $request->getUrn());
	}

}

(new DeleteTeamMemberRequestTest)->run();
