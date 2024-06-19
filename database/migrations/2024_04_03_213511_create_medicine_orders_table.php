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
        Schema::create('medicine_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            $table->unsignedBigInteger('medical_center_id');
            $table->foreign('medical_center_id')->references('id')->on('medical_centers')->onDelete('cascade');
            $table->unsignedBigInteger('medicine_orderable_id');
            $table->string('medicine_orderable_type');
            $table->integer('quantity');
            $table->unsignedBigInteger('activity_id');
            $table->foreign('activity_id')->references('id')->on('activities')->onDelete('cascade');
            $table->unsignedBigInteger('medical_center_medicine_id');
            $table->foreign('medical_center_medicine_id')->references('id')->on('medical_center_medicines')->onDelete('cascade');;
            $table->boolean('is_aprroved');
            $table->softDeletes();
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
        Schema::dropIfExists('medicine_orders');
    }
};
