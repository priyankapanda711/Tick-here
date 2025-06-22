<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSeatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seats', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('venue_id'); // foreign key
            $table->string('row_label');
            $table->integer('seat_no');
            $table->string('label');
            $table->decimal('price', 10, 2);
            $table->boolean('is_booked')->default(false);
            $table->timestamps();

            $table->foreign('venue_id')
                  ->references('id')
                  ->on('venues')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('seats');
    }
}
