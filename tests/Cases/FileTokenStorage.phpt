<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Tests\Cases;

use Tester\Assert;
use Znojil\RevolutBusiness\Exception;
use Znojil\RevolutBusiness\FileTokenStorage;
use Znojil\RevolutBusiness\TokenPair;

require __DIR__ . '/../bootstrap.php';

/**
 * @testCase
 */
final class FileTokenStorageTest extends \Tester\TestCase{

	private const FixturesDataDir = __DIR__ . '/../Fixtures/data/token';

	public function testLoad(): void{
		Assert::null((new FileTokenStorage(self::FixturesDataDir))->load());

		/** @var TokenPair */
		$tokenPair = (new FileTokenStorage(self::FixturesDataDir . '/valid.json'))->load();
		Assert::same('oa_access-token', $tokenPair->accessToken);
		Assert::same('2017-06-01T11:11:11+02:00', $tokenPair->expirationDatetime->format('c'));
		Assert::same('oa_refresh-token', $tokenPair->refreshToken);

		// the token file is corrupted, the original error is kept as previous
		foreach([
			['empty', Exception\JsonException::class], // not valid JSON
			['mistyped', \TypeError::class], // mistyped field
			['invalid-date', \Exception::class] // invalid expiration
		] as [$fixture, $previous]){
			$filePath = self::FixturesDataDir . "/$fixture.json";
			/** @var Exception\IOException */
			$e = Assert::exception(
				fn() => (new FileTokenStorage($filePath))->load(),
				Exception\IOException::class,
				"Token file '$filePath' is corrupted."
			);
			Assert::type($previous, $e->getPrevious());
		}
	}

	public function testSave(): void{
		$fileTokenStorage = new FileTokenStorage(TempDir . '/temp_token.json');
		$tokenPair = new TokenPair('access-Token', new \DateTimeImmutable, 'Refresh-token');

		$fileTokenStorage->save($tokenPair);

		/** @var TokenPair */
		$loadedTokenPair = $fileTokenStorage->load();
		Assert::same('access-Token', $loadedTokenPair->accessToken);
		Assert::same($tokenPair->expirationDatetime->format('c'), $loadedTokenPair->expirationDatetime->format('c'));
		Assert::same('Refresh-token', $loadedTokenPair->refreshToken);

		// throws
		@mkdir($dir = TempDir . '/not-a-file');
		Assert::exception(
			fn() => (new FileTokenStorage($dir))->save(new TokenPair('a', new \DateTimeImmutable, 'r')),
			Exception\IOException::class
		);
	}

}

(new FileTokenStorageTest)->run();
