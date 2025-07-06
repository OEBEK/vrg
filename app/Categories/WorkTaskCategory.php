<?php

namespace App\Categories;

class WorkTaskCategory implements TaskCategory
{
    private $priority;

    public function __construct(string $priority)
    {
        $this->priority = $priority;
    }

    public function getType(): string
    {
        return 'WorkTask';
    }

    public function getProperties(): array
    {
        return ['priority' => $this->priority];
    }
}