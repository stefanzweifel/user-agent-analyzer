<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMediaTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('media', function (Blueprint $table) {
            $table->increments('id');
            $table->string('model_id', 36);
            $table->string('model_type');
            $table->string('collection_name');
            $table->string('name');
            $table->string('file_name');
            $table->string('disk');
            $table->unsignedInteger('size');
            $table->text('manipulations');
            $table->text('custom_properties');
            $table->unsignedInteger('order_column')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::drop('media');
    }
}
