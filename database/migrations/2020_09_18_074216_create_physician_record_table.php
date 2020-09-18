<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePhysicianRecordTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('physician_record', function (Blueprint $table) {
            $table->id();
            $table->foreignId('record_id')->nullable()->constrained('records');
            $table->foreignId('physician_id')->nullable()->constrained('physicians');
            $table->foreignId('participation_type_id')->nullable()->constrained('participation_types');
            $table->decimal('amount', 19, 4);
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
        Schema::dropIfExists('physician_record');
    }
}
