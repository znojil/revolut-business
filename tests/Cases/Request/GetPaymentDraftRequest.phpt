<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Tests\Cases\Request;

use Tester\Assert;
use Znojil\RevolutBusiness\Enum;
use Znojil\RevolutBusiness\Request\GetPaymentDraftRequest;

require __DIR__ . '/../../bootstrap.php';

/**
 * @testCase
 */
final class GetPaymentDraftRequestTest extends \Tester\TestCase{

	public function testConfiguration(): void{
		$request = new GetPaymentDraftRequest('e7e54cb2-861a-aaaa-80e9-3e6600f3db10');

		Assert::same('GET', $request->getMethod());
		Assert::same('payment-drafts/e7e54cb2-861a-aaaa-80e9-3e6600f3db10', $request->getUrn());
	}

	public function testCreateResponse(): void{
		$result = (new GetPaymentDraftRequest('e7e54cb2-861a-aaaa-80e9-3e6600f3db10'))
			->createResponse(\Znojil\RevolutBusiness\Tests\Fixtures\ResponseFactory::create('payment-drafts/payment-draft'));

		// the API returns schedule_for, the specification documents scheduled_for
		Assert::same('2026-06-24', $result->scheduledFor?->format('Y-m-d'));
		Assert::same('Draft Payment through API', $result->title);
		Assert::same(Enum\PaymentDraftSource::BusinessApp, $result->source);
		Assert::count(2, $result->payments);

		$payment = $result->payments[0];
		Assert::same('645a7696-22f3-aa47-9c74-cbae0449cc46', $payment->id);
		Assert::same(123.45, $payment->amount->amount);
		Assert::same(Enum\Currency::Gbp, $payment->amount->currency);
		Assert::same(Enum\Currency::Gbp, $payment->currency);
		Assert::same('05018b0d-e67c-4fec-bea6-415e9da9432c', $payment->accountId);
		Assert::same(Enum\PaymentState::Pending, $payment->state); // the API sends payment states in upper case
		Assert::same('7e18625a-3e6c-4d4f-8429-216c25309a5f', $payment->receiver?->counterpartyId);
		Assert::same('ff29e658-f07f-4d81-bc0f-7ad0ff141357', $payment->receiver?->accountId);
		Assert::null($payment->receiver?->cardId);
		Assert::same('Invoice 2026/03', $payment->reference);
		Assert::same(Enum\TransferReasonCode::Services, $payment->transferReasonCode);
		Assert::same('1.16', $payment->currentChargeOptions->rate); // the rate is a string in this schema
		Assert::same(0.5, $payment->currentChargeOptions->fee?->amount);

		// only the required properties
		$minimal = $result->payments[1];
		Assert::same(Enum\PaymentState::Failed, $minimal->state);
		Assert::same('Insufficient balance', $minimal->errorMessage);
		Assert::null($minimal->receiver); // no longer required since the specification update
		Assert::null($minimal->currency);
		Assert::null($minimal->reference);
		Assert::null($minimal->transferReasonCode);
		Assert::null($minimal->currentChargeOptions->rate);
		Assert::null($minimal->currentChargeOptions->fee);
	}

}

(new GetPaymentDraftRequestTest)->run();
