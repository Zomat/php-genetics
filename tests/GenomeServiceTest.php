<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Zomat\PhpGenetics\Services\GenomeService;
use Zomat\PhpGenetics\ValueObjects\Genome;
use Zomat\PhpGenetics\ValueObjects\GenomePair;
use Zomat\PhpGenetics\ValueObjects\Item;

class GenomeServiceTest extends TestCase
{
    public function testCreateGenomeWithRandomValues()
    {
        $genomeService = new GenomeService();
        $genome = $genomeService->create(5);

        $this->assertInstanceOf(Genome::class, $genome);
        $this->assertCount(5, $genome->get());
    }

    public function testMutation()
    {
        $genomeService = new GenomeService();
        $genome = new Genome([0, 1, 0, 1, 0]);

        $genomeService->setMutationLimit(2)->setMutationProbability(0.5);
        $mutatedGenome = $genomeService->mutation($genome);

        $this->assertInstanceOf(Genome::class, $mutatedGenome);
    }

    public function testSinglePointCrossover()
    {
        $genomeService = new GenomeService();
        $genome1 = new Genome([0, 1, 0, 1, 0]);
        $genome2 = new Genome([1, 0, 1, 0, 1]);

        $genomePair = $genomeService->singlePointCrossover($genome1, $genome2);

        $this->assertInstanceOf(GenomePair::class, $genomePair);
    }

    public function testToItemNames()
    {
        $genomeService = new GenomeService();
        $genome = new Genome([1, 0, 1]);

        $items = [
            new Item("Item 1", 2, 3),
            new Item("Item 2", 3, 2),
            new Item("Item 3", 4, 1),
        ];

        $itemNames = $genomeService->toItemNames($genome, $items);

        $this->assertEquals("Item 1, Item 3", $itemNames);
    }
}
