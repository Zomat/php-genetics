<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Zomat\PhpGenetics\Services\PopulationService;
use Zomat\PhpGenetics\ValueObjects\Population;
use Zomat\PhpGenetics\ValueObjects\Genome;
use Zomat\PhpGenetics\Contracts\FitnessServiceInterface;
use Zomat\PhpGenetics\Contracts\GenomeServiceInterface;
use Zomat\PhpGenetics\ValueObjects\Item;
use Zomat\PhpGenetics\EventBus;

class PopulationServiceTest extends TestCase
{
    public function testGeneratePopulation()
    {
        $fitnessServiceMock = $this->createMock(FitnessServiceInterface::class);
        $genomeServiceMock = $this->createMock(GenomeServiceInterface::class);

        $genomeServiceMock->expects($this->exactly(5))
            ->method('create')
            ->with(5)
            ->willReturn(new Genome([0, 1, 0, 1, 0]));

        $populationService = new PopulationService($fitnessServiceMock, $genomeServiceMock, new EventBus);

        $population = $populationService->generate(5, 5);

        $this->assertInstanceOf(Population::class, $population);
        $this->assertCount(5, $population->genomes);
    }

    public function testSortPopulation()
    {
        $fitnessServiceMock = $this->createMock(FitnessServiceInterface::class);
        $genomeServiceMock = $this->createMock(GenomeServiceInterface::class);

        $items = [
            new Item("Item 1", 2, 5),
            new Item("Item 2", 3, 6),
            new Item("Item 3", 4, 1),
        ];
        
        $fitnessServiceMock->setItems($items);
        $fitnessServiceMock->setWeightLimit(10);

        $genome1 = new Genome([0, 1, 0]);
        $genome2 = new Genome([1, 0, 1]);
        $genome3 = new Genome([0, 0, 1]);

        $fitnessMap = [
            [$genome1, 3],
            [$genome2, 6],
            [$genome3, 4],
        ];
    
        $fitnessServiceMock->expects($this->exactly(6))
            ->method('getFitness')
            ->will($this->returnValueMap($fitnessMap));

        $population = new Population([$genome1, $genome2, $genome3]);

        $populationService = new PopulationService($fitnessServiceMock, $genomeServiceMock, new EventBus);

        $sortedPopulation = $populationService->sort($population);

        $this->assertInstanceOf(Population::class, $sortedPopulation);
        $this->assertCount(3, $sortedPopulation->genomes);

        $this->assertEquals([1, 0, 1], $sortedPopulation->genomes[0]->get());
        $this->assertEquals([0, 0, 1], $sortedPopulation->genomes[1]->get());
        $this->assertEquals([0, 1, 0], $sortedPopulation->genomes[2]->get());
    }
}
