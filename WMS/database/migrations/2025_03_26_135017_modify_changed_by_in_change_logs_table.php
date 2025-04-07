<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('change_logs', function (Blueprint $table) {
            $table->unsignedBigInteger('changed_by')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('change_logs', function (Blueprint $table) {
            $table->unsignedBigInteger('changed_by')->nullable(false)->change();
        });
    }
};
