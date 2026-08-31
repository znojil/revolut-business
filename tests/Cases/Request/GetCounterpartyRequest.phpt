<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Tests\Cases\Request;

use Tester\Assert;
use Znojil\RevolutBusiness\Request\GetCounterpartyRequest;

require __DIR__ . '/../../bootstrap.php';

/**
 * @testCase
 */
final class GetCounterpartyRequestTest extends \Tester\TestCase{

	public function testConfiguration(): void{
		$request = new GetCounterpartyRequest('7e18625a-3e6c-4d4f-8429-216c25309a5f');

		Assert::same('GET', $request->getMethod());
		Assert::same('counterparty/7e18625a-3e6c-4d4f-8429-216c25309a5f', $request->getUrn());
	}

	public function testCreateResponse(): void{
		$result = (new GetCounterpartyRequest('7e18625a-3e6c-4d4f-8429-216c25309a5f'))
			->createResponse(\Znojil\RevolutBusiness\Tests\Fixtures\ResponseFactory::create('counterparties/counterparty'));

		Assert::same('7e18625a-3e6c-4d4f-8429-216c25309a5f', $result->id);
		Assert::same('Acme Corp', $result->name);
		Assert::same('US', $result->country);
		Assert::same(\Znojil\RevolutBusiness\Enum\CounterpartyState::Created, $result->state);
		Assert::same('2022-08-05T14:29:22+00:00', $result->updatedAt->format('c'));
		Assert::same([], $result->cards);
	}

}

(new GetCounterpartyRequestTest)->run();
