<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\DTO;

use Znojil\RevolutBusiness\Enum\AccountNameValidationResult;

/**
 * @phpstan-import-type IndividualNameResponseData from IndividualNameDTO
 * @phpstan-import-type AccountNameValidationReasonResponseData from AccountNameValidationReasonDTO
 * @phpstan-type AccountNameValidationResponseData array{result_code: string, reason?: AccountNameValidationReasonResponseData, company_name?: string, individual_name?: IndividualNameResponseData, name_validation_id?: string}
 */
final readonly class AccountNameValidationDTO{

	/**
	 * @param AccountNameValidationResponseData $data
	 * @throws \Znojil\RevolutBusiness\Exception\UnexpectedValueException
	 */
	public static function fromResponseData(array $data): self{
		return new self(
			\Znojil\RevolutBusiness\Internal\EnumMapper::from(AccountNameValidationResult::class, $data['result_code']),
			isset($data['reason']) ? AccountNameValidationReasonDTO::fromResponseData($data['reason']) : null,
			$data['company_name'] ?? null,
			isset($data['individual_name']) ? IndividualNameDTO::fromResponseData($data['individual_name']) : null,
			$data['name_validation_id'] ?? null
		);
	}

	public function __construct(
		public AccountNameValidationResult $resultCode,
		public ?AccountNameValidationReasonDTO $reason,
		public ?string $companyName,
		public ?IndividualNameDTO $individualName,
		public ?string $nameValidationId
	){}

}
