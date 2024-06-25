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
        Schema::create('medicines', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id')->nullable();;
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            $table->string('name');
            $table->string('type')->nullable();
            $table->string('scientific_name')->nullable();
            $table->string('titer')->nullable();
            $table->integer('code')->nullable();
            $table->string('unit')->nullable();
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
        Schema::dropIfExists('medicines');
    }
};
