<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIbookingReservationItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ibooking__reservation_items', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            // Your fields
  
          $table->integer('service_id')->unsigned()->nullable();
          $table->foreign('service_id')->references('id')->on('ibooking__services');
  
          $table->integer('resource_id')->unsigned()->nullable();
          $table->foreign('resource_id')->references('id')->on('ibooking__resources');
  
          $table->integer('category_id')->unsigned()->nullable();
          $table->foreign('category_id')->references('id')->on('ibooking__categories');
          
          $table->string('category_title');
          $table->string('service_title');
          $table->string('resource_title');
          $table->float('price', 50, 2)->default(0);
          
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
        Schema::dropIfExists('ibooking__reservation_items');
    }
}
