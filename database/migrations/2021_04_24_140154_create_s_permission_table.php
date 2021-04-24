<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSPermissionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('s_permission', function (Blueprint $table) {
            $table->integer('permission_id', true);
            $table->integer('s_role_id');
            $table->integer('s_route_id');
            $table->char('create', 1)->default('y');
            $table->char('update', 1)->default('y');
            $table->char('read', 1)->default('y');
            $table->char('delete', 1)->default('y');
            $table->char('permission_1')->nullable();
            $table->char('permission_2')->nullable();
            $table->char('permission_3')->nullable();
            $table->char('permission_4')->nullable();
            $table->char('keterangan')->nullable();
            $table->integer('created_by');
            $table->timestamp('created_date');
            $table->integer('updated_by')->nullable();
            $table->timestamp('updated_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('s_permission');
    }
}
