<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Tests\Cases\Request;

use Tester\Assert;
use Znojil\RevolutBusiness\DTO\PaymentReceiverDTO;
use Znojil\RevolutBusiness\Enum;
use Znojil\RevolutBusiness\Request\GetIndicativeQuoteRequest;
use Znojil\RevolutBusiness\Tests\Fixtures\ResponseFactory;

require __DIR__ . '/../../bootstrap.php';

/**
 * @testCase
 */
final class GetIndicativeQuoteRequestTest extends \Tester\TestCase{

	public function testConfiguration(): void{
		$request = (new GetIndicativeQuoteRequest(
			'a1b2c3d4-e5f6-7890-abcd-ef1234567890',
			new PaymentReceiverDTO('f7e6d5c4-b3a2-1098-7654-321fedcba098', '12345678-90ab-cdef-1234-567890abcdef', 'card-id'),
			100.5,
			Enum\Currency::Eur,
			Enum\ChargeBearer::Shared,
			Enum\TransferReasonCode::Services,
			Enum\ExchangeReasonCode::Payroll
		));
		Assert::same('POST', $request->getMethod());
		Assert::same('pay/indicative-quote', $request->getUrn());
		Assert::same(['Content-Type' => 'application/json'], $request->getHeaders());
		Assert::same([
			'account_id' => 'a1b2c3d4-e5f6-7890-abcd-ef1234567890',
			'receiver' => [
				'counterparty_id' => 'f7e6d5c4-b3a2-1098-7654-321fedcba098',
				'account_id' => '12345678-90ab-cdef-1234-567890abcdef',
				'card_id' => 'card-id'
			],
			'amount' => 100.5,
			'currency' => 'EUR',
			'charge_bearer' => 'shared',
			'transfer_reason_code' => 'services',
			'exchange_reason_code' => 'payroll'
		], $request->getData());

		// default properties
		Assert::same([
			'account_id' => 'a1b2c3d4-e5f6-7890-abcd-ef1234567890',
			'receiver' => ['counterparty_id' => 'f7e6d5c4-b3a2-1098-7654-321fedcba098'],
			'amount' => 100.0,
			'currency' => 'GBP'
		], $this->getRequest()->getData());
	}

	public function testCreateResponse(): void{
		$result = $this->getRequest()->createResponse(ResponseFactory::create('transfers/indicative-quote'));

		Assert::same(100.0, $result->amount->amount);
		Assert::same(Enum\Currency::Gbp, $result->amount->currency);
		Assert::same(101.25, $result->estimatedTotal->amount);

		Assert::same(1.25, $result->fee->total->amount);
		Assert::same(0.5, $result->fee->transferFee?->amount);
		Assert::same(0.75, $result->fee->exchangeFee?->amount);

		// the API returns a float here, the specification declares a string
		Assert::same(1.1725, $result->estimatedExchangeRate);
		Assert::same(117.25, $result->estimatedAmountAfterExchange?->amount);
		Assert::same(Enum\Currency::Eur, $result->estimatedAmountAfterExchange?->currency);

		Assert::same('2026-03-20', $result->estimatedArrival?->date->format('Y-m-d'));
		Assert::same(Enum\EstimatedArrivalSpeed::ByDate, $result->estimatedArrival?->speed);
		Assert::same([Enum\QuoteWarning::VolatileFxRate], $result->warnings);
	}

	public function testCreateResponseForSameCurrencyTransfer(): void{
		$result = $this->getRequest()->createResponse(ResponseFactory::create('transfers/indicative-quote-same-currency'));

		// the exchange related fields are omitted when no conversion happens
		Assert::null($result->estimatedExchangeRate);
		Assert::null($result->estimatedAmountAfterExchange);
		Assert::null($result->estimatedArrival);
		Assert::same([], $result->warnings);

		// the breakdown is omitted when the total fee is zero
		Assert::same(0.0, $result->fee->total->amount);
		Assert::null($result->fee->transferFee);
		Assert::null($result->fee->exchangeFee);
	}

	private function getRequest(): GetIndicativeQuoteRequest{
		return new GetIndicativeQuoteRequest(
			'a1b2c3d4-e5f6-7890-abcd-ef1234567890',
			new PaymentReceiverDTO('f7e6d5c4-b3a2-1098-7654-321fedcba098', null, null),
			100,
			Enum\Currency::Gbp
		);
	}

}

(new GetIndicativeQuoteRequestTest)->run();
