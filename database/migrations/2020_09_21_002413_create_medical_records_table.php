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
        // TODO: Wait for other ACPN columns
        Schema::create('medical_records', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('batch_name');
            $table->date('admission_date');
            $table->date('discharge_date')->nullable();
            $table->string('final_diagnosis');
            $table->string('record_type');
            $table->decimal('total_fee', 19, 4);
            $table->decimal('non_medical_fee', 19, 4);
            $table->decimal('pooled_fee', 19, 4);
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
        Schema::dropIfExists('medical_records');
    }
}
