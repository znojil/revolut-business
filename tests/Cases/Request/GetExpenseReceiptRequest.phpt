<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Tests\Cases\Request;

use Tester\Assert;
use Znojil\Http\Message\Response;
use Znojil\RevolutBusiness\Request\GetExpenseReceiptRequest;

require __DIR__ . '/../../bootstrap.php';

/**
 * @testCase
 */
final class GetExpenseReceiptRequestTest extends \Tester\TestCase{

	public function testConfiguration(): void{
		$request = new GetExpenseReceiptRequest('8decf9f8-053e-46cb-92d8-a7b450fe5ae0', '84c0169a-37f9-4bfa-ab1e-f2c81dbc34cf');

		Assert::same('GET', $request->getMethod());
		Assert::same(
			'expenses/8decf9f8-053e-46cb-92d8-a7b450fe5ae0/receipts/84c0169a-37f9-4bfa-ab1e-f2c81dbc34cf/content',
			$request->getUrn()
		);
	}

	public function testCreateResponse(): void{
		$request = new GetExpenseReceiptRequest('8decf9f8-053e-46cb-92d8-a7b450fe5ae0', '84c0169a-37f9-4bfa-ab1e-f2c81dbc34cf');

		// the body is the raw file, not JSON — the media type is only available in the header
		$result = $request->createResponse(
			new Response(200, ['Content-Type' => 'application/pdf'], "%PDF-1.4\nbinary content")
		);
		Assert::same("%PDF-1.4\nbinary content", $result->content);
		Assert::same('application/pdf', $result->contentType);

		// without content type
		$result = $request->createResponse(new Response(200, body: 'binary content'));
		Assert::same('binary content', $result->content);
		Assert::null($result->contentType);
	}

}

(new GetExpenseReceiptRequestTest)->run();
