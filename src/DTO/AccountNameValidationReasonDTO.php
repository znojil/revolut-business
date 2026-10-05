<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\DTO;

use Znojil\RevolutBusiness\Enum\AccountNameValidationReasonType;

/**
 * @phpstan-type AccountNameValidationReasonResponseData array{type?: string, code?: string}
 */
final readonly class AccountNameValidationReasonDTO{

	/**
	 * @param AccountNameValidationReasonResponseData $data
	 */
	public static function fromResponseData(array $data): self{
		return new self(
			isset($data['type']) ? AccountNameValidationReasonType::from($data['type']) : null,
			$data['code'] ?? null
		);
	}

	public function __construct(
		public ?AccountNameValidationReasonType $type,
		public ?string $code
	){}

}
