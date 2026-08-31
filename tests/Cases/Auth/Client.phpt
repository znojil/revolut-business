<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Tests\Cases\Auth;

use Tester\Assert;
use Znojil\Http\Message\Response;
use Znojil\RevolutBusiness;
use Znojil\RevolutBusiness\Auth;

require __DIR__ . '/../../bootstrap.php';

/**
 * @testCase
 */
final class ClientTest extends \Tester\TestCase{

	protected function tearDown(): void{
		parent::tearDown();
		\Mockery::close();
	}

	public function testSendThrows(): void{
		foreach([
			// Revolut error format
			['{"message":"Invalid client credentials","code":1002}', 'Invalid client credentials (1002)'],
			// OAuth error format, returned by the token endpoint
			['{"error":"invalid_grant","error_description":"Refresh token is invalid"}', 'Refresh token is invalid (invalid_grant)'],
			// response body is not valid JSON
			['non-JSON', "Auth request failed. Result:\nnon-JSON"]
		] as [$body, $message]){
			Assert::exception(
				fn() => $this->getClient(new Response(401, body: $body))
					->send(new Auth\Request\RefreshTokenRequest('Refresh-token')),
				Auth\Exception\AuthenticationException::class,
				$message,
				401
			);
		}
	}

	private function getClient(Response $response): Auth\Client{
		$httpClient = \Mockery::mock(RevolutBusiness\Http\Client::class);
		$httpClient->shouldReceive('send')
			->once()
			->andReturn($response);

		return new Auth\Client(
			new RevolutBusiness\Config('client-id', 'example.com', RevolutBusiness\Tests\Fixtures\PrivateKey::get(), true),
			$httpClient
		);
	}

}

(new ClientTest)->run();
