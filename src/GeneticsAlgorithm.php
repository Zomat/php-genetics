<?php

declare(strict_types=1);

namespace Zomat\PhpGenetics;
use Zomat\PhpGenetics\Contracts\EventBusInterface;
use Zomat\PhpGenetics\Contracts\FitnessServiceInterface;
use Zomat\PhpGenetics\Contracts\GenomeServiceInterface;
use Zomat\PhpGenetics\Contracts\PopulationServiceInterface;
use Zomat\PhpGenetics\Contracts\SelectionServiceInterface;
use Zomat\PhpGenetics\ValueObjects\GeneticsAlgorithmConfig;
use Zomat\PhpGenetics\ValueObjects\Genome;
use Zomat\PhpGenetics\ValueObjects\Population;
use Zomat\PhpGenetics\ValueObjects\Result;

class GeneticsAlgorithm
{
    public function __construct(
        private GeneticsAlgorithmConfig $config,
        private array $items,
        private FitnessServiceInterface $fitnessService,
        private GenomeServiceInterface $genomeService,
        private PopulationServiceInterface $populationService,
        private SelectionServiceInterface $selectionService,
        private ?EventBusInterface $eventBus
    ) {}

    public function run(): Result
    {
        $population = $this->populationService->generate(
            size: $this->config->populationSize,
            genomeLength: count($this->items)
        );

        $generation = 0;

        while ($generation < $this->config->generationLimit) {
            $this->eventBus->add(['Generation nr: ' . $generation]);

            $population = $this->populationService->sort($population);
            
            if ($this->fitnessLimitReached($population->genomes[0])) {
                $this->eventBus->add(['Population limit reached!']);
                break;
            }

            $this->eventBus->add(['Creating new generation']);
            $population = $this->createNextGeneration($population);
            $generation++;
        }

        $resultGenome = $this->populationService->sort($population)->genomes[0];

        return new Result(
            genome: $resultGenome,
            itemNames: $this->genomeService->toItemNames($resultGenome, $this->items),
            fitness: $this->fitnessService->getFitness($resultGenome),
            generation: $generation
        );
    }

    private function createNextGeneration(Population $population): Population
    {
        $nextGeneration = array();

        if ($this->config->elitarism) {
            $this->eventBus->add(['Elitarism']);
            $nextGeneration[] = $population->genomes[0];
            $nextGeneration[] = $population->genomes[1];
        }
        
        for ($j = 0; $j < (count($population->genomes) / 2) - (int) $this->config->elitarism; $j++) {
            $this->eventBus->add(['Getting selection pair']);
            $parents = $this->selectionService->getSelectionPair($population);
            
            $this->eventBus->add([
                'Parents',
                'parent1' => (string) $parents->genome1,
                'parent2' => (string) $parents->genome2,
            ]);
            
            $offspring = $this->genomeService->singlePointCrossover($parents->genome1, $parents->genome2);

            $this->eventBus->add([
                'Offspring',
                'offspring1' => (string) $parents->genome1,
                'offspring2' => (string) $parents->genome2,
            ]);
            
            $nextGeneration = array_merge($nextGeneration, [
                $this->genomeService->mutation($offspring->genome1),
                $this->genomeService->mutation($offspring->genome2)
            ]);
        }

        return new Population($nextGeneration);
    }

    private function fitnessLimitReached(Genome $genome): bool
    {
        return $this->config->fitnessLimit !== null &&
           $this->fitnessService->getFitness($genome) >= $this->config->fitnessLimit;
    }
}