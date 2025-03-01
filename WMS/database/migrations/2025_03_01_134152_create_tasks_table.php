<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('task_name');
            $table->unsignedBigInteger('project_id'); // Link to the project
            $table->unsignedBigInteger('parent_id')->nullable(); // Link to parent task (for sub-tasks)
            $table->string('assigned_staff');
            $table->date('due_date');
            $table->enum('status', ['assigned', 'in progress', 'completed', 'overdue']);
            $table->text('comment')->nullable();
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
            $table->foreign('parent_id')->references('id')->on('tasks')->onDelete('cascade'); // Self-referencing
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};