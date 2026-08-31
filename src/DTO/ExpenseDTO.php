<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\DTO;

use Znojil\RevolutBusiness\Enum;
use Znojil\RevolutBusiness\Internal\EnumMapper;

/**
 * @phpstan-import-type MoneyResponseData from MoneyDTO
 * @phpstan-import-type ExpenseSplitResponseData from ExpenseSplitDTO
 * @phpstan-type ExpenseResponseData array{id: string, state: string, transaction_type: string, description?: string, submitted_at?: string, completed_at?: string, payer?: string, merchant?: string, transaction_id?: string, expense_date: string, labels: array<string, list<string>>, splits: list<ExpenseSplitResponseData>, receipt_ids: list<string>, spent_amount: MoneyResponseData}
 */
final readonly class ExpenseDTO{

	/**
	 * @param ExpenseResponseData $data
	 * @throws \Znojil\RevolutBusiness\Exception\UnexpectedValueException
	 */
	public static function fromResponseData(array $data): self{
		return new self(
			$data['id'],
			EnumMapper::from(Enum\ExpenseState::class, $data['state']),
			EnumMapper::from(Enum\ExpenseTransactionType::class, $data['transaction_type']),
			$data['description'] ?? null,
			isset($data['submitted_at']) ? new \DateTimeImmutable($data['submitted_at']) : null,
			isset($data['completed_at']) ? new \DateTimeImmutable($data['completed_at']) : null,
			$data['payer'] ?? null,
			$data['merchant'] ?? null,
			$data['transaction_id'] ?? null,
			new \DateTimeImmutable($data['expense_date']),
			$data['labels'],
			array_map(ExpenseSplitDTO::fromResponseData(...), $data['splits']),
			$data['receipt_ids'],
			MoneyDTO::fromResponseData($data['spent_amount'])
		);
	}

	/**
	 * @param array<string, list<string>> $labels label group name => the single label selected from that group
	 * @param list<ExpenseSplitDTO> $splits
	 * @param list<string> $receiptIds
	 */
	public function __construct(
		public string $id,
		public Enum\ExpenseState $state,
		public Enum\ExpenseTransactionType $transactionType,
		public ?string $description,
		public ?\DateTimeImmutable $submittedAt,
		public ?\DateTimeImmutable $completedAt,
		public ?string $payer,
		public ?string $merchant,
		public ?string $transactionId,
		public \DateTimeImmutable $expenseDate,
		public array $labels,
		public array $splits,
		public array $receiptIds,
		public MoneyDTO $spentAmount
	){}

}
