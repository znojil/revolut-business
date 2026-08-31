<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\DTO;

/**
 * @phpstan-type LabelResponseData array{id: string, name: string, created_at: string, updated_at: string}
 */
final readonly class LabelDTO{

	/**
	 * @param LabelResponseData $data
	 */
	public static function fromResponseData(array $data): self{
		return new self(
			$data['id'],
			$data['name'],
			new \DateTimeImmutable($data['created_at']),
			new \DateTimeImmutable($data['updated_at'])
		);
	}

	public function __construct(
		public string $id,
		public string $name,
		public \DateTimeImmutable $createdAt,
		public \DateTimeImmutable $updatedAt
	){}

}
