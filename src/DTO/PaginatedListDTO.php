<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\DTO;

/**
 * @template T
 */
final readonly class PaginatedListDTO{

	/**
	 * @param list<T> $items
	 */
	public function __construct(
		public array $items,
		public ?string $nextPageToken
	){}

}
