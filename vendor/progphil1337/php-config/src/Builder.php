<?php

namespace Progphil1337\Config;

use RuntimeException;

class Builder {

	public function fromFile(string $file): array {

		$this->checkFileValidity($file);

		$reader = $this->getReader($file);

		return $reader->read(file_get_contents($file));
	}

	public function fromFiles(array $files): array {

		$data = [];

		foreach ($files as $file) {
			$this->checkFileValidity($file);

			$reader = $this->getReader($file);

			$data = array_merge_recursive($data, $reader->read(file_get_contents($file)));
		}

		return $data;
	}

	private function getReader(string $file): Reader {

		$split = explode('.', $file);
		$fileType = end($split);
		unset($split);

		$type = Type::get($fileType);

		return $type->getReader();
	}

	private function checkFileValidity(string $file): void {

		if (!file_exists($file)) {
			throw new RuntimeException(sprintf('Config file does not exist: %s', $file));
		}

		if (!is_file($file)) {
			throw new RuntimeException(sprintf('Given path is not a file: %s', $file));
		}

		if (!is_readable($file)) {
			throw new RuntimeException(sprintf('Config file is not readable: %s', $file));
		}
	}
}
