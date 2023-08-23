<?php

declare(strict_types=1);

namespace Zomat\PhpGenetics;

use Zomat\PhpGenetics\GenomeService;

class PopulationService
{
    private GenomeService $genomeService;

    public function __construct(
        public FitnessService $fitnessService,
    )
    {
        $this->genomeService = new GenomeService();
    }

    public function generate(int $size, int $genomeLength) : Population
    {
        $population = array();

        for ($i = 0; $i < $size; $i++) {
            $population[] = $this->genomeService->create($genomeLength);
        }

        return new Population($population);
    }

    public function sort(Population $population) : Population
    {
        $genomes = $population->genomes;
        
        usort($genomes, function($a, $b) {
            $fitnessA = $this->fitnessService->getFitness($a);
            $fitnessB = $this->fitnessService->getFitness($b);
            return $fitnessB <=> $fitnessA;
        });

        return new Population($genomes);
    }
}