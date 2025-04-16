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
 
 test('a subtask can be updated', function () {
     // Disable strict mode and foreign key checks to bypass project_id constraints
     DB::statement("SET sql_mode=''");
     DB::statement("SET FOREIGN_KEY_CHECKS=0");
 
     // Create an admin user and authenticate
     $admin = User::factory()->create([
         'first_name' => 'Admin',
         'role'       => 'admin',
     ]);
     $this->actingAs($admin);
 
     // Create a parent task (this represents the main task)
     $parentTask = Task::create([
         'task_name'        => 'Parent Task',
         'task_description' => 'Parent Task Description',
         'assigned_staff'   => 'Bob',  // Example staff for parent
         'assigned_date'    => Carbon::now()->toDateString(),
         'due_date'         => Carbon::now()->addDays(10)->toDateString(),
         'comment'          => 'Parent comment',
     ]);
 
     // Create a subtask with parent_id referencing the parent task
     $subtask = Task::create([
         'task_name'        => 'Subtask Old Name',
         'task_description' => 'Subtask Old Description',
         'assigned_staff'   => 'Alice',
         'assigned_date'    => Carbon::now()->subDay()->toDateString(),
         'due_date'         => Carbon::now()->addDays(5)->toDateString(),
         'comment'          => 'Initial subtask comment',
         'parent_id'        => $parentTask->id,
     ]);
 
     // Prepare updated data for the subtask, including a valid status field
     $updateData = [
         'task_name'        => 'Subtask Updated Name',
         'task_description' => 'Subtask Updated Description',
         'assigned_staff'   => 'Alice',
         'assigned_date'    => Carbon::now()->toDateString(),
         'due_date'         => Carbon::now()->addDays(7)->toDateString(),
         'comment'          => 'New subtask comment',
         'status'           => 'assigned', // Valid status required by validation
     ];
 
     // Create a new Request with the update data and set its method to PUT
     $request = new Request($updateData);
     $request->setMethod('PUT');
 
     // Instantiate the TaskController and call the update method directly for the subtask
     $controller = new TaskController();
     $response = $controller->update($request, $subtask);
 
     // Assert that a redirect response is returned
     $this->assertInstanceOf(\Illuminate\Http\RedirectResponse::class, $response);
 
     // Verify that the updated data is stored in the database for the subtask
     $this->assertDatabaseHas('tasks', [
         'id'               => $subtask->id,
         'task_name'        => 'Subtask Updated Name',
         'task_description' => 'Subtask Updated Description',
         'comment'          => 'New subtask comment',
         'parent_id'        => $parentTask->id,
         'status'           => 'assigned',
     ]);
 });
 
 test('a subtask can be deleted ', function () {
     // Disable strict mode and foreign key checks to bypass project_id constraints
     DB::statement("SET sql_mode=''");
     DB::statement("SET FOREIGN_KEY_CHECKS=0");
 
     // Create an admin user and authenticate
     $admin = User::factory()->create([
         'first_name' => 'Admin',
         'role'       => 'admin',
     ]);
     $this->actingAs($admin);
 
     // Create a parent task
     $parentTask = Task::create([
         'task_name'        => 'Parent Task For Subtask',
         'task_description' => 'Parent Description',
         'assigned_staff'   => 'Bob',
         'assigned_date'    => Carbon::now()->toDateString(),
         'due_date'         => Carbon::now()->addDays(10)->toDateString(),
         'comment'          => 'Parent comment',
     ]);
 
     // Create a subtask with parent_id set to the parent's id
     $subtaskName = 'Subtask To Delete';
     $subtask = Task::create([
         'task_name'        => $subtaskName,
         'task_description' => 'Subtask Description',
         'assigned_staff'   => 'Alice',
         'assigned_date'    => Carbon::now()->toDateString(),
         'due_date'         => Carbon::now()->addDays(5)->toDateString(),
         'parent_id'        => $parentTask->id,
     ]);
 
     // Store the subtask's ID before deletion
     $subtaskId = $subtask->id;
 
     // Instantiate the TaskController and call the destroy method directly for the subtask
     $controller = new TaskController();
     $response = $controller->destroy($subtask);
 
     // Assert that a redirect response is returned
     $this->assertInstanceOf(\Illuminate\Http\RedirectResponse::class, $response);
 
     // Verify that the subtask was completely removed from the database by checking Task::find returns null
     $this->assertNull(Task::find($subtaskId), "Subtask was not deleted from the database");
 });