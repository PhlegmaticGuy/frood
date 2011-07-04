<?php
/**
 * Static methods for parameter value casting.
 *
 * PHP version 5
 *
 * @category Library
 * @package  Frood
 * @author   Jens Riisom Schultz <jers@fynskemedier.dk>
 * @since    2011-06-30
 */

/**
 * FroodParameterCaster - Static methods for parameter value casting.
 * FroodParameters extends this to use the functionality in a direct way.
 *
 * @category   Library
 * @package    Frood
 * @subpackage Class
 * @author     Jens Riisom Schultz <jers@fynskemedier.dk>
 *
 */
abstract class FroodParameterCaster {
	/** @var string The constant to tell the get function that you want an integer. */
	const AS_INTEGER = 'integer';

	/** @var string The constant to tell the get function that you want a float. */
	const AS_FLOAT = 'float';

	/** @var string The constant to tell the get function that you want an array. */
	const AS_ARRAY = 'array';

	/** @var string The constant to tell the get function that you want a string. */
	const AS_STRING = 'string';

	/** @var string The constant to tell the get function that you want a ISO-8859-1 encoded string. */
	const AS_ISO = 'ISO-8859-1 encoded string';

	/** @var string The constant to tell the get function that you want a UTF-8 encoded string. */
	const AS_UTF8 = 'UTF-8 encoded string';

	/** @var string The constant to tell the get function that you want a json decoded string (i.e. an array). */
	const AS_JSON = 'JSON formatted string';

	/**
	 * Attempt to cast a value as the given type.
	 *
	 * @param string $type  Ensure that the parameter value is of the given type. Use one of the AS_ class constants.
	 * @param mixed  $value The value to cast.
	 *
	 * @return mixed It's like a box of chocolates.
	 *
	 * @throws FroodCastingException If the value could not be cast.
	 * @throws RuntimeException      If the type is unknown.
	 */
	protected static function _cast($type, $value) {
		switch ($type) {
			case null:
				return $value;
			case self::AS_INTEGER:
				return self::_castAsInteger($value);
			case self::AS_STRING:
				return self::_castAsString($value);
			case self::AS_FLOAT:
				return self::_castAsFloat($value);
			case self::AS_ARRAY:
				return self::_castAsArray($value);
			case self::AS_ISO:
				return self::_castAsIso($value);
			case self::AS_UTF8:
				return self::_castAsUtf8($value);
			case self::AS_JSON:
				return self::_castAsJson($value);
			default:
				throw new RuntimeException('Unknown type, ' . $type . '.');
		}
	}

	/**
	 * Attempt to cast a value to integer.
	 *
	 * @param mixed $value The value to cast.
	 *
	 * @throws FroodCastingException If the value could not be cast.
	 *
	 * @return integer
	 */
	private static function _castAsInteger($value) {
		if (is_int($value)) {
			return (int) $value;
		}
		if (is_string($value) && preg_match('/^\s*\-?[0-9]+\s*$/', $value)) {
			return intval($value);
		}

		throw new FroodCastingException($value, self::AS_INTEGER);
	}

	/**
	 * Attempt to cast a value to string.
	 *
	 * @param mixed $value The value to cast.
	 *
	 * @throws FroodCastingException If the value could not be cast.
	 *
	 * @return string
	 */
	private static function _castAsString($value) {
		if ($value !== null && !is_array($value) && !is_object($value)) {
			return (string) $value;
		}

		throw new FroodCastingException($value, self::AS_STRING);
	}

	/**
	 * Attempt to cast a value to float.
	 *
	 * @param mixed $value The value to cast.
	 *
	 * @throws FroodCastingException If the value could not be cast.
	 *
	 * @return float
	 *
	 * @SuppressWarnings(PHPMD.UnusedLocalVariable)
	 */
	private static function _castAsFloat($value) {
		if (is_float($value)) {
			return (float) $value;
		}
		if ($value !== null && !is_array($value) && !is_object($value) && is_string($value) && preg_match('/^\s*\-?[0-9\.,]+\s*$/', $value)) {
			return floatval(str_replace(',', '.', $value));
		}
		try {
			return (float) self::_cast(self::AS_INTEGER, $value);
		} catch (FroodCastingException $e) {
			throw new FroodCastingException($value, self::AS_FLOAT);
		}
	}

	/**
	 * Attempt to cast a value to array.
	 *
	 * @param mixed $value The value to cast.
	 *
	 * @throws FroodCastingException If the value could not be cast.
	 *
	 * @return array
	 */
	private static function _castAsArray($value) {
		if (!is_object($value) && is_array($value)) {
			return $value;
		}

		throw new FroodCastingException($value, self::AS_ARRAY);
	}

	/**
	 * Attempt to cast a value to an ISO-8859-1 encoded string.
	 *
	 * @param mixed $value The value to cast.
	 *
	 * @throws FroodCastingException If the value could not be cast.
	 *
	 * @return array
	 *
	 * @SuppressWarnings(PHPMD.UnusedLocalVariable)
	 */
	private static function _castAsIso($value) {
		try {
			return self::_encode(self::_cast(self::AS_STRING, $value), 'ISO-8859-1');
		} catch (FroodCastingException $e) {
			throw new FroodCastingException($value, self::AS_ISO);
		}
	}

	/**
	 * Attempt to cast a value to an UTF-8 encoded string.
	 *
	 * @param mixed $value The value to cast.
	 *
	 * @throws FroodCastingException If the value could not be cast.
	 *
	 * @return array
	 *
	 * @SuppressWarnings(PHPMD.UnusedLocalVariable)
	 */
	private static function _castAsUtf8($value) {
		try {
			return self::_encode(self::_cast(self::AS_STRING, $value), 'UTF-8');
		} catch (FroodCastingException $e) {
			throw new FroodCastingException($value, self::AS_UTF8);
		}
	}

	/**
	 * Attempt to cast a value to a JSON encoded array.
	 *
	 * @param mixed $value The value to cast.
	 *
	 * @todo Uncomment the error handling (PHP 5.3.3+)
	 *
	 * @throws FroodCastingException If the value could not be cast.
	 *
	 * @return array
	 *
	 * @SuppressWarnings(PHPMD.UnusedLocalVariable)
	 */
	private static function _castAsJson($value) {
		try {
			$result = json_decode(self::_cast(self::AS_UTF8, $value), true);
		} catch (FroodCastingException $e) {
			throw new FroodCastingException($value, self::AS_JSON);
		}

		return $result;

		// Full JSON decoding error handling is not available until PHP 5.3.3
		/*
		$errorMessage = 'Parameter value, ' . var_export($value, true) . ', could not be cast as ' . self::AS_JSON;
		switch (json_last_error()) {
			case JSON_ERROR_NONE:
				return $result;
				break;
			case JSON_ERROR_DEPTH:
				throw new FroodCastingException($value, self::AS_JSON, $errorMessage . ' (The maximum stack depth has been exceeded)');
				break;
			case JSON_ERROR_STATE_MISMATCH:
				throw new FroodCastingException($value, self::AS_JSON, $errorMessage . ' (Invalid or malformed JSON)');
				break;
			case JSON_ERROR_CTRL_CHAR:
				throw new FroodCastingException($value, self::AS_JSON, $errorMessage . ' (Control character error, possibly incorrectly encoded)');
				break;
			case JSON_ERROR_SYNTAX:
				throw new FroodCastingException($value, self::AS_JSON, $errorMessage . ' (Syntax error)');
				break;
			case JSON_ERROR_UTF8:
				throw new FroodCastingException($value, self::AS_JSON, $errorMessage . ' (Malformed UTF-8 characters, possibly incorrectly encoded)');
				break;
			default:
				throw new FroodCastingException($value, self::AS_JSON, $errorMessage . ' (Unknown error)');
				break;
		}
		*/
	}

	/**
	 * Get the content encoding.
	 *
	 * @return string
	 */
	private static function _contentEncoding() {
		if (preg_match('/charset=([\w-]+)/', $_SERVER['CONTENT_TYPE'], $match)) {
			return $match[1];
		} else {
			return 'UTF-8';
		}
	}

	/**
	 * Reencode (if nessesary) the string to our choosen character set.
	 *
	 * @param string $value   The string to encode.
	 * @param string $charset The charset to encode to.
	 *
	 * @return string
	 */
	private static function _encode($value, $charset) {
		return iconv(self::_contentEncoding(), "$charset//TRANSLIT", $value);
	}
}
