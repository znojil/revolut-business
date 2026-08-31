<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Tests\Cases\Request;

use Tester\Assert;
use Znojil\RevolutBusiness\Request\GetFailedWebhookEventsRequest;

require __DIR__ . '/../../bootstrap.php';

/**
 * @testCase
 */
final class GetFailedWebhookEventsRequestTest extends \Tester\TestCase{

	public function testConfiguration(): void{
		$request = new GetFailedWebhookEventsRequest(
			'6fc346be-5cb5-4c14-aadc-e8aba6a655d7',
			100,
			new \DateTimeImmutable('2023-05-09 16:36:38.028960', new \DateTimeZone('UTC'))
		);
		Assert::same(\Znojil\RevolutBusiness\Http\ApiVersion::V2, $request->getApiVersion());
		Assert::same('GET', $request->getMethod());
		Assert::same('webhooks/6fc346be-5cb5-4c14-aadc-e8aba6a655d7/failed-events?limit=100&created_before=2023-05-09T16%3A36%3A38.028960Z', $request->getUrn());

		// default properties
		Assert::same(
			'webhooks/6fc346be-5cb5-4c14-aadc-e8aba6a655d7/failed-events',
			(new GetFailedWebhookEventsRequest('6fc346be-5cb5-4c14-aadc-e8aba6a655d7'))->getUrn()
		);
	}

	public function testCreateResponse(): void{
		$result = (new GetFailedWebhookEventsRequest('6fc346be-5cb5-4c14-aadc-e8aba6a655d7'))
			->createResponse(\Znojil\RevolutBusiness\Tests\Fixtures\ResponseFactory::create('webhooks/failed-events'));

		Assert::count(2, $result);

		$event = $result[0];
		Assert::same('84c0169a-37f9-4bfa-ab1e-f2c81dbc34cf', $event->id);
		Assert::same('6fc346be-5cb5-4c14-aadc-e8aba6a655d7', $event->webhookId);
		Assert::same('https://www.example.com/webhook', $event->webhookUrl);
		Assert::same('2023-05-09T16:36:38+00:00', $event->createdAt->format('c'));
		Assert::same('2023-05-09T16:41:38+00:00', $event->lastSentDate?->format('c'));

		// the payload is passed through untouched, it is not modelled yet
		Assert::same('TransactionStateChanged', $event->payload['event']);
		Assert::same([
			'id' => '645a7696-22f3-aa47-9c74-cbae0449cc46',
			'old_state' => 'pending',
			'new_state' => 'completed',
			'request_id' => 'app_charges-9f5d5eb3-1e06-46c5-b1c0-3914763e0bcb'
		], $event->payload['data']);

		Assert::null($result[1]->lastSentDate); // never delivered
		Assert::same('PayoutLinkCreated', $result[1]->payload['event']);
	}

}

(new GetFailedWebhookEventsRequestTest)->run();
