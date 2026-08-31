<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\DTO;

/**
 * @phpstan-import-type AddressResponseData from AddressDTO
 * @phpstan-import-type EstimatedTimeResponseData from EstimatedTimeDTO
 * @phpstan-type AccountBankDetailsResponseData array{iban?: string, bic?: string, account_no?: string, sort_code?: string, routing_number?: string, beneficiary: string, beneficiary_address: AddressResponseData, bank_country?: string, pooled?: bool, unique_reference?: string, schemes: list<string>, estimated_time: EstimatedTimeResponseData}
 */
final readonly class AccountBankDetailsDTO{

	/**
	 * @param AccountBankDetailsResponseData $data
	 * @throws \Znojil\RevolutBusiness\Exception\UnexpectedValueException
	 */
	public static function fromResponseData(array $data): self{
		return new self(
			$data['iban'] ?? null,
			$data['bic'] ?? null,
			$data['account_no'] ?? null,
			$data['sort_code'] ?? null,
			$data['routing_number'] ?? null,
			$data['beneficiary'],
			AddressDTO::fromResponseData($data['beneficiary_address']),
			$data['bank_country'] ?? null,
			$data['pooled'] ?? null,
			$data['unique_reference'] ?? null,
			$data['schemes'],
			EstimatedTimeDTO::fromResponseData($data['estimated_time'])
		);
	}

	/**
	 * @param list<string> $schemes
	 */
	public function __construct(
		public ?string $iban,
		public ?string $bic,
		public ?string $accountNo,
		public ?string $sortCode,
		public ?string $routingNumber,
		public string $beneficiary,
		public AddressDTO $beneficiaryAddress,
		public ?string $bankCountry,
		public ?bool $pooled,
		public ?string $uniqueReference,
		public array $schemes,
		public EstimatedTimeDTO $estimatedTime
	){}

}
