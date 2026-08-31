<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Tests\Cases\Request;

use Tester\Assert;

require __DIR__ . '/../../bootstrap.php';

/**
 * @testCase
 */
final class TerminateCardRequestTest extends \Tester\TestCase{

	public function testConfiguration(): void{
		$request = new \Znojil\RevolutBusiness\Request\TerminateCardRequest('123e4567-e89b-12d3-a456-426614174000');

		// a DELETE that does not delete: the card moves to the terminated state and stays retrievable by ID
		Assert::same('DELETE', $request->getMethod());
		Assert::same('cards/123e4567-e89b-12d3-a456-426614174000', $request->getUrn());
	}

}

(new TerminateCardRequestTest)->run();
