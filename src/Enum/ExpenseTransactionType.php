<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Enum;

enum ExpenseTransactionType: string{

	case Atm = 'atm';

	case CardPayment = 'card_payment';

	case Fee = 'fee';

	case Transfer = 'transfer';

	case External = 'external';

	case MileageReimbursement = 'mileage_reimbursement';

	case RevPayment = 'rev_payment';

}
