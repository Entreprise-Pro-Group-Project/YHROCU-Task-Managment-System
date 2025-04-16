<?php
 
namespace Database\Factories;
 
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;
 
class ProjectFactory extends Factory
{
    protected $model = Project::class;
 
    public function definition()
    {
        return [
            // Provide default values for Project attributes
            'project_name'        => $this->faker->sentence(3),
            'project_description' => $this->faker->paragraph,
            'project_date'        => now()->toDateString(),
            'due_date'            => now()->addDays(7)->toDateString(),
            'supervisor_name'     => 'John',
            // etc...
        ];
    }
}