<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIbookingReservationTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ibooking__reservation_translations', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            // Your translatable fields

            $table->integer('reservation_id')->unsigned();
            $table->string('locale')->index();
            $table->unique(['reservation_id', 'locale']);
            $table->foreign('reservation_id')->references('id')->on('ibooking__reservations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ibooking__reservation_translations', function (Blueprint $table) {
            $table->dropForeign(['reservation_id']);
        });
        Schema::dropIfExists('ibooking__reservation_translations');
    }
}
