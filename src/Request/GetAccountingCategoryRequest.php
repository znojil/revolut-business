<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Request;

use Znojil\RevolutBusiness\DTO\AccountingCategoryDTO;

/**
 * @extends BaseRequest<AccountingCategoryDTO>
 * @link https://developer.revolut.com/docs/api/business#get-accounting-category
 *
 * @phpstan-import-type AccountingCategoryResponseData from AccountingCategoryDTO
 */
final class GetAccountingCategoryRequest extends BaseRequest{

	public function __construct(
		private readonly string $accountingCategoryId
	){}

	public function getMethod(): string{
		return 'GET';
	}

	public function getUrn(): string{
		return 'accounting-categories/' . $this->accountingCategoryId;
	}

	public function createResponse(\Psr\Http\Message\ResponseInterface $httpResponse): AccountingCategoryDTO{
		/** @var AccountingCategoryResponseData */
		$data = $this->parseJsonResponseBody((string) $httpResponse->getBody());

		return AccountingCategoryDTO::fromResponseData($data);
	}

}
