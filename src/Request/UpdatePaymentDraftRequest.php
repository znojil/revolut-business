<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Request;

use Znojil\RevolutBusiness\Clear;

/**
 * @extends BaseRequest<null>
 * @link https://developer.revolut.com/docs/api/business#update-payment-draft
 */
final class UpdatePaymentDraftRequest extends BaseRequest{

	/**
	 * @param string|Clear|null $title null leaves the title unchanged, Clear::Value removes it
	 * @param \DateTimeInterface|Clear|null $scheduleFor null leaves the date unchanged, Clear::Value removes it
	 */
	public function __construct(
		private readonly string $paymentDraftId,
		private readonly string|Clear|null $title = null,
		private readonly \DateTimeInterface|Clear|null $scheduleFor = null
	){}

	public function getMethod(): string{
		return 'PATCH';
	}

	public function getUrn(): string{
		return 'payment-drafts/' . $this->paymentDraftId;
	}

	public function getHeaders(): array{
		return ['Content-Type' => 'application/json'];
	}

	public function getData(): array{
		return $this->buildRequiredData([
			'title' => $this->title,
			'schedule_for' => $this->scheduleFor instanceof \DateTimeInterface
				? $this->scheduleFor->format('Y-m-d')
				: $this->scheduleFor
		]);
	}

	public function createResponse(\Psr\Http\Message\ResponseInterface $httpResponse): null{
		return null;
	}

}
