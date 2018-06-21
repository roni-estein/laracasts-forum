<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateThreadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('threads', function (Blueprint $table) {
            $table->increments('id');
            $table->string('slug')->unique()->nullable();
            $table->integer('user_id')->unsigned();
            $table->integer('channel_id')->unsigned();
            $table->integer('replies_count')->unsigned()->default(0);
            $table->integer('best_reply_id')->unsigned()->nullable();
            $table->integer('visits')->unsigned()->default(0);
            $table->string('title');
            $table->text('body');
            
            $table->timestamps();

            $table->foreign('best_reply_id')->references('id')->on('replies')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('threads');
    }
}
