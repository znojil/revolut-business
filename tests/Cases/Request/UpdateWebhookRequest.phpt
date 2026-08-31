<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Tests\Cases\Request;

use Tester\Assert;
use Znojil\RevolutBusiness\Enum\WebhookEventType;
use Znojil\RevolutBusiness\Request\UpdateWebhookRequest;

require __DIR__ . '/../../bootstrap.php';

/**
 * @testCase
 */
final class UpdateWebhookRequestTest extends \Tester\TestCase{

	public function testConfiguration(): void{
		$request = new UpdateWebhookRequest(
			'6fc346be-5cb5-4c14-aadc-e8aba6a655d7',
			'https://www.example.com/new',
			[WebhookEventType::TransactionCreated]
		);
		Assert::same(\Znojil\RevolutBusiness\Http\ApiVersion::V2, $request->getApiVersion());
		Assert::same('PATCH', $request->getMethod());
		Assert::same('webhooks/6fc346be-5cb5-4c14-aadc-e8aba6a655d7', $request->getUrn());
		Assert::same(['Content-Type' => 'application/json'], $request->getHeaders());
		Assert::same([
			'url' => 'https://www.example.com/new',
			'events' => ['TransactionCreated']
		], $request->getData());

		// default properties
		Assert::same([
			'url' => 'https://www.example.com/new'
		], (new UpdateWebhookRequest('6fc346be-5cb5-4c14-aadc-e8aba6a655d7', 'https://www.example.com/new'))->getData());

		// at least one property must be provided
		Assert::exception(
			fn() => (new UpdateWebhookRequest('6fc346be-5cb5-4c14-aadc-e8aba6a655d7'))->getData(),
			\Znojil\RevolutBusiness\Exception\InvalidArgumentException::class
		);
	}

	public function testCreateResponse(): void{
		// updating returns the basic shape, without the signing secret
		$result = (new UpdateWebhookRequest('6fc346be-5cb5-4c14-aadc-e8aba6a655d7', 'https://www.example.com/new'))
			->createResponse(\Znojil\RevolutBusiness\Tests\Fixtures\ResponseFactory::create('webhooks/webhook-with-secret'));

		Assert::same('6fc346be-5cb5-4c14-aadc-e8aba6a655d7', $result->id);
		Assert::same([WebhookEventType::TransactionCreated, WebhookEventType::TransactionStateChanged], $result->events);
	}

}

(new UpdateWebhookRequestTest)->run();
