<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Enum;

enum CounterpartyAccountType: string{

	case External = 'external';

	case Revolut = 'revolut';

}
