<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Tests\Cases\Request;

use Tester\Assert;
use Znojil\RevolutBusiness\Request\GetSensitiveCardDetailsRequest;

require __DIR__ . '/../../bootstrap.php';

/**
 * @testCase
 */
final class GetSensitiveCardDetailsRequestTest extends \Tester\TestCase{

	public function testConfiguration(): void{
		$request = new GetSensitiveCardDetailsRequest('123e4567-e89b-12d3-a456-426614174000');

		Assert::same('GET', $request->getMethod());
		Assert::same('cards/123e4567-e89b-12d3-a456-426614174000/sensitive-details', $request->getUrn());
	}

	public function testCreateResponse(): void{
		$result = (new GetSensitiveCardDetailsRequest('123e4567-e89b-12d3-a456-426614174000'))
			->createResponse(new \Znojil\Http\Message\Response(200, body: '{"pan":"4111111111111111","cvv":"123","expiry":"06/2030"}'));

		Assert::same('4111111111111111', $result->pan);
		Assert::same('123', $result->cvv);
		Assert::same('06/2030', $result->expiry);
	}

}

(new GetSensitiveCardDetailsRequestTest)->run();
