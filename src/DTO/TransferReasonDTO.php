<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\DTO;

use Znojil\RevolutBusiness\Enum\Currency;
use Znojil\RevolutBusiness\Enum\TransferReasonCode;

/**
 * @phpstan-type TransferReasonResponseData array{country: string, currency: string, code: string, description: string}
 */
final readonly class TransferReasonDTO{

	/**
	 * @param TransferReasonResponseData $data
	 */
	public static function fromResponseData(array $data): self{
		return new self(
			$data['country'],
			Currency::tryFrom($data['currency']) ?? $data['currency'],
			TransferReasonCode::tryFrom($data['code']) ?? $data['code'],
			$data['description']
		);
	}

	/**
	 * @param string $country ISO 3166-1 alpha-2
	 */
	public function __construct(
		public string $country,
		public string|Currency $currency,
		public string|TransferReasonCode $code,
		public string $description
	){}

}
