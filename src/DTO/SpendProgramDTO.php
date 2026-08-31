<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\DTO;

/**
 * @phpstan-type SpendProgramResponseData array{label: string}
 */
final readonly class SpendProgramDTO{

	/**
	 * @param SpendProgramResponseData $data
	 */
	public static function fromResponseData(array $data): self{
		return new self($data['label']);
	}

	public function __construct(
		public string $label
	){}

}
