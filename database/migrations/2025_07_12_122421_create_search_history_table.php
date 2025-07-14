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
        Schema::create('search_history', function (Blueprint $table) {
            $table->id();
            $table->string('from');
            $table->string('to');
            $table->integer('count')->default(1);
            $table->timestamp('last_searched_at')->useCurrent();
            $table->timestamps();

            // Index pour optimiser les requêtes
            $table->index(['from', 'to']);
            $table->index('last_searched_at');
            $table->index('count');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('search_history');
    }
};
