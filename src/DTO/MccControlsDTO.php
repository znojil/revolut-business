<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\DTO;

use Znojil\RevolutBusiness\Enum\MccControlType;

/**
 * @phpstan-type MccControlsResponseData array{control_type: string, mccs: non-empty-list<string>}
 */
final readonly class MccControlsDTO{

	/**
	 * @param MccControlsResponseData $data
	 * @throws \Znojil\RevolutBusiness\Exception\UnexpectedValueException
	 */
	public static function fromResponseData(array $data): self{
		return new self(
			\Znojil\RevolutBusiness\Internal\EnumMapper::from(MccControlType::class, strtolower($data['control_type'])),
			$data['mccs']
		);
	}

	/**
	 * @param non-empty-list<string> $mccs ISO 18245 four-digit codes, max 100
	 */
	public function __construct(
		public MccControlType $controlType,
		public array $mccs
	){}

	/**
	 * @return MccControlsResponseData
	 */
	public function toRequestData(): array{
		return [
			'control_type' => $this->controlType->value,
			'mccs' => $this->mccs
		];
	}

}
