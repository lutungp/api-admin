<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_info', function (Blueprint $table) {
            $table->integer('userinfo_id', true);
            $table->integer('s_user_id')->index('lnk_users_users_info');
            $table->string('userinfo_email');
            $table->string('userinfo_alamat');
            $table->string('userinfo_phone');
            $table->integer('created_by');
            $table->date('created_date');
            $table->enum('userinfo_aktif', ['y', 't'])->default('y');
            $table->string('userinfo_nama');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users_info');
    }
}
