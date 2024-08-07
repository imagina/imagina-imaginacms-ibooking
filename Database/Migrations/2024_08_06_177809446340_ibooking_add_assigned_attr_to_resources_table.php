<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration {
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::table('ibooking__resources', function (Blueprint $table) {
      $table->integer('assigned_to_id')->unsigned()->default(1)->after('status');
      $table->foreign('assigned_to_id')->references('id')->on('users');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
  }
};
