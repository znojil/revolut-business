<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Tests\Cases\Request;

use Tester\Assert;
use Znojil\RevolutBusiness\Enum;
use Znojil\RevolutBusiness\Request\GetExpensesRequest;

require __DIR__ . '/../../bootstrap.php';

/**
 * @testCase
 */
final class GetExpensesRequestTest extends \Tester\TestCase{

	public function testConfiguration(): void{
		$request = new GetExpensesRequest(
			new \DateTimeImmutable('2024-09-01 00:00:00.000000', new \DateTimeZone('UTC')),
			new \DateTimeImmutable('2024-09-13 18:47:01.782000', new \DateTimeZone('UTC')),
			500,
			Enum\ExpenseState::AwaitingReview,
			Enum\ExpenseTransactionType::MileageReimbursement
		);
		Assert::same('GET', $request->getMethod());
		Assert::same('expenses?from=2024-09-01T00%3A00%3A00.000000Z&to=2024-09-13T18%3A47%3A01.782000Z&count=500&state=awaiting_review&transaction_type=mileage_reimbursement', $request->getUrn());

		// default properties
		Assert::same('expenses', (new GetExpensesRequest)->getUrn());
	}

	public function testCreateResponse(): void{
		$result = (new GetExpensesRequest)
			->createResponse(\Znojil\RevolutBusiness\Tests\Fixtures\ResponseFactory::create('expenses/expenses'));

		Assert::count(2, $result);

		$expense = $result[0];
		Assert::same('8decf9f8-053e-46cb-92d8-a7b450fe5ae0', $expense->id);
		Assert::same(Enum\ExpenseState::Approved, $expense->state);
		Assert::same(Enum\ExpenseTransactionType::CardPayment, $expense->transactionType);
		Assert::same('Printer paper', $expense->description); // undocumented in the specification, present in the examples
		Assert::same('Ray Trenfield', $expense->payer);
		Assert::same('Best Printers Company', $expense->merchant);
		Assert::same('163e0ef6-2414-4fcf-846f-1f871059d506', $expense->transactionId);
		Assert::same('2024-09-13T18:47:01+00:00', $expense->expenseDate->format('c'));
		Assert::same('2024-09-13T20:48:40+00:00', $expense->submittedAt?->format('c'));
		Assert::same('2024-09-13T20:48:40+00:00', $expense->completedAt?->format('c'));

		Assert::same(24.38, $expense->spentAmount->amount);
		Assert::same(Enum\Currency::Gbp, $expense->spentAmount->currency);
		Assert::same(['84c0169a-37f9-4bfa-ab1e-f2c81dbc34cf'], $expense->receiptIds);
		// each label group holds exactly one selected label
		Assert::same(['Office supplies' => ['photocopying'], 'Department' => ['HR']], $expense->labels);

		Assert::count(2, $expense->splits);
		Assert::same(15.39, $expense->splits[0]->amount->amount);
		Assert::same('Printing & Stationery', $expense->splits[0]->category->name);
		Assert::same('461', $expense->splits[0]->category->code);
		Assert::same('VAT', $expense->splits[0]->taxRate->name);
		Assert::same(20.0, $expense->splits[0]->taxRate->percentage);
		// only the required properties on the nested category and tax rate
		Assert::null($expense->splits[1]->category->code);
		Assert::null($expense->splits[1]->taxRate->percentage);

		// only the required properties on the expense itself
		$minimal = $result[1];
		Assert::null($minimal->description);
		Assert::null($minimal->submittedAt);
		Assert::null($minimal->completedAt);
		Assert::null($minimal->payer);
		Assert::null($minimal->merchant);
		Assert::null($minimal->transactionId);
		Assert::same([], $minimal->labels);
		Assert::same([], $minimal->splits);
		Assert::same([], $minimal->receiptIds);
		Assert::same(0.0, $minimal->spentAmount->amount);
	}

}

(new GetExpensesRequestTest)->run();
