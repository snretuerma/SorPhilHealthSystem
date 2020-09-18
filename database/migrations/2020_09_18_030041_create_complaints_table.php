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
            $table->string('name');
            $table->foreignId('record_id')->nullable()->constrained('records');
            $table->foreignId('physician_id')->nullable()->constrained('physicians');
            $table->boolean('is_resolved');
            $table->date('resolution_date');
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
        $table->dropForeign(['record_id']);
        $table->dropColumn('record_id');
        $table->dropForeign(['physician_id']);
        $table->dropColumn('physician_id');
        Schema::dropIfExists('complaints');
    }
}
