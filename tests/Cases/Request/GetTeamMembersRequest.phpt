<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Tests\Cases\Request;

use Tester\Assert;
use Znojil\RevolutBusiness\Enum\TeamMemberState;
use Znojil\RevolutBusiness\Request\GetTeamMembersRequest;

require __DIR__ . '/../../bootstrap.php';

/**
 * @testCase
 */
final class GetTeamMembersRequestTest extends \Tester\TestCase{

	public function testConfiguration(): void{
		$request = new GetTeamMembersRequest(new \DateTimeImmutable('2026-03-19 07:15:00.000000', new \DateTimeZone('UTC')), 1000);
		Assert::same('GET', $request->getMethod());
		Assert::same('team-members?created_before=2026-03-19T07%3A15%3A00.000000Z&limit=1000', $request->getUrn());

		// default properties
		Assert::same('team-members', (new GetTeamMembersRequest)->getUrn());
	}

	public function testCreateResponse(): void{
		$result = (new GetTeamMembersRequest)
			->createResponse(\Znojil\RevolutBusiness\Tests\Fixtures\ResponseFactory::create('teams/team-members'));

		Assert::count(2, $result);

		Assert::same('7e18625a-3e6c-4d4f-8429-216c25309a5f', $result[0]->id);
		Assert::same('ray.trenfield@example.com', $result[0]->email);
		Assert::same('Ray', $result[0]->firstName);
		Assert::same('Trenfield', $result[0]->lastName);
		Assert::same(TeamMemberState::Active, $result[0]->state);
		Assert::same('admin', $result[0]->roleId); // role ids are not always UUIDs
		Assert::same('a1b2c3d4-e5f6-4a5b-bc6d-7e8f9a0b1c2d', $result[0]->departmentId);
		Assert::same('0e1a8d4b-1d1e-457d-9f10-3e7007a82ea8', $result[0]->managerId);

		// an invited member has no name yet and belongs nowhere
		Assert::same(TeamMemberState::Waiting, $result[1]->state);
		Assert::null($result[1]->firstName);
		Assert::null($result[1]->lastName);
		Assert::null($result[1]->departmentId);
		Assert::null($result[1]->managerId);
	}

}

(new GetTeamMembersRequestTest)->run();
