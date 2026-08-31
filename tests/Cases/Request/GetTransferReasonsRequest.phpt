<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Tests\Cases\Request;

use Tester\Assert;
use Znojil\RevolutBusiness\Enum;
use Znojil\RevolutBusiness\Request\GetTransferReasonsRequest;

require __DIR__ . '/../../bootstrap.php';

/**
 * @testCase
 */
final class GetTransferReasonsRequestTest extends \Tester\TestCase{

	public function testConfiguration(): void{
		$request = new GetTransferReasonsRequest;

		Assert::same('GET', $request->getMethod());
		Assert::same('transfer-reasons', $request->getUrn());
	}

	public function testCreateResponse(): void{
		$result = (new GetTransferReasonsRequest)
			->createResponse(\Znojil\RevolutBusiness\Tests\Fixtures\ResponseFactory::create('transfers/transfer-reasons'));

		Assert::count(3, $result);

		Assert::same('GB', $result[0]->country);
		Assert::same(Enum\Currency::Gbp, $result[0]->currency);
		Assert::same(Enum\TransferReasonCode::Services, $result[0]->code);
		Assert::same('Payment for services', $result[0]->description);

		Assert::same(Enum\TransferReasonCode::Goods, $result[1]->code);

		// the documented list proved incomplete, so an unknown code falls back to the raw string
		// instead of breaking the very endpoint that is supposed to enumerate them
		Assert::same('undocumented_reason', $result[2]->code);
		Assert::same(Enum\Currency::Inr, $result[2]->currency);
	}

}

(new GetTransferReasonsRequestTest)->run();
