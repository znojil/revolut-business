<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Tests\Cases\Request;

use Tester\Assert;
use Znojil\RevolutBusiness\Request\GetLabelsRequest;

require __DIR__ . '/../../bootstrap.php';

/**
 * @testCase
 */
final class GetLabelsRequestTest extends \Tester\TestCase{

	public function testConfiguration(): void{
		$request = new GetLabelsRequest('123e4567-e89b-12d3-a456-426614174000', 100, 'page-token');
		Assert::same('GET', $request->getMethod());
		Assert::same('label-groups/123e4567-e89b-12d3-a456-426614174000/labels?limit=100&page_token=page-token', $request->getUrn());

		// default properties
		Assert::same('label-groups/123e4567-e89b-12d3-a456-426614174000/labels', (new GetLabelsRequest('123e4567-e89b-12d3-a456-426614174000'))->getUrn());
	}

	public function testCreateResponse(): void{
		$result = (new GetLabelsRequest('group-id'))
			->createResponse(\Znojil\RevolutBusiness\Tests\Fixtures\ResponseFactory::create('accounting/labels'));

		Assert::null($result->nextPageToken); // the last page does not return a cursor
		Assert::count(2, $result->items);

		Assert::same('f0e1d2c3-b4a5-4678-9012-3456789abcde', $result->items[0]->id);
		Assert::same('Consultants', $result->items[0]->name);
		Assert::same('2026-03-19T07:15:00+00:00', $result->items[0]->createdAt->format('c'));
		Assert::same('2026-03-19T08:45:22+00:00', $result->items[0]->updatedAt->format('c'));

		Assert::same('IT & Tech Support', $result->items[1]->name);
	}

}

(new GetLabelsRequestTest)->run();
