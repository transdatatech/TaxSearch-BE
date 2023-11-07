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
        Schema::create('property_files', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('batch_id');
            $table->unsignedBigInteger('status_id');
            $table->string('path');
            $table->timestamps();


            $table->foreign('batch_id')->references('id')->on('file_batches')
            ->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('status_id')->references('id')->on('file_status')
            ->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('property_files');
    }
};
