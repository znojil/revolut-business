# Znojil Revolut Business

[![Latest Stable Version](https://img.shields.io/packagist/v/znojil/revolut-business)](https://packagist.org/packages/znojil/revolut-business)
[![PHP Version Require](https://img.shields.io/packagist/dependency-v/znojil/revolut-business/php)](https://packagist.org/packages/znojil/revolut-business)
[![License](https://img.shields.io/packagist/l/znojil/revolut-business)](LICENSE)
[![Tests](https://github.com/znojil/revolut-business/actions/workflows/tests.yml/badge.svg?branch=main)](https://github.com/znojil/revolut-business/actions/workflows/tests.yml)

A simple and modern PHP library for communicating with the [Revolut Business API](https://developer.revolut.com/docs/api/business).

Covers all 98 endpoints of the Merchant API v1.0 and the Webhooks API v2.0, with typed DTOs, enums and full PHPStan level max coverage.

## 🚀 Installation

```bash
composer require znojil/revolut-business
```

## 🔑 Authorization

The Revolut Business API uses OAuth 2.0 with a JWT client assertion. Before you can send a single request, you need to complete a one-time consent flow:

1. **Create an API certificate.** In Revolut Business, go to _Settings → API_, upload your public key and set a redirect URI. Revolut gives you a **client ID** and you choose an **issuer** (the domain of your redirect URI).
2. **Get the authorization code.** Open the consent link shown next to your certificate. After you confirm, Revolut redirects to your redirect URI with a `?code=` query parameter.
3. **Exchange the code** for a token pair — once, using `Client::authorize()`.

See [Make your first API request](https://developer.revolut.com/docs/guides/manage-accounts/get-started/make-your-first-api-request) for the full walkthrough.

```php
use Znojil\RevolutBusiness\Client;
use Znojil\RevolutBusiness\Config;
use Znojil\RevolutBusiness\FileTokenStorage;

$config = new Config(
	clientId: 'YOUR_CLIENT_ID',
	issuer: 'example.com', // the domain of your redirect URI
	privateKey: file_get_contents('/path/to/privatekey.pem'),
	sandbox: false // true for the sandbox environment
);

$client = new Client($config, new FileTokenStorage('/path/to/tokens.json'));

// once, with the code from the redirect
$client->authorize($_GET['code']);
```

From then on the library handles tokens on its own — the access token is refreshed from the stored refresh token whenever it expires, so you only ever call `send()`.

> The refresh token expires after 90 days and cannot be renewed automatically. Once that happens, `AuthenticationException` is thrown and you have to run the consent flow again.

## 📖 Usage

### 1. Sending a Request

Every endpoint is a `*Request` class. Pass it to `Client::send()` and you get back a typed result:

```php
use Znojil\RevolutBusiness\Request\GetAccountsRequest;

foreach($client->send(new GetAccountsRequest) as $account){
	$account->id; // string
	$account->name; // ?string
	$account->balance; // float
	$account->currency; // Currency enum, or string for a currency not in the enum
	$account->state; // AccountState enum
	$account->accountType; // AccountType enum
	$account->createdAt; // DateTimeImmutable
}
```

Requests that need a body take their required parameters first and the optional ones as named arguments:

```php
use Znojil\RevolutBusiness\DTO\PaymentReceiverDTO;
use Znojil\RevolutBusiness\Enum\Currency;
use Znojil\RevolutBusiness\Enum\TransferReasonCode;
use Znojil\RevolutBusiness\Request\CreatePaymentRequest;

$result = $client->send(new CreatePaymentRequest(
	accountId: '05018b0d-e67c-4fec-bea6-415e9da9432c',
	receiver: new PaymentReceiverDTO('7e18625a-3e6c-4d4f-8429-216c25309a5f', null, null),
	amount: 123.45,
	currency: Currency::Gbp,
	requestId: 'invoice-2026-03', // your own idempotency key
	reference: 'Invoice 2026/03',
	transferReasonCode: TransferReasonCode::Services
));

$result->id; // string
$result->state; // TransactionState enum
```

Listing endpoints are paginated by time, not by page number — you walk backwards using the timestamp of the oldest item you received:

```php
use Znojil\RevolutBusiness\Request\GetTransactionsRequest;

$before = null;
do{
	$transactions = $client->send(new GetTransactionsRequest(to: $before, count: 100));

	foreach($transactions as $transaction){
		$transaction->id;
		$transaction->type; // TransactionType enum
		$transaction->createdAt;
	}

	$before = $transactions !== [] ? end($transactions)->createdAt : null;
}while(count($transactions) === 100);
```

### 2. Custom Token Storage

`FileTokenStorage` stores the token pair as JSON in a file with `0600` permissions. To keep tokens in a database or a cache instead, implement `Znojil\RevolutBusiness\TokenStorage`:

```php
use Znojil\RevolutBusiness\TokenPair;
use Znojil\RevolutBusiness\TokenStorage;

final class MyTokenStorage implements TokenStorage{

	public function load(): ?TokenPair{
		// return null when nothing is stored yet
	}

	public function save(TokenPair $tokenPair): void{
		$tokenPair->accessToken; // string
		$tokenPair->expirationDatetime; // DateTimeImmutable
		$tokenPair->refreshToken; // string
	}

}

$client = new Client($config, new MyTokenStorage);
```

`save()` is called both by `authorize()` and by every automatic token refresh, so whatever you write to must be durable — losing the refresh token means going through the consent flow again.

### 3. Using a Custom HTTP Client

By default the library uses [znojil/http](https://github.com/znojil/http). You can inject your own implementation as the third argument to the `Client` constructor. It must implement the `Znojil\RevolutBusiness\Http\Client` interface.

```php
use Znojil\RevolutBusiness\Client;
use Znojil\RevolutBusiness\Http\Client as RevolutHttpClient;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;

class MyCustomHttpClient implements RevolutHttpClient{
	public function send(string $method, string|UriInterface $uri, array $headers = [], mixed $data = null, array $options = []): ResponseInterface{
		// your implementation
	}
}

$client = new Client($config, $tokenStorage, new MyCustomHttpClient);
```

String keys in `$options` are `Znojil\RevolutBusiness\Http\Option::*` enum values that every implementation must honor, and unknown string keys must be rejected with an exception. Integer keys are raw `CURLOPT_*` constants — non-cURL implementations must reject them with an exception rather than silently ignore them, so that a consumer never ends up with options that silently don't apply.

## 🧩 Conventions

A few things that are specific to this library rather than to the API itself.

### Clearing a Property

On `Update*` requests, `null` and "remove this value" are two different intentions, so `null` cannot mean both. Passing `null` leaves a property **unchanged**; to have the API remove it, pass the `Clear::Value` sentinel:

```php
use Znojil\RevolutBusiness\Clear;
use Znojil\RevolutBusiness\Request\UpdatePaymentDraftRequest;

// only the title is sent, the schedule stays as it is
$client->send(new UpdatePaymentDraftRequest($draftId, title: 'New title'));

// the schedule is explicitly removed
$client->send(new UpdatePaymentDraftRequest($draftId, scheduleFor: Clear::Value));
```

Where a property cannot be cleared through the API, its parameter simply doesn't accept `Clear`.

### Requests That Need At Least One Property

Most `Update*` endpoints reject an empty body. Those requests throw `InvalidArgumentException` when you construct one with nothing to update, rather than sending a request that is guaranteed to fail.

### Enums

Most enums are closed: an unknown value in a response throws `UnexpectedValueException`, so you learn about an API change instead of silently getting a wrong value.

Three of them are open, because their real value sets are larger than what the documentation lists and a new entry must not break a running application. `Currency`, `PaymentRoute` and `TransferReasonCode` are typed as `string|Enum` on the response side — you get the enum case when the value is known and the raw string when it isn't:

```php
use Znojil\RevolutBusiness\Enum\Currency;
use Znojil\RevolutBusiness\Request\GetAccountRequest;

$account = $client->send(new GetAccountRequest($accountId));

if($account->currency instanceof Currency){
	// a known currency
}else{
	// a currency not covered by the enum, as a raw string
}
```

On the request side these are always plain enums — you can only send what the library knows.

### Parameter Validation

Requests do not validate their parameters. The API returns precise errors for invalid input, and duplicating those rules here would mean maintaining a second copy of somebody else's specification. Every request class links to its official documentation page in a `@link` annotation.

## 📋 Available Requests

All 98 requests live in the `Znojil\RevolutBusiness\Request` namespace, grouped here the same way as the [official documentation](https://developer.revolut.com/docs/api/business).

### Accounting

| Request | Documentation |
|---|---|
| `CreateAccountingCategoryRequest` | [Create an accounting category](https://developer.revolut.com/docs/api/business#create-accounting-category) |
| `GetAccountingCategoriesRequest` | [Retrieve accounting categories](https://developer.revolut.com/docs/api/business#get-accounting-categories) |
| `GetAccountingCategoryRequest` | [Retrieve an accounting category](https://developer.revolut.com/docs/api/business#get-accounting-category) |
| `UpdateAccountingCategoryRequest` | [Update an accounting category](https://developer.revolut.com/docs/api/business#update-accounting-category) |
| `DeleteAccountingCategoryRequest` | [Delete an accounting category](https://developer.revolut.com/docs/api/business#delete-accounting-category) |
| `CreateLabelGroupRequest` | [Create a label group](https://developer.revolut.com/docs/api/business#create-label-group) |
| `GetLabelGroupsRequest` | [Retrieve label groups](https://developer.revolut.com/docs/api/business#get-label-groups) |
| `GetLabelGroupRequest` | [Retrieve a label group](https://developer.revolut.com/docs/api/business#get-label-group) |
| `UpdateLabelGroupRequest` | [Update a label group](https://developer.revolut.com/docs/api/business#update-label-group) |
| `DeleteLabelGroupRequest` | [Delete a label group](https://developer.revolut.com/docs/api/business#delete-label-group) |
| `CreateLabelRequest` | [Create a label](https://developer.revolut.com/docs/api/business#create-label) |
| `GetLabelsRequest` | [Retrieve labels](https://developer.revolut.com/docs/api/business#get-labels) |
| `UpdateLabelRequest` | [Update a label](https://developer.revolut.com/docs/api/business#update-label) |
| `DeleteLabelRequest` | [Delete a label](https://developer.revolut.com/docs/api/business#delete-label) |
| `CreateTaxRateRequest` | [Create a tax rate](https://developer.revolut.com/docs/api/business#create-tax-rate) |
| `GetTaxRatesRequest` | [Retrieve tax rates](https://developer.revolut.com/docs/api/business#get-tax-rates) |
| `GetTaxRateRequest` | [Retrieve a tax rate](https://developer.revolut.com/docs/api/business#get-tax-rate) |
| `UpdateTaxRateRequest` | [Update a tax rate](https://developer.revolut.com/docs/api/business#update-tax-rate) |
| `DeleteTaxRateRequest` | [Delete a tax rate](https://developer.revolut.com/docs/api/business#delete-tax-rate) |

### Accounts

| Request | Documentation |
|---|---|
| `GetAccountsRequest` | [Retrieve all accounts](https://developer.revolut.com/docs/api/business#get-accounts) |
| `GetAccountRequest` | [Retrieve an account](https://developer.revolut.com/docs/api/business#get-account) |
| `GetAccountBankDetailsRequest` | [Retrieve account's full bank details](https://developer.revolut.com/docs/api/business#get-account-details) |

### Cards

| Request | Documentation |
|---|---|
| `GetCardsRequest` | [Retrieve all cards](https://developer.revolut.com/docs/api/business#get-cards) |
| `CreateCardRequest` | [Create a card](https://developer.revolut.com/docs/api/business#create-card) |
| `GetCardRequest` | [Retrieve a card](https://developer.revolut.com/docs/api/business#get-card) |
| `UpdateCardRequest` | [Update a card](https://developer.revolut.com/docs/api/business#update-card) |
| `TerminateCardRequest` | [Terminate a card](https://developer.revolut.com/docs/api/business#delete-card) |
| `UpdateCardContactsRequest` | [Update card contacts](https://developer.revolut.com/docs/api/business#update-card-contacts) |
| `UpdateCardReferencesRequest` | [Update card references](https://developer.revolut.com/docs/api/business#update-card-references) |
| `FreezeCardRequest` | [Freeze a card](https://developer.revolut.com/docs/api/business#freeze-card) |
| `UnfreezeCardRequest` | [Unfreeze a card](https://developer.revolut.com/docs/api/business#unfreeze-card) |
| `LockCardRequest` | [Lock a card](https://developer.revolut.com/docs/api/business#lock-card) |
| `UnlockCardRequest` | [Unlock a card](https://developer.revolut.com/docs/api/business#unlock-card) |
| `GetSensitiveCardDetailsRequest` | [Retrieve sensitive card details](https://developer.revolut.com/docs/api/business#get-sensitive-card-details) |

### Card invitations

| Request | Documentation |
|---|---|
| `CreateCardInvitationRequest` | [Create a card invitation](https://developer.revolut.com/docs/api/business#create-card-invitation) |
| `GetCardInvitationsRequest` | [Retrieve card invitations](https://developer.revolut.com/docs/api/business#get-card-invitations) |
| `GetCardInvitationRequest` | [Retrieve a card invitation](https://developer.revolut.com/docs/api/business#get-card-invitation) |
| `UpdateCardInvitationRequest` | [Update a card invitation](https://developer.revolut.com/docs/api/business#update-card-invitation) |
| `CancelCardInvitationRequest` | [Cancel a card invitation](https://developer.revolut.com/docs/api/business#cancel-card-invitation) |

### Counterparties

| Request | Documentation |
|---|---|
| `ValidateAccountNameRequest` | [Validate an account name](https://developer.revolut.com/docs/api/business#validate-account-name) |
| `GetCounterpartiesRequest` | [Retrieve counterparties](https://developer.revolut.com/docs/api/business#get-counterparties) |
| `CreateCounterpartyRequest` | [Create a counterparty](https://developer.revolut.com/docs/api/business#add-counterparty) |
| `GetCounterpartyCountriesRequest` | [Retrieve counterparty countries](https://developer.revolut.com/docs/api/business#get-counterparty-countries) |
| `GetCounterpartyFieldsRequest` | [Retrieve counterparty requirements](https://developer.revolut.com/docs/api/business#get-counterparty-requirements) |
| `GetCounterpartyRequest` | [Retrieve a counterparty](https://developer.revolut.com/docs/api/business#get-counterparty) |
| `DeleteCounterpartyRequest` | [Delete a counterparty](https://developer.revolut.com/docs/api/business#delete-counterparty) |
| `UpdateCounterpartyPaymentMethodRequest` | [Update a counterparty payment method](https://developer.revolut.com/docs/api/business#update-counterparty-payment-method) |

### Expenses

| Request | Documentation |
|---|---|
| `GetExpensesRequest` | [Retrieve expenses](https://developer.revolut.com/docs/api/business#get-expenses) |
| `GetExpenseRequest` | [Retrieve an expense](https://developer.revolut.com/docs/api/business#get-expense) |
| `GetExpenseReceiptRequest` | [Retrieve an expense receipt](https://developer.revolut.com/docs/api/business#get-expense-receipt) |

### Foreign exchange

| Request | Documentation |
|---|---|
| `GetExchangeRateRequest` | [Get exchange rate](https://developer.revolut.com/docs/api/business#get-rate) |
| `ExchangeMoneyRequest` | [Exchange money](https://developer.revolut.com/docs/api/business#exchange-money) |
| `GetExchangeReasonsRequest` | [Retrieve exchange reasons](https://developer.revolut.com/docs/api/business#get-exchange-reasons) |

### Payment drafts

| Request | Documentation |
|---|---|
| `GetPaymentDraftsRequest` | [Retrieve payment drafts](https://developer.revolut.com/docs/api/business#get-payment-drafts) |
| `CreatePaymentDraftRequest` | [Create a payment draft](https://developer.revolut.com/docs/api/business#create-payment-draft) |
| `GetPaymentDraftRequest` | [Retrieve a payment draft](https://developer.revolut.com/docs/api/business#get-payment-draft) |
| `DeletePaymentDraftRequest` | [Delete a payment draft](https://developer.revolut.com/docs/api/business#delete-payment-draft) |
| `UpdatePaymentDraftRequest` | [Update a payment draft](https://developer.revolut.com/docs/api/business#update-payment-draft) |
| `CreatePaymentDraftPaymentRequest` | [Add a payment to a draft](https://developer.revolut.com/docs/api/business#add-payment-draft-payment) |
| `UpdatePaymentDraftPaymentRequest` | [Update a draft payment](https://developer.revolut.com/docs/api/business#update-payment-draft-payment) |
| `DeletePaymentDraftPaymentRequest` | [Delete a draft payment](https://developer.revolut.com/docs/api/business#delete-payment-draft-payment) |

### Payout links

| Request | Documentation |
|---|---|
| `CreatePayoutLinkRequest` | [Create a payout link](https://developer.revolut.com/docs/api/business#create-payout-link) |
| `GetPayoutLinksRequest` | [Retrieve payout links](https://developer.revolut.com/docs/api/business#get-payout-links) |
| `GetPayoutLinkRequest` | [Retrieve a payout link](https://developer.revolut.com/docs/api/business#get-payout-link) |
| `CancelPayoutLinkRequest` | [Cancel a payout link](https://developer.revolut.com/docs/api/business#cancel-payout-link) |

### Simulations

Sandbox only.

| Request | Documentation |
|---|---|
| `SimulateTransactionStateRequest` | [Simulate a transfer state update](https://developer.revolut.com/docs/api/business#simulate-transfer-state-update) |
| `SimulateTopUpRequest` | [Simulate an account top-up](https://developer.revolut.com/docs/api/business#simulate-top-up) |

### Teams

| Request | Documentation |
|---|---|
| `GetTeamMembersRequest` | [Retrieve team members](https://developer.revolut.com/docs/api/business#get-team-members) |
| `InviteTeamMemberRequest` | [Invite a team member](https://developer.revolut.com/docs/api/business#invite-team-member) |
| `GetTeamMemberRequest` | [Retrieve a team member](https://developer.revolut.com/docs/api/business#get-team-member-by-id) |
| `DeleteTeamMemberRequest` | [Delete a team member](https://developer.revolut.com/docs/api/business#delete-team-member) |
| `AssignTeamMemberDepartmentRequest` | [Assign a department](https://developer.revolut.com/docs/api/business#assign-department) |
| `UnassignTeamMemberDepartmentRequest` | [Unassign a department](https://developer.revolut.com/docs/api/business#unassign-department) |
| `AssignTeamMemberManagerRequest` | [Assign a manager](https://developer.revolut.com/docs/api/business#assign-manager) |
| `UnassignTeamMemberManagerRequest` | [Unassign a manager](https://developer.revolut.com/docs/api/business#unassign-manager) |
| `UpdateTeamMemberRoleRequest` | [Update a team member's role](https://developer.revolut.com/docs/api/business#update-team-member-role) |
| `SuspendTeamMemberRequest` | [Suspend a team member](https://developer.revolut.com/docs/api/business#suspend-team-member) |
| `UnsuspendTeamMemberRequest` | [Unsuspend a team member](https://developer.revolut.com/docs/api/business#unsuspend-team-member) |
| `GetRolesRequest` | [Retrieve roles](https://developer.revolut.com/docs/api/business#get-roles) |
| `CreateDepartmentRequest` | [Create a department](https://developer.revolut.com/docs/api/business#create-department) |
| `GetDepartmentsRequest` | [Retrieve departments](https://developer.revolut.com/docs/api/business#get-departments) |
| `GetDepartmentRequest` | [Retrieve a department](https://developer.revolut.com/docs/api/business#get-department) |
| `UpdateDepartmentRequest` | [Update a department](https://developer.revolut.com/docs/api/business#update-department) |
| `DeleteDepartmentRequest` | [Delete a department](https://developer.revolut.com/docs/api/business#delete-department) |

### Transactions

| Request | Documentation |
|---|---|
| `GetTransactionsRequest` | [Retrieve transactions](https://developer.revolut.com/docs/api/business#get-transactions) |
| `GetTransactionRequest` | [Retrieve a transaction](https://developer.revolut.com/docs/api/business#get-transaction) |

### Transfers

| Request | Documentation |
|---|---|
| `CreatePaymentRequest` | [Create a payment](https://developer.revolut.com/docs/api/business#create-payment) |
| `GetIndicativeQuoteRequest` | [Get an indicative quote](https://developer.revolut.com/docs/api/business#get-indicative-quote) |
| `GetPaymentFieldsRequest` | [Retrieve payment requirements](https://developer.revolut.com/docs/api/business#get-payment-requirements) |
| `CreateTransferRequest` | [Move money between your accounts](https://developer.revolut.com/docs/api/business#create-transfer) |
| `GetTransferReasonsRequest` | [Retrieve transfer reasons](https://developer.revolut.com/docs/api/business#get-transfer-reasons) |

### Webhooks

Version 2.0 of the Webhooks API. The deprecated v1 endpoints are not implemented.

| Request | Documentation |
|---|---|
| `CreateWebhookRequest` | [Create a webhook](https://developer.revolut.com/docs/api/business#create-webhook) |
| `GetWebhooksRequest` | [Retrieve webhooks](https://developer.revolut.com/docs/api/business#get-webhooks) |
| `GetWebhookRequest` | [Retrieve a webhook](https://developer.revolut.com/docs/api/business#get-webhook) |
| `UpdateWebhookRequest` | [Update a webhook](https://developer.revolut.com/docs/api/business#update-webhook) |
| `DeleteWebhookRequest` | [Delete a webhook](https://developer.revolut.com/docs/api/business#delete-webhook) |
| `RotateWebhookSigningSecretRequest` | [Rotate a webhook signing secret](https://developer.revolut.com/docs/api/business#rotate-webhook-signing-secret) |
| `GetFailedWebhookEventsRequest` | [Retrieve failed webhook events](https://developer.revolut.com/docs/api/business#get-failed-webhook-events) |

## ⚠️ Error Handling

The client throws exceptions to help you identify the issue:

- `Znojil\RevolutBusiness\Exception\ClientException`: For HTTP client-side errors (4xx).
- `Znojil\RevolutBusiness\Exception\ServerException`: For HTTP server-side errors (5xx).
- `Znojil\RevolutBusiness\Exception\ResponseException`: For other unsuccessful HTTP responses. The base class of the three above — it carries `apiErrorCode`, `apiErrorId` and the raw `responseBody`.
- `Znojil\RevolutBusiness\Exception\JsonException`: When a response body is not valid JSON.
- `Znojil\RevolutBusiness\Exception\JsonResponseException`: When a response body is valid JSON but not the object or array the endpoint promises (subtype of `ResponseException`).
- `Znojil\RevolutBusiness\Exception\UnexpectedValueException`: When a response contains an enum value the library does not know.
- `Znojil\RevolutBusiness\Exception\InvalidArgumentException`: For invalid input (e.g. an update request with no properties to update).
- `Znojil\RevolutBusiness\Exception\MissingTokenException`: When no token pair is stored yet — run the authorization flow first.
- `Znojil\RevolutBusiness\Exception\IOException`: When `FileTokenStorage` cannot read or write the token file.
- `Znojil\RevolutBusiness\Auth\Exception\AuthenticationException`: When the token exchange or refresh fails (e.g. an expired or revoked refresh token).
- `Znojil\RevolutBusiness\Auth\Exception\ClientAssertionException`: When the JWT client assertion cannot be created (e.g. an invalid private key).

```php
use Znojil\RevolutBusiness\Auth\Exception\AuthenticationException;
use Znojil\RevolutBusiness\Exception\ClientException;
use Znojil\RevolutBusiness\Exception\ServerException;

try{
	$result = $client->send(new CreatePaymentRequest(...));
}catch(ClientException $e){
	echo $e->getMessage(); // error message from Revolut
	echo $e->getCode(); // HTTP status code
	echo $e->apiErrorCode; // ?int error code from Revolut
	echo $e->apiErrorId; // ?string error id, useful when contacting support
}catch(ServerException $e){
	// Revolut server error (5xx)
}catch(AuthenticationException $e){
	// the refresh token has expired or been revoked — run the consent flow again
}
```

> All exceptions thrown by the library implement the `Znojil\RevolutBusiness\Exception\Exception` marker interface, so a single catch can cover them all.

## 📄 License

This library is open-source software licensed under the [MIT license](https://choosealicense.com/licenses/mit/).
