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
        Schema::create('medical_records', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            $table->unsignedBigInteger('account_id')->nullable();
            $table->foreign('account_id')->references('id')->on('accounts');
            $table->enum('category', ['child', 'pregnant']);
            $table->string('name');
            $table->string('mother_name');
            $table->string('father_name');
            $table->string('last_name');
            $table->enum('gender', ['Male', 'Female']);
            $table->string('phone_number');
            $table->enum('residence_status' , ['Resident' , 'Immigrant' , 'Returnee']);
            $table->boolean('special_needs');
            $table->string('related_person')->default(false);
            $table->string('related_person_phone_number');
            $table->date('birth_date');
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
        Schema::dropIfExists('medical_records');
    }
};
