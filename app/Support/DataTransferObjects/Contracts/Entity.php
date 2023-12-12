<?php

namespace Support\DataTransferObjects\Contracts;

interface Entity
{
    public static function arrayOf(array $arrayOfParameters): array;

    public function all(): array;

    public function only(string ...$keys): static;

    public function except(string ...$keys): static;

    public function toArray(): array;
}
