<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->api(200 , 'all users' , UserResource::collection(User::all()));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        $user = $request->all();
        if($request->hasFile('avatar')){
            $user['avatar'] = Str::after($request->file('avatar')->storePublicly('/public'),'public/');
        }
        $user = User::create($user);
        return response()->api(200 , 'saved successfully', new UserResource($user));
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return response()->api(200 , 'user\'s info', new UserResource($user));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, User $user)
    {
        $userData = $request->all();
        if($request->hasFile('avatar')){
            $userData['avatar'] = Str::after($request->file('avatar')->storePublicly('/public'),'public/');
            Storage::disk('public')->delete($user->avatar);
        }

        $user->fill($userData)->update();
        return response()->api(200 , 'updated successfully', new UserResource($user));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();
        return response()->api(200 , 'deleted successfully', new UserResource($user));
    }
}
