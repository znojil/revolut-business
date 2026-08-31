<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Request;

use Znojil\RevolutBusiness\DTO\PayoutLinkDTO;

/**
 * @extends BaseRequest<PayoutLinkDTO>
 * @link https://developer.revolut.com/docs/api/business#get-payout-link
 *
 * @phpstan-import-type PayoutLinkResponseData from PayoutLinkDTO
 */
final class GetPayoutLinkRequest extends BaseRequest{

	public function __construct(
		private readonly string $payoutLinkId
	){}

	public function getMethod(): string{
		return 'GET';
	}

	public function getUrn(): string{
		return 'payout-links/' . $this->payoutLinkId;
	}

	public function createResponse(\Psr\Http\Message\ResponseInterface $httpResponse): PayoutLinkDTO{
		/** @var PayoutLinkResponseData */
		$data = $this->parseJsonResponseBody((string) $httpResponse->getBody());

		return PayoutLinkDTO::fromResponseData($data);
	}

}
