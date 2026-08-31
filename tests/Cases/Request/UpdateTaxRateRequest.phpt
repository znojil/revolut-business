<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Tests\Cases\Request;

use Tester\Assert;

require __DIR__ . '/../../bootstrap.php';

/**
 * @testCase
 */
final class UpdateTaxRateRequestTest extends \Tester\TestCase{

	public function testConfiguration(): void{
		$request = new \Znojil\RevolutBusiness\Request\UpdateTaxRateRequest('123e4567-e89b-12d3-a456-426614174000', '20% VAT');

		Assert::same('PATCH', $request->getMethod());
		Assert::same('tax-rates/123e4567-e89b-12d3-a456-426614174000', $request->getUrn());
		Assert::same(['Content-Type' => 'application/json'], $request->getHeaders());
		Assert::same(['name' => '20% VAT'], $request->getData());
	}

}

(new UpdateTaxRateRequestTest)->run();
