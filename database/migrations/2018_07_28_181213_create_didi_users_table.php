<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDidiUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('didi_users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->comment('名字');
            $table->string('openid')->unique()->comment('微信的openid')->nullable();
            $table->string('session_key')->comment('微信会话密钥')->nullable();
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
        Schema::dropIfExists('didi_users');
    }
}
