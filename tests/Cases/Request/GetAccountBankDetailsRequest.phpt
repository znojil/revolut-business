<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Tests\Cases\Request;

use Tester\Assert;
use Znojil\RevolutBusiness\Request\GetAccountBankDetailsRequest;

require __DIR__ . '/../../bootstrap.php';

/**
 * @testCase
 */
final class GetAccountBankDetailsRequestTest extends \Tester\TestCase{

	public function testConfiguration(): void{
		$request = new GetAccountBankDetailsRequest('123e4567-e89b-12d3-a456-426614174000');

		Assert::same('GET', $request->getMethod());
		Assert::same('accounts/123e4567-e89b-12d3-a456-426614174000/bank-details', $request->getUrn());
	}

	public function testCreateResponse(): void{
		$result = (new GetAccountBankDetailsRequest('123e4567-e89b-12d3-a456-426614174000'))
			->createResponse(\Znojil\RevolutBusiness\Tests\Fixtures\ResponseFactory::create('accounts/bank-details'));

		Assert::count(1, $result);

		$bankDetails = $result[0];
		Assert::same('GB66REVO00996995908888', $bankDetails->iban);
		Assert::same('REVOGB21', $bankDetails->bic);
		Assert::same('International account', $bankDetails->beneficiary);
		Assert::same('GB', $bankDetails->bankCountry);
		Assert::false($bankDetails->pooled);
		Assert::same(['swift'], $bankDetails->schemes);

		// not returned for this account
		Assert::null($bankDetails->accountNo);
		Assert::null($bankDetails->sortCode);
		Assert::null($bankDetails->routingNumber);
		Assert::null($bankDetails->uniqueReference);

		Assert::same('Revolut LTD', $bankDetails->beneficiaryAddress->streetLine1);
		Assert::same('1 Canada Square', $bankDetails->beneficiaryAddress->streetLine2);
		Assert::same('London', $bankDetails->beneficiaryAddress->city);
		Assert::same('GB', $bankDetails->beneficiaryAddress->country);
		Assert::same('E14 5AB', $bankDetails->beneficiaryAddress->postcode);
		Assert::null($bankDetails->beneficiaryAddress->region);

		Assert::same(\Znojil\RevolutBusiness\Enum\TimeUnit::Days, $bankDetails->estimatedTime->unit);
		Assert::same(1, $bankDetails->estimatedTime->min);
		Assert::same(3, $bankDetails->estimatedTime->max);
	}

}

(new GetAccountBankDetailsRequestTest)->run();
