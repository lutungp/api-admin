<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSRoutesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('s_routes', function (Blueprint $table) {
            $table->integer('route_id', true);
            $table->string('route_path');
            $table->integer('s_route_id');
            $table->integer('route_level');
            $table->char('route_aktif', 1)->default('y');
            $table->char('route_hidden', 1)->default('t');
            $table->char('route_alwaysshow', 1)->default('y');
            $table->string('route_title');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('s_routes');
    }
}
