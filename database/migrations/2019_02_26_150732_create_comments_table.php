<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger("meme_id");
            $table->foreign("meme_id")->references('id')->on('memes')->onDelete('cascade');;
            $table->unsignedInteger("user_id");
            $table->foreign("user_id")->references('id')->on('users')->onDelete('cascade');;
            $table->text("messages");
            $table->unsignedInteger("comment_id")->nullable();
            $table->foreign("comment_id")->references('id')->on('comments')->onDelete('cascade');;
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
        Schema::dropIfExists('comments');
    }
}
