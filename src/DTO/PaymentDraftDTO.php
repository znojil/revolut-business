<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\DTO;

use Znojil\RevolutBusiness\Enum\PaymentDraftSource;

/**
 * @phpstan-import-type DraftPaymentInfoResponseData from DraftPaymentInfoDTO
 * @phpstan-type PaymentDraftResponseData array{scheduled_for?: string, schedule_for?: string, title?: string, payments: list<DraftPaymentInfoResponseData>, source?: string}
 */
final readonly class PaymentDraftDTO{

	/**
	 * @param PaymentDraftResponseData $data
	 * @throws \Znojil\RevolutBusiness\Exception\UnexpectedValueException
	 */
	public static function fromResponseData(array $data): self{
		// the API returns `schedule_for`; the documentation lists `scheduled_for`
		$scheduledFor = $data['scheduled_for'] ?? $data['schedule_for'] ?? null;

		return new self(
			$scheduledFor !== null ? new \DateTimeImmutable($scheduledFor) : null,
			$data['title'] ?? null,
			array_map(DraftPaymentInfoDTO::fromResponseData(...), $data['payments']),
			isset($data['source']) ? \Znojil\RevolutBusiness\Internal\EnumMapper::from(PaymentDraftSource::class, $data['source']) : null
		);
	}

	/**
	 * @param list<DraftPaymentInfoDTO> $payments
	 */
	public function __construct(
		public ?\DateTimeImmutable $scheduledFor,
		public ?string $title,
		public array $payments,
		public ?PaymentDraftSource $source
	){}

}
