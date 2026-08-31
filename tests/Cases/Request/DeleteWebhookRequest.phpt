<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Tests\Cases\Request;

use Tester\Assert;

require __DIR__ . '/../../bootstrap.php';

/**
 * @testCase
 */
final class DeleteWebhookRequestTest extends \Tester\TestCase{

	public function testConfiguration(): void{
		$request = new \Znojil\RevolutBusiness\Request\DeleteWebhookRequest('6fc346be-5cb5-4c14-aadc-e8aba6a655d7');

		Assert::same(\Znojil\RevolutBusiness\Http\ApiVersion::V2, $request->getApiVersion());
		Assert::same('DELETE', $request->getMethod());
		Assert::same('webhooks/6fc346be-5cb5-4c14-aadc-e8aba6a655d7', $request->getUrn());
	}

}

(new DeleteWebhookRequestTest)->run();
