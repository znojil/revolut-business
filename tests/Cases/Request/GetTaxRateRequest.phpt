<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Tests\Cases\Request;

use Tester\Assert;
use Znojil\RevolutBusiness\Request\GetTaxRateRequest;

require __DIR__ . '/../../bootstrap.php';

/**
 * @testCase
 */
final class GetTaxRateRequestTest extends \Tester\TestCase{

	public function testConfiguration(): void{
		$request = new GetTaxRateRequest('123e4567-e89b-12d3-a456-426614174000');

		Assert::same('GET', $request->getMethod());
		Assert::same('tax-rates/123e4567-e89b-12d3-a456-426614174000', $request->getUrn());
	}

	public function testCreateResponse(): void{
		$result = (new GetTaxRateRequest('09ad1ad3-a1c0-4c09-b8f8-24b439e72690'))
			->createResponse(\Znojil\RevolutBusiness\Tests\Fixtures\ResponseFactory::create('accounting/tax-rate'));

		Assert::same('09ad1ad3-a1c0-4c09-b8f8-24b439e72690', $result->id);
		Assert::same('20.5% (VAT on Expenses)', $result->name);
		Assert::same('2026-03-11T10:33:51+00:00', $result->createdAt->format('c'));
		Assert::same('2026-03-11T11:33:51+00:00', $result->updatedAt->format('c'));
		Assert::same(20.5, $result->percentage);
	}

}

(new GetTaxRateRequestTest)->run();
