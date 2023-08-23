<?php

declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';

use Zomat\PhpGenetics\Item;
use Zomat\PhpGenetics\GenomeService;
use Zomat\PhpGenetics\FitnessService;
use Zomat\PhpGenetics\Population;
use Zomat\PhpGenetics\PopulationService;
use Zomat\PhpGenetics\SelectionService;

$items = [
    new Item('Laptop', 500, 2200),
    new Item('Headphones', 150, 160),
    new Item('Mug', 150, 350),
    new Item('Notepad', 40, 333),
    new Item('Water Bottle', 40, 192),
    new Item('Mints', 5, 25),
    new Item('Socks', 10, 38),
    new Item('Tissues', 15, 80),
    new Item('Phone', 500, 200),
    new Item('Baseball Cap', 100, 70),
];

$generationLimit = 1000;
$fitnessLimit = null;

$genomeService = new GenomeService;
$fitnessService = new FitnessService(items: $items, weightLimit: 3000);
$populationService = new PopulationService($fitnessService);
$selectionService = new SelectionService($fitnessService);

/**
 * Run evolution
 */
$population = $populationService->generate(10, count($items));

$result = $population->genomes[0];

echo "Initial Population: \n";
echo $genomeService->toItemNames($result, $items);
echo " => Fitness: " . $fitnessService->getFitness($result) . PHP_EOL;

for ($i = 0; $i < $generationLimit; $i++) {
    $population = $populationService->sort($population);
    
    if (!is_null($fitnessLimit) && $fitnessService->getFitness($population->genomes[0]) >= $fitnessLimit) {
        break;
    }

    $nextGeneration = array();

    /** Elitarism */
    $nextGeneration[] = $population->genomes[0];
    $nextGeneration[] = $population->genomes[1];
    
    for ($j = 0; $j < (count($population->genomes) / 2) - 1; $j++) {
        $parents = $selectionService->getSelectionPair($population);

        $offspring = $genomeService->singlePointCrossover($parents->genomes[0], $parents->genomes[1]);
        
        $nextGeneration = array_merge($nextGeneration, [
            $genomeService->mutation($offspring[0], 1),
            $genomeService->mutation($offspring[1], 1),
        ]);
    }

    $population = new Population($nextGeneration);
}

$population = $populationService->sort($population);

/** Result => Genome with best fitness  */
$result = $population->genomes[0];

echo "Result population: " . PHP_EOL;

echo $genomeService->toItemNames($result, $items);
echo " => Fitness: " . $fitnessService->getFitness($result);

echo PHP_EOL;
