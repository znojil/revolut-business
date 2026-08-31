<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Enum;

enum MerchantControlType: string{

	case Allow = 'allow';

	case Block = 'block';

}
