<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Zomat\PhpGenetics\Services\FitnessService;
use Zomat\PhpGenetics\ValueObjects\Genome;
use Zomat\PhpGenetics\ValueObjects\Item;
use Zomat\PhpGenetics\EventBus;

class FitnessServiceTest extends TestCase
{
    public function testGetFitnessWithValidData()
    {
        $items = [
            new Item("item1", 1, 2),
            new Item("item2", 2, 3),
            new Item("item3", 3, 4),
        ];

        $fitnessService = new FitnessService(new EventBus);
        $fitnessService->setItems($items)->setWeightLimit(5);

        $genome = new Genome([1, 1, 0]);

        $fitness = $fitnessService->getFitness($genome);

        $this->assertEquals(3, $fitness);

        $genome = new Genome([1, 1, 1]);

        $fitness = $fitnessService->getFitness($genome);

        $this->assertEquals(0, $fitness);
    }

    public function testGetFitnessWithInvalidData()
    {
        $items = [];

        $fitnessService = new FitnessService(new EventBus);
        $fitnessService->setItems($items)->setWeightLimit(0);

        $genome = new Genome([1, 0, 1]);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Items and weight limit must be set before calling getFitness");
        $fitnessService->getFitness($genome);
    }

    public function testGetFitnessWithMismatchedGenome()
    {
        $items = [
            new Item("item1", 1, 2),
            new Item("item2", 2, 3),
            new Item("item3", 3, 4),
        ];

        $fitnessService = new FitnessService(new EventBus);
        $fitnessService->setItems($items)->setWeightLimit(5);

        $genome = new Genome([1, 0]);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Genome and items must be of the same length");
        $fitnessService->getFitness($genome);
    }
}
