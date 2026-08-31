<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Tests\Cases\Request;

use Tester\Assert;
use Znojil\RevolutBusiness\Request\GetRolesRequest;

require __DIR__ . '/../../bootstrap.php';

/**
 * @testCase
 */
final class GetRolesRequestTest extends \Tester\TestCase{

	public function testConfiguration(): void{
		$request = new GetRolesRequest(new \DateTimeImmutable('2026-03-19 07:15:00.000000', new \DateTimeZone('UTC')), 1000);
		Assert::same('GET', $request->getMethod());
		Assert::same('roles?created_before=2026-03-19T07%3A15%3A00.000000Z&limit=1000', $request->getUrn());

		// default properties
		Assert::same('roles', (new GetRolesRequest)->getUrn());
	}

	public function testCreateResponse(): void{
		$result = (new GetRolesRequest)
			->createResponse(\Znojil\RevolutBusiness\Tests\Fixtures\ResponseFactory::create('teams/roles'));

		Assert::count(2, $result);
		Assert::same('admin', $result[0]->id); // role ids are plain strings, not necessarily UUIDs
		Assert::same('Administrator', $result[0]->name);
		Assert::same('Bookkeeper', $result[1]->name);
		Assert::same('2026-02-20T12:00:00+00:00', $result[1]->updatedAt->format('c'));
	}

}

(new GetRolesRequestTest)->run();
