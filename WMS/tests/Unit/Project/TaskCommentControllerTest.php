<?php
 
 use App\Http\Controllers\TaskCommentController;
 use App\Models\Task;
 use App\Models\TaskComment;
 use App\Models\User;
 use Carbon\Carbon;
 use Illuminate\Http\Request;
 use Illuminate\Support\Facades\DB;
 use Tests\TestCase;
 use Illuminate\Foundation\Testing\RefreshDatabase;
 
 uses(TestCase::class, RefreshDatabase::class);
 
 test('a comment can be added to a task', function () {
     // Disable strict mode and foreign key checks to bypass missing project_id constraints
     DB::statement("SET sql_mode=''");
     DB::statement('SET FOREIGN_KEY_CHECKS=0');
 
     // Create a user and authenticate
     $user = User::factory()->create([
         'first_name' => 'Alice',
         'email'      => 'alice@example.com',
     ]);
     $this->actingAs($user);
 
     // Create a task for the comment without specifying project_id
     $task = Task::create([
         'task_name'        => 'Task For Comment',
         'assigned_staff'   => 'Alice',  // This should match the lookup in your controller if any
         'task_description' => 'Task description here',
         'assigned_date'    => Carbon::now()->toDateString(),
         'due_date'         => Carbon::now()->addDays(3)->toDateString(),
         // 'project_id' is intentionally omitted
     ]);
 
     // Prepare valid comment data
     $commentData = [
         'comment' => 'This is a test comment.',
     ];
 
     // Create a new Request with the comment data
     $request = new Request($commentData);
 
     // Instantiate the TaskCommentController and call the store method directly
     $controller = new TaskCommentController();
     $response   = $controller->store($request, $task);
 
     // Assert that a redirect response is returned, redirecting to tasks.show route
     $this->assertInstanceOf(\Illuminate\Http\RedirectResponse::class, $response);
     $this->assertEquals(route('tasks.show', $task->id), $response->getTargetUrl());
 
     // Verify that the comment is stored in the database
     $this->assertDatabaseHas('task_comments', [
         'task_id' => $task->id,
         'user_id' => $user->id,
         'comment' => 'This is a test comment.',
     ]);
 });