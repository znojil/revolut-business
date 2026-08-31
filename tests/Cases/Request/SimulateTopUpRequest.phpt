<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Tests\Cases\Request;

use Tester\Assert;
use Znojil\RevolutBusiness\Enum;
use Znojil\RevolutBusiness\Request\SimulateTopUpRequest;

require __DIR__ . '/../../bootstrap.php';

/**
 * @testCase
 */
final class SimulateTopUpRequestTest extends \Tester\TestCase{

	public function testConfiguration(): void{
		$request = new SimulateTopUpRequest(
			'e042f1fe-f721-49cc-af82-db7a6c46944f',
			100.0,
			Enum\Currency::Gbp,
			'Test Top-up',
			Enum\SimulationTopUpState::Completed
		);
		Assert::same('POST', $request->getMethod());
		Assert::same('sandbox/topup', $request->getUrn());
		Assert::same(['Content-Type' => 'application/json'], $request->getHeaders());
		Assert::same([
			'account_id' => 'e042f1fe-f721-49cc-af82-db7a6c46944f',
			'amount' => 100.0,
			'currency' => 'GBP',
			'reference' => 'Test Top-up',
			'state' => 'completed'
		], $request->getData());

		// default properties
		Assert::same([
			'account_id' => 'e042f1fe-f721-49cc-af82-db7a6c46944f',
			'amount' => 1000.5,
			'currency' => 'GBP'
		], (new SimulateTopUpRequest(
			'e042f1fe-f721-49cc-af82-db7a6c46944f',
			1000.5,
			Enum\Currency::Gbp
		))->getData());
	}

	public function testCreateResponse(): void{
		$result = (new SimulateTopUpRequest('e042f1fe-f721-49cc-af82-db7a6c46944f', 1000.0, Enum\Currency::Gbp))
			->createResponse(\Znojil\RevolutBusiness\Tests\Fixtures\ResponseFactory::create('simulations/transaction-result'));

		Assert::same('63d2a8bd-8b67-a2de-b1d2-b58ee21d7073', $result->id);
		Assert::same(Enum\TransactionState::Completed, $result->state);
		Assert::same('2023-01-26T16:22:21+00:00', $result->createdAt->format('c'));
		Assert::same('2023-01-26T16:22:22+00:00', $result->completedAt?->format('c'));
	}

}

(new SimulateTopUpRequestTest)->run();
