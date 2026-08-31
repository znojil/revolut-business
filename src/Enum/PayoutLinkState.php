<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Enum;

enum PayoutLinkState: string{

	case Active = 'active';

	case Awaiting = 'awaiting';

	case Cancelled = 'cancelled';

	case Created = 'created';

	case Expired = 'expired';

	case Failed = 'failed';

	case Processed = 'processed';

	case Processing = 'processing';

}
