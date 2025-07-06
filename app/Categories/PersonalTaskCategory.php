<?php

namespace App\Categories;

class PersonalTaskCategory implements TaskCategory
{
    private $mood;

    public function __construct(string $mood)
    {
        $this->mood = $mood;
    }

    public function getType(): string
    {
        return 'PersonalTask';
    }

    public function getProperties(): array
    {
        return ['mood' => $this->mood];
    }
}