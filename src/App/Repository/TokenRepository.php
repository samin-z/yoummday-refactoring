<?php

declare(strict_types=1);

namespace App\Repository;

use App\Provider\TokenDataProvider;

/**
 * @phpstan-type TokenData array{token: string, permissions: string[]}
 */
final class TokenRepository implements TokenRepositoryInterface
{
    public function __construct(
        private readonly TokenDataProvider $tokenDataProvider
    ) {
    }

    /**
     * @return array<TokenData>
     */
    public function findAll(): array
    {
        return $this->tokenDataProvider->getTokens();
    }

    /**
     * @return TokenData|null
     */
    public function findByTokenId(string $tokenId): ?array
    {
        $tokens = $this->findAll();

        foreach ($tokens as $token) {
            if ($token['token'] === $tokenId) {
                return $token;
            }
        }

        return null;
    }
}

