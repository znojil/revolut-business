<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Request;

/**
 * @extends BaseRequest<null>
 * @link https://developer.revolut.com/docs/api/business#delete-accounting-category
 */
final class DeleteAccountingCategoryRequest extends BaseRequest{

	public function __construct(
		private readonly string $accountingCategoryId
	){}

	public function getMethod(): string{
		return 'DELETE';
	}

	public function getUrn(): string{
		return 'accounting-categories/' . $this->accountingCategoryId;
	}

	public function createResponse(\Psr\Http\Message\ResponseInterface $httpResponse): null{
		return null;
	}

}
