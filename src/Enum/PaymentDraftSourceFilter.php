<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Enum;

enum PaymentDraftSourceFilter: string{

	case All = 'all';

	case Api = 'api';

	case Email = 'email';

	case Integration = 'integration';

}
