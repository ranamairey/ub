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
        Schema::create('women_treatment_programs', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('medical_record_id');
            $table->foreign('medical_record_id')->references('id')->on('medical_records')->onDelete('cascade');

            $table->unsignedBigInteger('employee_id');
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');


            $table->unsignedBigInteger('employee_choise_id');
            $table->foreign('employee_choise_id')->references('id')->on('employee_choises')->onDelete('cascade');


            $table->enum('acceptance_type', ['new', 'old']);

            $table->string('acceptance_reason');

            $table->float('target_weight');

            $table->date('date')->format('Y-m-d');


            $table->date('tetanus_date')->format('Y-m-d')->nullable();
            $table->date('vitamin_a_date')->format('Y-m-d')->nullable();

            $table->date('end_date')->format('Y-m-d')->nullable();
            $table->string('end_cause')->nullable();

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
        Schema::dropIfExists('women_treatment_programs');
    }
};
