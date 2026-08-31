<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Request;

use Znojil\RevolutBusiness\DTO;

/**
 * @extends BaseRequest<DTO\PaginatedListDTO<DTO\LabelGroupDTO>>
 * @link https://developer.revolut.com/docs/api/business#get-label-groups
 *
 * @phpstan-import-type LabelGroupResponseData from DTO\LabelGroupDTO
 */
final class GetLabelGroupsRequest extends BaseRequest{

	public function __construct(
		private readonly ?int $limit = null,
		private readonly ?string $pageToken = null
	){}

	public function getMethod(): string{
		return 'GET';
	}

	public function getUrn(): string{
		return $this->buildUrn('label-groups', [
			'limit' => $this->limit,
			'page_token' => $this->pageToken
		]);
	}

	/**
	 * @return DTO\PaginatedListDTO<DTO\LabelGroupDTO>
	 */
	public function createResponse(\Psr\Http\Message\ResponseInterface $httpResponse): DTO\PaginatedListDTO{
		/** @var array{next_page_token?: string, label_groups: list<LabelGroupResponseData>} */
		$data = $this->parseJsonResponseBody((string) $httpResponse->getBody());

		return new DTO\PaginatedListDTO(
			array_map(DTO\LabelGroupDTO::fromResponseData(...), $data['label_groups']),
			$data['next_page_token'] ?? null
		);
	}

}
