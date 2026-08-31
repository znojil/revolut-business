<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Tests\Cases;

use Tester\Assert;
use Znojil\Http\Message\Response;
use Znojil\RevolutBusiness;

require __DIR__ . '/../bootstrap.php';

/**
 * @testCase
 */
final class ClientTest extends \Tester\TestCase{

	protected function tearDown(): void{
		parent::tearDown();
		\Mockery::close();
	}

	public function testAuthorize(): void{
		$httpClient = \Mockery::mock(RevolutBusiness\Http\Client::class);
		$httpClient->shouldReceive('send')
			->once()
			->with(
				'POST',
				\Mockery::on(fn(\Psr\Http\Message\UriInterface $uri): bool => (string) $uri === 'https://sandbox-b2b.revolut.com/api/1.0/auth/token'),
				\Mockery::on(fn(array $headers): bool => ($headers['Content-Type'] ?? null) === 'application/x-www-form-urlencoded'),
				\Mockery::on(function (array $data): bool{
					return ($data['grant_type'] ?? null) === 'authorization_code'
						&& ($data['code'] ?? null) === 'oa_code'
						&& ($data['client_assertion_type'] ?? null) === 'urn:ietf:params:oauth:client-assertion-type:jwt-bearer'
						&& is_string($data['client_assertion'] ?? null);
				})
			)
			->andReturn(new Response(200, body: '{"access_token":"oa_access","token_type":"bearer","expires_in":2399,"refresh_token":"oa_refresh"}'));

		$saved = null;
		$tokenStorage = \Mockery::mock(RevolutBusiness\TokenStorage::class);
		$tokenStorage->shouldReceive('save')
			->once()
			->with(\Mockery::on(function (RevolutBusiness\TokenPair $tokenPair) use (&$saved): bool{
				$saved = $tokenPair;

				return true;
			}));

		(new RevolutBusiness\Client(
			new RevolutBusiness\Config('client-id', 'example.com', RevolutBusiness\Tests\Fixtures\PrivateKey::get(), true),
			$tokenStorage,
			$httpClient
		))->authorize('oa_code');

		Assert::same('oa_access', $saved?->accessToken);
		Assert::same('oa_refresh', $saved?->refreshToken); // the refresh token comes from the response here, unlike RefreshTokenRequest which carries it over
		Assert::false($saved?->isExpired());
	}

	public function testSend(): void{
		$httpClient = \Mockery::mock(RevolutBusiness\Http\Client::class);
		$httpClient->shouldReceive('send')
			->once()
			->with(
				'POST',
				\Mockery::on(fn(\Psr\Http\Message\UriInterface $uri): bool => (string) $uri === 'https://sandbox-b2b.revolut.com/api/1.0/accounting-categories'),
				\Mockery::on(function (array $headers): bool{
					return ($headers['Authorization'] ?? null) === 'Bearer Access-Token'
						&& ($headers['Content-Type'] ?? null) === 'application/json';
				}),
				\Mockery::on(function (array $data): bool{
					return ($data['name'] ?? null) === 'Name'
						&& ($data['code'] ?? null) === 'codE';
				}),
				\Mockery::any()
			)
			->andReturn(new Response(200, body: '{"id": "6a37383e-cfd3-4a2f-aa81-e3a6e6939efa"}'));

		$response = $this->getClient($httpClient)
			->send(new RevolutBusiness\Request\CreateAccountingCategoryRequest('Name', 'codE'));

		Assert::same('6a37383e-cfd3-4a2f-aa81-e3a6e6939efa', $response);
	}

	public function testSendThrows(): void{
		// response
		Assert::exception(
			function(): void{
				$this->getClient($this->getHttpClientWithResponse(
					new Response(400, body: '{"error_id":"36e1a581-b05e-4018-b202-6b371092a19a","code":2101,"message":"Path parameter \'id\' must be a valid UUID"}')
				))->send(new RevolutBusiness\Request\GetAccountingCategoryRequest('64a2d310-e2a0-43c2-872f-969098141ebb'));
			},
			RevolutBusiness\Exception\ClientException::class,
			"Path parameter 'id' must be a valid UUID (2101)",
			400
		);

		// response code mapping
		foreach([
			[199, RevolutBusiness\Exception\ResponseException::class],
			[302, RevolutBusiness\Exception\ResponseException::class],
			[400, RevolutBusiness\Exception\ClientException::class],
			[499, RevolutBusiness\Exception\ClientException::class],
			[500, RevolutBusiness\Exception\ServerException::class],
			[503, RevolutBusiness\Exception\ServerException::class]
		] as [$statusCode, $exception]){
			Assert::exception(
				fn() => $this->getClient($this->getHttpClientWithResponse(new Response($statusCode)))
					->send(new RevolutBusiness\Request\GetAccountsRequest),
				$exception
			);
		}

		// response body is not valid JSON
		Assert::exception(
			function(): void{
				$this->getClient($this->getHttpClientWithResponse(
					new Response(400, body: 'non-JSON')
				))->send(new RevolutBusiness\Request\GetAccountingCategoryRequest('64a2d310-e2a0-43c2-872f-969098141ebb'));
			},
			RevolutBusiness\Exception\ClientException::class,
			"Request failed. Result:\nnon-JSON"
		);
	}

	private function getClient(?RevolutBusiness\Http\Client $httpClient = null): RevolutBusiness\Client{
		$tokenStorage = \Mockery::mock(RevolutBusiness\TokenStorage::class);
		$tokenStorage->shouldReceive('load')
			->once()
			->andReturn(new RevolutBusiness\TokenPair('Access-Token', new \DateTimeImmutable('+30 minutes'), 'refreshToken'));

		return new RevolutBusiness\Client(
			new RevolutBusiness\Config('client-id', 'example.com', 'key', true),
			$tokenStorage,
			$httpClient ?? \Mockery::mock(RevolutBusiness\Http\Client::class)
		);
	}

	private function getHttpClientWithResponse(Response $response): RevolutBusiness\Http\Client{
		$httpClient = \Mockery::mock(RevolutBusiness\Http\Client::class);
		$httpClient->shouldReceive('send')
			->once()
			->andReturn($response);

		return $httpClient;
	}

}

(new ClientTest)->run();
