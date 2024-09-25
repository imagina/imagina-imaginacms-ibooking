<?php

namespace Modules\Ibooking\Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;
use Modules\Ibooking\Entities\ReservationItem;
use Modules\Ibooking\Entities\Reservation;

class IbookingMoveValuesRIToReservationSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run()
  {
    Model::unguard();

    // Run only if table ibooking__reservation_items still have the column start_date
    if (Schema::hasColumn('ibooking__reservation_items', 'start_date')) {
      $reservationItems = \DB::table('ibooking__reservation_items')->get();
      //Map each Reservation Item
      foreach ($reservationItems as $item) {
        //---- Update the shift time for reservation item
        $startDate = Carbon::parse($item->start_date);
        $endDate = Carbon::parse($item->end_date);
        ReservationItem::where('id', $item->id)->update([
          'shift_time' => $startDate->diffInMinutes($endDate)
        ]);

        //---- Move the start and end date to Reservation form his reservation item
        Reservation::where('id', $item->reservation_id)->update([
          'start_date' => $item->start_date,
          'end_date' => $item->end_date
        ]);
      }

      //---- Remove the start and end date columns from reservation items
      Schema::table('ibooking__reservation_items', function ($table) {
        $table->dropColumn(['start_date']);
        $table->dropColumn(['end_date']);
      });
    }

    // Run only if table ibooking__reservation_items still have the column resource_id
    if (Schema::hasColumn('ibooking__reservation_items', 'resource_id')) {
      $reservationItems = \DB::table('ibooking__reservation_items')->get();
      //Map each Reservation Item
      foreach ($reservationItems as $item) {
        //---- Move the resource id and title to Reservation form his reservation item
        Reservation::where('id', $item->reservation_id)->update([
          'resource_id' => $item->resource_id,
          'resource_title' => $item->resource_title
        ]);
      }

      //---- Remove the resource id and title columns from reservation items
      Schema::table('ibooking__reservation_items', function ($table) {
        $table->dropForeign(['resource_id']);
        $table->dropColumn(['resource_id']);
        $table->dropColumn(['resource_title']);
      });
    }
  }
}
