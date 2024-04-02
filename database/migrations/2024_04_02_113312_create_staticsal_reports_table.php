<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staticsal_reports', function (Blueprint $table) {
            $table->id();


            $table->unsignedBigInteger('statistician_id');
            $table->foreign('statistician_id')->references('id')->on('employees')->onDelete('cascade');

            $table->string('file_path');

            $table->string('file_type');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('staticsal_reports');
    }
};
