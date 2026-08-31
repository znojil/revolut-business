<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Request;

use Znojil\RevolutBusiness\DTO\WebhookWithSecretDTO;
use Znojil\RevolutBusiness\Http\ApiVersion;

/**
 * @extends BaseRequest<WebhookWithSecretDTO>
 * @link https://developer.revolut.com/docs/api/business#get-webhook
 *
 * @phpstan-import-type WebhookWithSecretResponseData from WebhookWithSecretDTO
 */
final class GetWebhookRequest extends BaseRequest{

	public function __construct(
		private readonly string $webhookId
	){}

	public function getApiVersion(): ApiVersion{
		return ApiVersion::V2;
	}

	public function getMethod(): string{
		return 'GET';
	}

	public function getUrn(): string{
		return 'webhooks/' . $this->webhookId;
	}

	public function createResponse(\Psr\Http\Message\ResponseInterface $httpResponse): WebhookWithSecretDTO{
		/** @var WebhookWithSecretResponseData */
		$data = $this->parseJsonResponseBody((string) $httpResponse->getBody());

		return WebhookWithSecretDTO::fromResponseData($data);
	}

}
