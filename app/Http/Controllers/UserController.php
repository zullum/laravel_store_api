<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Testing\Fluent\Concerns\Has;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Models\User;

class UserController extends Controller
{

    public $successStatus = 200;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return User::all();
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

        $input_validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'password' => 'required|string',
            'email' => 'required|email|unique:users',
            'role' => 'required|string|in:admin,manager,customer',
        ]);
        if ($input_validator->fails()) {
            return response()->json(['error'=>$input_validator->errors()], 400);
        }

        $user = new User;
        $user->name = $request->name;
        $user->password = Hash::make($request->password);
        $user->email = $request->email;
        $user->role = $request->role;
        $result = $user->save();

        if($result) {
            return response()->json(['response'=>'User created succesfully'], 201);
        }
        return response()->json(['response'=>'Operation store failed'], 400);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return $user;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {

        $input_validator = Validator::make($request->all(), [
            'name' => array_key_exists('name', $request->all()) ? 'required|string' : '',
            'password' => array_key_exists('color', $request->all()) ?  'required|string' : '',
            'email' => array_key_exists('type', $request->all()) ?  'required|email|unique:users' : '',
            'role' => array_key_exists('size', $request->all()) ?  'required|string|in:admin,manager,customer' : '',
        ]);
        if ($input_validator->fails()) {
            return response()->json(['error'=>$input_validator->errors()], 400);
        }

        foreach ($request->all() as $key => $value) {

            $user->{$key} = $value;
        }

        $result = $user->save();
        if($result) {
            return response()->json(['response'=>'User updated succesfully'], 201);
        }
        return response()->json(['response'=>'Operation update failed'], 400);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $result = $user->delete();
        if($result) {
            return response()->json(['response'=>'Product deleted succesfully'], 200);
        }
        return response()->json(['response'=>'Operation delete failed'], 400);
    }


    /**
     * login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request){

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }

        if(Auth::attempt(['email' => request('email'), 'password' => request('password')])) {
            $user = Auth::user();
            $success['token'] =  $user->createToken('authToken')->accessToken;
            return response()->json(['success' => $success], $this->successStatus);
        }

        return response()->json(['error'=>'Unauthorised'], 401);

    }

    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:45',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'c_password' => 'required|same:password',
            'role' => 'required|string|in:admin,manager,customer',
        ]);
        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }
        $input = $request->all();
        $input['password'] = Hash::make($input['password']);
        $user = User::create($input);
        // $success['token'] =  $user->createToken('authToken')->accessToken;
        // $success['name'] =  $user->name;
        // return response()->json(['success'=>$success], $this->successStatus);

        $token = $user->createToken('authToken')->accessToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 201);
    }

    public function login_required() {
        return response()->json(['error'=>'Unauthorised'], 401);
    }

    public function logoutApi(Request $request){

        $user = Auth::user()->token();
        if($user) {
            $user->revoke();
            return response()->json([
                'message' => 'Successfully logged out'
            ]);
        }

        return response()->json([
            'error' => 'Something went wrong in api'
        ]);
    }
}
