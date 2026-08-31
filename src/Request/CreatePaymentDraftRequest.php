<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Request;

use Znojil\RevolutBusiness\DTO\DraftPaymentDTO;

/**
 * @extends BaseRequest<string>
 * @link https://developer.revolut.com/docs/api/business#create-payment-draft
 */
final class CreatePaymentDraftRequest extends BaseRequest{

	/**
	 * @param non-empty-list<DraftPaymentDTO> $payments
	 */
	public function __construct(
		private readonly array $payments,
		private readonly ?string $title = null,
		private readonly ?\DateTimeInterface $scheduleFor = null
	){}

	public function getMethod(): string{
		return 'POST';
	}

	public function getUrn(): string{
		return 'payment-drafts';
	}

	public function getHeaders(): array{
		return ['Content-Type' => 'application/json'];
	}

	public function getData(): array{
		return $this->buildData([
			'title' => $this->title,
			'schedule_for' => $this->scheduleFor?->format('Y-m-d'),
			'payments' => array_map(fn(DraftPaymentDTO $p): array => $p->toRequestData(), $this->payments)
		]);
	}

	public function createResponse(\Psr\Http\Message\ResponseInterface $httpResponse): string{
		/** @var array{id: string} */
		$data = $this->parseJsonResponseBody((string) $httpResponse->getBody());

		return $data['id'];
	}

}
