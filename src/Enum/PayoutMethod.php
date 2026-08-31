<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Enum;

enum PayoutMethod: string{

	case BankAccount = 'bank_account';

	case Card = 'card';

	case Revolut = 'revolut';

}
