<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Enum;

enum AccountType: string{

	case Blocking = 'blocking';

	case Credit = 'credit';

	case Current = 'current';

	case Debt = 'debt';

	case Merchant = 'merchant';

	case Other = 'other';

	case Savings = 'savings';

}
