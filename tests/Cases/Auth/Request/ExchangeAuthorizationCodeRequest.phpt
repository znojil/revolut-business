<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Tests\Cases\Auth\Request;

use Tester\Assert;
use Znojil\RevolutBusiness\Auth\Request\ExchangeAuthorizationCodeRequest;

require __DIR__ . '/../../../bootstrap.php';

/**
 * @testCase
 */
final class ExchangeAuthorizationCodeRequestTest extends \Tester\TestCase{

	public function testConfiguration(): void{
		Assert::same([
			'grant_type' => 'authorization_code',
			'code' => 'authorization-code'
		], (new ExchangeAuthorizationCodeRequest('authorization-code'))->getData());
	}

	public function testCreateResponse(): void{
		$response = new \Znojil\Http\Message\Response(200, body: '{"access_token":"oa_access","token_type":"bearer","expires_in":2399,"refresh_token":"oa_refresh"}');
		$request = new ExchangeAuthorizationCodeRequest('authorization-code');
		$result = $request->createResponse($response);

		Assert::same('oa_access', $result->accessToken);
		Assert::same('oa_refresh', $result->refreshToken);
		Assert::false($result->isExpired());
		Assert::true($result->expirationDatetime < new \DateTimeImmutable('+1 hour'));
	}

}

(new ExchangeAuthorizationCodeRequestTest)->run();
