<?php

namespace App\Contracts;

interface AIServiceInterface
{
    public function generateAnswer(string $question, ?string $context = null): array;

    public function isAvailable(): bool;

    public function getProviderName(): string;
}
