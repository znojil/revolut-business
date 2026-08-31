<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\DTO;

use Znojil\RevolutBusiness\Enum\EstimatedArrivalSpeed;

/**
 * @phpstan-type EstimatedArrivalResponseData array{date: string, speed: string}
 */
final readonly class EstimatedArrivalDTO{

	/**
	 * @param EstimatedArrivalResponseData $data
	 * @throws \Znojil\RevolutBusiness\Exception\UnexpectedValueException
	 */
	public static function fromResponseData(array $data): self{
		return new self(
			new \DateTimeImmutable($data['date']),
			\Znojil\RevolutBusiness\Internal\EnumMapper::from(EstimatedArrivalSpeed::class, $data['speed'])
		);
	}

	public function __construct(
		public \DateTimeImmutable $date,
		public EstimatedArrivalSpeed $speed
	){}

}
