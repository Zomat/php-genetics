<?php 

declare(strict_types=1);

namespace Zomat\PhpGenetics\Contracts;

use Zomat\PhpGenetics\ValueObjects\Population;

interface PopulationServiceInterface
{
    public function generate(int $size, int $genomeLength): Population;

    public function sort(Population $population): Population;
}
