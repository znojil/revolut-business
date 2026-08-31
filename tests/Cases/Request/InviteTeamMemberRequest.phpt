<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Tests\Cases\Request;

use Tester\Assert;
use Znojil\RevolutBusiness\Request\InviteTeamMemberRequest;

require __DIR__ . '/../../bootstrap.php';

/**
 * @testCase
 */
final class InviteTeamMemberRequestTest extends \Tester\TestCase{

	public function testConfiguration(): void{
		$request = new InviteTeamMemberRequest('invited@example.com', 'b7ec67d3-5af1-42c8-bece-3d28nlmo894d');

		Assert::same('POST', $request->getMethod());
		Assert::same('team-members', $request->getUrn());
		Assert::same(['Content-Type' => 'application/json'], $request->getHeaders());
		Assert::same([
			'email' => 'invited@example.com',
			'role_id' => 'b7ec67d3-5af1-42c8-bece-3d28nlmo894d'
		], $request->getData());
	}

	public function testCreateResponse(): void{
		$result = (new InviteTeamMemberRequest('invited@example.com', 'b7ec67d3-5af1-42c8-bece-3d28nlmo894d'))
			->createResponse(\Znojil\RevolutBusiness\Tests\Fixtures\ResponseFactory::create('teams/team-member-invitation'));

		Assert::same('0e1a8d4b-1d1e-457d-9f10-3e7007a82ea8', $result->id);
		Assert::same('invited@example.com', $result->email);
		Assert::same('b7ec67d3-5af1-42c8-bece-3d28nlmo894d', $result->roleId);
		Assert::same('2026-03-19T07:15:00+00:00', $result->createdAt->format('c'));
	}

}

(new InviteTeamMemberRequestTest)->run();
