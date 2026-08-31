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
	 * @throws \Znojil\RevolutBusiness\Exception\UnexpectedValueException
	 */
	public static function fromResponseData(array $data): self{
		return new self(
			\Znojil\RevolutBusiness\Internal\EnumMapper::from(ExchangeReasonCode::class, $data['code']),
			$data['name']
		);
	}

	public function __construct(
		public ExchangeReasonCode $code,
		public string $name
	){}

}
