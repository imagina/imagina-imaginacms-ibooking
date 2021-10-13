<?php

namespace Modules\Ibooking\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use Modules\Ibooking\Entities\Category;
use Modules\Ibooking\Entities\Service;


class TestData extends Seeder
{

  public function run()
  {
    Model::unguard();

    //Categories
    $categories = [
      [
        'es' => [
          'title' => 'Medicina',
          'slug' => 'medicina',
          'description' => 'Medicina...',
        ],
        'status' => 1
      ],
      [
        'es' => [
          'title' => 'Estilo de Vida',
          'slug' => 'estilo-de-vida',
          'description' => 'Estilo de Vida...',
        ],
        'status' => 1
      ]
    ];

    //Create Categories
    foreach ($categories as $category) {
      Category::create($category);
    }

    //Services
    $servies = [
      [
        'es' => [
          'title' => 'Psicología',
          'slug' => 'psicologia',
          'description' => 'Psicología...',
        ],
        'category_id' => 2,
        'shift_time' => 35,
      ],
      [
        'es' => [
          'title' => 'Nutrición',
          'slug' => 'nutricion',
          'description' => 'Nutrición...',
        ],
        'category_id' => 2,
        'shift_time' => 40,
      ],
      [
        'es' => [
          'title' => 'Ortopedía',
          'slug' => 'ortopedia',
          'description' => 'Ortopedía...',
        ],
        'category_id' => 1,
        'shift_time' => 45,
      ],
      [
        'es' => [
          'title' => 'Odontología',
          'slug' => 'odontologia',
          'description' => 'Odontología...',
        ],
        'category_id' => 1,
        'shift_time' => 50,
      ]
    ];

    //Create Categories
    foreach ($servies as $service) {
      Service::create($service);
    }
  }
}
