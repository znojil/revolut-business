<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Tests\Cases\Auth\Request;

use Tester\Assert;
use Znojil\RevolutBusiness\Auth\Request\RefreshTokenRequest;

require __DIR__ . '/../../../bootstrap.php';

/**
 * @testCase
 */
final class RefreshTokenRequestTest extends \Tester\TestCase{

	public function testConfiguration(): void{
		Assert::same([
			'grant_type' => 'refresh_token',
			'refresh_token' => 'refresh-code'
		], (new RefreshTokenRequest('refresh-code'))->getData());
	}

	public function testCreateResponse(): void{
		$response = new \Znojil\Http\Message\Response(200, body: '{"access_token":"oa_access","token_type":"bearer","expires_in":2399}');
		$request = new RefreshTokenRequest('refresh-code');
		$result = $request->createResponse($response);

		Assert::same('oa_access', $result->accessToken);
		Assert::same('refresh-code', $result->refreshToken);
		Assert::false($result->isExpired());
		Assert::true($result->expirationDatetime < new \DateTimeImmutable('+1 hour'));
	}

}

(new RefreshTokenRequestTest)->run();
