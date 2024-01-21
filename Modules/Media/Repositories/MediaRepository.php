<?php


namespace Modules\Media\Repositories;


use Modules\Category\Models\Category;
use Modules\Media\Http\Controllers\MediaController;
use Modules\Media\Models\Media;

class MediaRepository
{

    public function delete($product_id=null,$media_id=null)
    {

      $media=  Media::when($product_id,function ($query)use($product_id){
            $query->where('product_id',$product_id);
        })->when($media_id,function ($query)use($media_id){
            $query->where('id',$media_id);
        })->get();

        foreach ($media as $item)
        {
            if(file_exists(public_path($item->url)))
            unlink(public_path($item->url));
             $item->delete();

        }

        return true;

    }


}
