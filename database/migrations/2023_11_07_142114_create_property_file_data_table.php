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
        Schema::create('property_file_data', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('file_id');
            $table->unsignedBigInteger('batch_id');
            $table->unsignedBigInteger('state_id')->nullable();
            $table->string('property_id')->nullable();
            $table->string('area')->nullable();
            $table->mediumText('address')->nullable();
            $table->string('zip_code')->nullable();
            $table->string('country')->nullable();
            $table->unsignedBigInteger('status_id')->nullable();

            $table->timestamps();


            $table->foreign('file_id')->references('id')->on('property_files')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('batch_id')->references('id')->on('file_batches')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('state_id')->references('id')->on('states')
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
        Schema::dropIfExists('property_file_data');
    }
};
