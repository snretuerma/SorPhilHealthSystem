<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBudgetsRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('budgets_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('budget_id')->constrained('budgets');
            $table->foreignId('record_id')->constrained('records');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $table->dropForeign(['budget_id']);
        $table->dropForeign(['record_id']);
        $table->dropColumn('budget_id');
        $table->dropColumn('record_id');
        Schema::dropIfExists('budgets_records');
    }
}
