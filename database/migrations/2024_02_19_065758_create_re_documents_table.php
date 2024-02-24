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
        Schema::create('re_documents', function (Blueprint $table) {
            $table->id('doc_id');
            $table->string('doc_type');
            $table->string('doc_lang');
            $table->string('doc_pick');
            $table->integer('doc_amount');
            $table->integer('doc_Y');
            $table->string('doc_M');
            $table->longText('doc_desc');  

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
        Schema::dropIfExists('re_documents');
    }
};
