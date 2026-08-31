<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Enum;

/**
 * ISO 4217
 * @link https://help.revolut.com/business/help/receiving-payments/currency-exchanges/which-currencies-can-i-exchange-and-keep-in-my-account/
 */
enum Currency: string{

	case Gbp = 'GBP';

	case Usd = 'USD';

	case Aed = 'AED';

	case Aud = 'AUD';

	case Cad = 'CAD';

	case Chf = 'CHF';

	case Czk = 'CZK';

	case Dkk = 'DKK';

	case Eur = 'EUR';

	case Hkd = 'HKD';

	case Huf = 'HUF';

	case Ils = 'ILS';

	case Isk = 'ISK';

	case Jpy = 'JPY';

	case Mxn = 'MXN';

	case Nok = 'NOK';

	case Nzd = 'NZD';

	case Pln = 'PLN';

	case Qar = 'QAR';

	case Ron = 'RON';

	case Rsd = 'RSD';

	case Sar = 'SAR';

	case Sek = 'SEK';

	case Sgd = 'SGD';

	case Thb = 'THB';

	case Try = 'TRY';

	case Zar = 'ZAR';

	case Krw = 'KRW';

	case Cop = 'COP';

	case Php = 'PHP';

	case Inr = 'INR';

	case Clp = 'CLP';

}