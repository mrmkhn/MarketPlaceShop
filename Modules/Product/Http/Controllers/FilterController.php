<?php

namespace Modules\Product\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Modules\Product\Http\Resources\ProductResourceCollection;
use Modules\Product\Repositories\ProductRepository;

class FilterController extends Controller
{
    private $productRepository;
    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;

    }
    public function filter(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'search' => 'nullable',
            'max_amount' => 'nullable|numeric',
            'sort' => 'nullable',

        ]);
        if ($validator->fails()) {
            return response()->json([
                'ErrorMessage' => $validator->errors()->all(),
                'status' => 401
            ], 401);
        }
        $order=$this->getOrderBy($request->sort);
        $products=$this->productRepository->filter($request->search,$request->max_amount,$order['order_by'],$order['dir']);
        return response()->json([
            'data' => [
                'products' => new ProductResourceCollection($products),
            ],
            'status' => 200
        ], 200);

    }

    private function getOrderBy($sort_by)
    {
        switch ($sort_by) {
            case 'min_amount':
                $order_by='amount';
                $dir='asc';
                break;

            default:
                $order_by='created_at';
                $dir='desc';
        }
        return ['order_by'=>$order_by,'dir'=>$dir];
    }

}

