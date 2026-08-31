<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Request;

/**
 * @extends BaseRequest<string>
 * @link https://developer.revolut.com/docs/api/business#create-label-group
 */
final class CreateLabelGroupRequest extends BaseRequest{

	/**
	 * @param non-empty-list<string> $labels
	 */
	public function __construct(
		private readonly string $name,
		private readonly array $labels
	){}

	public function getMethod(): string{
		return 'POST';
	}

	public function getUrn(): string{
		return 'label-groups';
	}

	public function getHeaders(): array{
		return ['Content-Type' => 'application/json'];
	}

	public function getData(): array{
		return $this->buildData([
			'name' => $this->name,
			'labels' => array_map(fn(string $name): array => ['name' => $name], $this->labels)
		]);
	}

	public function createResponse(\Psr\Http\Message\ResponseInterface $httpResponse): string{
		/** @var array{id: string} */
		$data = $this->parseJsonResponseBody((string) $httpResponse->getBody());

		return $data['id'];
	}

}
