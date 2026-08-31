<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Tests\Cases;

use Tester\Assert;
use Znojil\RevolutBusiness\Config;

require __DIR__ . '/../bootstrap.php';

/**
 * @testCase
 */
final class ConfigTest extends \Tester\TestCase{

	public function testGetApiUrl(): void{
		foreach([
			['https://sandbox-b2b.revolut.com/api', true],
			['https://b2b.revolut.com/api', false]
		] as [$apiUrl, $sandbox]){
			Assert::same($apiUrl, (new Config('client-id', 'example.com', 'key', $sandbox))->getApiUrl());
		}
	}

}

(new ConfigTest)->run();
