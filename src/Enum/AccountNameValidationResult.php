<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Enum;

/**
 * @link https://developer.revolut.com/docs/api/business#validate-account-name
 */
enum AccountNameValidationResult: string{

	case Matched = 'matched';

	case CloseMatch = 'close_match';

	case NotMatched = 'not_matched';

	case CannotBeChecked = 'cannot_be_checked';

	case TemporarilyUnavailable = 'temporarily_unavailable';

}
