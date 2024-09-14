<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration {
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::table('ibooking__reservations', function (Blueprint $table) {
      $table->timestamp('end_date')->nullable()->after('status');
      $table->timestamp('start_date')->nullable()->after('status');
    });
    Schema::table('ibooking__reservation_items', function (Blueprint $table) {
      $table->integer('shift_time')->default(30)->nullable()->after('price');
    });
    Schema::table('ibooking__services', function (Blueprint $table) {
      $table->boolean('is_internal')->default(false)->after('shift_time');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
  }
};
