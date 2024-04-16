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
        Schema::create('health_education_lectures', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            $table->integer('male_children_number');
            $table->integer('female_children_number');
            $table->integer('adult_men_number');
            $table->integer('adult_women_number');
            $table->integer('total');
            $table->boolean('is_beneficiaries');
            $table->string('beneficiary_type');
            $table->string('material_name');
            $table->string('program');
            $table->string('program_category');
            $table->boolean('has_special_needs')->default(false);
            $table->string('partner');
            $table->string('access');
            $table->string('agency');
            $table->string('activity');
            $table->string('office');
            $table->string('coverage');
            $table->date('date')->format('Y-m-d');

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
        Schema::dropIfExists('health_education_lectures');
    }
};
