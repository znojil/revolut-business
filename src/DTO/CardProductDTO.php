<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\DTO;

/**
 * @phpstan-type CardProductResponseData array{code: string}
 */
final readonly class CardProductDTO{

	/**
	 * @param CardProductResponseData $data
	 */
	public static function fromResponseData(array $data): self{
		return new self($data['code']);
	}

	public function __construct(
		public string $code
	){}

	/**
	 * @return CardProductResponseData
	 */
	public function toRequestData(): array{
		return ['code' => $this->code];
	}

}
