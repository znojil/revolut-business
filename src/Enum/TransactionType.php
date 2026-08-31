<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Enum;

enum TransactionType: string{

	case Atm = 'atm';

	case CardPayment = 'card_payment';

	case CardRefund = 'card_refund';

	case CardChargeback = 'card_chargeback';

	case CardCredit = 'card_credit';

	case Exchange = 'exchange';

	case Fee = 'fee';

	case Charge = 'charge';

	case ChargeRefund = 'charge_refund';

	case Loan = 'loan';

	case Refund = 'refund';

	case RevPayment = 'rev_payment';

	case Tax = 'tax';

	case TaxRefund = 'tax_refund';

	// not documented in the specification
	case TempBlock = 'temp_block';

	case Topup = 'topup';

	case TopupReturn = 'topup_return';

	case Transfer = 'transfer';

}
