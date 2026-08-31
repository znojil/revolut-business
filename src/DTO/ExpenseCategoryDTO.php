<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\DTO;

/**
 * @phpstan-type ExpenseCategoryResponseData array{id: string, name: string, code?: string}
 */
final readonly class ExpenseCategoryDTO{

	/**
	 * @param ExpenseCategoryResponseData $data
	 */
	public static function fromResponseData(array $data): self{
		return new self(
			$data['id'],
			$data['name'],
			$data['code'] ?? null
		);
	}

	public function __construct(
		public string $id,
		public string $name,
		public ?string $code
	){}

}
