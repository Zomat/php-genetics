<?php

declare(strict_types=1);

namespace Zomat\PhpGenetics;

class GenomePair
{
    public function __construct(
        public readonly Genome $genome1,
        public readonly Genome $genome2
    ) {}
}