<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Enum;

enum TeamMemberState: string{

	case Active = 'active';

	case Confirmed = 'confirmed';

	case Created = 'created';

	case Disabled = 'disabled';

	case Locked = 'locked';

	case Waiting = 'waiting';

}
