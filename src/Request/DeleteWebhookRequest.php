<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Request;

use Znojil\RevolutBusiness\Http\ApiVersion;

/**
 * @extends BaseRequest<null>
 * @link https://developer.revolut.com/docs/api/business#delete-webhook
 */
final class DeleteWebhookRequest extends BaseRequest{

	public function __construct(
		private readonly string $webhookId
	){}

	public function getApiVersion(): ApiVersion{
		return ApiVersion::V2;
	}

	public function getMethod(): string{
		return 'DELETE';
	}

	public function getUrn(): string{
		return 'webhooks/' . $this->webhookId;
	}

	public function createResponse(\Psr\Http\Message\ResponseInterface $httpResponse): null{
		return null;
	}

}
