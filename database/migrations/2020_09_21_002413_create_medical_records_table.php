<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\SoftDeletes;

class CreateMedicalRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('medical_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->nullable()->constrained('patients');
            $table->date('admission_date');
            $table->date('discharge_date')->nullable();
            $table->string('final_diagnosis');
            $table->string('record_type');
            $table->decimal('total_fee', 19, 4);
            $table->decimal('non_medical_fee', 19, 4);
            $table->decimal('pooled_fee', 19, 4);
            $table->unsignedTinyInteger('total_public_doctors');
            $table->unsignedTinyInteger('total_private_doctors');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $table->dropForeign(['patient_id']);
        $table->dropColumn('patient_id');
        Schema::dropIfExists('medical_records');
    }
}
