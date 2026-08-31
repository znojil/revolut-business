<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Enum;

/**
 * @link https://developer.revolut.com/docs/guides/manage-accounts/api-usage-and-testing/test-flows-with-simulations#simulate-a-transfer-state-update
 */
enum SimulationAction: string{

	case Complete = 'complete';

	case Decline = 'decline';

	case Fail = 'fail';

	case Revert = 'revert';

}
