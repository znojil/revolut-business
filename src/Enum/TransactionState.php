<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Enum;

enum TransactionState: string{

	// not documented in the specification
	case Cancelled = 'cancelled';

	case Created = 'created';

	case Completed = 'completed';

	case Declined = 'declined';

	case Failed = 'failed';

	case Pending = 'pending';

	case Reverted = 'reverted';

}
