<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Request;

/**
 * @extends BaseRequest<string>
 * @link https://developer.revolut.com/docs/api/business#create-tax-rate
 */
final class CreateTaxRateRequest extends BaseRequest{

	public function __construct(
		private readonly string $name,
		private readonly float $percentage
	){}

	public function getMethod(): string{
		return 'POST';
	}

	public function getUrn(): string{
		return 'tax-rates';
	}

	public function getHeaders(): array{
		return ['Content-Type' => 'application/json'];
	}

	public function getData(): array{
		return $this->buildData([
			'name' => $this->name,
			'percentage' => $this->percentage
		]);
	}

	public function createResponse(\Psr\Http\Message\ResponseInterface $httpResponse): string{
		/** @var array{id: string} */
		$data = $this->parseJsonResponseBody((string) $httpResponse->getBody());

		return $data['id'];
	}

}
