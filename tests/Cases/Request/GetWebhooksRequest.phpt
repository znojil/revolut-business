<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Tests\Cases\Request;

use Tester\Assert;
use Znojil\RevolutBusiness\Enum\WebhookEventType;
use Znojil\RevolutBusiness\Request\GetWebhooksRequest;

require __DIR__ . '/../../bootstrap.php';

/**
 * @testCase
 */
final class GetWebhooksRequestTest extends \Tester\TestCase{

	public function testConfiguration(): void{
		$request = new GetWebhooksRequest;

		Assert::same(\Znojil\RevolutBusiness\Http\ApiVersion::V2, $request->getApiVersion());
		Assert::same('GET', $request->getMethod());
		Assert::same('webhooks', $request->getUrn());
	}

	public function testCreateResponse(): void{
		$result = (new GetWebhooksRequest)
			->createResponse(\Znojil\RevolutBusiness\Tests\Fixtures\ResponseFactory::create('webhooks/webhooks'));

		Assert::count(2, $result);
		Assert::same('6fc346be-5cb5-4c14-aadc-e8aba6a655d7', $result[0]->id);
		Assert::same([WebhookEventType::TransactionCreated, WebhookEventType::TransactionStateChanged], $result[0]->events);
		Assert::same('https://www.example.com/payout-links', $result[1]->url);
		Assert::same([WebhookEventType::PayoutLinkCreated, WebhookEventType::PayoutLinkStateChanged], $result[1]->events);
	}

}

(new GetWebhooksRequestTest)->run();
