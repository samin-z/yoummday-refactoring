<?php

declare(strict_types=1);

namespace App\Request;

final class QueryParameterExtractor
{
    private const DEFAULT_PERMISSION = 'read';

    public function extractPermission(array $queryParams): string
    {
        return $queryParams['permission'] ?? self::DEFAULT_PERMISSION;
    }
}

