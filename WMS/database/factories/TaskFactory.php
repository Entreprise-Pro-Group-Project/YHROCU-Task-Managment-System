<?php
 
namespace Database\Factories;
 
use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;
 
class TaskFactory extends Factory
{
    protected $model = Task::class;

    public function definition()
    {
        return [
            // Provide default values for Task columns
            'task_name'        => $this->faker->sentence,
            'task_description' => $this->faker->paragraph,
            'assigned_staff'   => $this->faker->name,
            'assigned_date'    => now()->toDateString(),
            'due_date'         => now()->addDays(3)->toDateString(),
            // etc...
        ];
    }
}