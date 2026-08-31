<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Tests\Cases\Request;

use Tester\Assert;
use Znojil\RevolutBusiness\Enum;
use Znojil\RevolutBusiness\Request\GetCounterpartiesRequest;

require __DIR__ . '/../../bootstrap.php';

/**
 * @testCase
 */
final class GetCounterpartiesRequestTest extends \Tester\TestCase{

	public function testConfiguration(): void{
		$request = new GetCounterpartiesRequest(
			'John',
			'12345678',
			'54-01-05',
			'GB66REVO00996995908888',
			'REVOGB21',
			new \DateTimeImmutable('2026-03-19 08:45:22.111444', new \DateTimeZone('UTC')),
			50
		);
		Assert::same('GET', $request->getMethod());
		Assert::same('counterparties?name=John&account_no=12345678&sort_code=54-01-05&iban=GB66REVO00996995908888&bic=REVOGB21&created_before=2026-03-19T08%3A45%3A22.111444Z&limit=50', $request->getUrn());

		// default properties
		Assert::same('counterparties', (new GetCounterpartiesRequest)->getUrn());
	}

	public function testCreateResponse(): void{
		$result = (new GetCounterpartiesRequest)
			->createResponse(\Znojil\RevolutBusiness\Tests\Fixtures\ResponseFactory::create('counterparties/counterparties'));

		Assert::count(2, $result);

		$counterparty = $result[0];
		Assert::same('7e18625a-3e6c-4d4f-8429-216c25309a5f', $counterparty->id);
		Assert::same('John Smith', $counterparty->name);
		Assert::same('johnsmith', $counterparty->revtag);
		Assert::same(Enum\ProfileType::Personal, $counterparty->profileType);
		Assert::same('GB', $counterparty->country);
		Assert::same(Enum\CounterpartyState::Created, $counterparty->state);
		Assert::same('2022-08-05T14:29:22+00:00', $counterparty->createdAt->format('c'));

		Assert::count(1, $counterparty->accounts);
		$account = $counterparty->accounts[0];
		Assert::same('ff29e658-f07f-4d81-bc0f-7ad0ff141357', $account->id);
		Assert::same(Enum\Currency::Gbp, $account->currency);
		Assert::same(Enum\CounterpartyAccountType::External, $account->type);
		Assert::same('12345678', $account->accountNo);
		Assert::same('54-01-05', $account->sortCode);
		Assert::same(Enum\RecipientCharges::No, $account->recipientCharges);
		Assert::same('1 Canada Square', $account->address?->streetLine1);
		Assert::null($account->iban);
		Assert::null($account->route);

		Assert::count(1, $counterparty->cards);
		$card = $counterparty->cards[0];
		Assert::same('4242', $card->lastDigits);
		Assert::same(Enum\CardScheme::Visa, $card->scheme);
		Assert::same('GBR', $card->country); // merchant style country code, three letters
		Assert::same(Enum\Currency::Gbp, $card->currency);

		// only the required properties
		$minimal = $result[1];
		Assert::same('Acme Corp', $minimal->name);
		Assert::same(Enum\CounterpartyState::Deleted, $minimal->state);
		Assert::null($minimal->revtag);
		Assert::null($minimal->profileType);
		Assert::null($minimal->country);
		Assert::same([], $minimal->accounts); // missing lists become empty, not null
		Assert::same([], $minimal->cards);
	}

}

(new GetCounterpartiesRequestTest)->run();
