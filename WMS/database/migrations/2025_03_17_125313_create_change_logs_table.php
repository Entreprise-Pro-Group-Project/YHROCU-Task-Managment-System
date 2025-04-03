<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('change_logs', function (Blueprint $table) {
            $table->id();
            $table->string('entity_type'); // 'project' or 'task'
            $table->unsignedBigInteger('entity_id'); // ID of the project or task
            $table->unsignedBigInteger('changed_by'); // The user who made the change
            $table->json('changes'); // Stores old and new values in JSON format
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('changed_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('change_logs');
    }
};