<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Tests\Fixtures;

final class PrivateKey{

	private static ?string $privateKey = null;

	public static function get(): string{
		if(self::$privateKey === null){
			if(($key = openssl_pkey_new(['private_key_type' => OPENSSL_KEYTYPE_RSA, 'private_key_bits' => 2048])) === false){
				throw new \RuntimeException('Unable to generate a test private key.');
			}

			openssl_pkey_export($key, $privateKey);
			/** @var string $privateKey */

			self::$privateKey = $privateKey;
		}

		return self::$privateKey;
	}

}
