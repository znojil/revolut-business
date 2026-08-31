<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Enum;

enum PayoutLinkCancellationReason: string{

	case TooManyNameCheckAttempts = 'too_many_name_check_attempts';

}
