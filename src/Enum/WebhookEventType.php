<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Enum;

/**
 * @link https://developer.revolut.com/docs/guides/manage-accounts/webhooks/about-webhooks#supported-event-types
 */
enum WebhookEventType: string{

	case PayoutLinkCreated = 'PayoutLinkCreated';

	case PayoutLinkStateChanged = 'PayoutLinkStateChanged';

	case TransactionCreated = 'TransactionCreated';

	case TransactionStateChanged = 'TransactionStateChanged';

}
