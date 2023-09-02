<?php

declare(strict_types=1);

namespace Zomat\PhpGenetics\Contracts;

use Zomat\PhpGenetics\ValueObjects\Genome;
use Zomat\PhpGenetics\ValueObjects\GenomePair;

interface GenomeServiceInterface
{
    public function setMutationLimit(int $mutationLimit): GenomeServiceInterface;

    public function setMutationProbability(float $mutationProbability): GenomeServiceInterface;

    public function create(int $length): Genome;

    public function mutation(Genome $genome): Genome;

    public function singlePointCrossover(Genome $genome1, Genome $genome2): GenomePair;

    public function toItemNames(Genome $genome, array $items): string;
}
