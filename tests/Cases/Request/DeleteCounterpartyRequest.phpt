<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Tests\Cases\Request;

use Tester\Assert;

require __DIR__ . '/../../bootstrap.php';

/**
 * @testCase
 */
final class DeleteCounterpartyRequestTest extends \Tester\TestCase{

	public function testConfiguration(): void{
		$request = new \Znojil\RevolutBusiness\Request\DeleteCounterpartyRequest('123e4567-e89b-12d3-a456-426614174000');

		Assert::same('DELETE', $request->getMethod());
		Assert::same('counterparty/123e4567-e89b-12d3-a456-426614174000', $request->getUrn());
	}

}

(new DeleteCounterpartyRequestTest)->run();
