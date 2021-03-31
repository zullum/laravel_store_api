<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Order::all();
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

        $input_validator = Validator::make($request->all(), [
            'quantity' => 'required|integer',
            'status' => 'required|string|in:completed,reserved',
            'store_id' => 'required|integer|exists:stores,id',
            'product_id' => 'required|integer|exists:products,id',
        ]);
        if ($input_validator->fails()) {
            return response()->json(['error'=>$input_validator->errors()], 400);
        }

        // set new product quantity_in_stock
        $product = Product::find($request->product_id);
        $current_quantity_in_stock = $product->quantity_in_stock;
        if($current_quantity_in_stock > 0 &&  $current_quantity_in_stock > $request->quantity) {
            $new_quantity_in_stock = $current_quantity_in_stock - $request->quantity;
            $product->quantity_in_stock = $new_quantity_in_stock;
            $product->save();
        } else {
            return response()->json(['response'=>'Your requested quantity is not available at the moment.'], 400);
        }


        $price = $request->quantity * $product->price;

        $order = new Order;
        $order->quantity = $request->quantity;
        $order->status = $request->status;
        $order->store_id = $request->store_id;
        $order->product_id = $request->product_id;
        $order->owner_id = $user_id;
        $order->price = $price;
        $result = $order->save();
        if($result) {
            return response()->json(['response'=>'Order created succesfully'], 201);
        }
        return response()->json(['response'=>'Operation Order failed'], 400);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        return $order;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        // Allow only status updates on Orders
        $input_validator = Validator::make($request->all(), [
            'status' => array_key_exists('status', $request->all()) ? 'required|string|in:completed,reserved' : '',
        ]);
        if ($input_validator->fails()) {
            return response()->json(['error'=>$input_validator->errors()], 400);
        }

        $order->status = $request->status;

        $result = $order->save();
        if($result) {
            return response()->json(['response'=>'Order status updated succesfully'], 201);
        }
        return response()->json(['response'=>'Operation status update failed'], 400);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        $result = $order->delete();
        if($result) {
            return response()->json(['response'=>'Order deleted succesfully'], 201);
        }
        return response()->json(['response'=>'Operation delete failed'], 400);
    }
}
