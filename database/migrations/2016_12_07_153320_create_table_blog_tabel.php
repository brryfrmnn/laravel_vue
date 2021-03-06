<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableBlogTabel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
      Schema::create('blog_post', function (Blueprint $table) {
        $table->increments('id');
        $table->string('title');
        $table->string('description');
        $table->timestamps();
      });
    }
    public function down() {
        Schema::drop('blog_post');
    }
}
