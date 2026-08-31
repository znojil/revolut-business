<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\DTO;

use Znojil\RevolutBusiness\Enum;
use Znojil\RevolutBusiness\Internal\EnumMapper;

/**
 * @phpstan-import-type AddressResponseData from AddressDTO
 * @phpstan-type CounterpartyAccountResponseData array{id: string, name?: string, bank_country?: string, currency: string, type: string, account_no?: string, iban?: string, sort_code?: string, routing_number?: string, bic?: string, clabe?: string, ifsc?: string, bsb_code?: string, route?: string, bank_number?: string, branch_code?: string, account_type?: string, tax_id?: string, national_id?: string, phone?: string, business_registration_id?: string, recipient_charges?: string, address?: AddressResponseData}
 */
final readonly class CounterpartyAccountDTO{

	/**
	 * @param CounterpartyAccountResponseData $data
	 * @throws \Znojil\RevolutBusiness\Exception\UnexpectedValueException
	 */
	public static function fromResponseData(array $data): self{
		return new self(
			$data['id'],
			$data['name'] ?? null,
			$data['bank_country'] ?? null,
			Enum\Currency::tryFrom($data['currency']) ?? $data['currency'],
			EnumMapper::from(Enum\CounterpartyAccountType::class, $data['type']),
			$data['account_no'] ?? null,
			$data['iban'] ?? null,
			$data['sort_code'] ?? null,
			$data['routing_number'] ?? null,
			$data['bic'] ?? null,
			$data['clabe'] ?? null,
			$data['ifsc'] ?? null,
			$data['bsb_code'] ?? null,
			isset($data['route']) ? (Enum\PaymentRoute::tryFrom($data['route']) ?? $data['route']) : null,
			$data['bank_number'] ?? null,
			$data['branch_code'] ?? null,
			isset($data['account_type']) ? EnumMapper::from(Enum\BankAccountType::class, $data['account_type']) : null,
			$data['tax_id'] ?? null,
			$data['national_id'] ?? null,
			$data['phone'] ?? null,
			$data['business_registration_id'] ?? null,
			isset($data['recipient_charges']) ? EnumMapper::from(Enum\RecipientCharges::class, $data['recipient_charges']) : null,
			isset($data['address']) ? AddressDTO::fromResponseData($data['address']) : null
		);
	}

	/**
	 * @param ?string $bankCountry ISO 3166-1 alpha-2
	 * @param ?Enum\RecipientCharges $recipientCharges deprecated, returned for legacy purposes only
	 */
	public function __construct(
		public string $id,
		public ?string $name,
		public ?string $bankCountry,
		public string|Enum\Currency $currency,
		public Enum\CounterpartyAccountType $type,
		public ?string $accountNo,
		public ?string $iban,
		public ?string $sortCode,
		public ?string $routingNumber,
		public ?string $bic,
		public ?string $clabe,
		public ?string $ifsc,
		public ?string $bsbCode,
		public string|Enum\PaymentRoute|null $route,
		public ?string $bankNumber,
		public ?string $branchCode,
		public ?Enum\BankAccountType $accountType,
		public ?string $taxId,
		public ?string $nationalId,
		public ?string $phone,
		public ?string $businessRegistrationId,
		public ?Enum\RecipientCharges $recipientCharges,
		public ?AddressDTO $address
	){}

}
