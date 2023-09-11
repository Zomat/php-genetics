<?php

declare(strict_types=1);

namespace Zomat\PhpGenetics\Services;

use Zomat\PhpGenetics\Contracts\EventBusInterface;
use Zomat\PhpGenetics\Contracts\FitnessServiceInterface;
use Zomat\PhpGenetics\Contracts\GenomeServiceInterface;
use Zomat\PhpGenetics\Contracts\PopulationServiceInterface;
use Zomat\PhpGenetics\ValueObjects\Population;

final class PopulationService implements PopulationServiceInterface
{
    public function __construct(
        private FitnessServiceInterface $fitnessService,
        private GenomeServiceInterface $genomeService,
        private EventBusInterface $eventBus
    )
    {}

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