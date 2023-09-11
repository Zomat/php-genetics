<?php

declare(strict_types=1);

namespace Zomat\PhpGenetics\Contracts;

interface EventBusInterface
{
    public function add(array $event): void;
}