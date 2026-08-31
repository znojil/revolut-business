<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Tests\Cases\Request;

use Tester\Assert;
use Znojil\RevolutBusiness\DTO\CardReferenceDTO;
use Znojil\RevolutBusiness\Request\UpdateCardReferencesRequest;

require __DIR__ . '/../../bootstrap.php';

/**
 * @testCase
 */
final class UpdateCardReferencesRequestTest extends \Tester\TestCase{

	public function testConfiguration(): void{
		$request = new UpdateCardReferencesRequest('123e4567-e89b-12d3-a456-426614174000', [
			new CardReferenceDTO('Budget', 'Engagement'),
			new CardReferenceDTO('Department', 'Business')
		]);

		Assert::same('PUT', $request->getMethod());
		Assert::same('cards/123e4567-e89b-12d3-a456-426614174000/references', $request->getUrn());
		Assert::same(['Content-Type' => 'application/json'], $request->getHeaders());
		Assert::same([
			['name' => 'Budget', 'value' => 'Engagement'],
			['name' => 'Department', 'value' => 'Business']
		], $request->getData());
	}

	public function testCreateResponse(): void{
		$result = (new UpdateCardReferencesRequest('123e4567-e89b-12d3-a456-426614174000', [new CardReferenceDTO('Cost centre', 'HR')]))
			->createResponse(new \Znojil\Http\Message\Response(200, body: '[{"name":"Cost centre","value":"HR"}]'));

		Assert::count(1, $result);
		Assert::same('Cost centre', $result[0]->name);
		Assert::same('HR', $result[0]->value);
	}

}

(new UpdateCardReferencesRequestTest)->run();
