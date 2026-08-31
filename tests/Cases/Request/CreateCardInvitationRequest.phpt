<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Tests\Cases\Request;

use Tester\Assert;
use Znojil\RevolutBusiness\DTO;
use Znojil\RevolutBusiness\Enum;
use Znojil\RevolutBusiness\Request\CreateCardInvitationRequest;

require __DIR__ . '/../../bootstrap.php';

/**
 * @testCase
 */
final class CreateCardInvitationRequestTest extends \Tester\TestCase{

	public function testConfiguration(): void{
		$request = new CreateCardInvitationRequest(
			'5027c902-cc61-5ce3-9b2c-bec85f5bbdce',
			'4016b891-bb50-4bd2-8a1b-adb74f4aacdd',
			'P14D',
			'Dariel Pattie',
			new DTO\SpendingLimitsDTO(
				new DTO\SpendingLimitDTO(50.0, Enum\Currency::Gbp),
				null,
				new DTO\SpendingLimitDTO(250, Enum\Currency::Gbp),
				null,
				null,
				null,
				null
			),
			new DTO\SpendingPeriodDTO(null, new \DateTimeImmutable('2026-12-31'), Enum\SpendingPeriodEndAction::Terminate),
			[Enum\BusinessMerchantCategory::Groceries, Enum\BusinessMerchantCategory::Restaurants],
			new DTO\MerchantControlsDTO(Enum\MerchantControlType::Block, [
				'14c8d663-2093-4086-96ed-334a4011333f',
				'06e0f7fa-38a2-4fcf-a115-df5caf190b55'
			]),
			new DTO\MccControlsDTO(Enum\MccControlType::Allow, ['5411']),
			['GB'],
			['26a852b8-4046-4e71-949d-22f4cb6be26a']
		);
		Assert::same('POST', $request->getMethod());
		Assert::same('card-invitations', $request->getUrn());
		Assert::same(['Content-Type' => 'application/json'], $request->getHeaders());
		Assert::same([
			'request_id' => '4016b891-bb50-4bd2-8a1b-adb74f4aacdd',
			'holder_id' => '5027c902-cc61-5ce3-9b2c-bec85f5bbdce',
			'virtual' => true,
			'expiry_period' => 'P14D',
			'label' => 'Dariel Pattie',
			'spending_limits' => [
				'single' => ['amount' => 50.0, 'currency' => 'GBP'],
				'week' => ['amount' => 250.0, 'currency' => 'GBP']
			],
			'spending_period' => ['end_date' => '2026-12-31', 'end_date_action' => 'terminate'],
			'categories' => ['groceries', 'restaurants'],
			'merchant_controls' => ['control_type' => 'block', 'merchant_ids' => [
				'14c8d663-2093-4086-96ed-334a4011333f',
				'06e0f7fa-38a2-4fcf-a115-df5caf190b55'
			]],
			'mcc_controls' => ['control_type' => 'allow', 'mccs' => ['5411']],
			'countries' => ['GB'],
			'accounts' => ['26a852b8-4046-4e71-949d-22f4cb6be26a']
		], $request->getData());

		// default properties
		Assert::same([
			'request_id' => '4016b891-bb50-4bd2-8a1b-adb74f4aacdd',
			'holder_id' => '3791712d-e5b5-4d5f-b1f1-79a93d57e08b',
			'virtual' => true
		], (new CreateCardInvitationRequest('3791712d-e5b5-4d5f-b1f1-79a93d57e08b', '4016b891-bb50-4bd2-8a1b-adb74f4aacdd'))->getData());
	}

	public function testCreateResponse(): void{
		$result = (new CreateCardInvitationRequest('7e18625a-3e6c-4d4f-8429-216c25309a5f', 'request-id'))
			->createResponse(\Znojil\RevolutBusiness\Tests\Fixtures\ResponseFactory::create('card-invitations/card-invitation'));

		Assert::same('4016b891-bb50-4bd2-8a1b-adb74f4aacdd', $result->id);
		Assert::same('Contractor card', $result->label);
	}

}

(new CreateCardInvitationRequestTest)->run();
