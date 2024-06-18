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
            $table->unsignedBigInteger('coverage_id');
            $table->foreign('coverage_id')->references('id')->on('coverages')->onDelete('cascade');
            $table->unsignedBigInteger('office_id');
            $table->foreign('office_id')->references('id')->on('offices')->onDelete('cascade');
            $table->unsignedBigInteger('activity_id')->nullable();
            $table->foreign('activity_id')->references('id')->on('activities')->onDelete('cascade');
            $table->unsignedBigInteger('agency_id');
            $table->foreign('agency_id')->references('id')->on('agencies')->onDelete('cascade');
            $table->unsignedBigInteger('access_id');
            $table->foreign('access_id')->references('id')->on('accesses')->onDelete('cascade');
            $table->unsignedBigInteger('partner_id');
            $table->foreign('partner_id')->references('id')->on('partners')->onDelete('cascade');
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
