<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Enum;

enum PaymentDraftSource: string{

	case Api = 'api';

	case BusinessApp = 'business_app';

	case Email = 'email';

	case Integration = 'integration';

}
