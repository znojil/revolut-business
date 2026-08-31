<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Request;

/**
 * @extends BaseRequest<null>
 * @link https://developer.revolut.com/docs/api/business#update-tax-rate
 */
final class UpdateTaxRateRequest extends BaseRequest{

	public function __construct(
		private readonly string $taxRateId,
		private readonly string $name
	){}

	public function getMethod(): string{
		return 'PATCH';
	}

	public function getUrn(): string{
		return 'tax-rates/' . $this->taxRateId;
	}

	public function getHeaders(): array{
		return ['Content-Type' => 'application/json'];
	}

	public function getData(): array{
		return $this->buildData(['name' => $this->name]);
	}

	public function createResponse(\Psr\Http\Message\ResponseInterface $httpResponse): null{
		return null;
	}

}
