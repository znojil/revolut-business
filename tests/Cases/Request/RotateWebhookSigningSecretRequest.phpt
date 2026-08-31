<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Tests\Cases\Request;

use Tester\Assert;
use Znojil\RevolutBusiness\Request\RotateWebhookSigningSecretRequest;

require __DIR__ . '/../../bootstrap.php';

/**
 * @testCase
 */
final class RotateWebhookSigningSecretRequestTest extends \Tester\TestCase{

	public function testConfiguration(): void{
		$request = new RotateWebhookSigningSecretRequest('6fc346be-5cb5-4c14-aadc-e8aba6a655d7', 'P3D');

		Assert::same(\Znojil\RevolutBusiness\Http\ApiVersion::V2, $request->getApiVersion());
		Assert::same('POST', $request->getMethod());
		Assert::same('webhooks/6fc346be-5cb5-4c14-aadc-e8aba6a655d7/rotate-signing-secret', $request->getUrn());
		Assert::same(['Content-Type' => 'application/json'], $request->getHeaders());
		Assert::same(['expiration_period' => 'P3D'], $request->getData());
	}

	public function testCreateResponse(): void{
		$result = (new RotateWebhookSigningSecretRequest('6fc346be-5cb5-4c14-aadc-e8aba6a655d7', 'P3D'))
			->createResponse(\Znojil\RevolutBusiness\Tests\Fixtures\ResponseFactory::create('webhooks/webhook-with-secret'));

		Assert::same('wsk_r59a4HfWVAKycbCaNO1RvgCJec02gRd8', $result->signingSecret);
	}

}

(new RotateWebhookSigningSecretRequestTest)->run();
