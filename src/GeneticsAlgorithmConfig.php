<?php

declare(strict_types=1);

namespace Zomat\PhpGenetics;

class GeneticsAlgorithmConfig
{
    public function __construct(
        public readonly int $generationLimit,
        public readonly ?int $fitnessLimit,
        public readonly int $weightLimit,
        public readonly int $populationSize,
        public readonly bool $elitarism,
    ) {}
}