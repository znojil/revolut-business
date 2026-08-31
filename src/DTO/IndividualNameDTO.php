<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\DTO;

/**
 * @phpstan-type IndividualNameResponseData array{first_name: string, last_name: string}
 */
final readonly class IndividualNameDTO{

	/**
	 * @param IndividualNameResponseData $data
	 */
	public static function fromResponseData(array $data): self{
		return new self(
			$data['first_name'],
			$data['last_name']
		);
	}

	public function __construct(
		public string $firstName,
		public string $lastName
	){}

	/**
	 * @return IndividualNameResponseData
	 */
	public function toRequestData(): array{
		return [
			'first_name' => $this->firstName,
			'last_name' => $this->lastName
		];
	}

}
