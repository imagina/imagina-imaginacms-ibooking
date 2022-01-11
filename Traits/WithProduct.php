<?php


namespace Modules\Ibooking\Traits;

trait WithProduct
{

    /**
    * Boot trait method
    */
    public static function bootWithProduct()
    {

        if(is_module_enabled('Icommerce')){
            //Listen event after create model
            static::createdWithBindings(function ($model) {
              $model->syncProduct();
            });

            static::updatedWithBindings(function ($model) {
              $model->syncProduct();
            });
        }

    }

    
    /**
    * Sync Product
    */
    public function syncProduct(){

        \Log::info('Ibooking: Trait|WithProduct|EntityID:'.$this->id);

        
        $data = [
            'name' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'summary' => $this->summary ?? substr($this->description, 0, 150),
            'price' => $this->price,
            'status' => $this->status ?? 1,
            'stock_status' => $this->stock_status ?? 1,
            'quantity' => $this->quantity ?? 999999,
            'entity_id' => $this->id,
            'entity_type' => get_class($this)
        ];

        //\Log::info('Ibooking: Trait|WithProduct|Data:'.json_encode($data));

        $product = app('Modules\\Icommerce\\Repositories\\ProductRepository')->where('entity_id',$this->id)->first();

        if($product)
            $product->update($data);
        else
            $product = app('Modules\\Icommerce\\Repositories\\ProductRepository')->create($data);
        
    }


    /**
     * Make the Productable morph relation
     * @return object
     */
    public function products()
    {
        return $this->morphMany("Modules\Icommerce\Entities\Product", 'entity');
    }

    public function getProductAttribute(){
        return $this->products->first();
    }

}
