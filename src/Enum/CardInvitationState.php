<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Enum;

/**
 * @link https://developer.revolut.com/docs/guides/manage-accounts/cards/manage-card-invitations#card-invitation-state
 */
enum CardInvitationState: string{

	case Created = 'created';

	case Expired = 'expired';

	case Failed = 'failed';

	case Redeemed = 'redeemed';

}
