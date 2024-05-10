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
        Schema::create('child_treatment_programs', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('medical_record_id');
            $table->foreign('medical_record_id')->references('id')->on('medical_records')->onDelete('cascade');

            $table->unsignedBigInteger('employee_id');
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');

            $table->unsignedBigInteger('employee_choise_id');
            $table->foreign('employee_choise_id')->references('id')->on('employee_choises')->onDelete('cascade');

            $table->enum('program_type', ['tsfp', 'otp']);

            $table->string('acceptance_reason');
            $table->enum('acceptance_party' , ['another-TSFP','OTP','Re-acceptance','SC','Community']);
            $table->enum('acceptance_type', ['new', 'old']);


            $table->float('target_weight');
//حصبة
            $table->boolean('measles_vaccine_received')->nullable();
            $table->date('measles_vaccine_date')->format('Y-m-d')->nullable();

            $table->date('date')->format('Y-m-d');


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
        Schema::dropIfExists('child_treatment_programs');
    }
};
