<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Store;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Product::all();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $user_id = $user->id;

        $stores = Store::where('manager_id', $user_id)->get()->pluck('id')->toArray();

        $input_validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'color' => 'required|string',
            'type' => 'required|string',
            'size' => 'required|string',
            'material' => 'required|string',
            'price' => 'required|numeric',
            'quantity_in_stock' => 'required|integer',
            // 'store_id' => 'required|integer|in:'.$stores,
            'store_id' => [
                'required',
                'integer',
                Rule::in($stores),
            ],
        ]);
        if ($input_validator->fails()) {
            return response()->json(['error'=>$input_validator->errors()], 400);
        }
        $product = new Product;
        $product->name = $request->name;
        $product->color = $request->color;
        $product->type = $request->type;
        $product->size = $request->size;
        $product->material = $request->material;
        $product->price = $request->price;
        $product->quantity_in_stock = $request->quantity_in_stock;
        $product->store_id = $request->store_id;
        $result = $product->save();
        if($result) {
            return response()->json(['response'=>'Product created succesfully'], 201);
        }
        return response()->json(['response'=>'Operation store failed'], 400);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        return $product;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $user = Auth::user();
        $user_id = $user->id;

        $stores = Store::where('manager_id', $user_id)->get()->pluck('id')->toArray();

        $input_validator = Validator::make($request->all(), [
            'name' => array_key_exists('name', $request->all()) ? 'required|string' : '',
            'color' => array_key_exists('color', $request->all()) ?  'required|string' : '',
            'type' => array_key_exists('type', $request->all()) ?  'required|string' : '',
            'size' => array_key_exists('size', $request->all()) ?  'required|string' : '',
            'material' => array_key_exists('material', $request->all()) ?  'required|string' : '',
            'price' => array_key_exists('price', $request->all()) ?  'required|numeric' : '',
            'quantity_in_stock' => array_key_exists('quantity_in_stock', $request->all()) ?  'required|integer' : '',
            'store_id' => [
                'required',
                'integer',
                Rule::in($stores),
            ],
        ]);
        if ($input_validator->fails()) {
            return response()->json(['error'=>$input_validator->errors()], 400);
        }

        foreach ($request->all() as $key => $value) {
            if($key != 'store_id') {
                $product->{$key} = $value;
            }
        }

        $result = $product->save();
        if($result) {
            return response()->json(['response'=>'Product updated succesfully'], 201);
        }
        return response()->json(['response'=>'Operation update failed'], 400);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $result = $product->delete();
        if($result) {
            return response()->json(['response'=>'Product deleted succesfully'], 201);
        }
        return response()->json(['response'=>'Operation delete failed'], 400);
    }
}
