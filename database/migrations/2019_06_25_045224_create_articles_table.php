<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('category_id')->unsigned(); //cungf kieeur
            $table->foreign('category_id')->references('id')->on('categories');
            $table->timestamps(); //create_at and update_at
            $table->string('name');
            $table->string('description');
            $table->string('content');
            $table->string('slug');
            $table->integer('is_active');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('articles');
    }
}
