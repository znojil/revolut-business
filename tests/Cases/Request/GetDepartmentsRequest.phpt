<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Tests\Cases\Request;

use Tester\Assert;
use Znojil\RevolutBusiness\Request\GetDepartmentsRequest;

require __DIR__ . '/../../bootstrap.php';

/**
 * @testCase
 */
final class GetDepartmentsRequestTest extends \Tester\TestCase{

	public function testConfiguration(): void{
		$request = new GetDepartmentsRequest(500, 'page-token');
		Assert::same('GET', $request->getMethod());
		Assert::same('departments?limit=500&page_token=page-token', $request->getUrn());

		// default properties
		Assert::same('departments', (new GetDepartmentsRequest)->getUrn());
	}

	public function testCreateResponse(): void{
		$result = (new GetDepartmentsRequest)
			->createResponse(\Znojil\RevolutBusiness\Tests\Fixtures\ResponseFactory::create('teams/departments'));

		Assert::same('eyJwYWdlIjoyfQ', $result->nextPageToken);
		Assert::count(2, $result->items);

		Assert::same('a1b2c3d4-e5f6-4a5b-bc6d-7e8f9a0b1c2d', $result->items[0]->id);
		Assert::same('Engineering', $result->items[0]->name);

		Assert::same('Finance', $result->items[1]->name);
	}

}

(new GetDepartmentsRequestTest)->run();
