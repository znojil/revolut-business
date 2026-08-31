<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Request;

use Znojil\RevolutBusiness\DTO\WebhookWithSecretDTO;
use Znojil\RevolutBusiness\Http\ApiVersion;

/**
 * @extends BaseRequest<WebhookWithSecretDTO>
 * @link https://developer.revolut.com/docs/api/business#rotate-webhook-signing-secret
 *
 * @phpstan-import-type WebhookWithSecretResponseData from WebhookWithSecretDTO
 */
final class RotateWebhookSigningSecretRequest extends BaseRequest{

	/**
	 * @param string $expirationPeriod ISO 8601 duration, max P7D — how long the old secret stays valid
	 */
	public function __construct(
		private readonly string $webhookId,
		private readonly string $expirationPeriod
	){}

	public function getApiVersion(): ApiVersion{
		return ApiVersion::V2;
	}

	public function getMethod(): string{
		return 'POST';
	}

	public function getUrn(): string{
		return "webhooks/$this->webhookId/rotate-signing-secret";
	}

	public function getHeaders(): array{
		return ['Content-Type' => 'application/json'];
	}

	public function getData(): array{
		return $this->buildData(['expiration_period' => $this->expirationPeriod]);
	}

	public function createResponse(\Psr\Http\Message\ResponseInterface $httpResponse): WebhookWithSecretDTO{
		/** @var WebhookWithSecretResponseData */
		$data = $this->parseJsonResponseBody((string) $httpResponse->getBody());

		return WebhookWithSecretDTO::fromResponseData($data);
	}

}
