<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Tests\Cases\Request;

use Tester\Assert;

require __DIR__ . '/../../bootstrap.php';

/**
 * @testCase
 */
final class UnfreezeCardRequestTest extends \Tester\TestCase{

	public function testConfiguration(): void{
		$request = new \Znojil\RevolutBusiness\Request\UnfreezeCardRequest('123e4567-e89b-12d3-a456-426614174000');

		Assert::same('POST', $request->getMethod());
		Assert::same('cards/123e4567-e89b-12d3-a456-426614174000/unfreeze', $request->getUrn());
		// a POST without a body sends no content type
		Assert::same([], $request->getHeaders());
	}

}

(new UnfreezeCardRequestTest)->run();
