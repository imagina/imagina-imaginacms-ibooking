<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration {
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::table('ibooking__reservation_items', function (Blueprint $table) {
      $table->float('resource_price', 50, 2)->default(0)->after('price');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
  }
};
