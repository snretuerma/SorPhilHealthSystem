<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersonnelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('personnels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hospital_id')->nullable()->constrained('hospitals');
            $table->string('first_name');
            $table->string('middle_name');
            $table->string('last_name');
            $table->string('name_suffix')->nullable();
            $table->unsignedTinyInteger('sex');
            $table->date('birthdate');
            $table->boolean('is_private');
            $table->boolean('designation');
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
        Schema::dropIfExists('personnels');
    }
}
