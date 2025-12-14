<?php

declare(strict_types=1);

namespace App\Validator;

final class ValidationResult
{
    private function __construct(
        private readonly bool $isValid,
        private readonly ?string $errorMessage = null
    ) {
    }

    public static function valid(): self
    {
        return new self(true);
    }

    public static function invalid(string $errorMessage): self
    {
        return new self(false, $errorMessage);
    }

    public function isValid(): bool
    {
        return $this->isValid;
    }

    public function getErrorMessage(): ?string
    {
        return $this->errorMessage;
    }
}

