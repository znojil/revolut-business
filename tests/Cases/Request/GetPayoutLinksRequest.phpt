<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Tests\Cases\Request;

use Tester\Assert;
use Znojil\RevolutBusiness\Enum;
use Znojil\RevolutBusiness\Request\GetPayoutLinksRequest;

require __DIR__ . '/../../bootstrap.php';

/**
 * @testCase
 */
final class GetPayoutLinksRequestTest extends \Tester\TestCase{

	public function testConfiguration(): void{
		$request = new GetPayoutLinksRequest(
			[Enum\PayoutLinkState::Active, Enum\PayoutLinkState::Expired],
			new \DateTimeImmutable('2023-07-11 13:55:54.834963', new \DateTimeZone('UTC')),
			100
		);
		Assert::same('GET', $request->getMethod());
		Assert::same('payout-links?state=active&state=expired&created_before=2023-07-11T13%3A55%3A54.834963Z&limit=100', $request->getUrn());

		// default properties
		Assert::same('payout-links', (new GetPayoutLinksRequest)->getUrn());
	}

	public function testCreateResponse(): void{
		$result = (new GetPayoutLinksRequest)
			->createResponse(\Znojil\RevolutBusiness\Tests\Fixtures\ResponseFactory::create('payout-links/payout-links'));

		Assert::count(2, $result);

		Assert::same(Enum\PayoutLinkState::Processed, $result[0]->state);
		Assert::true($result[0]->saveCounterparty);
		Assert::same('7e18625a-3e6c-4d4f-8429-216c25309a5f', $result[0]->counterpartyId);
		Assert::same('163e0ef6-2414-4fcf-846f-1f871059d506', $result[0]->transactionId);
		Assert::null($result[0]->url); // the URL is only returned for active links
		Assert::null($result[0]->expiryDate);

		Assert::same(Enum\PayoutLinkState::Cancelled, $result[1]->state);
		Assert::same(Enum\PayoutLinkCancellationReason::TooManyNameCheckAttempts, $result[1]->cancellationReason);
		Assert::same(10.0, $result[1]->amount);
		Assert::same(Enum\Currency::Eur, $result[1]->currency);
		Assert::null($result[1]->transferReasonCode);
	}

}

(new GetPayoutLinksRequestTest)->run();
