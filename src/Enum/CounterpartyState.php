<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Enum;

enum CounterpartyState: string{

	case Created = 'created';

	case Deleted = 'deleted';

	case Draft = 'draft';

}
