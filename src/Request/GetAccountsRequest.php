<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Request;

use Znojil\RevolutBusiness\DTO\AccountDTO;

/**
 * @extends BaseRequest<list<AccountDTO>>
 * @link https://developer.revolut.com/docs/api/business#get-accounts
 *
 * @phpstan-import-type AccountResponseData from AccountDTO
 */
final class GetAccountsRequest extends BaseRequest{

	public function getMethod(): string{
		return 'GET';
	}

	public function getUrn(): string{
		return 'accounts';
	}

	/**
	 * @return list<AccountDTO>
	 */
	public function createResponse(\Psr\Http\Message\ResponseInterface $httpResponse): array{
		/** @var list<AccountResponseData> */
		$data = $this->parseJsonResponseBody((string) $httpResponse->getBody());

		return array_map(AccountDTO::fromResponseData(...), $data);
	}

}
