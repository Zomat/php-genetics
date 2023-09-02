<?php

declare(strict_types=1);

namespace Zomat\PhpGenetics\ValueObjects;

class Genome
{
    public function __construct(
        private array $values
    ) {}

    public function __toString() : string
    {
        return implode(', ', $this->values);
    }

    public function get()
    {
        return $this->values;
    }

    public function getLength() : int
    {
        return count($this->values);
    }

    public function getGene(int $num) : int
    {
        return $this->values[$num];
    }

    public function setGene(int $num, int $value) : void
    {
        $this->values[$num] = $value;
    }
}
