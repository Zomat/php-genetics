<?php

declare(strict_types=1);

namespace Zomat\PhpGenetics;

class Item
{
    public function __construct(
        public readonly string $name,
        public readonly int $value,
        public readonly int $weight
    ) {}
}
