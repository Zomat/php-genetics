<?php

declare(strict_types=1);

namespace Zomat\PhpGenetics\Services;

use Zomat\PhpGenetics\Contracts\FitnessServiceInterface;
use Zomat\PhpGenetics\ValueObjects\Genome;

final class FitnessService implements FitnessServiceInterface
{
    private array $items;
    private int $weightLimit;

    public function setItems(array $items): FitnessServiceInterface
    {
        $this->items = $items;

        return $this;
    }

    public function setWeightLimit(int $weightLimit): FitnessServiceInterface
    {
        $this->weightLimit = $weightLimit;

        return $this;
    }

    public function getFitness(Genome $genome) : int
    {
        if (empty($this->items) || $this->weightLimit <= 0) {
            throw new \Exception("Items and weight limit must be set before calling getFitness");
        }

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
