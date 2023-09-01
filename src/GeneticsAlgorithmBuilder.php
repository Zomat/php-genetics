<?php

declare(strict_types=1);

namespace Zomat\PhpGenetics;

class GeneticsAlgorithmBuilder
{
    private int $generationLimit;

    private ?int $fitnessLimit = null;

    private int $weightLimit;

    private int $populationSize;

    private bool $elitarism = false;

    private array $items;

    public function setGenerationLimit(int $limit): GeneticsAlgorithmBuilder
    {
        $this->generationLimit = $limit;
        return $this;
    }

    public function setFitnessLimit(?int $limit): GeneticsAlgorithmBuilder
    {
        $this->fitnessLimit = $limit;
        return $this;
    }

    public function setWeightLimit(int $limit): GeneticsAlgorithmBuilder
    {
        $this->weightLimit = $limit;
        return $this;
    }

    public function setPopulationSize(int $size): GeneticsAlgorithmBuilder
    {
        $this->populationSize = $size;
        return $this;
    }

    public function setElitarism(bool $value): GeneticsAlgorithmBuilder
    {
        $this->elitarism = $value;
        return $this;
    }

    public function setItems(Item ...$items): GeneticsAlgorithmBuilder
    {
        $this->items = $items;
        return $this;
    }

    public function build(): GeneticsAlgorithm
    {
        if (! isset($this->generationLimit) || $this->generationLimit === null) {
            throw new \InvalidArgumentException("Generation limit must be set.");
        }
    
        if (! isset($this->weightLimit) || $this->weightLimit === null) {
            throw new \InvalidArgumentException("Weight limit must be set.");
        }
    
        if (empty($this->items)) {
            throw new \InvalidArgumentException("Items must be set.");
        }

        $config = new GeneticsAlgorithmConfig(
            generationLimit: $this->generationLimit,
            fitnessLimit: $this->fitnessLimit,
            weightLimit: $this->weightLimit,
            populationSize: $this->populationSize,
            elitarism: $this->elitarism,
        );

        $fitnessService = new FitnessService($this->items, $config->weightLimit);

        return new GeneticsAlgorithm(
            config: $config,
            items: $this->items,
            fitnessService: $fitnessService,
            genomeService: new GenomeService,
            populationService: new PopulationService($fitnessService),
            selectionService: new SelectionService($fitnessService),
        );
    }
}