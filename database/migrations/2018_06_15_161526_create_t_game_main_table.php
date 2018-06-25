<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTGameMainTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_game_main', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('g_name');
            $table->string('g_banner');
            $table->string('g_meta_information');//平台、发行日期
            $table->string('g_appraisal');//游戏测评
            $table->string('g_type');
            $table->string('g_price');
            $table->string('g_video');
            $table->string('g_img');
            $table->string('g_content',500);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t_game_main');
    }
}
