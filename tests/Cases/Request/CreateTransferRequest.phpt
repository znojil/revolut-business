<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Tests\Cases\Request;

use Tester\Assert;
use Znojil\RevolutBusiness\Enum;
use Znojil\RevolutBusiness\Request\CreateTransferRequest;

require __DIR__ . '/../../bootstrap.php';

/**
 * @testCase
 */
final class CreateTransferRequestTest extends \Tester\TestCase{

	public function testConfiguration(): void{
		$request = new CreateTransferRequest(
			'2e6de1bf-97ad-478d-aad1-9d7a3cdf1234',
			'ae2e1241-81dd-498d-868e-075484785678',
			10.0,
			Enum\Currency::Gbp,
			'129999',
			"John's transfer"
		);
		Assert::same('POST', $request->getMethod());
		Assert::same('transfer', $request->getUrn());
		Assert::same(['Content-Type' => 'application/json'], $request->getHeaders());
		Assert::same([
			'request_id' => '129999',
			'source_account_id' => '2e6de1bf-97ad-478d-aad1-9d7a3cdf1234',
			'target_account_id' => 'ae2e1241-81dd-498d-868e-075484785678',
			'amount' => 10.0,
			'currency' => 'GBP',
			'reference' => "John's transfer"
		], $request->getData());

		// default properties
		Assert::same([
			'request_id' => '129999',
			'source_account_id' => '2e6de1bf-97ad-478d-aad1-9d7a3cdf1234',
			'target_account_id' => 'ae2e1241-81dd-498d-868e-075484785678',
			'amount' => 10.0,
			'currency' => 'GBP'
		], (new CreateTransferRequest(
			'2e6de1bf-97ad-478d-aad1-9d7a3cdf1234',
			'ae2e1241-81dd-498d-868e-075484785678',
			10,
			Enum\Currency::Gbp,
			'129999'
		))->getData());
	}

	public function testCreateResponse(): void{
		$result = (new CreateTransferRequest('source-id', 'target-id', 100.0, Enum\Currency::Gbp, 'request-id'))
			->createResponse(\Znojil\RevolutBusiness\Tests\Fixtures\ResponseFactory::create('transfers/transfer-result'));

		Assert::same('9a6434d8-3581-4faa-988b-48875e785be7', $result->id);
		Assert::same(Enum\TransactionState::Pending, $result->state);
	}

}

(new CreateTransferRequestTest)->run();
