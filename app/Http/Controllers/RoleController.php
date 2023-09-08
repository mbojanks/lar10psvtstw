<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\RoleResource;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class RoleController extends Controller
{
    public function index()
    {
        Gate::authorize('view', 'roles');
        return RoleResource::collection(Role::all());
    }

    public function store(Request $request)
    {
        Gate::authorize('edit', 'roles');
        $role = Role::create($request->only('name'));
        //dodavanje i ovlascenja ukoliko ih ima u zahtevu
        if($permissions = $request->input('permissions')) {
            foreach($permissions as $permission_id) {
                DB::table('permission_role')->insert([
                    'role_id' => $role->id,
                    'permission_id' => $permission_id,
                ]);
            }
        }
        return response(new RoleResource($role), Response::HTTP_CREATED);
    }

    public function show($id)
    {
        Gate::authorize('view', 'roles');
        return new RoleResource(Role::find($id));
    }

    public function update(Request $request, $id)
    {
        Gate::authorize('edit', 'roles');
        $role = Role::find($id);
        $role->update($request->only('name'));
        //dodavanje i ovlascenja ukoliko ih ima u zahtevu, obrisu se postojeci i doda nova verzija
        if($permissions = $request->input('permissions')) {
            DB::table('permission_role')->where('role_id', $role->id)->delete();
            foreach($permissions as $permission_id) {
                DB::table('permission_role')->insert([
                    'role_id' => $role->id,
                    'permission_id' => $permission_id,
                ]);
            }
        }
        //dodavanje korisnika?
        return response(new RoleResource($role), Response::HTTP_ACCEPTED);
    }

    public function destroy($id)
    {
        Gate::authorize('edit', 'roles');
        // odrzavanje referencijalnog integriteta - podrazumeva se restrict: prvo obrisati zavisne podatke
        DB::table('permission_role')->where('role_id', $id)->delete();
        // ... a tek onda nezavisne
        Role::destroy($id);
        return response(null, Response::HTTP_NO_CONTENT);
    }
}
