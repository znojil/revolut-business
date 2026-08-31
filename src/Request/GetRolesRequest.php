<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Request;

use Znojil\RevolutBusiness\DTO\RoleDTO;

/**
 * @extends BaseRequest<list<RoleDTO>>
 * @link https://developer.revolut.com/docs/api/business#get-roles
 *
 * @phpstan-import-type RoleResponseData from RoleDTO
 */
final class GetRolesRequest extends BaseRequest{

	public function __construct(
		private readonly ?\DateTimeInterface $createdBefore = null,
		private readonly ?int $limit = null
	){}

	public function getMethod(): string{
		return 'GET';
	}

	public function getUrn(): string{
		return $this->buildUrn('roles', [
			'created_before' => $this->formatDatetime($this->createdBefore),
			'limit' => $this->limit
		]);
	}

	/**
	 * @return list<RoleDTO>
	 */
	public function createResponse(\Psr\Http\Message\ResponseInterface $httpResponse): array{
		/** @var list<RoleResponseData> */
		$data = $this->parseJsonResponseBody((string) $httpResponse->getBody());

		return array_map(RoleDTO::fromResponseData(...), $data);
	}

}
