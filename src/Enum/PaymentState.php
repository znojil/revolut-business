<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Enum;

enum PaymentState: string{

	case Cancelled = 'CANCELLED';

	case Completed = 'COMPLETED';

	case Created = 'CREATED';

	case Declined = 'DECLINED';

	case Deleted = 'DELETED';

	case Failed = 'FAILED';

	case Pending = 'PENDING';

	case Reverted = 'REVERTED';

}
