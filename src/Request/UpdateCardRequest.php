<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Request;

use Znojil\RevolutBusiness\Clear;
use Znojil\RevolutBusiness\DTO;
use Znojil\RevolutBusiness\Enum;

/**
 * @extends BaseRequest<DTO\CardDTO>
 * @link https://developer.revolut.com/docs/api/business#update-card
 *
 * @phpstan-import-type CardResponseData from DTO\CardDTO
 */
final class UpdateCardRequest extends BaseRequest{

	/**
	 * @param ?list<Enum\BusinessMerchantCategory> $categories
	 * @param DTO\MccControlsDTO|Clear|null $mccControls null leaves them unchanged, Clear::Value removes them
	 * @param ?list<string> $countries ISO 3166-1 alpha-2
	 * @param ?list<string> $accounts
	 */
	public function __construct(
		private readonly string $cardId,
		private readonly ?string $label = null,
		private readonly ?DTO\SpendingLimitsDTO $spendingLimits = null,
		private readonly ?DTO\SpendingPeriodDTO $spendingPeriod = null,
		private readonly ?array $categories = null,
		private readonly ?DTO\MerchantControlsDTO $merchantControls = null,
		private readonly DTO\MccControlsDTO|Clear|null $mccControls = null,
		private readonly ?array $countries = null,
		private readonly ?array $accounts = null
	){}

	public function getMethod(): string{
		return 'PATCH';
	}

	public function getUrn(): string{
		return 'cards/' . $this->cardId;
	}

	public function getHeaders(): array{
		return ['Content-Type' => 'application/json'];
	}

	public function getData(): array{
		return $this->buildRequiredData([
			'label' => $this->label,
			'spending_limits' => $this->spendingLimits?->toRequestData(),
			'spending_period' => $this->spendingPeriod?->toRequestData(),
			'categories' => $this->categories !== null
				? array_map(fn(Enum\BusinessMerchantCategory $c): string => $c->value, $this->categories)
				: null,
			'merchant_controls' => $this->merchantControls?->toRequestData(),
			'mcc_controls' => $this->mccControls instanceof DTO\MccControlsDTO
				? $this->mccControls->toRequestData()
				: $this->mccControls,
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
