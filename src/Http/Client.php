<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Http;

use Psr\Http\Message;

interface Client{

	/**
	 * @param array<string, string|string[]> $headers
	 * @param array<int|string, mixed> $options request options: string keys are Option::* enum values (transport-agnostic, must be honored by every implementation; unknown string keys must be rejected with an exception), int keys are raw CURLOPT_* constants — non-cURL implementations must reject them with an exception rather than silently ignore them
	 */
	function send(string $method, string|Message\UriInterface $uri, array $headers = [], mixed $data = null, array $options = []): Message\ResponseInterface;

}
