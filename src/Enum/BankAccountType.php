<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Enum;

enum BankAccountType: string{

	case Checking = 'checking';

	case Savings = 'savings';

}
