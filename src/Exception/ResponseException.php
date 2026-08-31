<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Exception;

class ResponseException extends \RuntimeException implements Exception{

	public function __construct(
		string $message,
		int $statusCode,
		public readonly ?int $apiErrorCode = null,
		public readonly ?string $apiErrorId = null,
		public readonly ?string $responseBody = null,
		?\Throwable $previous = null
	){
		parent::__construct($message, $statusCode, $previous);
	}

}
