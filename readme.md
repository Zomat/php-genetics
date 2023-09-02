# PHP Genetics Algorithm for Knapsack Problem

This repository contains a PHP implementation of a genetic algorithm for solving the Knapsack Problem. The Knapsack Problem is a classic optimization problem where given a set of items with associated weights and values, the goal is to select a subset of items that maximizes the total value while staying within a given weight limit.

## Getting Started

These instructions will help you understand the code and set it up for your own use.

### Prerequisites

- PHP 8.1 or higher
- Composer for dependency management

### Installation

1. Clone the repository to your local machine:

```bash
git clone https://github.com/Zomat/php-genetics.git
```

2. Navigate to the repository directory:

```bash
cd php-genetics
```

3. Install the required dependencies using Composer:

```bash
composer install
```

### Usage

In the knapsack.php file, you will find an example configuration of the genetic algorithm. You can customize the algorithm parameters and the items placed in the knapsack by modifying the relevant values in the code. Here's an example configuration:

```php
$gaBuilder = new GeneticsAlgorithmBuilder;

$gaBuilder->setGenerationLimit(1000)
->setPopulationSize(10)
->setMutationLimit(1)
->setMutationProbability(0.5)
->setElitism(true)
->setWeightLimit(3000);

$gaBuilder->setItems(
    new Item('Laptop', 500, 2200),
    new Item('Headphones', 150, 160),
    // Add more items here...
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

```

```bash
php knapsack.php
```

## Contributing

Feel free to contribute by opening issues or submitting pull requests. Your contributions are highly appreciated!

## License

This project is licensed under the MIT License.