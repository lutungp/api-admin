<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('s_roles', function (Blueprint $table) {
            $table->integer('role_id', true);
            $table->string('role_kode', 100)->nullable();
            $table->string('role_nama');
            $table->char('role_aktif', 1)->default('y');
            $table->integer('created_by');
            $table->timestamp('created_date');
            $table->string('updated_by', 100)->nullable();
            $table->timestamp('updated_date')->nullable();
            $table->smallInteger('revised')->default(0);
            $table->integer('disabled_by')->nullable();
            $table->timestamp('disabled_date')->nullable();
            $table->string('disabled_alasan', 200)->nullable();
            $table->string('role_keterangan');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('s_roles');
    }
}
