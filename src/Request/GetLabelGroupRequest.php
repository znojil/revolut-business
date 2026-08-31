<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Request;

use Znojil\RevolutBusiness\DTO\LabelGroupDTO;

/**
 * @extends BaseRequest<LabelGroupDTO>
 * @link https://developer.revolut.com/docs/api/business#get-label-group
 *
 * @phpstan-import-type LabelGroupResponseData from LabelGroupDTO
 */
final class GetLabelGroupRequest extends BaseRequest{

	public function __construct(
		private readonly string $groupId
	){}

	public function getMethod(): string{
		return 'GET';
	}

	public function getUrn(): string{
		return 'label-groups/' . $this->groupId;
	}

	public function createResponse(\Psr\Http\Message\ResponseInterface $httpResponse): LabelGroupDTO{
		/** @var LabelGroupResponseData */
		$data = $this->parseJsonResponseBody((string) $httpResponse->getBody());

		return LabelGroupDTO::fromResponseData($data);
	}

}
