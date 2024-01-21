<?php

namespace Modules\Product\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Modules\Media\Repositories\MediaRepository;
use Modules\Product\Http\Resources\ProductResourceCollection;
use Modules\Product\Repositories\ProductRepository;

class OrderController extends Controller
{
    private $productRepository;
    private $mediaRepository;
    public function __construct(ProductRepository $productRepository,MediaRepository $mediaRepository)
    {
        $this->productRepository = $productRepository;
        $this->mediaRepository = $mediaRepository;

    }
    public function delete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required',

        ]);
        if ($validator->fails()) {
            return response()->json([
                'ErrorMessage' => $validator->errors()->all(),
                'status' => 401
            ], 401);
        }
        $product=$this->productRepository->find($request->product_id);
        if($product && $product->created_by==auth()->id())
        {
           $this->productRepository->delete($product);
           $this->mediaRepository->delete();
            return response()->json([
                'data' => [
                    'message' =>'ok',
                ],
                'status' => 200
            ], 200);
        }
        else{
            return response()->json([
                'ErrorMessage' =>'access denied !',
                'status' => 403
            ], 403);
        }


    }


}

