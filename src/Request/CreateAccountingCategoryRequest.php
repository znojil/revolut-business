<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Request;

/**
 * @extends BaseRequest<string>
 * @link https://developer.revolut.com/docs/api/business#create-accounting-category
 */
final class CreateAccountingCategoryRequest extends BaseRequest{

	public function __construct(
		private readonly string $name,
		private readonly string $code,
		private readonly ?string $defaultTaxRateId = null
	){}

	public function getMethod(): string{
		return 'POST';
	}

	public function getUrn(): string{
		return 'accounting-categories';
	}

	public function getHeaders(): array{
		return ['Content-Type' => 'application/json'];
	}

	public function getData(): array{
		return $this->buildData([
			'name' => $this->name,
			'code' => $this->code,
			'default_tax_rate_id' => $this->defaultTaxRateId
		]);
	}

	public function createResponse(\Psr\Http\Message\ResponseInterface $httpResponse): string{
		/** @var array{id: string} */
		$data = $this->parseJsonResponseBody((string) $httpResponse->getBody());

		return $data['id'];
	}

}
