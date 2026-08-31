<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Tests\Cases\Request;

use Tester\Assert;
use Znojil\RevolutBusiness\Enum;
use Znojil\RevolutBusiness\Request\GetCardInvitationRequest;

require __DIR__ . '/../../bootstrap.php';

/**
 * @testCase
 */
final class GetCardInvitationRequestTest extends \Tester\TestCase{

	public function testConfiguration(): void{
		$request = new GetCardInvitationRequest('123e4567-e89b-12d3-a456-426614174000');

		Assert::same('GET', $request->getMethod());
		Assert::same('card-invitations/123e4567-e89b-12d3-a456-426614174000', $request->getUrn());
	}

	public function testCreateResponse(): void{
		$result = (new GetCardInvitationRequest('4016b891-bb50-4bd2-8a1b-adb74f4aacdd'))
			->createResponse(\Znojil\RevolutBusiness\Tests\Fixtures\ResponseFactory::create('card-invitations/card-invitation'));

		Assert::same('4016b891-bb50-4bd2-8a1b-adb74f4aacdd', $result->id);
		Assert::same(Enum\CardInvitationState::Redeemed, $result->state);
		// a redeemed invitation carries the id of the card it produced
		Assert::same('8a7b6c5d-4e3f-4a1b-8c9d-0e1f2a3b4c5d', $result->cardId);
	}

}

(new GetCardInvitationRequestTest)->run();
