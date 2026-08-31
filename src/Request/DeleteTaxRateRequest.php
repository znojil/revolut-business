<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Request;

/**
 * @extends BaseRequest<null>
 * @link https://developer.revolut.com/docs/api/business#delete-tax-rate
 */
final class DeleteTaxRateRequest extends BaseRequest{

	public function __construct(
		private readonly string $taxRateId
	){}

	public function getMethod(): string{
		return 'DELETE';
	}

	public function getUrn(): string{
		return 'tax-rates/' . $this->taxRateId;
	}

	public function createResponse(\Psr\Http\Message\ResponseInterface $httpResponse): null{
		return null;
	}

}
