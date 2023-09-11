<?php

declare(strict_types=1);

namespace Zomat\PhpGenetics;
use Zomat\PhpGenetics\Contracts\EventBusInterface;

class EventBus implements EventBusInterface
{
    private array $events;

    public function add(array $event): void
    {
        $this->events[] = $event;
    }
}
