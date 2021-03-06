<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDoctorRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('doctor_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('record_id')->constrained('credit_records');
            $table->foreignId('doctor_id')->constrained('doctors');
            $table->string('doctor_role')->nullable();
            $table->decimal('professional_fee', 19, 4)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $table->dropForeign(['record_id']);
        $table->dropColumn('record_id');
        $table->dropForeign(['doctor_id']);
        $table->dropColumn('doctor_id');
        Schema::dropIfExists('doctor_records');
    }
}
