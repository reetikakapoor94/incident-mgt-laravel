<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('incidents', function (Blueprint $table) {
        $table->id();
        $table->string('incident_id')->unique(); // RMGxxxxxYYYY format
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->enum('enterprise_type', ['Enterprise','Government'])->default('Enterprise');
        $table->string('reporter_name');
        $table->text('details');
        $table->timestamp('reported_at')->nullable();
        $table->enum('priority', ['High','Medium','Low'])->default('Low');
        $table->enum('status', ['Open','In progress','Closed'])->default('Open');
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incidents');
    }
};
