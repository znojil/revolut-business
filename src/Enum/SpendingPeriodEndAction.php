<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Enum;

enum SpendingPeriodEndAction: string{

	case Lock = 'lock';

	case Terminate = 'terminate';

}
