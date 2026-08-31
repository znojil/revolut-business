<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Request;

use Znojil\RevolutBusiness\DTO;

/**
 * @extends BaseRequest<DTO\PaginatedListDTO<DTO\DepartmentDTO>>
 * @link https://developer.revolut.com/docs/api/business#get-departments
 *
 * @phpstan-import-type DepartmentResponseData from DTO\DepartmentDTO
 */
final class GetDepartmentsRequest extends BaseRequest{

	public function __construct(
		private readonly ?int $limit = null,
		private readonly ?string $pageToken = null
	){}

	public function getMethod(): string{
		return 'GET';
	}

	public function getUrn(): string{
		return $this->buildUrn('departments', [
			'limit' => $this->limit,
			'page_token' => $this->pageToken
		]);
	}

	/**
	 * @return DTO\PaginatedListDTO<DTO\DepartmentDTO>
	 */
	public function createResponse(\Psr\Http\Message\ResponseInterface $httpResponse): DTO\PaginatedListDTO{
		/** @var array{next_page_token?: string, departments: list<DepartmentResponseData>} */
		$data = $this->parseJsonResponseBody((string) $httpResponse->getBody());

		return new DTO\PaginatedListDTO(
			array_map(DTO\DepartmentDTO::fromResponseData(...), $data['departments']),
			$data['next_page_token'] ?? null
		);
	}

}
