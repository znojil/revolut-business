<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Request;

use Znojil\RevolutBusiness\DTO\TransferReasonDTO;

/**
 * @extends BaseRequest<list<TransferReasonDTO>>
 * @link https://developer.revolut.com/docs/api/business#get-transfer-reasons
 *
 * @phpstan-import-type TransferReasonResponseData from TransferReasonDTO
 */
final class GetTransferReasonsRequest extends BaseRequest{

	public function getMethod(): string{
		return 'GET';
	}

	public function getUrn(): string{
		return 'transfer-reasons';
	}

	/**
	 * @return list<TransferReasonDTO>
	 */
	public function createResponse(\Psr\Http\Message\ResponseInterface $httpResponse): array{
		/** @var list<TransferReasonResponseData> */
		$data = $this->parseJsonResponseBody((string) $httpResponse->getBody());

		return array_map(TransferReasonDTO::fromResponseData(...), $data);
	}

}
