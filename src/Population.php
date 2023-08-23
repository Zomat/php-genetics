<?php

declare(strict_types=1);

namespace Zomat\PhpGenetics;

class Population
{
    public function __construct(
        public readonly array $genomes
    ) {}

    public function getSize() : int
    {
        return count($this->genomes);
    }
}
