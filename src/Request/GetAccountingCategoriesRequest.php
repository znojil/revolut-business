<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Request;

use Znojil\RevolutBusiness\DTO;

/**
 * @extends BaseRequest<DTO\PaginatedListDTO<DTO\AccountingCategoryDTO>>
 * @link https://developer.revolut.com/docs/api/business#get-accounting-categories
 *
 * @phpstan-import-type AccountingCategoryResponseData from DTO\AccountingCategoryDTO
 */
final class GetAccountingCategoriesRequest extends BaseRequest{

	public function __construct(
		private readonly ?int $limit = null,
		private readonly ?string $pageToken = null
	){}

	public function getMethod(): string{
		return 'GET';
	}

	public function getUrn(): string{
		return $this->buildUrn('accounting-categories', [
			'limit' => $this->limit,
			'page_token' => $this->pageToken
		]);
	}

	/**
	 * @return DTO\PaginatedListDTO<DTO\AccountingCategoryDTO>
	 */
	public function createResponse(\Psr\Http\Message\ResponseInterface $httpResponse): DTO\PaginatedListDTO{
		/** @var array{next_page_token?: string, accounting_categories: list<AccountingCategoryResponseData>} */
		$data = $this->parseJsonResponseBody((string) $httpResponse->getBody());

		return new DTO\PaginatedListDTO(
			array_map(DTO\AccountingCategoryDTO::fromResponseData(...), $data['accounting_categories']),
			$data['next_page_token'] ?? null
		);
	}

}
