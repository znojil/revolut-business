<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Tests\Cases\Request;

use Tester\Assert;
use Znojil\RevolutBusiness\Request\GetLabelGroupsRequest;

require __DIR__ . '/../../bootstrap.php';

/**
 * @testCase
 */
final class GetLabelGroupsRequestTest extends \Tester\TestCase{

	public function testConfiguration(): void{
		$request = new GetLabelGroupsRequest(100, 'page-token');
		Assert::same('GET', $request->getMethod());
		Assert::same('label-groups?limit=100&page_token=page-token', $request->getUrn());

		// default properties
		Assert::same('label-groups', (new GetLabelGroupsRequest)->getUrn());
	}

	public function testCreateResponse(): void{
		$result = (new GetLabelGroupsRequest)
			->createResponse(\Znojil\RevolutBusiness\Tests\Fixtures\ResponseFactory::create('accounting/label-groups'));

		Assert::same('MjAyNi0wMS0wMVQwOToxMzoxNS40MDZaN2RhNTFjZGY0LTZiYjctNDRkNi04OWU1LTc2OWEzZGYxZDc5ZA', $result->nextPageToken);
		Assert::count(2, $result->items);

		Assert::same('f0e1d2c3-b4a5-4678-9012-3456789abcde', $result->items[0]->id);
		Assert::same('Department', $result->items[0]->name);
		Assert::same('2026-03-19T07:15:00+00:00', $result->items[0]->createdAt->format('c'));
		Assert::same('2026-03-19T08:45:22+00:00', $result->items[0]->updatedAt->format('c'));

		Assert::same('Employee', $result->items[1]->name);
	}

}

(new GetLabelGroupsRequestTest)->run();
