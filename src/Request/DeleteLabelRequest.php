<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Request;

/**
 * @extends BaseRequest<null>
 * @link https://developer.revolut.com/docs/api/business#delete-label
 */
final class DeleteLabelRequest extends BaseRequest{

	public function __construct(
		private readonly string $groupId,
		private readonly string $labelId
	){}

	public function getMethod(): string{
		return 'DELETE';
	}

	public function getUrn(): string{
		return "label-groups/$this->groupId/labels/$this->labelId";
	}

	public function createResponse(\Psr\Http\Message\ResponseInterface $httpResponse): null{
		return null;
	}

}
