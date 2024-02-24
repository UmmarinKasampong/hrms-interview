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
        Schema::create('offsite_works', function (Blueprint $table) {
            $table->id('offsite_id');
            $table->text('offsite_place');
            $table->longText('offsite_direc');
            $table->longText('offsite_desc');
            $table->text('offsite_start');
            $table->text('offsite_end');

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
        Schema::dropIfExists('offsite_works');
    }
};
