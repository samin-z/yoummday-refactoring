<?php

namespace Progphil1337\Config\Reader;

use Progphil1337\Config\Reader;

class JsonReader implements Reader {

	public function read(string $content): array {

		return json_decode($content, true);
	}
}
