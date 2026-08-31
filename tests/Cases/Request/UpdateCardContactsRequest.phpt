<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Tests\Cases\Request;

use Tester\Assert;
use Znojil\RevolutBusiness\Request\UpdateCardContactsRequest;

require __DIR__ . '/../../bootstrap.php';

/**
 * @testCase
 */
final class UpdateCardContactsRequestTest extends \Tester\TestCase{

	public function testConfiguration(): void{
		$request = new UpdateCardContactsRequest('123e4567-e89b-12d3-a456-426614174000', [
			'3ea984a5-d599-4c97-b2b3-c6c12bb9b5e9',
			'02b969cb-b984-4a70-873d-f93220805e5e'
		]);

		Assert::same('PUT', $request->getMethod());
		Assert::same('cards/123e4567-e89b-12d3-a456-426614174000/contacts', $request->getUrn());
		Assert::same(['Content-Type' => 'application/json'], $request->getHeaders());
		Assert::same(['3ea984a5-d599-4c97-b2b3-c6c12bb9b5e9', '02b969cb-b984-4a70-873d-f93220805e5e'], $request->getData());
	}

	public function testCreateResponse(): void{
		Assert::same(
			['3ea984a5-d599-4c97-b2b3-c6c12bb9b5e9', '02b969cb-b984-4a70-873d-f93220805e5e'],
			(new UpdateCardContactsRequest('123e4567-e89b-12d3-a456-426614174000', [
				'3ea984a5-d599-4c97-b2b3-c6c12bb9b5e9',
				'02b969cb-b984-4a70-873d-f93220805e5e'
			]))
				->createResponse(new \Znojil\Http\Message\Response(200, body: '["3ea984a5-d599-4c97-b2b3-c6c12bb9b5e9","02b969cb-b984-4a70-873d-f93220805e5e"]'))
		);
	}

}

(new UpdateCardContactsRequestTest)->run();
