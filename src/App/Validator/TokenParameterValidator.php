<?php

declare(strict_types=1);

namespace App\Validator;

final class TokenParameterValidator
{
    public function validate(string $tokenId): ValidationResult
    {
        if (empty($tokenId)) {
            return ValidationResult::invalid('Token parameter is required');
        }

        return ValidationResult::valid();
    }
}

