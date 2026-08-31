<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Tests\Cases\Internal;

use Tester\Assert;
use Znojil\RevolutBusiness\Enum\AccountState;
use Znojil\RevolutBusiness\Internal\EnumMapper;

require __DIR__ . '/../../bootstrap.php';

/**
 * @testCase
 */
final class EnumMapperTest extends \Tester\TestCase{

	public function testFrom(): void{
		Assert::same(AccountState::Active, EnumMapper::from(AccountState::class, 'active'));
		Assert::exception(
			fn() => EnumMapper::from(AccountState::class, 'unexpected'),
			\Znojil\RevolutBusiness\Exception\UnexpectedValueException::class,
			"Value 'unexpected' is not valid for enum " . AccountState::class . "."
		);
	}

}

(new EnumMapperTest)->run();
