<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Tests\Fixtures;

use Znojil\RevolutBusiness\Request\BaseRequest;

/**
 * @extends BaseRequest<null>
 */
final class TestableBaseRequest extends BaseRequest{

	public function getMethod(): string{
		return 'GET';
	}

	public function getData(): array{
		return $this->buildData([
			'string' => 'String',
			'int' => 123,
			'float' => 123.45,
			'bool' => true,
			'null' => null,
			'false' => false,
			'zero' => 0,
			'empty_string' => '',
			'clear_value' => \Znojil\RevolutBusiness\Clear::Value
		]);
	}

	public function getUrn(): string{
		return $this->buildUrn('test', [
			'limit' => 100,
			'page_token' => 'String',
			'state' => ['created', 'cancelled'],
			'null' => null
		]);
	}

	public function createResponse(\Psr\Http\Message\ResponseInterface $response): null{
		return null;
	}

	public function buildRequiredData(array $mapping): array{
		return parent::buildRequiredData($mapping);
	}

	public function formatDatetime(?\DateTimeInterface $datetime): ?string{
		return parent::formatDatetime($datetime);
	}

	public function parseJsonResponseBody(string $responseBody): array{
		return parent::parseJsonResponseBody($responseBody);
	}

}
