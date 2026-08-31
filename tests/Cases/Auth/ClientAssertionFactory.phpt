<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Tests\Cases\Auth;

use Tester\Assert;
use Znojil\RevolutBusiness;
use Znojil\RevolutBusiness\Auth;
use Znojil\RevolutBusiness\Internal\Json;

require __DIR__ . '/../../bootstrap.php';

/**
 * @testCase
 */
final class ClientAssertionFactoryTest extends \Tester\TestCase{

	public function testCreate(): void{
		[$header, $payload] = explode('.', $this->getFactory(RevolutBusiness\Tests\Fixtures\PrivateKey::get())->create());

		/** @var array{alg: string, typ: string} */
		$decodedHeader = Json::decode($this->base64UrlDecode($header));
		Assert::same(['alg' => 'RS256', 'typ' => 'JWT'], $decodedHeader);

		/** @var array{iss: string, sub: string, aud: string, exp: int} */
		$claims = Json::decode($this->base64UrlDecode($payload));
		Assert::same('example.com', $claims['iss']);
		Assert::same('client-id', $claims['sub']);
		Assert::same('https://revolut.com', $claims['aud']); // escaped slashes would get the signature rejected
		Assert::true($claims['exp'] > time());
	}

	public function testCreateThrows(): void{
		Assert::exception(
			fn() => $this->getFactory('not-a-key')->create(),
			Auth\Exception\ClientAssertionException::class,
			'Private key is not valid: %a%'
		);
	}

	private function getFactory(string $privateKey): Auth\ClientAssertionFactory{
		return new Auth\ClientAssertionFactory(new RevolutBusiness\Config('client-id', 'example.com', $privateKey, true));
	}

	private function base64UrlDecode(string $value): string{
		return (string) base64_decode(strtr($value, '-_', '+/'), true);
	}

}

(new ClientAssertionFactoryTest)->run();
