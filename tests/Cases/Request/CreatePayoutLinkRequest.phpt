<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Tests\Cases\Request;

use Tester\Assert;
use Znojil\RevolutBusiness\Enum;
use Znojil\RevolutBusiness\Request\CreatePayoutLinkRequest;

require __DIR__ . '/../../bootstrap.php';

/**
 * @testCase
 */
final class CreatePayoutLinkRequestTest extends \Tester\TestCase{

	public function testConfiguration(): void{
		$request = new CreatePayoutLinkRequest(
			'John Smith',
			'account-id',
			105.5,
			Enum\Currency::Gbp,
			'Invoice 2026/03',
			'request-id',
			false,
			[Enum\PayoutMethod::Revolut, Enum\PayoutMethod::BankAccount],
			'P3D',
			Enum\TransferReasonCode::Services
		);
		Assert::same('POST', $request->getMethod());
		Assert::same('payout-links', $request->getUrn());
		Assert::same(['Content-Type' => 'application/json'], $request->getHeaders());
		Assert::same([
			'counterparty_name' => 'John Smith',
			'save_counterparty' => false, // an explicit false must not be dropped
			'request_id' => 'request-id',
			'account_id' => 'account-id',
			'amount' => 105.5,
			'currency' => 'GBP',
			'reference' => 'Invoice 2026/03',
			'payout_methods' => ['revolut', 'bank_account'],
			'expiry_period' => 'P3D',
			'transfer_reason_code' => 'services'
		], $request->getData());

		// default properties
		Assert::same([
			'counterparty_name' => 'John Smith',
			'request_id' => 'request-id',
			'account_id' => 'account-id',
			'amount' => 105.5,
			'currency' => 'GBP',
			'reference' => 'Invoice 2026/03'
		], (new CreatePayoutLinkRequest('John Smith', 'account-id', 105.5, Enum\Currency::Gbp, 'Invoice 2026/03', 'request-id'))->getData());
	}

	public function testCreateResponse(): void{
		$result = (new CreatePayoutLinkRequest('John Smith', 'account-id', 105.5, Enum\Currency::Gbp, 'Invoice 2026/03', 'request-id'))
			->createResponse(\Znojil\RevolutBusiness\Tests\Fixtures\ResponseFactory::create('payout-links/payout-link'));

		Assert::same('12dcd8c2-6408-458f-98a9-3f4abc180898', $result->id);
		Assert::same(Enum\PayoutLinkState::Active, $result->state);
		Assert::same('John Smith', $result->counterpartyName);
		Assert::false($result->saveCounterparty);
		Assert::same('2023-07-18T13:55:55+00:00', $result->expiryDate?->format('c'));
		Assert::same([Enum\PayoutMethod::Revolut, Enum\PayoutMethod::BankAccount, Enum\PayoutMethod::Card], $result->payoutMethods);
		Assert::same(105.5, $result->amount);
		Assert::same(Enum\Currency::Gbp, $result->currency);
		Assert::same('https://revolut.me/pay/12dcd8c2-6408-458f-98a9-3f4abc180898', $result->url);
		Assert::same(Enum\TransferReasonCode::Services, $result->transferReasonCode);

		// only returned by the retrieval endpoints
		Assert::null($result->counterpartyId);
		Assert::null($result->transactionId);
		Assert::null($result->cancellationReason);
	}

}

(new CreatePayoutLinkRequestTest)->run();
