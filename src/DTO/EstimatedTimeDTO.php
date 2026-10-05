<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\DTO;

use Znojil\RevolutBusiness\Enum\TimeUnit;

/**
 * @phpstan-type EstimatedTimeResponseData array{unit: string, min?: int, max?: int}
 */
final readonly class EstimatedTimeDTO{

	/**
	 * @param EstimatedTimeResponseData $data
	 */
	public static function fromResponseData(array $data): self{
		return new self(
			TimeUnit::from($data['unit']),
			$data['min'] ?? null,
			$data['max'] ?? null
		);
	}

	public function __construct(
		public TimeUnit $unit,
		public ?int $min,
		public ?int $max
	){}

}
