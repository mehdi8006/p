<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaskTable extends Migration
{
    public function up()
    {
        Schema::create('task', function (Blueprint $table) {
            // Use increments() for auto-increment primary key
            $table->increments('task_id');
            $table->string('task_name', 255);
            $table->string('description', 255);
            $table->date('due_date')->nullable();
            $table->date('fin_date')->nullable();
            $table->integer('division_id')->unsigned();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('task');
    }
}
