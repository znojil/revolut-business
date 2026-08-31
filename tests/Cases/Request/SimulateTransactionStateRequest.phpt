<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Tests\Cases\Request;

use Tester\Assert;
use Znojil\RevolutBusiness\Enum;
use Znojil\RevolutBusiness\Request\SimulateTransactionStateRequest;

require __DIR__ . '/../../bootstrap.php';

/**
 * @testCase
 */
final class SimulateTransactionStateRequestTest extends \Tester\TestCase{

	public function testConfiguration(): void{
		$request = new SimulateTransactionStateRequest('123e4567-e89b-12d3-a456-426614174000', Enum\SimulationAction::Complete);
		Assert::same('POST', $request->getMethod());
		Assert::same('sandbox/transactions/123e4567-e89b-12d3-a456-426614174000/complete', $request->getUrn());
		// a POST without a body sends no content type
		Assert::same([], $request->getHeaders());
	}

	public function testCreateResponse(): void{
		$result = (new SimulateTransactionStateRequest('transaction-id', Enum\SimulationAction::Complete))
			->createResponse(\Znojil\RevolutBusiness\Tests\Fixtures\ResponseFactory::create('simulations/transaction-result'));

		Assert::same('63d2a8bd-8b67-a2de-b1d2-b58ee21d7073', $result->id);
		Assert::same(Enum\TransactionState::Completed, $result->state);
	}

}

(new SimulateTransactionStateRequestTest)->run();
