<?php

declare(strict_types=1);

namespace Zomat\PhpGenetics\Contracts;

use Zomat\PhpGenetics\ValueObjects\Genome;

interface FitnessServiceInterface
{
    public function setItems(array $items): FitnessServiceInterface;

    public function setWeightLimit(int $weightLimit): FitnessServiceInterface;

    public function getFitness(Genome $genome): int;
}