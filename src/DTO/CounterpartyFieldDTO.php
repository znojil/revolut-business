<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\DTO;

/**
 * @phpstan-type CounterpartyFieldResponseData array{name: string, required: bool, regex?: array{pattern: string, description: string}, options?: list<array{value: string, default?: bool}>}
 */
final readonly class CounterpartyFieldDTO{

	/**
	 * @param CounterpartyFieldResponseData $data
	 */
	public static function fromResponseData(array $data): self{
		return new self(
			$data['name'],
			$data['required'],
			$data['regex'] ?? null,
			isset($data['options'])
				? array_map(fn(array $v): array => [
					'value' => $v['value'],
					'default' => $v['default'] ?? false
				], $data['options'])
				: null
		);
	}

	/**
	 * @param ?array{pattern: string, description: string} $regex
	 * @param ?list<array{value: string, default: bool}> $options
	 */
	public function __construct(
		public string $name,
		public bool $required,
		public ?array $regex,
		public ?array $options
	){}

}
