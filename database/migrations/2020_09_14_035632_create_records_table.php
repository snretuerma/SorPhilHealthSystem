<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hospital_id')->constrained('hospitals');
            $table->string('patient_first_name');
            $table->string('patient_middle_name');
            $table->string('patient_last_name');
            $table->boolean('is_credited');
            $table->date('patient_in');
            $table->date('patient_out');
            $table->decimal('total_pf');
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
        $table->dropColumn('hospital_id');
        Schema::dropIfExists('records');
    }
}
