<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Request;

use Znojil\RevolutBusiness\DTO;
use Znojil\RevolutBusiness\Enum;

/**
 * @extends BaseRequest<DTO\CounterpartyDTO>
 * @link https://developer.revolut.com/docs/api/business#add-counterparty
 *
 * @phpstan-import-type CounterpartyResponseData from DTO\CounterpartyDTO
 */
final class CreateCounterpartyRequest extends BaseRequest{

	/**
	 * Internal (Revolut) counterparty identified by Revtag.
	 */
	public static function revolut(
		Enum\ProfileType $profileType,
		string $revtag,
		string $name,
	): self{
		return new self([
			'profile_type' => $profileType->value,
			'revtag' => $revtag,
			'name' => $name
		]);
	}

	/**
	 * External counterparty with bank routing details.
	 * @param string $bankCountry ISO 3166-1 alpha-2
	 */
	public static function bank(
		string|DTO\IndividualNameDTO $name,
		string $bankCountry,
		Enum\Currency $currency,
		?string $accountNo = null,
		?string $iban = null,
		?string $sortCode = null,
		?string $routingNumber = null,
		?string $bic = null,
		?string $clabe = null,
		?string $ifsc = null,
		?string $bsbCode = null,
		?DTO\AddressDTO $address = null,
		?Enum\PaymentRoute $route = null,
		?string $bankNumber = null,
		?string $branchCode = null,
		?Enum\BankAccountType $accountType = null,
		?string $taxId = null,
		?string $nationalId = null,
		?string $phone = null,
		?string $businessRegistrationId = null
	): self{
		return new self(array_filter([
			'company_name' => is_string($name) ? $name : null,
			'individual_name' => $name instanceof DTO\IndividualNameDTO ? $name->toRequestData() : null,
			'bank_country' => $bankCountry,
			'currency' => $currency->value,
			'account_no' => $accountNo,
			'iban' => $iban,
			'sort_code' => $sortCode,
			'routing_number' => $routingNumber,
			'bic' => $bic,
			'clabe' => $clabe,
			'ifsc' => $ifsc,
			'bsb_code' => $bsbCode,
			'address' => $address?->toRequestData(),
			'route' => $route?->value,
			'bank_number' => $bankNumber,
			'branch_code' => $branchCode,
			'account_type' => $accountType?->value,
			'tax_id' => $taxId,
			'national_id' => $nationalId,
			'phone' => $phone,
			'business_registration_id' => $businessRegistrationId
		], fn(mixed $v): bool => $v !== null));
	}

	/**
	 * @param array<mixed> $data
	 */
	private function __construct(
		private readonly array $data
	){}

	public function getMethod(): string{
		return 'POST';
	}

	public function getUrn(): string{
		return 'counterparty';
	}

	public function getHeaders(): array{
		return ['Content-Type' => 'application/json'];
	}

	public function getData(): array{
		return $this->data;
	}

	public function createResponse(\Psr\Http\Message\ResponseInterface $httpResponse): DTO\CounterpartyDTO{
		/** @var CounterpartyResponseData */
		$data = $this->parseJsonResponseBody((string) $httpResponse->getBody());

		return DTO\CounterpartyDTO::fromResponseData($data);
	}

}
