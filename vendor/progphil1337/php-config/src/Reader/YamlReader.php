<?php

namespace Progphil1337\Config\Reader;

use Progphil1337\Config\Reader;

class YamlReader implements Reader {

	public function read(string $content): array {

		return yaml_parse($content);
	}
}
