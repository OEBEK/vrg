<?php

namespace App\Categories;

use InvalidArgumentException;

class TaskCategoryFactory
{
    public static function create(string $type, array $properties = []): TaskCategory
    {
        switch ($type) {
            case 'WorkTask':
                if (!isset($properties['priority'])) {
                    throw new InvalidArgumentException('Priority is required for WorkTask.');
                }
                return new WorkTaskCategory($properties['priority']);
            case 'PersonalTask':
                if (!isset($properties['mood'])) {
                    throw new InvalidArgumentException('Mood is required for PersonalTask.');
                }
                return new PersonalTaskCategory($properties['mood']);
            case 'ShoppingTask':
                if (!isset($properties['estimated_cost'])) {
                    throw new InvalidArgumentException('Estimated cost is required for ShoppingTask.');
                }
                return new ShoppingTaskCategory($properties['estimated_cost']);
            default:
                throw new InvalidArgumentException("Unknown task category type: {$type}");
        }
    }

    public static function getAvailableCategories(): array
    {
        return [
            'WorkTask',
            'PersonalTask',
            'ShoppingTask',
        ];
    }
}