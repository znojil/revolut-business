<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\DTO;

use Znojil\RevolutBusiness\Enum\PaymentDraftSource;

/**
 * @phpstan-type PaymentDraftInfoResponseData array{id: string, scheduled_for?: string, title?: string, payments_count: int, source?: string}
 */
final readonly class PaymentDraftInfoDTO{

	/**
	 * @param PaymentDraftInfoResponseData $data
	 * @throws \Znojil\RevolutBusiness\Exception\UnexpectedValueException
	 */
	public static function fromResponseData(array $data): self{
		return new self(
			$data['id'],
			isset($data['scheduled_for']) ? new \DateTimeImmutable($data['scheduled_for']) : null,
			$data['title'] ?? null,
			$data['payments_count'],
			isset($data['source']) ? \Znojil\RevolutBusiness\Internal\EnumMapper::from(PaymentDraftSource::class, $data['source']) : null
		);
	}

	public function __construct(
		public string $id,
		public ?\DateTimeImmutable $scheduledFor,
		public ?string $title,
		public int $paymentsCount,
		public ?PaymentDraftSource $source
	){}

}
