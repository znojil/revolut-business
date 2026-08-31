<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Internal;

use Znojil\RevolutBusiness\Exception\UnexpectedValueException;

final class EnumMapper{

	/**
	 * @template T of \BackedEnum
	 * @param class-string<T> $enum
	 * @return T
	 * @throws UnexpectedValueException
	 */
	public static function from(string $enum, int|string $value): \BackedEnum{
		return $enum::tryFrom($value) ?? throw new UnexpectedValueException("Value '$value' is not valid for enum $enum.");
	}

}
