<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Tests\Cases\Request;

use Tester\Assert;
use Znojil\RevolutBusiness\Exception;
use Znojil\RevolutBusiness\Tests\Fixtures\TestableBaseRequest;

require __DIR__ . '/../../bootstrap.php';

/**
 * @testCase
 */
final class BaseRequestTest extends \Tester\TestCase{

	private TestableBaseRequest $request;

	protected function setUp(): void{
		$this->request = new TestableBaseRequest;
	}

	public function testDefaults(): void{
		Assert::same(\Znojil\RevolutBusiness\Http\ApiVersion::V1, $this->request->getApiVersion());
		Assert::same([], $this->request->getHeaders());
		Assert::same([], $this->request->getHttpClientOptions());
	}

	public function testBuildData(): void{
		Assert::same([
			'string' => 'String',
			'int' => 123,
			'float' => 123.45,
			'bool' => true,
			'false' => false,
			'zero' => 0,
			'empty_string' => '',
			'clear_value' => null
		], (new TestableBaseRequest)->getData());
	}

	public function testBuildUrn(): void{
		Assert::same(
			'test?limit=100&page_token=String&state=created&state=cancelled',
			$this->request->getUrn()
		);
	}

	public function testBuildRequiredDataThrows(): void{
		Assert::exception(function(): void{
			$this->request->buildRequiredData([]);
		}, Exception\InvalidArgumentException::class);
	}

	public function testFormatDatetime(): void{
		Assert::same(
			'2024-09-13T18:47:01.782000Z',
			$this->request->formatDatetime(new \DateTimeImmutable('2024-09-13 20:47:01.782000', new \DateTimeZone('Europe/Prague')))
		);
		Assert::null($this->request->formatDatetime(null));
	}

	public function testParseJsonResponseBodyThrows(): void{
		/** @var Exception\JsonResponseException */
		$e = Assert::exception(function(): void{
			$this->request->parseJsonResponseBody('null');
		}, Exception\JsonResponseException::class, 'Expected JSON object or array in response body.', 0);

		Assert::same('null', $e->responseBody);
	}

}

(new BaseRequestTest)->run();
