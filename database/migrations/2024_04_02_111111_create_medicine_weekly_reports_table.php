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
        Schema::create('medicine_weekly_reports', function (Blueprint $table) {
            $table->id();


            $table->unsignedBigInteger('employee_id');
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');


            $table->unsignedBigInteger('medical_center_id');
            $table->foreign('medical_center_id')->references('id')->on('medical_centers')->onDelete('cascade');

            $table->date('date')->format('Y-m-d');

            $table->json('file');

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
        Schema::dropIfExists('medicine_weekly_reports');
    }
};
