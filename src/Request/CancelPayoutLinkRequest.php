<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Request;

/**
 * @extends BaseRequest<null>
 * @link https://developer.revolut.com/docs/api/business#cancel-payout-link
 */
final class CancelPayoutLinkRequest extends BaseRequest{

	public function __construct(
		private readonly string $payoutLinkId
	){}

	public function getMethod(): string{
		return 'POST';
	}

	public function getUrn(): string{
		return "payout-links/$this->payoutLinkId/cancel";
	}

	public function createResponse(\Psr\Http\Message\ResponseInterface $httpResponse): null{
		return null;
	}

}
