<?php

declare(strict_types=1);

namespace Zomat\PhpGenetics;

use Zomat\PhpGenetics\Genome;

class GenomeService
{
    public function create(int $length) : Genome
    {
        $values = array();

        for ($i = 0; $i < $length; $i++) {
            $values[] = rand(0, 1);
        }

        return new Genome($values);
    }

    public function mutation(Genome $genome, int $num = 1, float $probability = 0.5) : Genome
    {
        for ($i = 0; $i < $num; $i++) {
            $index = rand(0, $genome->getLength() - 1);

            if ((random_int(0, PHP_INT_MAX) / PHP_INT_MAX) <= $probability) {
                $genome->setGene($index, abs($genome->getGene($index) - 1));
            }
        }

        return $genome;
    }

    public function singlePointCrossover(Genome $genome1, Genome $genome2) : GenomePair
    {
        if ($genome1->getLength() != $genome2->getLength()) {
            throw new \Exception("Genomes 1 and 2 must be of the same length");
        }

        $p = rand(1, $genome1->getLength() - 1);

        return new GenomePair(
            new Genome(array_merge(array_slice($genome1->get(), 0, $p), array_slice($genome2->get(), $p))),
            new Genome(array_merge(array_slice($genome2->get(), 0, $p), array_slice($genome1->get(), $p))),
        );
    }

    public function toItemNames(Genome $genome, array $items) : string
    {
        $names = array();

        foreach ($genome->get() as $num => $gene) {
            if ($gene == 1) {
                $names[] = $items[$num]->name;
            }
        }

        return implode(', ', $names);
    }
}
