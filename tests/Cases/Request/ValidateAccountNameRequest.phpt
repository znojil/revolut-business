<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Tests\Cases\Request;

use Tester\Assert;
use Znojil\RevolutBusiness\Enum;
use Znojil\RevolutBusiness\Request\ValidateAccountNameRequest;

require __DIR__ . '/../../bootstrap.php';

/**
 * @testCase
 */
final class ValidateAccountNameRequestTest extends \Tester\TestCase{

	public function testConfiguration(): void{
		$request = ValidateAccountNameRequest::uk('12345678', '54-01-05', 'John Smith Co.');
		Assert::same('POST', $request->getMethod());
		Assert::same('account-name-validation', $request->getUrn());
		Assert::same(['Content-Type' => 'application/json'], $request->getHeaders());

		foreach([
			// a string name means a business account, an IndividualNameDTO a personal one
			[ValidateAccountNameRequest::uk('12345678', '54-01-05', 'John Smith Co.'), [
				'account_no' => '12345678',
				'sort_code' => '54-01-05',
				'company_name' => 'John Smith Co.'
			]],
			[ValidateAccountNameRequest::uk('12345678', '54-01-05', new \Znojil\RevolutBusiness\DTO\IndividualNameDTO('John', 'Smith')), [
				'account_no' => '12345678',
				'sort_code' => '54-01-05',
				'individual_name' => ['first_name' => 'John', 'last_name' => 'Smith']
			]],
			[ValidateAccountNameRequest::au('486196918', '159517', 'Joseph Bloggs Pty Ltd'), [
				'account_no' => '486196918',
				'bsb' => '159517',
				'company_name' => 'Joseph Bloggs Pty Ltd'
			]],
			[ValidateAccountNameRequest::eu('RO1598536106002528374911', 'RO', Enum\Currency::Ron, 'Alexandru Ionescu SRL', 'REVOROBB'), [
				'iban' => 'RO1598536106002528374911',
				'bic' => 'REVOROBB',
				'recipient_country' => 'RO',
				'recipient_currency' => 'RON',
				'company_name' => 'Alexandru Ionescu SRL'
			]]
		] as [$validationRequest, $data]){
			Assert::same($data, $validationRequest->getData());
		}
	}

	public function testCreateResponse(): void{
		$result = ValidateAccountNameRequest::uk('12345678', '54-01-05', 'John Smith Co.')
			->createResponse(\Znojil\RevolutBusiness\Tests\Fixtures\ResponseFactory::create('counterparties/account-name-validation'));

		Assert::same(Enum\AccountNameValidationResult::CloseMatch, $result->resultCode);
		Assert::same(Enum\AccountNameValidationReasonType::UkCop, $result->reason?->type);
		Assert::same('close_match', $result->reason?->code);
		Assert::same('Joan', $result->individualName?->firstName);
		Assert::same('Smith', $result->individualName?->lastName);
		Assert::null($result->companyName);
		Assert::same('3f1a9d2b-6c4e-4f8a-9b2d-7e5c1a0f3b48', $result->nameValidationId);
	}

}

(new ValidateAccountNameRequestTest)->run();
