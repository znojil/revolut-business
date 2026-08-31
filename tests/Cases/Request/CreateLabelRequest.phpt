<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Tests\Cases\Request;

use Tester\Assert;
use Znojil\RevolutBusiness\Request\CreateLabelRequest;

require __DIR__ . '/../../bootstrap.php';

/**
 * @testCase
 */
final class CreateLabelRequestTest extends \Tester\TestCase{

	public function testConfiguration(): void{
		$request = new CreateLabelRequest('123e4567-e89b-12d3-a456-426614174000', 'Communications');

		Assert::same('POST', $request->getMethod());
		Assert::same('label-groups/123e4567-e89b-12d3-a456-426614174000/labels', $request->getUrn());
		Assert::same(['Content-Type' => 'application/json'], $request->getHeaders());
		Assert::same(['name' => 'Communications'], $request->getData());
	}

	public function testCreateResponse(): void{
		Assert::same(
			'6a37383e-cfd3-4a2f-aa81-e3a6e6939efa',
			(new CreateLabelRequest('123e4567-e89b-12d3-a456-426614174000', 'Communications'))
				->createResponse(new \Znojil\Http\Message\Response(201, body: '{"id":"6a37383e-cfd3-4a2f-aa81-e3a6e6939efa"}'))
		);
	}

}

(new CreateLabelRequestTest)->run();
