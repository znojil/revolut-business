<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Tests\Cases\Request;

use Tester\Assert;
use Znojil\RevolutBusiness\Enum;
use Znojil\RevolutBusiness\Request\GetCardsRequest;

require __DIR__ . '/../../bootstrap.php';

/**
 * @testCase
 */
final class GetCardsRequestTest extends \Tester\TestCase{

	public function testConfiguration(): void{
		$request = new GetCardsRequest(new \DateTimeImmutable('2026-03-10 14:45:12.987654', new \DateTimeZone('UTC')), 100);
		Assert::same('GET', $request->getMethod());
		Assert::same('cards?created_before=2026-03-10T14%3A45%3A12.987654Z&limit=100', $request->getUrn());

		// default properties
		Assert::same('cards', (new GetCardsRequest)->getUrn());
	}

	public function testCreateResponse(): void{
		$result = (new GetCardsRequest)->createResponse(\Znojil\RevolutBusiness\Tests\Fixtures\ResponseFactory::create('cards/cards'));

		Assert::count(2, $result);

		$card = $result[0];
		Assert::same('8a7b6c5d-4e3f-4a1b-8c9d-0e1f2a3b4c5d', $card->id);
		Assert::same('7e18625a-3e6c-4d4f-8429-216c25309a5f', $card->holderId);
		Assert::same(['0e1a8d4b-1d1e-457d-9f10-3e7007a82ea8'], $card->contactIds);
		Assert::true($card->virtual);
		Assert::same('4242', $card->lastDigits);
		Assert::same('03/2029', $card->expiry); // MM/YYYY despite what the format field claims
		Assert::same(Enum\CardState::Active, $card->state);
		Assert::false($card->canBeUnlocked);
		Assert::same('TRAVEL', $card->product?->code);
		Assert::same('Travel programme', $card->spendProgram?->label);
		Assert::same(['GB', 'IE'], $card->countries);
		Assert::same(['05018b0d-e67c-4fec-bea6-415e9da9432c'], $card->accounts);
		Assert::null($card->terminatedAt); // only the detail endpoint returns it

		Assert::same('Cost centre', $card->references[0]->name);
		Assert::same('HR', $card->references[0]->value);

		Assert::same(250.0, $card->spendingLimits?->single?->amount);
		Assert::same(2000.0, $card->spendingLimits?->month?->amount);
		Assert::same(10000.0, $card->spendingLimits?->allTime?->amount);
		Assert::null($card->spendingLimits?->day);
		Assert::null($card->spendingLimits?->week);
		Assert::null($card->spendingLimits?->month?->usage); // the listing omits usage, unlike the card detail

		Assert::same('2026-01-01', $card->spendingPeriod?->startDate?->format('Y-m-d'));
		Assert::same('2026-12-31', $card->spendingPeriod?->endDate?->format('Y-m-d'));
		Assert::same(Enum\SpendingPeriodEndAction::Lock, $card->spendingPeriod?->endDateAction);

		Assert::same([
			Enum\BusinessMerchantCategory::Airlines,
			Enum\BusinessMerchantCategory::Accommodation,
			Enum\BusinessMerchantCategory::Transport
		], $card->categories);
		Assert::same(Enum\MerchantControlType::Block, $card->merchantControls?->controlType);
		Assert::same(['b0f8f8a4-1c2d-4e3f-8a9b-0c1d2e3f4a5b'], $card->merchantControls?->merchantIds);
		Assert::same(Enum\MccControlType::Allow, $card->mccControls?->controlType);
		Assert::same(['5411', '7011'], $card->mccControls?->mccs);

		// only the required properties
		$minimal = $result[1];
		Assert::same(Enum\CardState::Frozen, $minimal->state);
		Assert::null($minimal->holderId);
		Assert::null($minimal->label);
		Assert::null($minimal->product);
		Assert::null($minimal->spendProgram);
		Assert::null($minimal->spendingLimits);
		Assert::null($minimal->spendingPeriod);
		Assert::null($minimal->merchantControls);
		Assert::null($minimal->mccControls);
		Assert::null($minimal->canBeUnlocked);
		Assert::same([], $minimal->contactIds);
		Assert::same([], $minimal->references);
		Assert::same([], $minimal->categories);
		Assert::same([], $minimal->countries);
		Assert::same([], $minimal->accounts);
	}

}

(new GetCardsRequestTest)->run();
