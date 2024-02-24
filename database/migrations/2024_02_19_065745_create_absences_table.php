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
        Schema::create('absences', function (Blueprint $table) {
            $table->id('absence_id');
            $table->string('absence_type');
            $table->longText('absence_desc');
            $table->text('absence_path')->nullable();
            $table->timestamp('absence_start')->nullable();
            $table->timestamp('absence_end')->nullable();

            $table->string('status_form');
            $table->longText('reject_desc');      
            $table->integer('to_manager');
            $table->integer('create_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absences');
    }
};
