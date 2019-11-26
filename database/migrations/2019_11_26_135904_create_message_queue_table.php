<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessagesQueueTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages_queue', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('to_user_id');
            $table->unsignedBigInteger('message_id');
            $table->foreign('to_user_id')->references('id')->on('users');
            $table->foreign('message_id')->references('id')->on('messages');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('message_queue');
    }
}
