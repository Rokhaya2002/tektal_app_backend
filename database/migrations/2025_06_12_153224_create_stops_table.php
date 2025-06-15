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
    Schema::create('stops', function (Blueprint $table) {
        $table->id();
        $table->string('name'); 
        $table->unsignedBigInteger('line_id'); 
        $table->integer('stop_order'); 
        $table->timestamps();

        $table->foreign('line_id')->references('id')->on('lines')->onDelete('cascade');
    });
}


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stops');
    }
};
