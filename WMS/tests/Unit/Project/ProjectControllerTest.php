<?php

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Notification;
use App\Notifications\ProjectCreated;
use App\Notifications\ProjectUpdated;
use App\Notifications\ProjectDeleted;
use App\Notifications\TaskAssigned;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

// Tell Pest to use our base TestCase and refresh the DB for each test
uses(TestCase::class, RefreshDatabase::class);

// 1. Creating a project with tasks
test('a project can be created with tasks', function () {
    Notification::fake();

    // Create a supervisor user (the one who should be notified).
    $supervisor = User::factory()->create([
        'first_name' => 'John',
        'role'       => 'supervisor',
    ]);

    // Create a staff user for task assignment.
    $staff = User::factory()->create([
        'first_name' => 'Alice',
        'role'       => 'staff',
    ]);

    // Create an admin user to perform the operation.
    $admin = User::factory()->create([
        'first_name' => 'Admin',
        'role'       => 'admin',
    ]);

    // Simulate a logged-in admin
    $this->actingAs($admin);
    
    // Disable all middleware for testing
    $this->withoutMiddleware();

    // Prepare form data
    $projectData = [
        'project_name'        => 'Test Project',
        'project_description' => 'Test Description',
        'project_date'        => Carbon::now()->toDateString(),
        'due_date'            => Carbon::now()->addDays(7)->toDateString(),
        'supervisor_name'     => 'John',
        'tasks'               => json_encode([
            [
                'task_name'        => 'Task 1',
                'task_description' => 'Task description 1',
                'assigned_staff'   => 'Alice',
                'assigned_date'    => Carbon::now()->toDateString(),
                'due_date'         => Carbon::now()->addDays(3)->toDateString(),
            ]
        ]),
    ];

    // Post to the store route
    $response = $this->post(route('projects.store'), $projectData);
    
    // For debugging - dump the response content in case of failure
    if ($response->status() !== 302) {
        dump($response->getContent());
    }
    
    // Expect redirect to admin dashboard
    $response->assertRedirect(route('admin.dashboard'));

    // Assert that the project and task exist in the database
    $this->assertDatabaseHas('projects', [
        'project_name' => 'Test Project'
    ]);
    $this->assertDatabaseHas('tasks', [
        'task_name' => 'Task 1'
    ]);

    // Assert that the supervisor received a ProjectCreated notification
    Notification::assertSentTo(
        [$supervisor],
        function (ProjectCreated $notification) use ($projectData) {
            return $notification->project->project_name === $projectData['project_name'];
        }
    );

    // Assert that the staff member received a TaskAssigned notification
    Notification::assertSentTo(
        [$staff],
        function (TaskAssigned $notification) {
            return true; // If it gets here, the notification was sent
        }
    );
});

// 2. Updating a project
test('a project can be updated', function () {
    Notification::fake();

    // Create users
    $supervisor = User::factory()->create([
        'first_name' => 'John',
        'role'       => 'supervisor',
    ]);
    $admin = User::factory()->create([
        'first_name' => 'Admin',
        'role'       => 'admin',
    ]);

    // Authenticate as admin BEFORE creating the project
    $this->actingAs($admin);
    
    // Create a project directly instead of using factory
    $project = new Project([
        'project_name'        => 'Old Project Name',
        'project_description' => 'Old Description',
        'project_date'        => Carbon::now()->toDateString(),
        'due_date'            => Carbon::now()->addDays(7)->toDateString(),
        'supervisor_name'     => 'John',
    ]);
    $project->save();

    // Data for updating the project
    $updateData = [
        'project_name'        => 'Updated Project Name',
        'project_description' => 'Updated Description',
        'project_date'        => Carbon::now()->toDateString(),
        'due_date'            => Carbon::now()->addDays(10)->toDateString(),
        'supervisor_name'     => 'John',
    ];

    // Create a request and controller directly
    $request = new \Illuminate\Http\Request($updateData);
    $request->setMethod('PUT');
    $request->request->add($updateData);
    
    $controller = new \App\Http\Controllers\ProjectController();
    $controller->update($request, $project);
    
    // Refresh the project from the database
    $project->refresh();
    
    // Perform assertions on the project
    $this->assertEquals('Updated Project Name', $project->project_name);
    $this->assertEquals('Updated Description', $project->project_description);

    // Verify supervisor received a ProjectUpdated notification
    Notification::assertSentTo(
        [$supervisor],
        ProjectUpdated::class
    );
});

// 3. Deleting a project
test('a project can be deleted', function () {
    Notification::fake();

    // Create users
    $supervisor = User::factory()->create([
        'first_name' => 'John',
        'role'       => 'supervisor',
    ]);
    $admin = User::factory()->create([
        'first_name' => 'Admin',
        'role'       => 'admin',
    ]);

    // Authenticate as admin BEFORE creating the project
    $this->actingAs($admin);
    
    // Create a project directly instead of using factory
    $project = new Project([
        'project_name'        => 'Project to Delete',
        'project_description' => 'Some Description',
        'project_date'        => Carbon::now()->toDateString(),
        'due_date'            => Carbon::now()->addDays(7)->toDateString(),
        'supervisor_name'     => 'John',
    ]);
    $project->save();
    
    // Store the ID before deletion
    $projectId = $project->id;
    
    // Create controller and call method directly
    $controller = new \App\Http\Controllers\ProjectController();
    $controller->destroy($project);
    
    // Check that the project no longer exists
    $this->assertNull(Project::find($projectId), "Project was not deleted from the database");

    // Verify supervisor received a ProjectDeleted notification
    Notification::assertSentTo(
        [$supervisor],
        ProjectDeleted::class
    );
});
