<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness;

/**
 * @phpstan-import-type TokenPairData from TokenPair
 */
final class FileTokenStorage implements TokenStorage{

	public function __construct(
		private readonly string $filePath
	){}

	/**
	 * @throws Exception\IOException if the token file exists but cannot be read
	 * @throws Exception\JsonException if the token file is not valid JSON
	 */
	public function load(): ?TokenPair{
		if(!is_file($this->filePath)){
			return null;
		}

		if(($content = @file_get_contents($this->filePath)) === false){
			throw new Exception\IOException("Unable to read token file '{$this->filePath}'.");
		}

		/** @var TokenPairData */
		$data = Internal\Json::decode($content);

		return TokenPair::fromArray($data);
	}

	/**
	 * @throws Exception\IOException if the token file cannot be written
	 */
	public function save(TokenPair $tokenPair): void{
		$dir = dirname($this->filePath);
		if(!is_dir($dir)){
			@mkdir($dir, 0700, true);
		}

		if(@file_put_contents($this->filePath, Internal\Json::encode($tokenPair), LOCK_EX) === false){
			throw new Exception\IOException("Unable to write token file '{$this->filePath}'.");
		}

		@chmod($this->filePath, 0600);
	}

}
