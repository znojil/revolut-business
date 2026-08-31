<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Request;

use Znojil\RevolutBusiness\DTO;
use Znojil\RevolutBusiness\Enum\Currency;

/**
 * @extends BaseRequest<DTO\AccountNameValidationDTO>
 * @link https://developer.revolut.com/docs/api/business#validate-account-name
 *
 * @phpstan-import-type AccountNameValidationResponseData from DTO\AccountNameValidationDTO
 */
final class ValidateAccountNameRequest extends BaseRequest{

	public static function uk(string $accountNo, string $sortCode, string|DTO\IndividualNameDTO $name): self{
		return new self(
			accountNo: $accountNo,
			sortCode: $sortCode,
			companyName: is_string($name) ? $name : null,
			individualName: $name instanceof DTO\IndividualNameDTO ? $name : null
		);
	}

	public static function au(string $accountNo, string $bsb, string|DTO\IndividualNameDTO $name): self{
		return new self(
			accountNo: $accountNo,
			bsb: $bsb,
			companyName: is_string($name) ? $name : null,
			individualName: $name instanceof DTO\IndividualNameDTO ? $name : null
		);
	}

	public static function eu(
		string $iban,
		string $recipientCountry,
		Currency $recipientCurrency,
		string|DTO\IndividualNameDTO $name,
		?string $bic = null
	): self{
		return new self(
			iban: $iban,
			bic: $bic,
			recipientCountry: $recipientCountry,
			recipientCurrency: $recipientCurrency,
			companyName: is_string($name) ? $name : null,
			individualName: $name instanceof DTO\IndividualNameDTO ? $name : null
		);
	}

	private function __construct(
		private readonly ?string $accountNo = null,
		private readonly ?string $sortCode = null,
		private readonly ?string $bsb = null,
		private readonly ?string $iban = null,
		private readonly ?string $bic = null,
		private readonly ?string $recipientCountry = null,
		private readonly ?Currency $recipientCurrency = null,
		private readonly ?string $companyName = null,
		private readonly ?DTO\IndividualNameDTO $individualName = null
	){}

	public function getMethod(): string{
		return 'POST';
	}

	public function getUrn(): string{
		return 'account-name-validation';
	}

	public function getHeaders(): array{
		return ['Content-Type' => 'application/json'];
	}

	public function getData(): array{
		return $this->buildRequiredData([
			'account_no' => $this->accountNo,
			'sort_code' => $this->sortCode,
			'bsb' => $this->bsb,
			'iban' => $this->iban,
			'bic' => $this->bic,
			'recipient_country' => $this->recipientCountry,
			'recipient_currency' => $this->recipientCurrency?->value,
			'company_name' => $this->companyName,
			'individual_name' => $this->individualName?->toRequestData()
		]);
	}

	public function createResponse(\Psr\Http\Message\ResponseInterface $httpResponse): DTO\AccountNameValidationDTO{
		/** @var AccountNameValidationResponseData */
		$data = $this->parseJsonResponseBody((string) $httpResponse->getBody());

		return DTO\AccountNameValidationDTO::fromResponseData($data);
	}

}
