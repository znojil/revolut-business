<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Request;

/**
 * @extends BaseRequest<string>
 * @link https://developer.revolut.com/docs/api/business#create-label
 */
final class CreateLabelRequest extends BaseRequest{

	public function __construct(
		private readonly string $groupId,
		private readonly string $name
	){}

	public function getMethod(): string{
		return 'POST';
	}

	public function getUrn(): string{
		return "label-groups/$this->groupId/labels";
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
