<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComplaintsTable extends Migration
{
    
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('complaints', function (Blueprint $table) {
            $table->id();
            $table->foreignId('personnel_id')->nullable()->constrained('personnels');
            $table->foreignId('medical_record_id')->nullable()->constrained('medical_records');
            $table->string('description');
            $table->date('resolution_date');
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
        $table->dropForeign(['medical_record_id']);
        $table->dropColumn('medical_record_id');
        $table->dropForeign(['personnel_id']);
        $table->dropColumn('personnel_id');
        $table->softDeletes();
        Schema::dropIfExists('complaints');
    }
}
