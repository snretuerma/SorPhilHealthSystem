<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePooledRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pooled_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('record_id')->constrained('credit_records');
            $table->text('full_time_doctors')->nullable();
            $table->text('part_time_doctors')->nullable();
            $table->decimal('full_time_individual_fee', 19, 4)->nullable();
            $table->decimal('part_time_individual_fee', 19, 4)->nullable();
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
        Schema::dropIfExists('pooled_records');
    }
}
