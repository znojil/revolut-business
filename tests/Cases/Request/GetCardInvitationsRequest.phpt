<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Tests\Cases\Request;

use Tester\Assert;
use Znojil\RevolutBusiness\Enum;
use Znojil\RevolutBusiness\Request\GetCardInvitationsRequest;

require __DIR__ . '/../../bootstrap.php';

/**
 * @testCase
 */
final class GetCardInvitationsRequestTest extends \Tester\TestCase{

	public function testConfiguration(): void{
		$request = new GetCardInvitationsRequest(
			new \DateTimeImmutable('2026-04-01 09:15:00.123456', new \DateTimeZone('UTC')),
			50,
			[Enum\CardInvitationState::Created, Enum\CardInvitationState::Expired]
		);
		Assert::same('GET', $request->getMethod());
		Assert::same('card-invitations?created_before=2026-04-01T09%3A15%3A00.123456Z&limit=50&state=created&state=expired', $request->getUrn());

		// default properties
		Assert::same('card-invitations', (new GetCardInvitationsRequest)->getUrn());
	}

	public function testCreateResponse(): void{
		$result = (new GetCardInvitationsRequest)
			->createResponse(\Znojil\RevolutBusiness\Tests\Fixtures\ResponseFactory::create('card-invitations/card-invitations'));

		Assert::count(2, $result);

		$invitation = $result[0];
		Assert::same('c3d4e5f6-a7b8-4c9d-8e0f-1a2b3c4d5e6f', $invitation->id);
		Assert::same(Enum\CardInvitationState::Created, $invitation->state);
		Assert::same('2026-04-01 09:15:00.123456', $invitation->createdAt->format('Y-m-d H:i:s.u'));
		Assert::same('2026-06-30', $invitation->expiryDate?->format('Y-m-d'));
		Assert::same('7e18625a-3e6c-4d4f-8429-216c25309a5f', $invitation->holderId);
		Assert::true($invitation->virtual);
		Assert::same('Contractor card', $invitation->label);
		Assert::null($invitation->cardId); // only set once the invitation has been redeemed

		Assert::same('Travel programme', $invitation->spendProgram?->label);
		Assert::same(250.0, $invitation->spendingLimits?->single?->amount);
		Assert::same(2000.0, $invitation->spendingLimits?->month?->amount);
		Assert::null($invitation->spendingLimits?->day);
		Assert::null($invitation->spendingLimits?->month?->usage); // do not report usage today, but they parse through the same container
		Assert::same(Enum\SpendingPeriodEndAction::Lock, $invitation->spendingPeriod?->endDateAction);
		Assert::same([Enum\BusinessMerchantCategory::Airlines, Enum\BusinessMerchantCategory::Accommodation], $invitation->categories);
		Assert::same(Enum\MerchantControlType::Block, $invitation->merchantControls?->controlType);
		Assert::same(Enum\MccControlType::Allow, $invitation->mccControls?->controlType);
		Assert::same(['5411', '7011'], $invitation->mccControls?->mccs);
		Assert::same(['GB', 'IE'], $invitation->countries);
		Assert::same(['05018b0d-e67c-4fec-bea6-415e9da9432c'], $invitation->accounts);

		// only the required properties, the missing lists become empty rather than null
		$minimal = $result[1];
		Assert::null($minimal->expiryDate);
		Assert::null($minimal->cardId);
		Assert::null($minimal->holderId); // not returned once the team member has been deleted
		Assert::null($minimal->label);
		Assert::null($minimal->spendProgram);
		Assert::null($minimal->spendingLimits);
		Assert::null($minimal->spendingPeriod);
		Assert::null($minimal->merchantControls);
		Assert::null($minimal->mccControls);
		Assert::same([], $minimal->categories);
		Assert::same([], $minimal->countries);
		Assert::same([], $minimal->accounts);
	}

}

(new GetCardInvitationsRequestTest)->run();
