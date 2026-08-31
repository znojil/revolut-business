<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\DTO;

/**
 * @phpstan-import-type CardReferenceResponseData from CardReferenceDTO
 * @phpstan-type TransactionCardResponseData array{id: string, card_number: string, first_name?: string, last_name?: string, phone?: string, references?: list<CardReferenceResponseData>}
 */
final readonly class TransactionCardDTO{

	/**
	 * @param TransactionCardResponseData $data
	 */
	public static function fromResponseData(array $data): self{
		return new self(
			$data['id'],
			$data['card_number'],
			$data['first_name'] ?? null,
			$data['last_name'] ?? null,
			$data['phone'] ?? null,
			array_map(CardReferenceDTO::fromResponseData(...), $data['references'] ?? [])
		);
	}

	/**
	 * @param list<CardReferenceDTO> $references the references assigned to the card when the transaction was made
	 */
	public function __construct(
		public string $id,
		public string $cardNumber,
		public ?string $firstName,
		public ?string $lastName,
		public ?string $phone,
		public array $references
	){}

}
