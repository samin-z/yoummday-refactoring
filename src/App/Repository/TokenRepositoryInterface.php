<?php

declare(strict_types=1);

namespace App\Repository;

/**
 * @phpstan-type TokenData array{token: string, permissions: string[]}
 */
interface TokenRepositoryInterface
{
    /**
     * @return array<TokenData>
     */
    public function findAll(): array;

    /**
     * @return TokenData|null
     */
    public function findByTokenId(string $tokenId): ?array;
}

