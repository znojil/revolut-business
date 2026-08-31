<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Tests\Cases\Request;

use Tester\Assert;
use Znojil\RevolutBusiness\Request\GetLabelGroupRequest;

require __DIR__ . '/../../bootstrap.php';

/**
 * @testCase
 */
final class GetLabelGroupRequestTest extends \Tester\TestCase{

	public function testConfiguration(): void{
		$request = new GetLabelGroupRequest('09ad1ad3-a1c0-4c09-b8f8-24b439e72690');

		Assert::same('GET', $request->getMethod());
		Assert::same('label-groups/09ad1ad3-a1c0-4c09-b8f8-24b439e72690', $request->getUrn());
	}

	public function testCreateResponse(): void{
		$result = (new GetLabelGroupRequest('09ad1ad3-a1c0-4c09-b8f8-24b439e72690'))
			->createResponse(\Znojil\RevolutBusiness\Tests\Fixtures\ResponseFactory::create('accounting/label-group'));

		Assert::same('09ad1ad3-a1c0-4c09-b8f8-24b439e72690', $result->id);
		Assert::same('Department', $result->name);
		Assert::same('2026-03-11T10:33:51+00:00', $result->createdAt->format('c'));
		Assert::same('2026-03-11T12:33:51+00:00', $result->updatedAt->format('c'));
	}

}

(new GetLabelGroupRequestTest)->run();
