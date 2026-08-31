<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Tests\Cases\Request;

use Tester\Assert;
use Znojil\RevolutBusiness\Enum\Currency;
use Znojil\RevolutBusiness\Request\GetExchangeRateRequest;

require __DIR__ . '/../../bootstrap.php';

/**
 * @testCase
 */
final class GetExchangeRateRequestTest extends \Tester\TestCase{

	public function testConfiguration(): void{
		$request = new GetExchangeRateRequest(Currency::Usd, Currency::Eur, 135.5);
		Assert::same('GET', $request->getMethod());
		Assert::same('rate?from=USD&to=EUR&amount=135.5', $request->getUrn());

		// default properties
		Assert::same('rate?from=EUR&to=USD', (new GetExchangeRateRequest(Currency::Eur, Currency::Usd))->getUrn());
	}

	public function testCreateResponse(): void{
		$result = (new GetExchangeRateRequest(Currency::Usd, Currency::Eur))
			->createResponse(\Znojil\RevolutBusiness\Tests\Fixtures\ResponseFactory::create('foreign-exchange/rate'));

		Assert::same(135.5, $result->from->amount);
		Assert::same(Currency::Usd, $result->from->currency);
		Assert::same(124.03, $result->to->amount);
		Assert::same(Currency::Eur, $result->to->currency);
		Assert::same(0.91535, $result->rate);
		Assert::same(0.54, $result->fee->amount);
		Assert::same(Currency::Usd, $result->fee->currency);
		Assert::same('2026-03-19T08:45:22+00:00', $result->rateDate->format('c'));
	}

}

(new GetExchangeRateRequestTest)->run();
