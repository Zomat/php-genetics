# PHP Genetics Algorithm for Knapsack Problem

This repository contains a PHP implementation of a genetic algorithm for solving the Knapsack Problem. The Knapsack Problem is a classic optimization problem where given a set of items with associated weights and values, the goal is to select a subset of items that maximizes the total value while staying within a given weight limit.

## Getting Started

These instructions will help you understand the code and set it up for your own use.

### Prerequisites

- PHP 7.4 or higher
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

The provided PHP script (`knapsack.php`) demonstrates how to solve the Knapsack Problem using a genetic algorithm. Below is a breakdown of the script's main components and how to use them:

- `Item` class: Represents an item with a name, weight, and value.

- `GenomeService` class: Handles operations related to genomes, such as crossover and mutation.

- `FitnessService` class: Calculates the fitness of a genome based on the selected items' total value and weight.

- `Population` class: Represents a population of genomes.

- `PopulationService` class: Generates and manages populations.

- `SelectionService` class: Handles selection of parent genomes for crossover.

- The script starts by defining a set of items, each with a name, weight, and value.

- The genetic algorithm parameters (`$generationLimit` and `$fitnessLimit`) are set.

- The main loop runs for a specified number of generations (`$generationLimit`). In each iteration, the population is sorted based on fitness.

- Elitism is applied by selecting the top two genomes from the population to carry over to the next generation.

- For the remaining genomes, parent genomes are selected using a selection mechanism. Single-point crossover is applied to create offspring genomes, which are then mutated.

- The new population is constructed from the elite genomes, offspring, and mutations.

- The process continues until the specified generation limit is reached or a fitness limit is achieved.

- The final population is sorted, and the genome with the best fitness is selected as the result.

- The result, along with its fitness, is printed to the console.

To run the genetic algorithm, execute the script in your terminal:

```bash
php genetic_algorithm.php
```

## Contributing

Feel free to contribute by opening issues or submitting pull requests. Your contributions are highly appreciated!

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.