<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Tests\Cases\Request;

use Tester\Assert;

require __DIR__ . '/../../bootstrap.php';

/**
 * @testCase
 */
final class DeletePaymentDraftPaymentRequestTest extends \Tester\TestCase{

	public function testConfiguration(): void{
		$request = new \Znojil\RevolutBusiness\Request\DeletePaymentDraftPaymentRequest('e7e54cb2-861a-aaaa-80e9-3e6600f3db10', '645a7696-22f3-aa47-9c74-cbae0449cc46');

		Assert::same('DELETE', $request->getMethod());
		Assert::same(
			'payment-drafts/e7e54cb2-861a-aaaa-80e9-3e6600f3db10/payments/645a7696-22f3-aa47-9c74-cbae0449cc46',
			$request->getUrn()
		);
	}

}

(new DeletePaymentDraftPaymentRequestTest)->run();
