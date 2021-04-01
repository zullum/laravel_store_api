<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessProduct;
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

        $user = Auth::user();
        $user_id = $user->id;
        $role = $user->role;
        if($role == 'admin') {
            return Product::all();
        } else if($role == 'manager') {
            return Product::join('stores', 'products.store_id', '=', 'stores.id')
            ->where('stores.manager_id', '=', $user_id)
            ->select(
                'products.id',
                'products.created_at',
                'products.updated_at',
                'products.name',
                'products.price',
                'products.type',
                'products.color',
                'products.size',
                'products.material',
                'products.quantity_in_stock',
                'products.store_id'
            )
            ->get();
        }

        return response()->json(['response'=>'You are not authorized to access this resource'], 400);
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

        $user = Auth::user();
        $user_id = $user->id;
        $role = $user->role;
        if($role == 'admin') {
            return $product;
        } else if($role == 'manager') {
            $managers_products = Product::join('stores', 'products.store_id', '=', 'stores.id')
            ->where('stores.manager_id', '=', $user_id)
            ->select('products.id')->get()->pluck('id')->toArray();

            if(in_array($product->id, $managers_products)) {
                return $product;
            }
            return response()->json(['response'=>'You are not authorized to access this resource'], 400);
        }

        return response()->json(['response'=>'You are not authorized to access this resource'], 400);
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
            return response()->json(['response'=>'Product deleted succesfully'], 200);
        }
        return response()->json(['response'=>'Operation delete failed'], 400);
    }

    public function scheduleUpdate(Request $request, Product $product) {
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
                'integer',
                Rule::in($stores),
            ],
            'update_date' => [
                'required',
                'date',
            ],
        ]);
        if ($input_validator->fails()) {
            return response()->json(['error'=>$input_validator->errors()], 400);
        }

        $delay_time = intval(strtotime($request->update_date)) - intval(strtotime("now"));
        if($delay_time < 0) {
            return response()->json(['error'=>'Please enter date greater than current date and time.'], 400);
        }

        ProcessProduct::dispatch($product, $request->all())->delay($delay_time);
        return response()->json(['response'=>'Product update scheduled succesfully'], 200);
    }
}
