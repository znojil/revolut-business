<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Tests\Cases\Auth;

use Tester\Assert;
use Znojil\RevolutBusiness;
use Znojil\RevolutBusiness\Auth;
use Znojil\RevolutBusiness\Http;
use Znojil\RevolutBusiness\TokenPair;
use Znojil\RevolutBusiness\TokenStorage;

require __DIR__ . '/../../bootstrap.php';

/**
 * @testCase
 */
final class AccessTokenProviderTest extends \Tester\TestCase{

	protected function tearDown(): void{
		parent::tearDown();
		\Mockery::close();
	}

	public function testGetAccessToken(): void{
		$tokenStorage = \Mockery::mock(TokenStorage::class);
		$tokenStorage->shouldReceive('load')
			->once()
			->andReturn(new TokenPair('Access-Token', new \DateTimeImmutable('+30 minutes'), 'Refresh-token'));
		$tokenStorage->shouldNotReceive('save');

		$provider = $this->getProvider($tokenStorage, \Mockery::mock(Http\Client::class));

		Assert::same('Access-Token', $provider->getAccessToken());
		Assert::same('Access-Token', $provider->getAccessToken()); // lazy loading
	}

	public function testGetAccessTokenRefreshesExpired(): void{
		$saved = null;

		$tokenStorage = \Mockery::mock(TokenStorage::class);
		$tokenStorage->shouldReceive('load')
			->once()
			->andReturn(new TokenPair('Old-Token', new \DateTimeImmutable('-1 minute'), 'Refresh-token'));
		$tokenStorage->shouldReceive('save')
			->once()
			->with(\Mockery::on(function (TokenPair $tokenPair) use (&$saved): bool{
				$saved = $tokenPair;

				return true;
			}));

		$httpClient = \Mockery::mock(Http\Client::class);
		$httpClient->shouldReceive('send')
			->once()
			->andReturn(new \Znojil\Http\Message\Response(200, body: '{"access_token":"New-Token","token_type":"Bearer","expires_in":2400}'));

		$provider = $this->getProvider($tokenStorage, $httpClient);

		Assert::same('New-Token', $provider->getAccessToken());
		Assert::same('New-Token', $provider->getAccessToken()); // lazy loading
		Assert::same('New-Token', $saved?->accessToken);
		Assert::same('Refresh-token', $saved?->refreshToken); // the token endpoint does not return it, it must be carried over
		Assert::false($saved?->isExpired());
	}

	public function testGetAccessTokenThrows(): void{
		$tokenStorage = \Mockery::mock(TokenStorage::class);
		$tokenStorage->shouldReceive('load')->andReturn(null);

		Assert::exception(
			fn() => $this->getProvider($tokenStorage, \Mockery::mock(Http\Client::class))->getAccessToken(),
			RevolutBusiness\Exception\MissingTokenException::class
		);
	}

	private function getProvider(TokenStorage $tokenStorage, Http\Client $httpClient): Auth\AccessTokenProvider{
		return new Auth\AccessTokenProvider(
			new Auth\Client(new RevolutBusiness\Config('client-id', 'example.com', RevolutBusiness\Tests\Fixtures\PrivateKey::get(), true), $httpClient),
			$tokenStorage
		);
	}

}

(new AccessTokenProviderTest)->run();
