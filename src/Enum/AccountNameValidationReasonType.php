<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Enum;

/**
 * @link https://developer.revolut.com/docs/guides/manage-accounts/counterparties/confirmation-of-payee#supported-regions-and-services
 */
enum AccountNameValidationReasonType: string{

	case UkCop = 'uk_cop';

	case EuCop = 'eu_cop';

	case RoCop = 'ro_cop';

	case AuCop = 'au_cop';

}
