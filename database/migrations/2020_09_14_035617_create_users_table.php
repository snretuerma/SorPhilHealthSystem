<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique();
            $table->string('password');
            $table->foreignId('role_id')->constrained('roles');
            $table->foreignId('hospital_id')->nullable()->constrained('hospitals');
            $table->rememberToken();
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
        $table->dropForeign(['role_id']);
        $table->dropColumn('role_id');
        $table->dropForeign(['hospital_id']);
        $table->dropColumn('hospital_id');
        Schema::dropIfExists('users');
    }
}
