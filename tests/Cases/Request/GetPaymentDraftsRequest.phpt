<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Tests\Cases\Request;

use Tester\Assert;
use Znojil\RevolutBusiness\Enum;
use Znojil\RevolutBusiness\Request\GetPaymentDraftsRequest;

require __DIR__ . '/../../bootstrap.php';

/**
 * @testCase
 */
final class GetPaymentDraftsRequestTest extends \Tester\TestCase{

	public function testConfiguration(): void{
		$request = new GetPaymentDraftsRequest(Enum\PaymentDraftSourceFilter::All);
		Assert::same('GET', $request->getMethod());
		Assert::same('payment-drafts?source=all', $request->getUrn());

		// default properties
		Assert::same('payment-drafts', (new GetPaymentDraftsRequest)->getUrn());
	}

	public function testCreateResponse(): void{
		$result = (new GetPaymentDraftsRequest)
			->createResponse(\Znojil\RevolutBusiness\Tests\Fixtures\ResponseFactory::create('payment-drafts/payment-drafts'));

		Assert::count(2, $result);

		Assert::same('e7e54cb2-861a-aaaa-80e9-3e6600f3db10', $result[0]->id);
		Assert::same('Draft Payment through API', $result[0]->title);
		Assert::same(1, $result[0]->paymentsCount);
		Assert::same('2026-06-24', $result[0]->scheduledFor?->format('Y-m-d'));
		Assert::same(Enum\PaymentDraftSource::Api, $result[0]->source);

		// only the required properties
		Assert::same(3, $result[1]->paymentsCount);
		Assert::null($result[1]->title);
		Assert::null($result[1]->scheduledFor);
		Assert::null($result[1]->source);
	}

}

(new GetPaymentDraftsRequestTest)->run();
