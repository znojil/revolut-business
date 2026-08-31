<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Request;

/**
 * @extends BaseRequest<string>
 * @link https://developer.revolut.com/docs/api/business#create-department
 */
final class CreateDepartmentRequest extends BaseRequest{

	public function __construct(
		private readonly string $name
	){}

	public function getMethod(): string{
		return 'POST';
	}

	public function getUrn(): string{
		return 'departments';
	}

	public function getHeaders(): array{
		return ['Content-Type' => 'application/json'];
	}

	public function getData(): array{
		return $this->buildData(['name' => $this->name]);
	}

	public function createResponse(\Psr\Http\Message\ResponseInterface $httpResponse): string{
		/** @var array{id: string} */
		$data = $this->parseJsonResponseBody((string) $httpResponse->getBody());

		return $data['id'];
	}

}
