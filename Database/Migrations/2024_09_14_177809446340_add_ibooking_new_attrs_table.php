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

      $table->integer('resource_id')->unsigned()->nullable()->after('status');
      $table->foreign('resource_id')->references('id')->on('ibooking__resources');
      $table->string('resource_title')->nullable()->after('status');
    });
    Schema::table('ibooking__reservation_items', function (Blueprint $table) {
      $table->integer('shift_time')->default(30)->nullable()->after('price');
      $table->text('options')->nullable()->after('price');
    });
    Schema::table('ibooking__services', function (Blueprint $table) {
      $table->boolean('is_internal')->default(false)->after('shift_time');
    });
    Schema::table('ibooking__resources', function (Blueprint $table) {
      $table->integer('assigned_to_id')->unsigned()->nullable()->after('status');
      $table->foreign('assigned_to_id')->references('id')->on(config('auth.table', 'users'))->onDelete('cascade');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
  }
};
