<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\ProductResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;


class ProductController extends Controller
{
    public function index(){

        $product = Product::get();
        if($product->count() > 0){
            return ProductResource::collection($product);
        }
        else{
            return response()->json(['message'=>'No record available'], 200);
        }

    }

    public function store(Request $request){

        $validator = Validator::make($request->all(), [
            'name'=> 'required|string|max:255',
            'description'=> 'required',
            'price'=> 'required|integer',
        ]);

        if($validator->fails()){
            return response()->json([
                'message'=>'All fields are mandatory',
                'error'=>$validator->messages(),
            ], 422);
        }

        $product -> update([
            'name'=> $request->name,
            'description'=> $request->description,
            'price'=> $request->price,
        ]);

        return response()->json([
            'message'=>'Product created successfully',
            'data'=> new ProductResource($product)
        ], 200);
    }

    public function show(Product $product){
        return new ProductResource($product);
    }

    public function update(Request $request, Product $product){

        $validator = Validator::make($request->all(), [
            'name'=> 'required|string|max:255',
            'description'=> 'required',
            'price'=> 'required|integer',
        ]);

        if($validator->fails()){
            return response()->json([
                'message'=>'All fields are mandatory',
                'error'=>$validator->messages(),
            ], 422);
        }

        $product = Product::create([
            'name'=> $request->name,
            'description'=> $request->description,
            'price'=> $request->price,
        ]);

        return response()->json([
            'message'=>'Product updated successfully',
            'data'=> new ProductResource($product)
        ], 200);
    }

    public function destroy(Product $product){
        $product-> delete();
        return response()->json([
            'message'=>'Product deleted successfully'
        ], 200);
    }
}
