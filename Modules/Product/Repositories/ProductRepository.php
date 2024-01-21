<?php

namespace Modules\Product\Repositories;

use Illuminate\Support\Str;
use Modules\Product\Models\Product;


class ProductRepository
{
    public function find($product_id)
    {
        return Product::find($product_id);
    }
    public function delete($product)
    {
        return $product->delete();
    }
    public function filter( $search=null, $max_amount=null, $sort_by='created_at',$dir='desc',$paginate=40)
    {

        return Product::when($search,function ($query)use($search){
            $query->where('title','like','%'.$search.'%');
        })->when($max_amount,function ($query)use($max_amount){
            $query->where('amount','<=',$max_amount);
        })->orderby($sort_by,$dir)->paginate($paginate);
    }


}
