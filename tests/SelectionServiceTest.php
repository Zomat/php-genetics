<?php 

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Zomat\PhpGenetics\Services\SelectionService;
use Zomat\PhpGenetics\Contracts\FitnessServiceInterface;
use Zomat\PhpGenetics\ValueObjects\Genome;
use Zomat\PhpGenetics\ValueObjects\GenomePair;
use Zomat\PhpGenetics\ValueObjects\Population;
use Zomat\PhpGenetics\EventBus;

class SelectionServiceTest extends TestCase
{
    public function testGetSelectionPair()
    {
        $fitnessServiceMock = $this->createMock(FitnessServiceInterface::class);

        $genome1 = new Genome([0, 1, 0]);
        $genome2 = new Genome([1, 0, 1]);
        $genome3 = new Genome([0, 0, 1]);
        $population = new Population([$genome1, $genome2, $genome3]);

        $fitnessServiceMock
            ->method('getFitness')
            ->willReturnMap([
                [$genome1, 3],
                [$genome2, 6],
                [$genome3, 4],
            ]);

        $selectionService = new SelectionService($fitnessServiceMock, new EventBus);

        $selectionPair = $selectionService->getSelectionPair($population);

        $this->assertInstanceOf(GenomePair::class, $selectionPair);
        $this->assertContains($selectionPair->genome1, $population->genomes);
        $this->assertContains($selectionPair->genome2, $population->genomes);
    }
}
