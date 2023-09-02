<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Zomat\PhpGenetics\GeneticsAlgorithmBuilder;
use Zomat\PhpGenetics\ValueObjects\Item;

class GeneticsAlgorithmBuilderTest extends TestCase
{
    public function testBuild()
    {
        // Create an instance of GeneticsAlgorithmBuilder
        $builder = new GeneticsAlgorithmBuilder();

        // Set values for the builder's properties
        $builder->setGenerationLimit(100)
            ->setWeightLimit(1000)
            ->setPopulationSize(50)
            ->setItems([new Item("Item A", 10, 20), new Item("Item B", 20, 30)]);

        // Build the GeneticsAlgorithm instance
        $algorithm = $builder->build();

        // Add assertions to verify the properties of the built algorithm
        $this->assertInstanceOf(\Zomat\PhpGenetics\GeneticsAlgorithm::class, $algorithm);
        // Add more assertions to check specific properties or configurations of the algorithm
    }

    public function testBuildWithMissingWeightLimit()
    {
        // Create an instance of GeneticsAlgorithmBuilder
        $builder = new GeneticsAlgorithmBuilder();

        // Set other required properties but omit weightLimit
        $builder->setGenerationLimit(100)
            ->setPopulationSize(50)
            ->setItems([new Item("Item A", 10, 20), new Item("Item B", 20, 30)]);

        // Attempt to build without setting weightLimit
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Weight limit must be set.");
        $algorithm = $builder->build();
    }

    public function testBuildWithMutationLimitGreaterThanItemCount()
    {
        // Create an instance of GeneticsAlgorithmBuilder
        $builder = new GeneticsAlgorithmBuilder();

        // Set required properties and a mutation limit higher than item count
        $builder->setGenerationLimit(100)
            ->setWeightLimit(1000)
            ->setPopulationSize(50)
            ->setMutationLimit(3) // Set a high mutation limit
            ->setItems([new Item("Item A", 10, 20), new Item("Item B", 20, 30)]);

        // Attempt to build with an invalid mutation limit
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Mutation limit can't be higher than item count.");
        $algorithm = $builder->build();
    }

    public function testBuildWithInvalidMutationProbability()
    {
        // Create an instance of GeneticsAlgorithmBuilder
        $builder = new GeneticsAlgorithmBuilder();

        // Set required properties and an invalid mutation probability
        $builder->setGenerationLimit(100)
            ->setWeightLimit(1000)
            ->setPopulationSize(50)
            ->setMutationProbability(1.5) // Set an invalid mutation probability
            ->setItems([new Item("Item A", 10, 20), new Item("Item B", 20, 30)]);

        // Attempt to build with an invalid mutation probability
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Mutation probability can't be higher than 1.");
        $algorithm = $builder->build();
    }

    public function testBuildWithEmptyItems()
    {
        // Create an instance of GeneticsAlgorithmBuilder
        $builder = new GeneticsAlgorithmBuilder();

        // Set required properties but omit items
        $builder->setGenerationLimit(100)
            ->setWeightLimit(1000)
            ->setPopulationSize(50);

        // Attempt to build with empty items
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Items must be set.");
        $algorithm = $builder->build();
    }
}
