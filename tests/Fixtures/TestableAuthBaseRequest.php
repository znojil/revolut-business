<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Tests\Fixtures;

use Znojil\RevolutBusiness\Auth\Request\BaseRequest;
use Znojil\RevolutBusiness\TokenPair;

final class TestableAuthBaseRequest extends BaseRequest{

	public function getData(): array{
		return [];
	}

	public function createResponse(\Psr\Http\Message\ResponseInterface $response): TokenPair{
		return new TokenPair('a', new \DateTimeImmutable, 'r');
	}

}
