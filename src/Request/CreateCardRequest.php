<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Request;

use Znojil\RevolutBusiness\DTO;
use Znojil\RevolutBusiness\Enum;

/**
 * @extends BaseRequest<DTO\CardDTO>
 * @link https://developer.revolut.com/docs/api/business#create-card
 *
 * @phpstan-import-type CardResponseData from DTO\CardDTO
 */
final class CreateCardRequest extends BaseRequest{

	/**
	 * @param ?non-empty-list<string> $contactIds max 5
	 * @param ?non-empty-list<DTO\CardReferenceDTO> $references max 5
	 * @param ?list<Enum\BusinessMerchantCategory> $categories
	 * @param ?list<string> $countries ISO 3166-1 alpha-2
	 * @param ?list<string> $accounts
	 */
	public function __construct(
		private readonly string $requestId,
		private readonly ?string $holderId = null,
		private readonly ?array $contactIds = null,
		private readonly ?DTO\CardProductDTO $product = null,
		private readonly ?string $label = null,
		private readonly ?array $references = null,
		private readonly ?DTO\SpendingLimitsDTO $spendingLimits = null,
		private readonly ?DTO\SpendingPeriodDTO $spendingPeriod = null,
		private readonly ?array $categories = null,
		private readonly ?DTO\MerchantControlsDTO $merchantControls = null,
		private readonly ?DTO\MccControlsDTO $mccControls = null,
		private readonly ?array $countries = null,
		private readonly ?array $accounts = null
	){}

	public function getMethod(): string{
		return 'POST';
	}

	public function getUrn(): string{
		return 'cards';
	}

	public function getHeaders(): array{
		return ['Content-Type' => 'application/json'];
	}

	public function getData(): array{
		return $this->buildData([
			'request_id' => $this->requestId,
			'virtual' => true, // the API only issues virtual cards
			'holder_id' => $this->holderId,
			'contact_ids' => $this->contactIds,
			'product' => $this->product?->toRequestData(),
			'label' => $this->label,
			'references' => $this->references !== null
				? array_map(fn(DTO\CardReferenceDTO $r): array => $r->toRequestData(), $this->references)
				: null,
			'spending_limits' => $this->spendingLimits?->toRequestData(),
			'spending_period' => $this->spendingPeriod?->toRequestData(),
			'categories' => $this->categories !== null
				? array_map(fn(Enum\BusinessMerchantCategory $c): string => $c->value, $this->categories)
				: null,
			'merchant_controls' => $this->merchantControls?->toRequestData(),
			'mcc_controls' => $this->mccControls?->toRequestData(),
			'countries' => $this->countries,
			'accounts' => $this->accounts
		]);
	}

	public function createResponse(\Psr\Http\Message\ResponseInterface $httpResponse): DTO\CardDTO{
		/** @var CardResponseData */
		$data = $this->parseJsonResponseBody((string) $httpResponse->getBody());

		return DTO\CardDTO::fromResponseData($data);
	}

}
