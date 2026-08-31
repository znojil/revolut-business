<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Request;

use Znojil\RevolutBusiness\DTO\WebhookDTO;
use Znojil\RevolutBusiness\Enum\WebhookEventType;
use Znojil\RevolutBusiness\Http\ApiVersion;

/**
 * @extends BaseRequest<WebhookDTO>
 * @link https://developer.revolut.com/docs/api/business#update-webhook
 *
 * @phpstan-import-type WebhookResponseData from WebhookDTO
 */
final class UpdateWebhookRequest extends BaseRequest{

	/**
	 * @param ?list<WebhookEventType> $events
	 */
	public function __construct(
		private readonly string $webhookId,
		private readonly ?string $url = null,
		private readonly ?array $events = null
	){}

	public function getApiVersion(): ApiVersion{
		return ApiVersion::V2;
	}

	public function getMethod(): string{
		return 'PATCH';
	}

	public function getUrn(): string{
		return 'webhooks/' . $this->webhookId;
	}

	public function getHeaders(): array{
		return ['Content-Type' => 'application/json'];
	}

	public function getData(): array{
		return $this->buildRequiredData([
			'url' => $this->url,
			'events' => $this->events !== null
				? array_map(fn(WebhookEventType $e): string => $e->value, $this->events)
				: null
		]);
	}

	public function createResponse(\Psr\Http\Message\ResponseInterface $httpResponse): WebhookDTO{
		/** @var WebhookResponseData */
		$data = $this->parseJsonResponseBody((string) $httpResponse->getBody());

		return WebhookDTO::fromResponseData($data);
	}

}
