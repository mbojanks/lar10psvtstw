<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserUpdateRequest;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
   public function index()
   {
       // return User::all();
       Gate::authorize('view', 'users');
       $user = User::paginate(8);
       return UserResource::collection($user);
   }

   /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
   public function store(UserCreateRequest $request)
   {
       Gate::authorize('edit', 'users');
       $imgurl = $this->uploadFiles($request);
       $user = User::create($request->only('first_name', 'last_name', 'email') + [
           'password' => Hash::make(1234),
       ] + $imgurl);

       return response(new UserResource($user), Response::HTTP_CREATED);
   }

   /**
    * Display the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
   public function show($id)
   {
       Gate::authorize('view', 'users');
       $user = User::find($id);
       return new UserResource($user);
   }

   /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
   public function update(UserUpdateRequest $request, $id)
   {
       Gate::authorize('edit', 'users');
       $user = User::find($id);
       $user->update($request->only('first_name','last_name','email','user_img'));
       return response(new UserResource($user), Response::HTTP_ACCEPTED);
   }

   public function updateRoles(Request $request, $id) {
       Gate::authorize('edit', 'users'); //roles?
       Gate::authorize('edit', 'roles');

       $user = User::find($id);
       $roles= $request->input('role_id'); //array!
       $user->roles()->attach($roles);
       /*$role = Role::find($roleid);
       $user->role()->associate($role);

       $user->save(); */

       return response(new UserResource($user), Response::HTTP_ACCEPTED);
   }

   /**
    * Remove the specified resource from storage.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
   public function destroy($id)
   {
       Gate::authorize('edit', 'users');
       // delete all roles
       $user = User::find($id);
       if ($user) {
           $user->roles()->detach();
           // ... and then the user can be deleted
           User::destroy($id);
       }
       return response(null, Response::HTTP_NO_CONTENT);
   }
}
