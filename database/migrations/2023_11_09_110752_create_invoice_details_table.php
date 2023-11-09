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
        Schema::create('invoice_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('property_file_data_id');
            $table->unsignedBigInteger('invoice_id');
            $table->mediumText('data');
            $table->float('price');
            $table->timestamps();

            $table->foreign('property_file_data_id')->references('id')->on('property_file_data')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('invoice_id')->references('id')->on('invoices')
                ->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_details');
    }
};
