<?php

namespace Tests\Unit;

use App\Models\Todo;
use App\Categories\WorkTaskCategory;
use App\Categories\PersonalTaskCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TodoModelTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_returns_null_if_category_type_is_not_set()
    {
        $todo = Todo::factory()->create(['category_type' => null]);
        $this->assertNull($todo->category);
    }

    /** @test */
    public function it_creates_work_task_category_from_array_extra_data()
    {
        $todo = Todo::factory()->create([
            'category_type' => 'WorkTask',
            'extra_data' => ['priority' => 'high']
        ]);
        $this->assertInstanceOf(WorkTaskCategory::class, $todo->category);
        $this->assertEquals('high', $todo->category->getProperties()['priority']);
    }

    /** @test */
    public function it_creates_work_task_category_from_json_string_extra_data()
    {
        $todo = Todo::factory()->create([
            'category_type' => 'WorkTask',
            'extra_data' => json_encode(['priority' => 'medium'])
        ]);
        $this->assertInstanceOf(WorkTaskCategory::class, $todo->category);
        $this->assertEquals('medium', $todo->category->getProperties()['priority']);
    }

    /** @test */
    public function it_creates_personal_task_category_from_array_extra_data()
    {
        $todo = Todo::factory()->create([
            'category_type' => 'PersonalTask',
            'extra_data' => ['mood' => 'happy']
        ]);
        $this->assertInstanceOf(PersonalTaskCategory::class, $todo->category);
        $this->assertEquals('happy', $todo->category->getProperties()['mood']);
    }

    /** @test */
    public function it_creates_personal_task_category_from_json_string_extra_data()
    {
        $todo = Todo::factory()->create([
            'category_type' => 'PersonalTask',
            'extra_data' => json_encode(['mood' => 'sad'])
        ]);
        $this->assertInstanceOf(PersonalTaskCategory::class, $todo->category);
        $this->assertEquals('sad', $todo->category->getProperties()['mood']);
    }

    /** @test */
    public function it_handles_invalid_argument_exception_gracefully()
    {
        $todo = Todo::factory()->create([
            'category_type' => 'WorkTask',
            'extra_data' => ['invalid_key' => 'value'] // Missing 'priority'
        ]);
        $this->assertNull($todo->category);
    }
}