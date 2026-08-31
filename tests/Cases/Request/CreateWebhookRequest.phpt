<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Tests\Cases\Request;

use Tester\Assert;
use Znojil\RevolutBusiness\Enum\WebhookEventType;
use Znojil\RevolutBusiness\Request\CreateWebhookRequest;

require __DIR__ . '/../../bootstrap.php';

/**
 * @testCase
 */
final class CreateWebhookRequestTest extends \Tester\TestCase{

	public function testConfiguration(): void{
		$request = new CreateWebhookRequest(
			'https://www.example.com/webhook',
			[WebhookEventType::TransactionCreated, WebhookEventType::PayoutLinkStateChanged]
		);
		Assert::same(\Znojil\RevolutBusiness\Http\ApiVersion::V2, $request->getApiVersion());
		Assert::same('POST', $request->getMethod());
		Assert::same('webhooks', $request->getUrn());
		Assert::same(['Content-Type' => 'application/json'], $request->getHeaders());
		Assert::same([
			'url' => 'https://www.example.com/webhook',
			'events' => ['TransactionCreated', 'PayoutLinkStateChanged']
		], $request->getData());

		// default properties
		Assert::same([
			'url' => 'https://www.example.com/webhook'
		], (new CreateWebhookRequest('https://www.example.com/webhook'))->getData());
	}

	public function testCreateResponse(): void{
		$result = (new CreateWebhookRequest('https://www.example.com/webhook'))
			->createResponse(\Znojil\RevolutBusiness\Tests\Fixtures\ResponseFactory::create('webhooks/webhook-with-secret'));

		Assert::same('6fc346be-5cb5-4c14-aadc-e8aba6a655d7', $result->id);
		Assert::same('https://www.example.com/webhook', $result->url);
		Assert::same([WebhookEventType::TransactionCreated, WebhookEventType::TransactionStateChanged], $result->events);
		Assert::same('wsk_r59a4HfWVAKycbCaNO1RvgCJec02gRd8', $result->signingSecret);
	}

}

(new CreateWebhookRequestTest)->run();
