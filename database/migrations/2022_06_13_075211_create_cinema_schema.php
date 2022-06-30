<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCinemaSchema extends Migration
{
    /**
    # Create a migration that creates all tables for the following user stories

    For an example on how a UI for an api using this might look like, please try to book a show at https://in.bookmyshow.com/.
    To not introduce additional complexity, please consider only one cinema.

    Please list the tables that you would create including keys, foreign keys and attributes that are required by the user stories.

    ## User Stories

     **Movie exploration**
     * As a user I want to see which films can be watched and at what times
     * As a user I want to only see the shows which are not booked out
    
     **Show administration**
     * As a cinema owner I want to run different films at different times
     * As a cinema owner I want to run multiple films at the same time in different locations

     **Pricing**
     * As a cinema owner I want to get paid differently per show
     * As a cinema owner I want to give different seat types a percentage premium, for example 50 % more for vip seat

     **Seating**
     * As a user I want to book a seat
     * As a user I want to book a vip seat/couple seat/super vip/whatever
     * As a user I want to see which seats are still available
     * As a user I want to know where I'm sitting on my ticket
     * As a cinema owner I dont want to configure the seating for every show
     */
    public function up()
    {
        Schema::create('movies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('status');
            $table->timestamps();
        });

        Schema::create('movie_shows', function (Blueprint $table) {
            $table->id();
            $table->foreignId('movie_id');
            $table->string('show_name');
            $table->string('location');
            $table->integer('number_of_seats');
            $table->time('start');
            $table->time('end');
            $table->boolean('status');
            $table->timestamps();

            $table->foreign('movie_id')->references('id')->on('movies')->onDelete('cascade');
        });

        Schema::create('prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('movie_show_id');
            $table->decimal('price', 10, 2);
            $table->string('seat_type');
            $table->integer('how_many_percent');
            $table->boolean('status');
            $table->timestamps();

            $table->foreign('movie_show_id')->references('id')->on('movie_shows')->onDelete('cascade');
        });

        Schema::create('booking', function (Blueprint $table) {
            $table->id();
            $table->foreignId('movie_show_id');
            $table->foreignId('user_id');
            $table->foreignId('price_id');
            $table->integer('booked_seats');
            $table->boolean('status');
            $table->timestamps();

            $table->foreign('movie_show_id')->references('id')->on('movie_shows')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('price_id')->references('id')->on('prices')->onDelete('cascade');
        });
        // throw new \Exception('implement in coding task 4, you can ignore this exception if you are just running the initial migrations.');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('booking');
        Schema::dropIfExists('prices');
        Schema::dropIfExists('movie_shows');
        Schema::dropIfExists('movies');
    }
}
