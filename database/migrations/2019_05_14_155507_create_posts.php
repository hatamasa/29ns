<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePosts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {

            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->string('shop_cd');
            $table->float('score');
            $table->integer('visit_count')->unsigned();
            $table->string('title', 255);
            $table->text('contents')->nullable()->default(null);
            $table->string('img_url_1')->nullable()->default(null);
            $table->string('img_url_2')->nullable()->default(null);
            $table->string('img_url_3')->nullable()->default(null);
            $table->integer('like_count')->unsigned();
            $table->integer('comment_count')->unsigned();
            $table->tinyInteger('is_deleted')->unsigned()->default(0);
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('posts');
    }
}
