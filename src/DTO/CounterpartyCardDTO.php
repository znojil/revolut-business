<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\DTO;

use Znojil\RevolutBusiness\Enum;

/**
 * @phpstan-type CounterpartyCardResponseData array{id: string, name: string, last_digits: string, scheme: string, country: string, currency: string}
 */
final readonly class CounterpartyCardDTO{

	/**
	 * @param CounterpartyCardResponseData $data
	 * @throws \Znojil\RevolutBusiness\Exception\UnexpectedValueException
	 */
	public static function fromResponseData(array $data): self{
		return new self(
			$data['id'],
			$data['name'],
			$data['last_digits'],
			\Znojil\RevolutBusiness\Internal\EnumMapper::from(Enum\CardScheme::class, $data['scheme']),
			$data['country'],
			Enum\Currency::tryFrom($data['currency']) ?? $data['currency']
		);
	}

	/**
	 * @param string $country ISO 3166-1 alpha-2
	 */
	public function __construct(
		public string $id,
		public string $name,
		public string $lastDigits,
		public Enum\CardScheme $scheme,
		public string $country,
		public string|Enum\Currency $currency
	){}

}
