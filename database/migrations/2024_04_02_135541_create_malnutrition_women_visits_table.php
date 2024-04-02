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
        Schema::create('malnutrition_women_visits', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('nutritionist_id');
            $table->foreign('nutritionist_id')->references('id')->on('employees')->onDelete('cascade');

            $table->unsignedBigInteger('programs_id');
            $table->foreign('programs_id')->references('id')->on('women_treatment_programs')->onDelete('cascade');


            $table->unsignedBigInteger('employee_info_id');
            $table->foreign('employee_info_id')->references('id')->on('employee_choises')->onDelete('cascade');

            $table->integer('muac');

            $table->text('note');

            $table->date('current_date')->format('Y-m-d');

            $table->date('next_visit_date')->format('Y-m-d');




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
        Schema::dropIfExists('malnutrition_women_visits');
    }
};
