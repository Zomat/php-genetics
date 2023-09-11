<?php

declare(strict_types=1);

namespace Zomat\PhpGenetics;
use Zomat\PhpGenetics\Contracts\EventBusInterface;
use Zomat\PhpGenetics\Contracts\EventStackInterface;
use Zomat\PhpGenetics\Services\FitnessService;
use Zomat\PhpGenetics\Services\GenomeService;
use Zomat\PhpGenetics\Services\PopulationService;
use Zomat\PhpGenetics\Services\SelectionService;
use Zomat\PhpGenetics\ValueObjects\GeneticsAlgorithmConfig;

class GeneticsAlgorithmBuilder
{
    private int $generationLimit;

    private ?int $fitnessLimit = null;

    private ?int $mutationLimit = 1;

    private ?float $mutationProbability = 0.5;

    private int $weightLimit;

    private int $populationSize;

    private bool $elitarism = false;

    private array $items;

    public function __construct(
        private EventBusInterface $eventBus
    ) {}

    public function setGenerationLimit(int $limit): GeneticsAlgorithmBuilder
    {
        $this->generationLimit = abs($limit);
        return $this;
    }

    public function setFitnessLimit(int $limit): GeneticsAlgorithmBuilder
    {
        $this->fitnessLimit = abs($limit);
        return $this;
    }

    public function setWeightLimit(int $limit): GeneticsAlgorithmBuilder
    {
        $this->weightLimit = abs($limit);
        return $this;
    }

    public function setMutationLimit(int $limit): GeneticsAlgorithmBuilder
    {
        $this->mutationLimit = abs($limit);
        return $this;
    }

    public function setMutationProbability(float $value): GeneticsAlgorithmBuilder
    {
        $this->mutationProbability = abs($value);
        return $this;
    }

    public function setPopulationSize(int $size): GeneticsAlgorithmBuilder
    {
        $this->populationSize = abs($size);
        return $this;
    }

    public function setElitarism(bool $value): GeneticsAlgorithmBuilder
    {
        $this->elitarism = $value;
        return $this;
    }

    public function setItems(array $items): GeneticsAlgorithmBuilder
    {
        $this->items = $items;
        return $this;
    }

    public function build(): GeneticsAlgorithm
    {
        if (empty($this->items)) {
            throw new \InvalidArgumentException("Items must be set.");
        }

        if (! isset($this->generationLimit) || $this->generationLimit === null) {
            throw new \InvalidArgumentException("Generation limit must be set.");
        }
    
        if (! isset($this->weightLimit) || $this->weightLimit === null) {
            throw new \InvalidArgumentException("Weight limit must be set.");
        }

        if (isset($this->mutationLimit) && $this->mutationLimit > count($this->items)) {
            throw new \InvalidArgumentException("Mutation limit can't be higher than item count.");
        }

        if (isset($this->mutationProbability) && $this->mutationProbability > 1) {
            throw new \InvalidArgumentException("Mutation probability can't be higher than 1.");
        }

        $config = new GeneticsAlgorithmConfig(
            generationLimit: $this->generationLimit,
            fitnessLimit: $this->fitnessLimit,
            weightLimit: $this->weightLimit,
            populationSize: $this->populationSize,
            elitarism: $this->elitarism,
        );

        $fitnessService = (new FitnessService($this->eventBus))
            ->setItems($this->items)
            ->setWeightLimit($this->weightLimit);

        $genomeService = (new GenomeService($this->eventBus))
            ->setMutationLimit($this->mutationLimit)
            ->setMutationProbability($this->mutationProbability);

        return new GeneticsAlgorithm(
            config: $config,
            items: $this->items,
            fitnessService: $fitnessService,
            genomeService: $genomeService,
            populationService: new PopulationService($fitnessService, $genomeService, $this->eventBus),
            selectionService: new SelectionService($fitnessService, $this->eventBus),
            eventBus: $this->eventBus
        );
    }
}