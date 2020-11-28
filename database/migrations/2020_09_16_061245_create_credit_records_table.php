<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCreditRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('credit_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hospital_id')->constrained('hospitals');
            $table->string('patient_name');
            $table->string('batch');
            $table->dateTime('admission_date');
            $table->dateTime('discharge_date');
            // private, new, old
            $table->string('record_type');
            $table->decimal('total', 19, 4);
            $table->decimal('non_medical_fee', 19, 4)->nullable();
            $table->decimal('medical_fee', 19, 4)->nullable();
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
        $table->dropForeign(['hospital_id']);
        Schema::dropIfExists('credit_records');
    }
}
