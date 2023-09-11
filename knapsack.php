<?php

declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';

use Zomat\PhpGenetics\ValueObjects\Item;
use Zomat\PhpGenetics\GeneticsAlgorithmBuilder;
use Zomat\PhpGenetics\EventBus;

$eventBus = new EventBus;

$gaBuilder = new GeneticsAlgorithmBuilder($eventBus);

$gaBuilder->setGenerationLimit(1000)
->setPopulationSize(10)
->setMutationLimit(1)
->setMutationProbability(0.5)
->setElitarism(true)
->setWeightLimit(3000)
->setElitarism(true);

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

$gaBuilder->setItems(
    $items
);

try {
    $ga = $gaBuilder->build();
} catch (\Exception $e) {
    exit("Can't build algorithm: {$e->getMessage()}" . PHP_EOL);
}

$result = $ga->run();

echo "Result population: " . PHP_EOL;
echo $result->itemNames . PHP_EOL;
echo " => Fitness: " . $result->fitness . PHP_EOL;
echo " => Generation: " . $result->generation . PHP_EOL;

echo "<pre>";
print_r($eventBus);
echo "</pre>";
