<?php

namespace Modules\Ibooking\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Ibooking\Database\Seeders\DataTestDatabaseSeeder;

class IbookingDatabaseSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $this->call(DataTestDatabaseSeeder::class);
  }
}
