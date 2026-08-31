<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Tests\Cases\Request;

use Tester\Assert;
use Znojil\RevolutBusiness\Enum\ExchangeReasonCode;
use Znojil\RevolutBusiness\Request\GetExchangeReasonsRequest;

require __DIR__ . '/../../bootstrap.php';

/**
 * @testCase
 */
final class GetExchangeReasonsRequestTest extends \Tester\TestCase{

	public function testConfiguration(): void{
		$request = new GetExchangeReasonsRequest;

		Assert::same('GET', $request->getMethod());
		Assert::same('exchange-reasons', $request->getUrn());
	}

	public function testCreateResponse(): void{
		$result = (new GetExchangeReasonsRequest)
			->createResponse(\Znojil\RevolutBusiness\Tests\Fixtures\ResponseFactory::create('foreign-exchange/exchange-reasons'));

		Assert::count(3, $result);
		Assert::same(ExchangeReasonCode::PaymentForGoodsAndServices, $result[0]->code);
		Assert::same('Payment for goods and services', $result[0]->name);
		Assert::same(ExchangeReasonCode::Payroll, $result[1]->code);
		Assert::same(ExchangeReasonCode::TravelAndTransportation, $result[2]->code);
	}

}

(new GetExchangeReasonsRequestTest)->run();
