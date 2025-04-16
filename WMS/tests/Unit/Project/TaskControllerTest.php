<?php
 
 use App\Http\Controllers\TaskController;
 use App\Models\Task;
 use App\Models\User;
 use Carbon\Carbon;
 use Illuminate\Http\Request;
 use Illuminate\Support\Facades\DB;
 use Tests\TestCase;
 use Illuminate\Foundation\Testing\RefreshDatabase;
 
 uses(TestCase::class, RefreshDatabase::class);
 
 test('task update', function () {
     // Disable strict mode and foreign key checks to bypass project_id constraints
     DB::statement("SET sql_mode=''");
     DB::statement('SET FOREIGN_KEY_CHECKS=0');
 
     // Create an admin (or supervisor) user and authenticate
     $admin = User::factory()->create([
         'first_name' => 'Admin',
         'role'       => 'admin',
     ]);
     $this->actingAs($admin);
 
     // Create a task without specifying project_id
     $task = Task::create([
         'task_name'        => 'Old Task Name',
         'task_description' => 'Old Description',
         'assigned_staff'   => 'Alice',
         'assigned_date'    => Carbon::now()->subDay()->toDateString(),
         'due_date'         => Carbon::now()->addDays(3)->toDateString(),
         'comment'          => 'Initial comment',
     ]);
 
     // Prepare updated data, now including a valid 'status'
     $updateData = [
         'task_name'        => 'Updated Task Name',
         'task_description' => 'Updated Description',
         'assigned_staff'   => 'Alice',
         'assigned_date'    => Carbon::now()->toDateString(),
         'due_date'         => Carbon::now()->addDays(5)->toDateString(),
         'comment'          => 'New optional comment',
         'status'           => 'assigned', // <--- Valid status
     ];
 
     // Create a new Request with the update data and set its method to PUT
     $request = new Request($updateData);
     $request->setMethod('PUT');
 
     // Instantiate the TaskController and call the update method directly
     $controller = new TaskController();
     $response   = $controller->update($request, $task);
 
     // Assert that a redirect response is returned
     $this->assertInstanceOf(\Illuminate\Http\RedirectResponse::class, $response);
 
     // Verify the updated data is stored in the database
     $this->assertDatabaseHas('tasks', [
         'id'               => $task->id,
         'task_name'        => 'Updated Task Name',
         'task_description' => 'Updated Description',
         'comment'          => 'New optional comment',
         'status'           => 'assigned',
     ]);
 });
 
 test('task deleted', function () {
     // Disable strict mode and foreign key checks to bypass project_id constraints
     DB::statement("SET sql_mode=''");
     DB::statement('SET FOREIGN_KEY_CHECKS=0');
 
     // Create an admin (or supervisor) user and authenticate
     $admin = User::factory()->create([
         'first_name' => 'Admin',
         'role'       => 'admin',
     ]);
     $this->actingAs($admin);
 
     // Create a task without specifying project_id; store task name for assertion
     $taskName = 'Task to Delete';
     $task = Task::create([
         'task_name'        => $taskName,
         'task_description' => 'Some Description',
         'assigned_staff'   => 'Alice',
         'assigned_date'    => Carbon::now()->toDateString(),
         'due_date'         => Carbon::now()->addDays(7)->toDateString(),
     ]);
 
     // Store the task's ID before deletion
     $taskId = $task->id;
 
     // Instantiate the TaskController and call the destroy method directly
     $controller = new TaskController();
     $response   = $controller->destroy($task);
 
     // Assert that a redirect response is returned
     $this->assertInstanceOf(\Illuminate\Http\RedirectResponse::class, $response);
 
     // Verify that the task was completely removed from the database
     $this->assertNull(Task::find($taskId), "Task was not deleted from the database");
 });