<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWorkLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('work_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->double("hours");
            $table->timestamp("date");
            $table->text("comment")->nullable();
            $table->unsignedInteger("user_id");
            $table->nullableMorphs("loggable");
            $table->softDeletes();
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
        Schema::dropIfExists('work_logs');
    }
}
