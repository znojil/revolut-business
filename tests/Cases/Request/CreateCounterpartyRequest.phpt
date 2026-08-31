<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Tests\Cases\Request;

use Tester\Assert;
use Znojil\RevolutBusiness\DTO;
use Znojil\RevolutBusiness\Enum;
use Znojil\RevolutBusiness\Request\CreateCounterpartyRequest;

require __DIR__ . '/../../bootstrap.php';

/**
 * @testCase
 */
final class CreateCounterpartyRequestTest extends \Tester\TestCase{

	public function testConfiguration(): void{
		$request = CreateCounterpartyRequest::revolut(Enum\ProfileType::Personal, 'johnsmith', 'John Smith');

		Assert::same('POST', $request->getMethod());
		Assert::same('counterparty', $request->getUrn());
		Assert::same(['Content-Type' => 'application/json'], $request->getHeaders());
		Assert::same([
			'profile_type' => 'personal',
			'revtag' => 'johnsmith',
			'name' => 'John Smith'
		], $request->getData());
	}

	public function testBankCounterparty(): void{
		// a string name means a business account
		Assert::same([
			'company_name' => 'Acme Corp',
			'bank_country' => 'GB',
			'currency' => 'GBP',
			'account_no' => '12345678',
			'sort_code' => '54-01-05'
		], CreateCounterpartyRequest::bank('Acme Corp', 'GB', Enum\Currency::Gbp, '12345678', sortCode: '54-01-05')->getData());

		// an IndividualNameDTO means a personal one, and is sent as an object
		Assert::same([
			'individual_name' => ['first_name' => 'John', 'last_name' => 'Smith'],
			'bank_country' => 'US',
			'currency' => 'USD',
			'account_no' => '486196918',
			'routing_number' => '021000021',
			'address' => [
				'country' => 'US',
				'street_line1' => '1 Wall Street',
				'city' => 'New York',
				'postcode' => '10005'
			],
			'route' => 'fedwire',
			'account_type' => 'checking'
		], CreateCounterpartyRequest::bank(
			new DTO\IndividualNameDTO('John', 'Smith'),
			'US',
			Enum\Currency::Usd,
			'486196918',
			routingNumber: '021000021',
			address: new DTO\AddressDTO('US', '1 Wall Street', null, 'New York', null, '10005'),
			route: Enum\PaymentRoute::Fedwire,
			accountType: Enum\BankAccountType::Checking
		)->getData());
	}

	public function testCreateResponse(): void{
		$result = CreateCounterpartyRequest::revolut(Enum\ProfileType::Business, 'acme', 'Acme Corp')
			->createResponse(\Znojil\RevolutBusiness\Tests\Fixtures\ResponseFactory::create('counterparties/counterparty'));

		Assert::same('7e18625a-3e6c-4d4f-8429-216c25309a5f', $result->id);
		Assert::same('Acme Corp', $result->name);
		Assert::same(Enum\ProfileType::Business, $result->profileType);
		Assert::same(Enum\CounterpartyState::Created, $result->state);

		Assert::count(1, $result->accounts);
		Assert::same(Enum\PaymentRoute::Fedwire, $result->accounts[0]->route);
		Assert::same(Enum\BankAccountType::Checking, $result->accounts[0]->accountType);
		Assert::null($result->accounts[0]->name); // only the required properties on the account
	}

}

(new CreateCounterpartyRequestTest)->run();
