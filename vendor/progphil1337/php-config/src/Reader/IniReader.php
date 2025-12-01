<?php

namespace Progphil1337\Config\Reader;

use Progphil1337\Config\Reader;

class IniReader implements Reader {

	public function read(string $content): array {

		return parse_ini_string($content, true);
	}
}
