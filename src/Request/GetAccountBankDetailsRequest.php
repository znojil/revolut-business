<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Request;

use Znojil\RevolutBusiness\DTO\AccountBankDetailsDTO;

/**
 * @extends BaseRequest<list<AccountBankDetailsDTO>>
 * @link https://developer.revolut.com/docs/api/business#get-account-details
 *
 * @phpstan-import-type AccountBankDetailsResponseData from AccountBankDetailsDTO
 */
final class GetAccountBankDetailsRequest extends BaseRequest{

	public function __construct(
		private readonly string $accountId
	){}

	public function getMethod(): string{
		return 'GET';
	}

	public function getUrn(): string{
		return "accounts/$this->accountId/bank-details";
	}

	/**
	 * @return list<AccountBankDetailsDTO>
	 */
	public function createResponse(\Psr\Http\Message\ResponseInterface $httpResponse): array{
		/** @var list<AccountBankDetailsResponseData> */
		$data = $this->parseJsonResponseBody((string) $httpResponse->getBody());

		return array_map(AccountBankDetailsDTO::fromResponseData(...), $data);
	}

}
