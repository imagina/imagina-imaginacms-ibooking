<?php

namespace Modules\Ibooking\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use Modules\Ibooking\Entities\Category;
use Modules\Ibooking\Entities\Service;
use Modules\Ibooking\Entities\Resource;

use Modules\Ibooking\Repositories\CategoryRepository;
use Modules\Ibooking\Repositories\ServiceRepository;
use Modules\Ibooking\Repositories\ResourceRepository;

class DataTestDatabaseSeeder extends Seeder
{
  public $category;
  public $service;
  public $resource;

  public function __construct(CategoryRepository $category, ServiceRepository $service, ResourceRepository $resource)
  {
    $this->category = $category;
    $this->service = $service;
    $this->resource = $resource;
  }

  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    Model::unguard();

    $this->category->create([
      "featured" => 0,
      "parent_id" => 0,
      "status" => 1,
      "es" => [
        "title" => "Salud",
        "slug" => "salud",
        "description" => "salud"
      ]]);
    $this->category->create([
      "featured" => 0,
      "parent_id" => 0,
      "status" => 1,
      "es" => [
        "title" => "Social",
        "slug" => "Social",
        "description" => "Social"
      ]
    ]);


    $this->service->create([
      "status" => 1,
      "with_meeting" => 1,
      "price" => 250000,
      "category_id" => "1",
      "es" => [
        "title" => "Psicologia",
        "description" => "<p>Introducci&oacute;n a la carpinter&iacute;a</p>\n",
        "slug" => "psicologia"
      ],
      "schedule" => [
        "zone" => "main",
        "status" => 1,
        "from_date" => null,
        "to_date" => null,
        "work_times" => [
          ["day_id" => 3, "start_time" => "09:00", "end_time" => "11:00", "shift_time" => "60"],
          ["day_id" => 2, "start_time" => "09:00", "end_time" => "11:00", "shift_time" => "60"],
          ["day_id" => 1, "start_time" => "07:00", "end_time" => "12:30", "shift_time" => "60"]
        ]
      ]
    ]);

    /*
    $this->service->create([
      "status" => 1,
      "with_meeting" => 1,
      "price" => 250000,
      "category_id" => "1",
      "categories" => ["0" => "1"],
      "es" => [
        "title" => "psiquiatria",
        "description" => "<p>Introducci&oacute;n a la carpinter&iacute;a</p>\n",
        "slug" => "psiquiatria"
      ],
      "schedule" => [
        "zone" => "main",
        "status" => 1,
        "from_date" => "",
        "to_date" => "",
        "work_times" => [
          [
            "day_id" => 3,
            "start_time" => "09:00",
            "end_time" => "11:00",
            "shift_time" => "60"
          ],
          [
            "day_id" => 2,
            "start_time" => "09:00",
            "end_time" => "11:00",
            "shift_time" => "60"
          ],
          [
            "day_id" => 1,
            "start_time" => "07:00",
            "end_time" => "12:30",
            "shift_time" => "60"
          ]
        ]
      ]
    ]);
    $this->service->create([
      "status" => 1,
      "with_meeting" => 1,
      "price" => 250000,
      "category_id" => "1",
      "categories" => ["0" => "1"],
      "es" => [
        "title" => "nutricion",
        "description" => "<p>Introducci&oacute;n a la carpinter&iacute;a</p>\n",
        "slug" => "nutricion"
      ],
      "schedule" => [
        "zone" => "main",
        "status" => 1,
        "from_date" => "",
        "to_date" => "",
        "work_times" => [
          [
            "day_id" => 3,
            "start_time" => "09:00",
            "end_time" => "11:00",
            "shift_time" => "60"
          ],
          [
            "day_id" => 2,
            "start_time" => "09:00",
            "end_time" => "11:00",
            "shift_time" => "60"
          ],
          [
            "day_id" => 1,
            "start_time" => "07:00",
            "end_time" => "12:30",
            "shift_time" => "60"
          ]
        ]
      ]
    ]);
    $this->service->create([
      "status" => 1,
      "with_meeting" => 1,
      "price" => 250000,
      "category_id" => "2",
      "categories" => ["0" => "2"],
      "es" => [
        "title" => "liderazgo",
        "description" => "<p>Introducci&oacute;n a la carpinter&iacute;a</p>\n",
        "slug" => "liderazgo"
      ],
      "schedule" => [
        "zone" => "main",
        "status" => 1,
        "from_date" => "",
        "to_date" => "",
        "work_times" => [
          [
            "day_id" => 3,
            "start_time" => "09:00",
            "end_time" => "11:00",
            "shift_time" => "60"
          ],
          [
            "day_id" => 2,
            "start_time" => "09:00",
            "end_time" => "11:00",
            "shift_time" => "60"
          ],
          [
            "day_id" => 1,
            "start_time" => "07:00",
            "end_time" => "12:30",
            "shift_time" => "60"
          ]
        ]
      ]
    ]);
    $this->service->create([
      "status" => 1,
      "with_meeting" => 1,
      "price" => 250000,
      "category_id" => "2",
      "categories" => ["0" => "2"],
      "es" => [
        "title" => "estilodevida",
        "description" => "<p>Introducci&oacute;n a la carpinter&iacute;a</p>\n",
        "slug" => "estilodevida"
      ],
      "schedule" => [
        "zone" => "main",
        "status" => 1,
        "from_date" => "",
        "to_date" => "",
        "work_times" => [
          [
            "day_id" => 3,
            "start_time" => "09:00",
            "end_time" => "11:00",
            "shift_time" => "60"
          ],
          [
            "day_id" => 2,
            "start_time" => "09:00",
            "end_time" => "11:00",
            "shift_time" => "60"
          ],
          [
            "day_id" => 1,
            "start_time" => "07:00",
            "end_time" => "12:30",
            "shift_time" => "60"
          ]
        ]
      ]
    ]);


                Resource::create([
                  "status" => 1,
                  "services" => ["0" => "1"],
                  "es" => [
                    "title" => "andrea perez",
                    "slug" => "andreaperez",
                    "description" => "andrea perez"
                  ]
                ]);
                Resource::create([
                  "status" => 1,
                  "services" => ["0" => "2"],
                  "es" => [
                    "title" => "camiloandrade",
                    "slug" => "camiloandrade",
                    "description" => "camiloandrade"
                  ]
                ]);
                Resource::create([
                  "status" => 1,
                  "services" => ["0" => "3"],
                  "es" => [
                    "title" => "dahianaguzman",
                    "slug" => "dahianaguzman",
                    "description" => "dahianaguzman"
                  ]
                ]);
                Resource::create([
                  "status" => 1,
                  "services" => ["0" => "4"],
                  "es" => [
                    "title" => "laurajimenez",
                    "slug" => "laurajimenez",
                    "description" => "laurajimenez"
                  ]
                ]);
                Resource::create([
                  "status" => 1,
                  "services" => ["0" => "5"],
                  "es" => [
                    "title" => "pepitoperez",
                    "slug" => "pepitoperez",
                    "description" => "pepitoperez"
                  ]
                ]);
                */
  }
}
