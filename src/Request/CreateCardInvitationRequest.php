<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Request;

use Znojil\RevolutBusiness\DTO;
use Znojil\RevolutBusiness\Enum;

/**
 * @extends BaseRequest<DTO\CardInvitationDTO>
 * @link https://developer.revolut.com/docs/api/business#create-card-invitation
 *
 * @phpstan-import-type CardInvitationResponseData from DTO\CardInvitationDTO
 */
final class CreateCardInvitationRequest extends BaseRequest{

	/**
	 * @param ?string $expiryPeriod ISO 8601 duration in days only, e.g. P90D (the default)
	 * @param ?list<Enum\BusinessMerchantCategory> $categories
	 * @param ?list<string> $countries ISO 3166-1 alpha-2
	 * @param ?list<string> $accounts
	 */
	public function __construct(
		private readonly string $holderId,
		private readonly string $requestId,
		private readonly ?string $expiryPeriod = null,
		private readonly ?string $label = null,
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
		return 'card-invitations';
	}

	public function getHeaders(): array{
		return ['Content-Type' => 'application/json'];
	}

	public function getData(): array{
		return $this->buildData([
			'request_id' => $this->requestId,
			'holder_id' => $this->holderId,
			'virtual' => true, // the API only issues virtual cards
			'expiry_period' => $this->expiryPeriod,
			'label' => $this->label,
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

	public function createResponse(\Psr\Http\Message\ResponseInterface $httpResponse): DTO\CardInvitationDTO{
		/** @var CardInvitationResponseData */
		$data = $this->parseJsonResponseBody((string) $httpResponse->getBody());

		return DTO\CardInvitationDTO::fromResponseData($data);
	}

}
