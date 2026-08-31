<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Request;

use Znojil\RevolutBusiness\DTO\WebhookDTO;
use Znojil\RevolutBusiness\Http\ApiVersion;

/**
 * @extends BaseRequest<list<WebhookDTO>>
 * @link https://developer.revolut.com/docs/api/business#get-webhooks
 *
 * @phpstan-import-type WebhookResponseData from WebhookDTO
 */
final class GetWebhooksRequest extends BaseRequest{

	public function getApiVersion(): ApiVersion{
		return ApiVersion::V2;
	}

	public function getMethod(): string{
		return 'GET';
	}

	public function getUrn(): string{
		return 'webhooks';
	}

	/**
	 * @return list<WebhookDTO>
	 */
	public function createResponse(\Psr\Http\Message\ResponseInterface $httpResponse): array{
		/** @var list<WebhookResponseData> */
		$data = $this->parseJsonResponseBody((string) $httpResponse->getBody());

		return array_map(WebhookDTO::fromResponseData(...), $data);
	}

}
