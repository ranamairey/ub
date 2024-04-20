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
        Schema::create('routine_child_visits', function (Blueprint $table) {
            $table->id();


            $table->unsignedBigInteger('employee_id');
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');

            $table->unsignedBigInteger('employee_choise_id');
            $table->foreign('employee_choise_id')->references('id')->on('employee_choises')->onDelete('cascade');

            $table->unsignedBigInteger('medical_record_id');
            $table->foreign('medical_record_id')->references('id')->on('medical_records')->onDelete('cascade');

            $table->enum('current_status', ['sam', 'mam','normal']);

            $table->boolean('health_education');

            $table->boolean('sam_acceptance');

            $table->boolean('nutritional_survey');

            $table->boolean('micronutrients');

            $table->boolean('fat_intake');

            $table->boolean('high_energy_biscuits');

            $table->float('z_score');

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
        Schema::dropIfExists('routine_child_visits');
    }
};
