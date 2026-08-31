<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Request;

use Znojil\RevolutBusiness\DTO;

/**
 * @extends BaseRequest<DTO\PaginatedListDTO<DTO\LabelDTO>>
 * @link https://developer.revolut.com/docs/api/business#get-labels
 *
 * @phpstan-import-type LabelResponseData from DTO\LabelDTO
 */
final class GetLabelsRequest extends BaseRequest{

	public function __construct(
		private readonly string $groupId,
		private readonly ?int $limit = null,
		private readonly ?string $pageToken = null
	){}

	public function getMethod(): string{
		return 'GET';
	}

	public function getUrn(): string{
		return $this->buildUrn("label-groups/$this->groupId/labels", [
			'limit' => $this->limit,
			'page_token' => $this->pageToken
		]);
	}

	/**
	 * @return DTO\PaginatedListDTO<DTO\LabelDTO>
	 */
	public function createResponse(\Psr\Http\Message\ResponseInterface $httpResponse): DTO\PaginatedListDTO{
		/** @var array{next_page_token?: string, labels: list<LabelResponseData>} */
		$data = $this->parseJsonResponseBody((string) $httpResponse->getBody());

		return new DTO\PaginatedListDTO(
			array_map(DTO\LabelDTO::fromResponseData(...), $data['labels']),
			$data['next_page_token'] ?? null
		);
	}

}
