<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Request;

use Znojil\RevolutBusiness\DTO\WebhookWithSecretDTO;
use Znojil\RevolutBusiness\Enum\WebhookEventType;
use Znojil\RevolutBusiness\Http\ApiVersion;

/**
 * @extends BaseRequest<WebhookWithSecretDTO>
 * @link https://developer.revolut.com/docs/api/business#create-webhook
 *
 * @phpstan-import-type WebhookWithSecretResponseData from WebhookWithSecretDTO
 */
final class CreateWebhookRequest extends BaseRequest{

	/**
	 * @param ?list<WebhookEventType> $events
	 */
	public function __construct(
		private readonly string $url,
		private readonly ?array $events = null
	){}

	public function getApiVersion(): ApiVersion{
		return ApiVersion::V2;
	}

	public function getMethod(): string{
		return 'POST';
	}

	public function getUrn(): string{
		return 'webhooks';
	}

	public function getHeaders(): array{
		return ['Content-Type' => 'application/json'];
	}

	public function getData(): array{
		return $this->buildData([
			'url' => $this->url,
			'events' => $this->events !== null
				? array_map(fn(WebhookEventType $e): string => $e->value, $this->events)
				: null
		]);
	}

	public function createResponse(\Psr\Http\Message\ResponseInterface $httpResponse): WebhookWithSecretDTO{
		/** @var WebhookWithSecretResponseData */
		$data = $this->parseJsonResponseBody((string) $httpResponse->getBody());

		return WebhookWithSecretDTO::fromResponseData($data);
	}

}
