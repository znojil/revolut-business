<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Tests\Cases\Auth\Request;

use Tester\Assert;

require __DIR__ . '/../../../bootstrap.php';

/**
 * @testCase
 */
final class BaseRequestTest extends \Tester\TestCase{

	public function testConfiguration(): void{
		$request = new \Znojil\RevolutBusiness\Tests\Fixtures\TestableAuthBaseRequest;

		Assert::same('POST', $request->getMethod());
		Assert::same('1.0/auth/token', $request->getUrn());
		Assert::same(['Content-Type' => 'application/x-www-form-urlencoded'], $request->getHeaders());
	}

}

(new BaseRequestTest)->run();
