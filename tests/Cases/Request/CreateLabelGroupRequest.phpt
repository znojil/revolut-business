<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Tests\Cases\Request;

use Tester\Assert;
use Znojil\RevolutBusiness\Request\CreateLabelGroupRequest;

require __DIR__ . '/../../bootstrap.php';

/**
 * @testCase
 */
final class CreateLabelGroupRequestTest extends \Tester\TestCase{

	public function testConfiguration(): void{
		$request = new CreateLabelGroupRequest('Department', ['Sales & Account Management', 'Product Management']);

		Assert::same('POST', $request->getMethod());
		Assert::same('label-groups', $request->getUrn());
		Assert::same(['Content-Type' => 'application/json'], $request->getHeaders());
		Assert::same([
			'name' => 'Department',
			'labels' => [['name' => 'Sales & Account Management'], ['name' => 'Product Management']]
		], $request->getData());
	}

	public function testCreateResponse(): void{
		Assert::same(
			'6a37383e-cfd3-4a2f-aa81-e3a6e6939efa',
			(new CreateLabelGroupRequest('Department', ['Sales & Account Management']))
				->createResponse(new \Znojil\Http\Message\Response(201, body: '{"id":"6a37383e-cfd3-4a2f-aa81-e3a6e6939efa"}'))
		);
	}

}

(new CreateLabelGroupRequestTest)->run();
