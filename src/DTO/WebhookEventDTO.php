<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\DTO;

/**
 * @phpstan-type WebhookEventResponseData array{id: string, created_at: string, updated_at: string, webhook_id: string, webhook_url: string, payload: array<string, mixed>, last_sent_date?: string}
 */
final readonly class WebhookEventDTO{

	/**
	 * @param WebhookEventResponseData $data
	 */
	public static function fromResponseData(array $data): self{
		return new self(
			$data['id'],
			new \DateTimeImmutable($data['created_at']),
			new \DateTimeImmutable($data['updated_at']),
			$data['webhook_id'],
			$data['webhook_url'],
			$data['payload'],
			isset($data['last_sent_date']) ? new \DateTimeImmutable($data['last_sent_date']) : null
		);
	}

	/**
	 * @param array<string, mixed> $payload
	 */
	public function __construct(
		public string $id,
		public \DateTimeImmutable $createdAt,
		public \DateTimeImmutable $updatedAt,
		public string $webhookId,
		public string $webhookUrl,
		public array $payload,
		public ?\DateTimeImmutable $lastSentDate
	){}

}
