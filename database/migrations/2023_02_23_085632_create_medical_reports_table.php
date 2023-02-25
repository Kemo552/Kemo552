<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMedicalReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('medical_reports', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->dateTime('report_date')->default(now());
            $table->string('blood_groub', 3);
            $table->string('alergies')->default('None')->nullable(true);
            $table->boolean('heart_disease')->default(false);
            $table->string('blood_pressure')->default('120/80');
            $table->string('previous_surgeries')->default('None')->nullable(true);
            $table->string('doctor_name')->nullable(false);
            $table->unsignedInteger('doctor_phone')->nullable(false);
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
        Schema::dropIfExists('medical_reports');
    }
}
