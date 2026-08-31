<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Enum;

/**
 * @link https://developer.revolut.com/docs/api/business#get-indicative-quote
 */
enum EstimatedArrivalSpeed: string{

	case ByDate = 'by_date';

	case InSeconds = 'in_seconds';

	case Instant = 'instant';

	case Today = 'today';

	case Tomorrow = 'tomorrow';

}
