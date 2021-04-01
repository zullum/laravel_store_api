<?php

namespace App\Http\Controllers;

use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;

class StoreController extends Controller
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
            return Store::all();
        } else if($role == 'manager') {
            return Store::where('manager_id', $user_id)->get();
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
        $role = $user->role;

        $stores = Store::where('manager_id', $user_id)->get()->pluck('id')->toArray();

        $input_validator = Validator::make($request->all(), [
            'name' => 'required|string|min:2|max:45',
            'address' => 'required|string|min:2|max:45',
            'manager_id' => ($role == 'admin') ? 'required|integer|exists:users,id' : '',
        ]);
        if ($input_validator->fails()) {
            return response()->json(['error'=>$input_validator->errors()], 400);
        }
        if(!empty($request->manager_id)) {
            $manager_user = User::find($request->manager_id);
            if(!empty($manager_user) && $manager_user->role != 'manager') {
                return response()->json(['error'=>'Please provide valid manager id field'], 400);
            }
        }

        $store = new Store;
        $store->name = $request->name;
        $store->address = $request->address;
        $store->manager_id = ($role == 'admin') ? $request->manager_id : $user_id;
        $result = $store->save();
        if($result) {
            return response()->json(['response'=>'Store created succesfully'], 201);
        }
        return response()->json(['response'=>'Operation store failed'], 400);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Store  $store
     * @return \Illuminate\Http\Response
     */
    public function show(Store $store)
    {
        $user = Auth::user();
        $user_id = $user->id;
        $role = $user->role;
        if($role == 'admin') {
            return $store;
        } else if($role == 'manager') {
            $manager_stores = Store::where('manager_id', $user_id)->get()->pluck('id')->toArray();
            if(in_array($store->id, $manager_stores)) {
                return $store;
            }
            return response()->json(['response'=>'You are not authorized to access this resource'], 400);
        }

        return response()->json(['response'=>'You are not authorized to access this resource'], 400);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Store  $store
     * @return \Illuminate\Http\Response
     */
    public function edit(Store $store)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Store  $store
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Store $store)
    {

        $input_validator = Validator::make($request->all(), [
            'name' => array_key_exists('name', $request->all()) ? 'required|string|min:2|max:45' : '',
            'address' => array_key_exists('color', $request->all()) ?  'required|string|min:2|max:45' : '',
        ]);
        if ($input_validator->fails()) {
            return response()->json(['error'=>$input_validator->errors()], 400);
        }

        foreach ($request->all() as $key => $value) {
            if ($key != 'manager_id') {
                $store->{$key} = $value;
            }
        }

        $result = $store->save();
        if($result) {
            return response()->json(['response'=>'Store updated succesfully'], 201);
        }
        return response()->json(['response'=>'Operation update failed'], 400);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Store  $store
     * @return \Illuminate\Http\Response
     */
    public function destroy(Store $store)
    {
        $result = $store->delete();
        if($result) {
            return response()->json(['response'=>'Store deleted succesfully'], 200);
        }
        return response()->json(['response'=>'Operation delete failed'], 400);
    }
}
