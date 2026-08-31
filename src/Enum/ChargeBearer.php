<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Enum;

enum ChargeBearer: string{

	case Debtor = 'debtor';

	case PreferDebtor = 'prefer_debtor';

	case Shared = 'shared';

}
