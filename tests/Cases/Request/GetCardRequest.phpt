<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Tests\Cases\Request;

use Tester\Assert;
use Znojil\RevolutBusiness\Request\GetCardRequest;

require __DIR__ . '/../../bootstrap.php';

/**
 * @testCase
 */
final class GetCardRequestTest extends \Tester\TestCase{

	public function testConfiguration(): void{
		$request = new GetCardRequest('123e4567-e89b-12d3-a456-426614174000');

		Assert::same('GET', $request->getMethod());
		Assert::same('cards/123e4567-e89b-12d3-a456-426614174000', $request->getUrn());
	}

	public function testCreateResponse(): void{
		// terminated cards drop out of the listing but stay retrievable by ID
		$result = (new GetCardRequest('8a7b6c5d-4e3f-4a1b-8c9d-0e1f2a3b4c5d'))
			->createResponse(\Znojil\RevolutBusiness\Tests\Fixtures\ResponseFactory::create('cards/card'));

		Assert::same('8a7b6c5d-4e3f-4a1b-8c9d-0e1f2a3b4c5d', $result->id);
		Assert::same(\Znojil\RevolutBusiness\Enum\CardState::Terminated, $result->state);
		Assert::same('2026-03-10T14:45:12+00:00', $result->terminatedAt?->format('c'));
		Assert::same('Marketing card', $result->label);
		// the detail endpoint is the only one that reports spending-limit usage
		Assert::same(250.0, $result->spendingLimits?->single?->amount);
		Assert::same(2000.0, $result->spendingLimits?->month?->amount);
		Assert::same(450.5, $result->spendingLimits?->month?->usage);
		Assert::null($result->spendingLimits?->allTime?->usage);
	}

}

(new GetCardRequestTest)->run();
