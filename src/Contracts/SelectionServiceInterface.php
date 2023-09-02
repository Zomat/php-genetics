<?php 

declare(strict_types=1);

namespace Zomat\PhpGenetics\Contracts;

use Zomat\PhpGenetics\ValueObjects\GenomePair;
use Zomat\PhpGenetics\ValueObjects\Population;

interface SelectionServiceInterface
{
    public function getSelectionPair(Population $population): GenomePair;
}