<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Request;

/**
 * @extends BaseRequest<null>
 * @link https://developer.revolut.com/docs/api/business#update-accounting-category
 */
final class UpdateAccountingCategoryRequest extends BaseRequest{

	public function __construct(
		private readonly string $accountingCategoryId,
		private readonly ?string $name = null,
		private readonly ?string $code = null,
		private readonly ?string $defaultTaxRateId = null
	){}

	public function getMethod(): string{
		return 'PATCH';
	}

	public function getUrn(): string{
		return 'accounting-categories/' . $this->accountingCategoryId;
	}

	public function getHeaders(): array{
		return ['Content-Type' => 'application/json'];
	}

	public function getData(): array{
		return $this->buildRequiredData([
			'name' => $this->name,
			'code' => $this->code,
			'default_tax_rate_id' => $this->defaultTaxRateId
		]);
	}

	public function createResponse(\Psr\Http\Message\ResponseInterface $httpResponse): null{
		return null;
	}

}
