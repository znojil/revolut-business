<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Enum;

/**
 * @link https://developer.revolut.com/docs/guides/manage-accounts/api-usage-and-testing/test-flows-with-simulations#simulate-a-top-up
 */
enum SimulationTopUpState: string{

	case Completed = 'completed';

	case Failed = 'failed';

	case Pending = 'pending';

	case Reverted = 'reverted';

}
