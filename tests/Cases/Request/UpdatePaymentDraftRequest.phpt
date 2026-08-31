<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Tests\Cases\Request;

use Tester\Assert;
use Znojil\RevolutBusiness\Clear;
use Znojil\RevolutBusiness\Request\UpdatePaymentDraftRequest;

require __DIR__ . '/../../bootstrap.php';

/**
 * @testCase
 */
final class UpdatePaymentDraftRequestTest extends \Tester\TestCase{

	public function testConfiguration(): void{
		$request = new UpdatePaymentDraftRequest(
			'123e4567-e89b-12d3-a456-426614174000',
			'New title',
			new \DateTimeImmutable('2026-06-24 08:00:00')
		);
		Assert::same('PATCH', $request->getMethod());
		Assert::same('payment-drafts/123e4567-e89b-12d3-a456-426614174000', $request->getUrn());
		Assert::same(['Content-Type' => 'application/json'], $request->getHeaders());
		Assert::same([
			'title' => 'New title',
			'schedule_for' => '2026-06-24', // a date, not a date-time
		], $request->getData());

		// Clear::Value
		Assert::same(
			['title' => null, 'schedule_for' => null],
			(new UpdatePaymentDraftRequest('draft-id', Clear::Value, Clear::Value))->getData()
		);
		Assert::same(
			['title' => null],
			(new UpdatePaymentDraftRequest('draft-id', Clear::Value))->getData()
		);
		Assert::same(
			['schedule_for' => null],
			(new UpdatePaymentDraftRequest('draft-id', scheduleFor: Clear::Value))->getData()
		);

		// at least one property must be provided
		Assert::exception(
			fn() => (new UpdatePaymentDraftRequest('draft-id'))->getData(),
			\Znojil\RevolutBusiness\Exception\InvalidArgumentException::class
		);
	}

}

(new UpdatePaymentDraftRequestTest)->run();
