<?php

declare(strict_types=1);

namespace Zomat\PhpGenetics;

class GeneticsAlgorithm
{
    public function __construct(
        private GeneticsAlgorithmConfig $config,
        private array $items,
        private FitnessService $fitnessService,
        private GenomeService $genomeService,
        private PopulationService $populationService,
        private SelectionService $selectionService
    ) {}

    public function run(): Result
    {
        $population = $this->populationService->generate(
            size: $this->config->populationSize,
            genomeLength: count($this->items)
        );

        for ($i = 0; $i < $this->config->generationLimit; $i++) {
            $population = $this->populationService->sort($population);
            
            if (!is_null($this->config->fitnessLimit) &&
                $this->fitnessService->getFitness($population->genomes[0]) >= $this->config->fitnessLimit
            ) {
                break;
            }

            $nextGeneration = array();

            if ($this->config->elitarism) {
                $nextGeneration[] = $population->genomes[0];
                $nextGeneration[] = $population->genomes[1];
            }
            
            for ($j = 0; $j < (count($population->genomes) / 2) - (int) $this->config->elitarism; $j++) {
                $parents = $this->selectionService->getSelectionPair($population);

                $offspring = $this->genomeService->singlePointCrossover($parents->genome1, $parents->genome2);
                
                $nextGeneration = array_merge($nextGeneration, [
                    $this->genomeService->mutation($offspring->genome1, 1),
                    $this->genomeService->mutation($offspring->genome2, 1)
                ]);
            }

            $population = new Population($nextGeneration);
        }

        $population = $this->populationService->sort($population);

        $resultGenome = $population->genomes[0];

        return new Result(
            genome: $resultGenome,
            itemNames: $this->genomeService->toItemNames($resultGenome, $this->items),
            fitness: $this->fitnessService->getFitness($resultGenome)
        );
    }
}