<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Enum;

/**
 * @link https://developer.revolut.com/docs/guides/manage-accounts/accounts-and-transactions/retrieve-expenses#expense-state
 */
enum ExpenseState: string{

	case Approved = 'approved';

	case AwaitingReview = 'awaiting_review';

	case MissingInfo = 'missing_info';

	case PendingReimbursement = 'pending_reimbursement';

	case RefundRequested = 'refund_requested';

	case Refunded = 'refunded';

	case Rejected = 'rejected';

	case Reverted = 'reverted';

}
