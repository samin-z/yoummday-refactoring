<?php

namespace Progphil1337\Config;

use InvalidArgumentException;
use Progphil1337\Config\Reader\YamlReader;
use Progphil1337\Config\Reader\JsonReader;

enum Type: int {

	case YAML = 0;
	case JSON = 1;
	case INI = 2;

	public function getReader(): Reader {
		return match($this) {
			self::YAML => new YamlReader(),
			self::JSON => new JsonReader(),
			default => throw new \RuntimeException()
		};
	}

	private static function getMapping(): array {

		return [
			self::YAML->value => [
				'yml',
				'yaml'
			],
			self::JSON->value => [
				'json'
			],
			self::INI->value => [
				'ini'
			]
		];
	}

	public static function get(int|string $value): ?static {

		$mapping = self::getMapping();

		if (is_int($value) && array_key_exists($value, $mapping)) {
			return self::from($value);
		}

		$value = mb_strtolower($value);

		$reverseMapping = [];
		foreach ($mapping as $key => $values) {
			foreach ($values as $val) {
				$reverseMapping[ $val ] = $key;
			}
		}

		if (array_key_exists($value, $reverseMapping)) {
			return self::from($reverseMapping[ $value ]);
		}

		throw new InvalidArgumentException(sprintf('Config type not supported: %s%sSupported types: %s', $value, PHP_EOL, implode(', ', array_keys($reverseMapping))));
	}
}
