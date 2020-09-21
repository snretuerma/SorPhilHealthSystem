<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecordsPersonnelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('records_personnels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('record_id')->nullable()->constrained('medical_records');
            $table->foreignId('personnel_id')->nullable()->constrained('personnels');
            $table->foreignId('contribution_id')->nullable()->constrained('contributions');
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
        $table->dropForeign(['personnel_id']);
        $table->dropColumn('personnel_id');
        $table->dropForeign(['contribution_id']);
        $table->dropColumn('contribution_id');
        Schema::dropIfExists('records_personnels');
    }
}
