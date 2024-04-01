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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('medical_record_id');
            $table->foreign('medical_record_id')->references('id')->on('medical_records')->onDelete('cascade');

            // موظف الاستقبال
            $table->unsignedBigInteger('receptionist_id');
            $table->foreign('receptionist_id')->references('id')->on('employees')->onDelete('cascade');
            // 
            // للطبيب او الاخصائي
            $table->unsignedBigInteger('employee_id');
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            $table->enum('employee_type' , ['Doctor' , 'Nutritionist']);
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
        Schema::dropIfExists('appointments');
    }
};
