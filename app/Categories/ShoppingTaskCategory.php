<?php

namespace App\Categories;

class ShoppingTaskCategory implements TaskCategory
{
    private $estimatedCost;

    public function __construct(float $estimatedCost)
    {
        $this->estimatedCost = $estimatedCost;
    }

    public function getType(): string
    {
        return 'ShoppingTask';
    }

    public function getProperties(): array
    {
        return ['estimated_cost' => $this->estimatedCost];
    }
}