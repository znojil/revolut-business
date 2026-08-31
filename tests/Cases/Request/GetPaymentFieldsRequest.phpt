<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Tests\Cases\Request;

use Tester\Assert;
use Znojil\RevolutBusiness\DTO\PaymentReceiverDTO;
use Znojil\RevolutBusiness\Request\GetPaymentFieldsRequest;

require __DIR__ . '/../../bootstrap.php';

/**
 * @testCase
 */
final class GetPaymentFieldsRequestTest extends \Tester\TestCase{

	public function testConfiguration(): void{
		$request = new GetPaymentFieldsRequest(
			'a1b2c3d4-e5f6-7890-abcd-ef1234567890',
			new PaymentReceiverDTO('f7e6d5c4-b3a2-1098-7654-321fedcba098', '12345678-90ab-cdef-1234-567890abcdef', null)
		);

		Assert::same('POST', $request->getMethod());
		Assert::same('pay/fields', $request->getUrn());
		Assert::same(['Content-Type' => 'application/json'], $request->getHeaders());
		Assert::same([
			'account_id' => 'a1b2c3d4-e5f6-7890-abcd-ef1234567890',
			'receiver' => [
				'counterparty_id' => 'f7e6d5c4-b3a2-1098-7654-321fedcba098',
				'account_id' => '12345678-90ab-cdef-1234-567890abcdef'
			]
		], $request->getData());
	}

	public function testCreateResponse(): void{
		$result = (new GetPaymentFieldsRequest(
			'a1b2c3d4-e5f6-7890-abcd-ef1234567890',
			new PaymentReceiverDTO('f7e6d5c4-b3a2-1098-7654-321fedcba098', null, null)
		))->createResponse(\Znojil\RevolutBusiness\Tests\Fixtures\ResponseFactory::create('transfers/payment-fields'));

		Assert::count(3, $result);

		Assert::same('reference', $result[0]->name);
		Assert::true($result[0]->required);
		// this endpoint returns validation, the counterparty one returns regex
		Assert::same(['min_length' => 1, 'max_length' => 140], $result[0]->validation);
		Assert::null($result[0]->options);

		Assert::same([
			'max_length' => 50,
			'regex' => ['pattern' => '^[a-zA-Z0-9]+$', 'description' => 'Alphanumeric characters only']
		], $result[1]->validation);

		Assert::false($result[2]->required);
		Assert::null($result[2]->validation);
		// a missing default is normalised to false
		Assert::same([
			['value' => 'shared', 'default' => true],
			['value' => 'debtor', 'default' => false]
		], $result[2]->options);
	}

}

(new GetPaymentFieldsRequestTest)->run();
