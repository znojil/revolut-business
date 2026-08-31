<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Tests\Cases\Request;

use Tester\Assert;
use Znojil\RevolutBusiness\Request\GetCounterpartyCountriesRequest;

require __DIR__ . '/../../bootstrap.php';

/**
 * @testCase
 */
final class GetCounterpartyCountriesRequestTest extends \Tester\TestCase{

	public function testConfiguration(): void{
		$request = new GetCounterpartyCountriesRequest;

		Assert::same('GET', $request->getMethod());
		Assert::same('counterparties/countries', $request->getUrn());
	}

	public function testCreateResponse(): void{
		// the response is unwrapped from the countries envelope
		Assert::same([
			['country' => 'GB', 'currencies' => ['GBP', 'EUR', 'USD']],
			['country' => 'US', 'currencies' => ['USD']]
		], (new GetCounterpartyCountriesRequest)
			->createResponse(\Znojil\RevolutBusiness\Tests\Fixtures\ResponseFactory::create('counterparties/countries')));
	}

}

(new GetCounterpartyCountriesRequestTest)->run();
