<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Enum;

/**
 * @link https://developer.revolut.com/docs/api/business#get-indicative-quote
 */
enum QuoteWarning: string{

	case VolatileFxRate = 'volatile_fx_rate';

}
