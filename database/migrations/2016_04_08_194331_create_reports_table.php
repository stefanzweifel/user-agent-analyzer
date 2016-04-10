<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->increments('id');
            $table->string('process_id', 36);

            $table->integer('total')->default(0);
            $table->integer('desktop')->default(0);
            $table->integer('tablet')->default(0);
            $table->integer('mobile')->default(0);
            $table->integer('robots')->default(0);
            $table->integer('other')->default(0);
            $table->integer('unkown')->default(0);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('reports');
    }
}
