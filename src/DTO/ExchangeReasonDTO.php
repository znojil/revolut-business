<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\DTO;

use Znojil\RevolutBusiness\Enum\ExchangeReasonCode;

/**
 * @phpstan-type ExchangeReasonResponseData array{code: string, name: string}
 */
final readonly class ExchangeReasonDTO{

	/**
	 * @param ExchangeReasonResponseData $data
	 */
	public static function fromResponseData(array $data): self{
		return new self(
			ExchangeReasonCode::from($data['code']),
			$data['name']
		);
	}

	public function __construct(
		public ExchangeReasonCode $code,
		public string $name
	){}

}
