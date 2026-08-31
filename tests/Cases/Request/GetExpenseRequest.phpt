<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Tests\Cases\Request;

use Tester\Assert;
use Znojil\RevolutBusiness\Enum;
use Znojil\RevolutBusiness\Request\GetExpenseRequest;

require __DIR__ . '/../../bootstrap.php';

/**
 * @testCase
 */
final class GetExpenseRequestTest extends \Tester\TestCase{

	public function testConfiguration(): void{
		$request = new GetExpenseRequest('123e4567-e89b-12d3-a456-426614174000');

		Assert::same('GET', $request->getMethod());
		Assert::same('expenses/123e4567-e89b-12d3-a456-426614174000', $request->getUrn());
	}

	public function testCreateResponse(): void{
		$result = (new GetExpenseRequest('8decf9f8-053e-46cb-92d8-a7b450fe5ae0'))
			->createResponse(\Znojil\RevolutBusiness\Tests\Fixtures\ResponseFactory::create('expenses/expense'));

		Assert::same('8decf9f8-053e-46cb-92d8-a7b450fe5ae0', $result->id);
		Assert::same(Enum\ExpenseState::AwaitingReview, $result->state);
		// external is an expense-only transaction type, it is not part of the transactions taxonomy
		Assert::same(Enum\ExpenseTransactionType::External, $result->transactionType);
		Assert::same('2024-09-13T20:48:40+00:00', $result->submittedAt?->format('c'));
		Assert::null($result->completedAt); // not completed yet
		Assert::same(['Department' => ['HR']], $result->labels);
		Assert::count(1, $result->splits);
		Assert::same(15.39, $result->spentAmount->amount);
	}

}

(new GetExpenseRequestTest)->run();
