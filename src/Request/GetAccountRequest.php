<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Request;

use Znojil\RevolutBusiness\DTO\AccountDTO;

/**
 * @extends BaseRequest<AccountDTO>
 * @link https://developer.revolut.com/docs/api/business#get-account
 *
 * @phpstan-import-type AccountResponseData from AccountDTO
 */
final class GetAccountRequest extends BaseRequest{

	public function __construct(
		private readonly string $accountId
	){}

	public function getMethod(): string{
		return 'GET';
	}

	public function getUrn(): string{
		return 'accounts/' . $this->accountId;
	}

	public function createResponse(\Psr\Http\Message\ResponseInterface $httpResponse): AccountDTO{
		/** @var AccountResponseData */
		$data = $this->parseJsonResponseBody((string) $httpResponse->getBody());

		return AccountDTO::fromResponseData($data);
	}

}
