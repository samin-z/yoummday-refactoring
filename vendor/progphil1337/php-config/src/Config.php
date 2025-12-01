<?php

namespace Progphil1337\Config;

use RuntimeException;

class Config {

	public static function create(string|array $files): self {

		$builder = new Builder();

		if (is_array($files)) {
			$data = $builder->fromFiles($files);
		} else {
			$data = $builder->fromFile($files);
		}

		return new Config($data);
	}

	private string $hierarchyOperator = '::';

	private function __construct(private array $data) {

	}

	public function setHierarchyOperator(string $hierarchyOperator): void {

		$this->hierarchyOperator = $hierarchyOperator;
	}

	public function map(callable $func): self {

		foreach ($this->data as &$data) {
			$this->mapOnLayer($data, $func);
		}

		return $this;
	}

	private function mapOnLayer(array &$data, callable $func): void {
		foreach ($data as &$value) {
			if (is_array($value)) {
				$this->mapOnLayer($value, $func);
			} else {
				$value = $func($value);
			}
		}
	}

	public function get(string $path): mixed {

		$steps = explode($this->hierarchyOperator, $path);
		$value = $this->data;
		foreach ($steps as $step) {
			$value = $value[ $step ];
		}

		return $value;
	}
}
