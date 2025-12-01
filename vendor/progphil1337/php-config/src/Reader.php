<?php

namespace Progphil1337\Config;

interface Reader {

	public function read(string $content): array;
}
