<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Enum;

enum TransactionCounterpartyAccountType: string{

	case External = 'external';

	case Revolut = 'revolut';

	case Self = 'self';

}
