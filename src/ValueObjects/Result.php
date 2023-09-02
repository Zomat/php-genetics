<?php

declare(strict_types=1);

namespace Zomat\PhpGenetics\ValueObjects;

class Result
{
    public function __construct(
        public readonly Genome $genome,
        public readonly string $itemNames,
        public readonly int $fitness,
        public readonly int $generation
    ) {}
}