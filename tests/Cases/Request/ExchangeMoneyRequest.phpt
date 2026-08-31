<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Tests\Cases\Request;

use Tester\Assert;
use Znojil\RevolutBusiness\Enum;
use Znojil\RevolutBusiness\Request\ExchangeMoneyRequest;

require __DIR__ . '/../../bootstrap.php';

/**
 * @testCase
 */
final class ExchangeMoneyRequestTest extends \Tester\TestCase{

	public function testSellAndConfiguration(): void{
		$request = ExchangeMoneyRequest::sell(135.5, 'from-account-id', Enum\Currency::Usd, 'to-account-id', Enum\Currency::Eur, 'request-id');

		Assert::same('POST', $request->getMethod());
		Assert::same('exchange', $request->getUrn());
		Assert::same(['Content-Type' => 'application/json'], $request->getHeaders());
		Assert::same([
			'from' => [
				'account_id' => 'from-account-id',
				'currency' => 'USD',
				'amount' => 135.5
			],
			'to' => [
				'account_id' => 'to-account-id',
				'currency' => 'EUR'
			],
			'request_id' => 'request-id'
		], $request->getData());
	}

	public function testBuyPutsAmountIntoTo(): void{
		Assert::same([
			'from' => [
				'account_id' => 'from-account-id',
				'currency' => 'USD'
			],
			'to' => [
				'account_id' => 'to-account-id',
				'currency' => 'EUR',
				'amount' => 200.0
			],
			'reference' => 'exchange',
			'request_id' => 'request-id',
			'exchange_reason_code' => 'payroll'
		], ExchangeMoneyRequest::buy(
			200.0,
			'from-account-id',
			Enum\Currency::Usd,
			'to-account-id',
			Enum\Currency::Eur,
			'request-id',
			'exchange',
			Enum\ExchangeReasonCode::Payroll
		)->getData());
	}

	public function testCreateResponse(): void{
		$result = ExchangeMoneyRequest::sell(135.5, 'from-account-id', Enum\Currency::Usd, 'to-account-id', Enum\Currency::Eur, 'request-id')
			->createResponse(\Znojil\RevolutBusiness\Tests\Fixtures\ResponseFactory::create('foreign-exchange/exchange'));

		Assert::same('630f9c2e-2e74-a06d-ab61-deb7ggkkd6cb', $result->id);
		Assert::same('exchange', $result->type);
		Assert::same(Enum\TransactionState::Completed, $result->state);
		Assert::same('2022-08-31T17:36:46+00:00', $result->createdAt->format('c'));
		Assert::same('2022-08-31T17:36:46+00:00', $result->completedAt?->format('c'));
		Assert::null($result->reasonCode); // only present when the state is declined or failed
	}

}

(new ExchangeMoneyRequestTest)->run();
