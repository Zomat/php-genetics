<?php

declare(strict_types=1);

namespace Zomat\PhpGenetics\Services;

use Zomat\PhpGenetics\Contracts\FitnessServiceInterface;
use Zomat\PhpGenetics\Contracts\SelectionServiceInterface;
use Zomat\PhpGenetics\ValueObjects\GenomePair;
use Zomat\PhpGenetics\ValueObjects\Population;

final class SelectionService implements SelectionServiceInterface
{
    public function __construct(
        private FitnessServiceInterface $fitnessService
    ) {}

    public function getSelectionPair(Population $population) : GenomePair
    {
        $weights = array();
        
        foreach ($population->genomes as $num => $genome) {
            $weights[$num] = $this->fitnessService->getFitness($genome);
        }

        $index1 = $this->getBucketFromWeights($weights);

        do {
            $index2 = $this->getBucketFromWeights($weights);
        } while ($index1 != $index2);
       
        return new GenomePair(
            $population->genomes[$index1],
            $population->genomes[$index2]
        );
    }

    private function getBucketFromWeights(array $values) {
        $total = $currentTotal = $bucket = 0;
        $firstRand = mt_rand(1, 100);
    
        foreach ($values as $amount) {
            $total += $amount;
        }
    
        $rand = ($firstRand / 100) * $total;
    
        foreach ($values as $amount) {
            $currentTotal += $amount;
    
            if ($rand > $currentTotal) {
                $bucket++;
            }
            else {
                break;
            }
        }
    
        return $bucket;
    }
}