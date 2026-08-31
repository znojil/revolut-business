<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Request;

use Znojil\RevolutBusiness\Exception;
use Znojil\RevolutBusiness\Http;

/**
 * @template T
 * @implements Http\Request<T>
 */
abstract class BaseRequest implements Http\Request{

	public function getApiVersion(): Http\ApiVersion{
		return Http\ApiVersion::V1;
	}

	public function getHeaders(): array{
		return [];
	}

	public function getData(): array{
		return [];
	}

	public function getHttpClientOptions(): array{
		return [];
	}

	/**
	 * @param array<string, mixed> $queryParams
	 */
	protected function buildUrn(string $path, array $queryParams = []): string{
		$parts = [];
		foreach($queryParams as $k => $v){
			if($v === null){
				continue;
			}

			foreach(is_array($v) ? $v : [$v] as $v2){
				$parts[] = http_build_query([$k => $v2]);
			}
		}

		return $parts !== [] ? ($path . '?' . implode('&', $parts)) : $path;
	}

	/**
	 * @param array<string, mixed> $mapping
	 * @return array<string, mixed>
	 */
	protected function buildData(array $mapping): array{
		$data = [];
		foreach($mapping as $k => $v){
			if($v === \Znojil\RevolutBusiness\Clear::Value){
				$data[$k] = null;
			}elseif($v !== null){
				$data[$k] = $v;
			}
		}

		return $data;
	}

	/**
	 * @param array<string, mixed> $mapping
	 * @return non-empty-array<string, mixed>
	 * @throws Exception\InvalidArgumentException if no property is provided
	 */
	protected function buildRequiredData(array $mapping): array{
		if(($data = $this->buildData($mapping)) === []){
			throw new Exception\InvalidArgumentException('At least one property must be provided.');
		}

		return $data;
	}

	protected function formatDatetime(?\DateTimeInterface $datetime): ?string{
		return $datetime !== null
			? \DateTimeImmutable::createFromInterface($datetime)->setTimezone(new \DateTimeZone('UTC'))->format('Y-m-d\TH:i:s.u\Z')
			: null;
	}

	/**
	 * @throws Exception\JsonException if malformed JSON
	 * @throws Exception\JsonResponseException if unexpected response body shape
	 * @return array<int|string, mixed>
	 */
	protected function parseJsonResponseBody(string $responseBody): array{
		$data = \Znojil\RevolutBusiness\Internal\Json::decode($responseBody);
		if(!is_array($data)){
			throw new Exception\JsonResponseException(
				'Expected JSON object or array in response body.',
				0,
				responseBody: $responseBody
			);
		}

		return $data;
	}

}
