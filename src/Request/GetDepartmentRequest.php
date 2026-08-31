<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Request;

use Znojil\RevolutBusiness\DTO\DepartmentDTO;

/**
 * @extends BaseRequest<DepartmentDTO>
 * @link https://developer.revolut.com/docs/api/business#get-department
 *
 * @phpstan-import-type DepartmentResponseData from DepartmentDTO
 */
final class GetDepartmentRequest extends BaseRequest{

	public function __construct(
		private readonly string $departmentId
	){}

	public function getMethod(): string{
		return 'GET';
	}

	public function getUrn(): string{
		return 'departments/' . $this->departmentId;
	}

	public function createResponse(\Psr\Http\Message\ResponseInterface $httpResponse): DepartmentDTO{
		/** @var DepartmentResponseData */
		$data = $this->parseJsonResponseBody((string) $httpResponse->getBody());

		return DepartmentDTO::fromResponseData($data);
	}

}
