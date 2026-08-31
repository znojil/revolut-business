<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\DTO;

/**
 * @phpstan-type CardReferenceResponseData array{name: string, value: string}
 */
final readonly class CardReferenceDTO{

	/**
	 * @param CardReferenceResponseData $data
	 */
	public static function fromResponseData(array $data): self{
		return new self(
			$data['name'],
			$data['value']
		);
	}

	public function __construct(
		public string $name,
		public string $value
	){}

	/**
	 * @return CardReferenceResponseData
	 */
	public function toRequestData(): array{
		return [
			'name' => $this->name,
			'value' => $this->value
		];
	}

}
