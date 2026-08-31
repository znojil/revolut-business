<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Tests\Cases\Request;

use Tester\Assert;
use Znojil\RevolutBusiness\Request\CreateTaxRateRequest;

require __DIR__ . '/../../bootstrap.php';

/**
 * @testCase
 */
final class CreateTaxRateRequestTest extends \Tester\TestCase{

	public function testConfiguration(): void{
		$request = new CreateTaxRateRequest('20% (VAT on Expenses)', 20.0);

		Assert::same('POST', $request->getMethod());
		Assert::same('tax-rates', $request->getUrn());
		Assert::same(['Content-Type' => 'application/json'], $request->getHeaders());
		Assert::same(['name' => '20% (VAT on Expenses)', 'percentage' => 20.0], $request->getData());

		// a zero percentage must not be dropped as an empty value + cast float
		Assert::same(['name' => 'No VAT', 'percentage' => 0.0], (new CreateTaxRateRequest('No VAT', 0))->getData());
	}

	public function testCreateResponse(): void{
		Assert::same(
			'6a37383e-cfd3-4a2f-aa81-e3a6e6939efa',
			(new CreateTaxRateRequest('20% (VAT on Expenses)', 20.0))
				->createResponse(new \Znojil\Http\Message\Response(201, body: '{"id":"6a37383e-cfd3-4a2f-aa81-e3a6e6939efa"}'))
		);
	}

}

(new CreateTaxRateRequestTest)->run();
