<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecordDoctorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('record_doctors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('record_id')->constrained('medical_records');
            $table->foreignId('doctor_id')->constrained('doctors');
            $table->strimg('doctor_role');
            $table->decimal('fee', 19, 4);
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
        Schema::dropIfExists('record_doctors');
    }
}
