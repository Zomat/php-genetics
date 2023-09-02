<?php

declare(strict_types=1);

namespace Zomat\PhpGenetics\ValueObjects;

class Population
{
    public function __construct(
        public readonly array $genomes
    )
    {
        foreach ($this->genomes as $genome) {
            if (!$genome instanceof Genome) {
                throw new \InvalidArgumentException('All elements in genomes array must be instances of Genome.');
            }
        }
    }

    public function getSize() : int
    {
        return count($this->genomes);
    }
}
