<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIbookingServiceResourcesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ibooking__service_resources', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            // Your fields
          $table->integer('service_id')->unsigned();
          $table->foreign('service_id')->references('id')->on('ibooking__services')->onDelete('cascade');
  
          $table->integer('resource_id')->unsigned();
          $table->foreign('resource_id')->references('id')->on('ibooking__resources')->onDelete('cascade');
          
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
        Schema::dropIfExists('ibooking__service_resources');
    }
}
