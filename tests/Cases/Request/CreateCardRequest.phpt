<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Tests\Cases\Request;

use Tester\Assert;
use Znojil\RevolutBusiness\DTO;
use Znojil\RevolutBusiness\Enum;
use Znojil\RevolutBusiness\Request\CreateCardRequest;

require __DIR__ . '/../../bootstrap.php';

/**
 * @testCase
 */
final class CreateCardRequestTest extends \Tester\TestCase{

	public function testConfiguration(): void{
		$request = new CreateCardRequest(
			'7a10f3eb-fe56-4699-9bd0-044a63508828',
			'173ab846-de2a-1234-5678-160bd2e660e6',
			['0e1a8d4b-1d1e-457d-9f10-3e7007a82ea8'],
			new DTO\CardProductDTO('TRAVEL'),
			'Kirby Janette',
			[new DTO\CardReferenceDTO('Cost centre', 'HR')],
			new DTO\SpendingLimitsDTO(
				new DTO\SpendingLimitDTO(200.22, Enum\Currency::Gbp),
				null,
				new DTO\SpendingLimitDTO(200.44, Enum\Currency::Gbp),
				null,
				null,
				null,
				null
			),
			new DTO\SpendingPeriodDTO(
				null,
				new \DateTimeImmutable('2026-12-15'),
				Enum\SpendingPeriodEndAction::Terminate
			),
			[Enum\BusinessMerchantCategory::Groceries, Enum\BusinessMerchantCategory::Restaurants],
			new DTO\MerchantControlsDTO(Enum\MerchantControlType::Block, [
				'46df0a1b-3678-4ded-9cf5-9f4da1b5019d',
				'e8a87432-f71e-4deb-be84-969a02792929'
			]),
			new DTO\MccControlsDTO(Enum\MccControlType::Allow, ['5411', '7011']),
			['GB', 'SG'],
			['75aa436d-2a04-4ab9-af14-ed0955769b8c']
		);
		Assert::same('POST', $request->getMethod());
		Assert::same('cards', $request->getUrn());
		Assert::same(['Content-Type' => 'application/json'], $request->getHeaders());
		Assert::same([
			'request_id' => '7a10f3eb-fe56-4699-9bd0-044a63508828',
			'virtual' => true,
			'holder_id' => '173ab846-de2a-1234-5678-160bd2e660e6',
			'contact_ids' => ['0e1a8d4b-1d1e-457d-9f10-3e7007a82ea8'],
			'product' => ['code' => 'TRAVEL'],
			'label' => 'Kirby Janette',
			'references' => [['name' => 'Cost centre', 'value' => 'HR']],
			'spending_limits' => [
				'single' => ['amount' => 200.22, 'currency' => 'GBP'],
				'week' => ['amount' => 200.44, 'currency' => 'GBP']
			],
			'spending_period' => [
				'end_date' => '2026-12-15',
				'end_date_action' => 'terminate'
			],
			'categories' => ['groceries', 'restaurants'],
			'merchant_controls' => [
				'control_type' => 'block',
				'merchant_ids' => [
					'46df0a1b-3678-4ded-9cf5-9f4da1b5019d',
					'e8a87432-f71e-4deb-be84-969a02792929'
				]
			],
			'mcc_controls' => [
				'control_type' => 'allow',
				'mccs' => ['5411', '7011']
			],
			'countries' => ['GB', 'SG'],
			'accounts' => ['75aa436d-2a04-4ab9-af14-ed0955769b8c']
		], $request->getData());

		// default properties
		Assert::same([
			'request_id' => '7a10f3eb-fe56-4699-9bd0-044a63508829',
			'virtual' => true
		], (new CreateCardRequest('7a10f3eb-fe56-4699-9bd0-044a63508829'))->getData());
	}


	public function testCreateResponse(): void{
		$result = (new CreateCardRequest('request-id'))
			->createResponse(\Znojil\RevolutBusiness\Tests\Fixtures\ResponseFactory::create('cards/card'));

		Assert::same('8a7b6c5d-4e3f-4a1b-8c9d-0e1f2a3b4c5d', $result->id);
		Assert::true($result->virtual);
		Assert::same('4242', $result->lastDigits);
	}

}

(new CreateCardRequestTest)->run();
