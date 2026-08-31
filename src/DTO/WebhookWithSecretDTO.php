<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\DTO;

use Znojil\RevolutBusiness\Enum\WebhookEventType;
use Znojil\RevolutBusiness\Internal\EnumMapper;

/**
 * @phpstan-type WebhookWithSecretResponseData array{id: string, url: string, events: list<string>, signing_secret: string}
 */
final readonly class WebhookWithSecretDTO{

	/**
	 * @param WebhookWithSecretResponseData $data
	 * @throws \Znojil\RevolutBusiness\Exception\UnexpectedValueException
	 */
	public static function fromResponseData(array $data): self{
		return new self(
			$data['id'],
			$data['url'],
			array_map(
				fn(string $v): WebhookEventType => EnumMapper::from(WebhookEventType::class, $v),
				$data['events']
			),
			$data['signing_secret']
		);
	}

	/**
	 * @param list<WebhookEventType> $events
	 */
	public function __construct(
		public string $id,
		public string $url,
		public array $events,
		public string $signingSecret
	){}

}
