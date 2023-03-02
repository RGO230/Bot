<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tgusers', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('username');
            $table->unsignedBigInteger('user_id');
            $table->unsignedSmallInteger('isvip')->default(1);
            $table->unsignedBigInteger('counter')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tgusers');
    }
};
