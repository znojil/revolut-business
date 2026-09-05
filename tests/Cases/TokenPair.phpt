<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Tests\Cases;

use Tester\Assert;
use Znojil\RevolutBusiness\TokenPair;

require __DIR__ . '/../bootstrap.php';

/**
 * @testCase
 */
final class TokenPairTest extends \Tester\TestCase{

	public function testJsonSerialize(): void{
		$expirationDatetime = new \DateTimeImmutable('2017-06-01 11:00:00');
		$tokenPair = TokenPair::fromArray([
			'accessToken' => 'access-Token',
			'expirationDatetime' => $expirationDatetime->format('c'),
			'refreshToken' => 'refresh'
		]);

		Assert::same([
			'accessToken' => 'access-Token',
			'expirationDatetime' => $expirationDatetime->format('c'),
			'refreshToken' => 'refresh'
		], $tokenPair->jsonSerialize());
	}

	public function testIsExpired(): void{
		foreach([
			['+2 minutes', false],
			['-1 second', true],
			['+30 seconds', true] // a token valid for another 30 seconds is treated as expired, so it cannot die mid-request
		] as [$modify, $isExpired]){
			Assert::same($isExpired, (new TokenPair('access', new \DateTimeImmutable($modify), 'refresh'))->isExpired());
		}

		// custom margin
		foreach([
			[60, false],
			[90, false], // not 119, that would leave the assertion only a one second budget against the real clock
			[120, true],
			[300, true]
		] as [$marginSeconds, $isExpired]){
			Assert::same($isExpired, (new TokenPair('access', new \DateTimeImmutable('+2 minutes'), 'refresh'))->isExpired($marginSeconds));
		}
	}

}

(new TokenPairTest)->run();
