<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserAgentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_agents', function (Blueprint $table) {
            $table->string('id', 36);
            $table->string('process_id', 36);
            $table->string('ua_string');
            $table->integer('device_type_id')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->primary('id');
            $table->index('process_id');
            $table->index('ua_string');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('user_agents');
    }
}
