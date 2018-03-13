<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->increments('id');
            $table->string("name");
            $table->string("description")->nullable();
            $table->unsignedInteger("module_id")->nullable();
            $table->unsignedInteger("assigned_to")->nullable();
            $table->unsignedInteger("status_id")->nullable();
            $table->unsignedInteger("priority_id")->nullable();
            $table->unsignedInteger("created_by");
            $table->string("estimated_time")->nullable();
            $table->date("due_date")->nullable();
            $table->boolean("is_completed")->default(false);
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
        Schema::dropIfExists('tasks');
    }
}
