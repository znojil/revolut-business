<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Enum;

enum CardState: string{

	case Active = 'active';

	case Created = 'created';

	case Frozen = 'frozen';

	case Locked = 'locked';

	case Pending = 'pending';

	case Terminated = 'terminated';

}
