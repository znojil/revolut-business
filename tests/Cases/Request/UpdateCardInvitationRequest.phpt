<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Tests\Cases\Request;

use Tester\Assert;
use Znojil\RevolutBusiness\DTO;
use Znojil\RevolutBusiness\Enum;
use Znojil\RevolutBusiness\Request\UpdateCardInvitationRequest;

require __DIR__ . '/../../bootstrap.php';

/**
 * @testCase
 */
final class UpdateCardInvitationRequestTest extends \Tester\TestCase{

	public function testConfiguration(): void{
		$request = new UpdateCardInvitationRequest(
			'123e4567-e89b-12d3-a456-426614174000',
			'New card label',
			new DTO\SpendingLimitsDTO(
				new DTO\SpendingLimitDTO(100, Enum\Currency::Gbp),
				null,
				null,
				new DTO\SpendingLimitDTO(300.0, Enum\Currency::Gbp),
				null,
				null,
				null
			),
			new DTO\SpendingPeriodDTO(new \DateTimeImmutable('2025-12-20'), new \DateTimeImmutable('2027-12-20'), Enum\SpendingPeriodEndAction::Lock),
			[
				Enum\BusinessMerchantCategory::Services,
				Enum\BusinessMerchantCategory::Shopping,
				Enum\BusinessMerchantCategory::Furniture
			],
			new DTO\MerchantControlsDTO(Enum\MerchantControlType::Block, [
				'46df0a1b-3678-4ded-9cf5-9f4da1b5019d',
				'e8a87432-f71e-4deb-be84-969a02792929',
				'3f09819f-63d1-473a-966c-54d8b9f43a93'
			]),
			new DTO\MccControlsDTO(Enum\MccControlType::Allow, ['7995']),
			['GB', 'SG', 'ES'],
			[
				'f52c6c84-26b9-4e95-bbcf-99ed6523fb51',
				'9ae4345a-5ee5-496b-b776-241fcc5a5ba4'
			]
		);
		Assert::same('PATCH', $request->getMethod());
		Assert::same('card-invitations/123e4567-e89b-12d3-a456-426614174000', $request->getUrn());
		Assert::same(['Content-Type' => 'application/json'], $request->getHeaders());
		Assert::same([
			'label' => 'New card label',
			'spending_limits' => [
				'single' => ['amount' => 100.0, 'currency' => 'GBP'],
				'month' => ['amount' => 300.0, 'currency' => 'GBP']
			],
			'spending_period' => [
				'start_date' => '2025-12-20',
				'end_date' => '2027-12-20',
				'end_date_action' => 'lock'
			],
			'categories' => ['services', 'shopping', 'furniture'],
			'merchant_controls' => ['control_type' => 'block', 'merchant_ids' => [
				'46df0a1b-3678-4ded-9cf5-9f4da1b5019d',
				'e8a87432-f71e-4deb-be84-969a02792929',
				'3f09819f-63d1-473a-966c-54d8b9f43a93'
			]],
			'mcc_controls' => ['control_type' => 'allow', 'mccs' => ['7995']],
			'countries' => ['GB', 'SG', 'ES'],
			'accounts' => [
				'f52c6c84-26b9-4e95-bbcf-99ed6523fb51',
				'9ae4345a-5ee5-496b-b776-241fcc5a5ba4'
			]
		], $request->getData());

		// the MCC controls are the only card property the API lets you clear
		Assert::same(
			['mcc_controls' => null],
			(new UpdateCardInvitationRequest('card-id', mccControls: \Znojil\RevolutBusiness\Clear::Value))->getData()
		);

		// default properties, at least one property
		Assert::same([
			'label' => 'Renamed card invitation'
		], (new UpdateCardInvitationRequest('card-id', 'Renamed card invitation'))->getData());

		// at least one property must be provided
		Assert::exception(
			fn() => (new UpdateCardInvitationRequest('card-id'))->getData(),
			\Znojil\RevolutBusiness\Exception\InvalidArgumentException::class
		);
	}

	public function testCreateResponse(): void{
		$result = (new UpdateCardInvitationRequest('4016b891-bb50-4bd2-8a1b-adb74f4aacdd', 'Renamed invitation'))
			->createResponse(\Znojil\RevolutBusiness\Tests\Fixtures\ResponseFactory::create('card-invitations/card-invitation'));

		Assert::same('4016b891-bb50-4bd2-8a1b-adb74f4aacdd', $result->id);
		Assert::same(Enum\CardInvitationState::Redeemed, $result->state);
	}

}

(new UpdateCardInvitationRequestTest)->run();
