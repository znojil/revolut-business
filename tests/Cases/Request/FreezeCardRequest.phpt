<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Tests\Cases\Request;

use Tester\Assert;

require __DIR__ . '/../../bootstrap.php';

/**
 * @testCase
 */
final class FreezeCardRequestTest extends \Tester\TestCase{

	public function testConfiguration(): void{
		$request = new \Znojil\RevolutBusiness\Request\FreezeCardRequest('123e4567-e89b-12d3-a456-426614174000');

		Assert::same('POST', $request->getMethod());
		Assert::same('cards/123e4567-e89b-12d3-a456-426614174000/freeze', $request->getUrn());
		// a POST without a body sends no content type
		Assert::same([], $request->getHeaders());
	}

}

(new FreezeCardRequestTest)->run();
