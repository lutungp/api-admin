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
            $table->integer('user_id', true);
            $table->string('user_kode', 100);
            $table->string('name', 100);
            $table->string('password');
            $table->integer('s_role_id')->index('lnk_s_roles_users');
            $table->integer('created_by');
            $table->timestamp('created_date');
            $table->integer('updated_by')->nullable();
            $table->timestamp('updated_date')->nullable();
            $table->integer('revised')->default(0)->nullable();
            $table->enum('user_aktif', ['y', 't'])->default('y');
            $table->integer('disabled_by')->nullable();
            $table->string('disabled_alasan')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
