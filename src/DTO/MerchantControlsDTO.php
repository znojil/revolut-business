<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\DTO;

use Znojil\RevolutBusiness\Enum\MerchantControlType;

/**
 * @phpstan-type MerchantControlsResponseData array{control_type: string, merchant_ids: non-empty-list<string>}
 */
final readonly class MerchantControlsDTO{

	/**
	 * @param MerchantControlsResponseData $data
	 * @throws \Znojil\RevolutBusiness\Exception\UnexpectedValueException
	 */
	public static function fromResponseData(array $data): self{
		return new self(
			\Znojil\RevolutBusiness\Internal\EnumMapper::from(MerchantControlType::class, $data['control_type']),
			$data['merchant_ids']
		);
	}

	/**
	 * @param non-empty-list<string> $merchantIds max 20
	 */
	public function __construct(
		public MerchantControlType $controlType,
		public array $merchantIds
	){}

	/**
	 * @return MerchantControlsResponseData
	 */
	public function toRequestData(): array{
		return [
			'control_type' => $this->controlType->value,
			'merchant_ids' => $this->merchantIds
		];
	}

}
