<?php

declare(strict_types=1);

namespace Zomat\PhpGenetics;

class FitnessService
{
    public function __construct(
        public readonly array $items,
        public readonly int $weightLimit
    ) {}

    public function getFitness(Genome $genome) : int
    {
        if (count($genome->get()) != count($this->items)) {
            throw new \Exception("Genome and items must be of the same length");
        }

        $weight = 0;
        $value = 0;

        foreach ($this->items as $num => $item) {
            if ($genome->getGene($num) == 1) {
                $weight += $item->weight;
                $value += $item->value;
            }

            if ($weight > $this->weightLimit) {
                return 0;
            }
        }

        return $value;
    }
}
