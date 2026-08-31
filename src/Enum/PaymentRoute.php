<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Enum;

enum PaymentRoute: string{

	case Ach = 'ach';

	case Fedwire = 'fedwire';

	case Swift = 'swift';

}
