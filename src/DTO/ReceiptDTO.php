<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\DTO;

final readonly class ReceiptDTO{

	public function __construct(
		public string $content,
		public ?string $contentType
	){}

}
