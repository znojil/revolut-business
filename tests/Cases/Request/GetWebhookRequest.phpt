<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Tests\Cases\Request;

use Tester\Assert;
use Znojil\RevolutBusiness\Request\GetWebhookRequest;

require __DIR__ . '/../../bootstrap.php';

/**
 * @testCase
 */
final class GetWebhookRequestTest extends \Tester\TestCase{

	public function testConfiguration(): void{
		$request = new GetWebhookRequest('6fc346be-5cb5-4c14-aadc-e8aba6a655d7');

		Assert::same(\Znojil\RevolutBusiness\Http\ApiVersion::V2, $request->getApiVersion());
		Assert::same('GET', $request->getMethod());
		Assert::same('webhooks/6fc346be-5cb5-4c14-aadc-e8aba6a655d7', $request->getUrn());
	}

	public function testCreateResponse(): void{
		$result = (new GetWebhookRequest('6fc346be-5cb5-4c14-aadc-e8aba6a655d7'))
			->createResponse(\Znojil\RevolutBusiness\Tests\Fixtures\ResponseFactory::create('webhooks/webhook-with-secret'));

		Assert::same('6fc346be-5cb5-4c14-aadc-e8aba6a655d7', $result->id);
		Assert::same('wsk_r59a4HfWVAKycbCaNO1RvgCJec02gRd8', $result->signingSecret);
	}

}

(new GetWebhookRequestTest)->run();
