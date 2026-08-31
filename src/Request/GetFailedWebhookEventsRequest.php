<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Request;

use Znojil\RevolutBusiness\DTO\WebhookEventDTO;
use Znojil\RevolutBusiness\Http\ApiVersion;

/**
 * @extends BaseRequest<list<WebhookEventDTO>>
 * @link https://developer.revolut.com/docs/api/business#get-failed-webhook-events
 *
 * @phpstan-import-type WebhookEventResponseData from WebhookEventDTO
 */
final class GetFailedWebhookEventsRequest extends BaseRequest{

	/**
	 * @param ?\DateTimeInterface $createdBefore events are retained for 21 days
	 */
	public function __construct(
		private readonly string $webhookId,
		private readonly ?int $limit = null,
		private readonly ?\DateTimeInterface $createdBefore = null
	){}

	public function getApiVersion(): ApiVersion{
		return ApiVersion::V2;
	}

	public function getMethod(): string{
		return 'GET';
	}

	public function getUrn(): string{
		return $this->buildUrn("webhooks/$this->webhookId/failed-events", [
			'limit' => $this->limit,
			'created_before' => $this->formatDatetime($this->createdBefore)
		]);
	}

	/**
	 * @return list<WebhookEventDTO>
	 */
	public function createResponse(\Psr\Http\Message\ResponseInterface $httpResponse): array{
		/** @var list<WebhookEventResponseData> */
		$data = $this->parseJsonResponseBody((string) $httpResponse->getBody());

		return array_map(WebhookEventDTO::fromResponseData(...), $data);
	}

}
