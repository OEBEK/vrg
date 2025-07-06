<?php

namespace App\Categories;

interface TaskCategory
{
    public function getType(): string;
    public function getProperties(): array;
}