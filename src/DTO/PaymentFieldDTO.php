<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\DTO;

/**
 * @phpstan-type PaymentFieldResponseData array{name: string, required: bool, validation?: array{min_length?: int, max_length?: int, regex?: array{pattern: string, description: string}}, options?: list<array{value: string, default?: bool}>}
 */
final readonly class PaymentFieldDTO{

	/**
	 * @param PaymentFieldResponseData $data
	 */
	public static function fromResponseData(array $data): self{
		return new self(
			$data['name'],
			$data['required'],
			$data['validation'] ?? null,
			isset($data['options'])
				? array_map(fn(array $v): array => [
					'value' => $v['value'],
					'default' => $v['default'] ?? false
				], $data['options'])
				: null
		);
	}

	/**
	 * @param ?array{min_length?: int, max_length?: int, regex?: array{pattern: string, description: string}} $validation
	 * @param ?list<array{value: string, default: bool}> $options
	 */
	public function __construct(
		public string $name,
		public bool $required,
		public ?array $validation,
		public ?array $options
	){}

}
