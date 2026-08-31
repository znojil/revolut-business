<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Tests\Cases\Request;

use Tester\Assert;
use Znojil\RevolutBusiness\Request\GetPayoutLinkRequest;

require __DIR__ . '/../../bootstrap.php';

/**
 * @testCase
 */
final class GetPayoutLinkRequestTest extends \Tester\TestCase{

	public function testConfiguration(): void{
		$request = new GetPayoutLinkRequest('123e4567-e89b-12d3-a456-426614174000');

		Assert::same('GET', $request->getMethod());
		Assert::same('payout-links/123e4567-e89b-12d3-a456-426614174000', $request->getUrn());
	}

	public function testCreateResponse(): void{
		$result = (new GetPayoutLinkRequest('12dcd8c2-6408-458f-98a9-3f4abc180898'))
			->createResponse(\Znojil\RevolutBusiness\Tests\Fixtures\ResponseFactory::create('payout-links/payout-link'));

		Assert::same('12dcd8c2-6408-458f-98a9-3f4abc180898', $result->id);
		Assert::same(\Znojil\RevolutBusiness\Enum\PayoutLinkState::Active, $result->state);
		Assert::same('5a7fcb46-4be4-47d4-a56f-27a2e5b78dd1', $result->requestId);
		Assert::same('85f515e4-588f-4496-a6a5-a7615a193e6b', $result->accountId);
		Assert::same('Invoice 2026/03', $result->reference);
		Assert::same('2023-07-11T13:55:54+00:00', $result->createdAt->format('c'));
		Assert::same('2023-07-11T13:55:55+00:00', $result->updatedAt->format('c'));
	}

}

(new GetPayoutLinkRequestTest)->run();
