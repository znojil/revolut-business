<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\DTO;

/**
 * @phpstan-type AddressResponseData array{country: string, street_line1: string, street_line2?: string, city: string, region?: string, postcode: string}
 */
final readonly class AddressDTO{

	/**
	 * @param AddressResponseData $data
	 */
	public static function fromResponseData(array $data): self{
		return new self(
			$data['country'],
			$data['street_line1'],
			$data['street_line2'] ?? null,
			$data['city'],
			$data['region'] ?? null,
			$data['postcode']
		);
	}

	/**
	 * @param string $country ISO 3166-1 alpha-2
	 */
	public function __construct(
		public string $country,
		public string $streetLine1,
		public ?string $streetLine2,
		public string $city,
		public ?string $region,
		public string $postcode
	){}

	/**
	 * @return AddressResponseData
	 */
	public function toRequestData(): array{
		return [
			'country' => $this->country,
			'street_line1' => $this->streetLine1,
			'city' => $this->city,
			'postcode' => $this->postcode
		] + array_filter([
			'street_line2' => $this->streetLine2,
			'region' => $this->region
		], fn(?string $v): bool => $v !== null);
	}

}
