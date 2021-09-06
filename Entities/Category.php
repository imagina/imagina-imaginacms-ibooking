<?php

namespace Modules\Ibooking\Entities;

use Astrotomic\Translatable\Translatable;
use Modules\Core\Icrud\Entities\CrudModel;

class Category extends CrudModel
{
  use Translatable;

  public $transformer = 'Modules\Ibooking\Transformers\CategoryTransformer';
  public $requestValidation = [
    'create' => 'Modules\Ibooking\Http\Requests\CreateCategoryRequest',
    'update' => 'Modules\Ibooking\Http\Requests\UpdateCategoryRequest',
  ];

  protected $table = 'ibooking__categories';
  public $translatedAttributes = ['title', 'description', 'slug'];
  protected $casts = ['options' => 'array'];
  protected $fillable = [
    'parent_id',
    'featured',
    'status',
    'options'
  ];

  /**
   * Relation Parent
   * @return mixed
   */
  public function parent()
  {
    return $this->belongsTo('Modules\Ibooking\Entities\Category', 'parent_id');
  }

  /**
   * Relation Children
   * @return mixed
   */
  public function children()
  {
    return $this->hasMany('Modules\Ibooking\Entities\Category', 'parent_id');
  }
}
