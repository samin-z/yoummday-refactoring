<?php

declare(strict_types=1);

namespace App\Service;

/**
 * @phpstan-type TokenData array{token: string, permissions: string[]}
 */
final class PermissionChecker
{
    /**
     * @param TokenData $token
     */
    public function hasPermission(array $token, string $permission): bool
    {
        $permissions = $token['permissions'] ?? [];
        return in_array($permission, $permissions, true);
    }
}

