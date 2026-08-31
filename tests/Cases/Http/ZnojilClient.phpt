<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Tests\Cases\Http;

use Tester\Assert;
use Znojil\RevolutBusiness;

require __DIR__ . '/../../bootstrap.php';

/**
 * @testCase
 */
final class ZnojilClientTest extends \Tester\TestCase{

	public function testUnknownStringOptionThrows(): void{
		Assert::exception(
			fn() => (new RevolutBusiness\Http\ZnojilClient(\Mockery::mock(\Znojil\Http\Client::class)))
				->send('POST', 'https://example.com', options: ['tyop' => true]),
			RevolutBusiness\Exception\InvalidArgumentException::class,
			"Unknown HTTP client option 'tyop'."
		);
	}

}

(new ZnojilClientTest)->run();
