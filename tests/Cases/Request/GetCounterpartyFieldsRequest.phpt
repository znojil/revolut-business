<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Tests\Cases\Request;

use Tester\Assert;
use Znojil\RevolutBusiness\Enum;
use Znojil\RevolutBusiness\Request\GetCounterpartyFieldsRequest;

require __DIR__ . '/../../bootstrap.php';

/**
 * @testCase
 */
final class GetCounterpartyFieldsRequestTest extends \Tester\TestCase{

	public function testConfiguration(): void{
		foreach([
			[
				new GetCounterpartyFieldsRequest('US', Enum\Currency::Usd, Enum\ProfileType::Business, Enum\PaymentRoute::Fedwire),
				'counterparties/fields?country=US&currency=USD&recipient_type=business&route=fedwire'
			],
			[
				new GetCounterpartyFieldsRequest('BR', Enum\Currency::Usd, Enum\ProfileType::Personal),
				'counterparties/fields?country=BR&currency=USD&recipient_type=personal'
			]
		] as [$request, $urn]){
			Assert::same('GET', $request->getMethod());
			Assert::same($urn, $request->getUrn());
		}
	}

	public function testCreateResponse(): void{
		$result = (new GetCounterpartyFieldsRequest('BR', Enum\Currency::Usd, Enum\ProfileType::Personal))
			->createResponse(\Znojil\RevolutBusiness\Tests\Fixtures\ResponseFactory::create('counterparties/fields'));

		Assert::count(3, $result);

		Assert::same('first_name', $result[0]->name);
		Assert::true($result[0]->required);
		Assert::null($result[0]->regex);
		Assert::null($result[0]->options);

		Assert::same(['pattern' => '^\d{11}$', 'description' => 'CPF (Cadastro de Pessoas Fisicas)'], $result[1]->regex);

		Assert::false($result[2]->required);
		// a missing default is normalised to false
		Assert::same([
			['value' => 'checking', 'default' => true],
			['value' => 'savings', 'default' => false]
		], $result[2]->options);
	}

}

(new GetCounterpartyFieldsRequestTest)->run();
