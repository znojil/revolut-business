<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Tests\Cases\Request;

use Tester\Assert;
use Znojil\RevolutBusiness\DTO\AddressDTO;
use Znojil\RevolutBusiness\Request\UpdateCounterpartyPaymentMethodRequest;

require __DIR__ . '/../../bootstrap.php';

/**
 * @testCase
 */
final class UpdateCounterpartyPaymentMethodRequestTest extends \Tester\TestCase{

	public function testConfiguration(): void{
		$request = new UpdateCounterpartyPaymentMethodRequest(
			'7e18625a-3e6c-4d4f-8429-216c25309a5f',
			'ff29e658-f07f-4d81-bc0f-7ad0ff141357',
			new AddressDTO('GB', '1 Canada Square', 'Floor 5', 'London', 'Greater London', 'E14 5AB')
		);

		Assert::same('PATCH', $request->getMethod());
		Assert::same(
			'counterparties/7e18625a-3e6c-4d4f-8429-216c25309a5f/payment-methods/ff29e658-f07f-4d81-bc0f-7ad0ff141357',
			$request->getUrn()
		);
		Assert::same(['Content-Type' => 'application/json'], $request->getHeaders());
		Assert::same([
			'address' => [
				'country' => 'GB',
				'street_line1' => '1 Canada Square',
				'city' => 'London',
				'postcode' => 'E14 5AB',
				'street_line2' => 'Floor 5',
				'region' => 'Greater London'
			]
		], $request->getData());

		// default properties
		Assert::same([
			'address' => [
				'country' => 'GB',
				'street_line1' => '1 Canada Square',
				'city' => 'London',
				'postcode' => 'E14 5AB'
			]
		], (new UpdateCounterpartyPaymentMethodRequest(
			'counterparty-id',
			'payment-method-id',
			new AddressDTO('GB', '1 Canada Square', null, 'London', null, 'E14 5AB')
		))->getData());
	}

}

(new UpdateCounterpartyPaymentMethodRequestTest)->run();