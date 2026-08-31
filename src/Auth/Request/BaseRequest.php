<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Auth\Request;

abstract class BaseRequest{

	final public function getMethod(): string{
		return 'POST';
	}

	final public function getUrn(): string{
		return '1.0/auth/token';
	}

	/**
	 * @return array<string, string>
	 */
	final public function getHeaders(): array{
		return ['Content-Type' => 'application/x-www-form-urlencoded'];
	}

	/**
	 * @return array<string, string>
	 */
	abstract public function getData(): array;

	abstract public function createResponse(\Psr\Http\Message\ResponseInterface $httpResponse): \Znojil\RevolutBusiness\TokenPair;

}
