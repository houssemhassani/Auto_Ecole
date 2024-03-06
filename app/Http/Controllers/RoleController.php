<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::orderBy("id", "DESC")->get();
        $data['roles'] = $roles;
        return response()->json($data, 200);
    }
    public function create()
    {
        $permission = Permission::get();
        $data['permissions']= $permission;
        return response()->json($data, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required',
                'permission' => 'required'
            ]);
            $role = Role::create(['name' => $request->name,'guard_name'=>'web']);
            $role->syncPermissions($request->permission);
            return $this->sendResponse(null, 'Successfully', 200);
        } catch (Exception $e) {
            return $this->sendError(null, 'Error', $e->getMessage(), 422);
            //throw $th;
        }
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $role = Role::find($id);
        $rolePermissions = Permission::join(
            "role_has_permissions",
            "role_has_permissions.permission_id",
            "=",
            "permissions.id"
        )->where(
                "role_has_permissions.role_id",
                $id
            )->get();
        $data['roles'] = $role;
        $data['permissions'] = $rolePermissions;
        return $this->sendResponse($data, 'Success', 200);

        //
    }
    public function edit(string $id)
    {
        $role = Role::find($id);
        $permission = Permission::get();
        $rolePermissions = DB::table("role_has_permissions")
            ->where("role_has_permissions.role_id", $id)
            ->pluck(
                "role_has_permissions.permission_id",
                "role_has_permissions.permission_id"
            )
            ->all();
        $data['role'] = $role;
        $data['permission'] = $permission;
        $data['rolePermission'] = $rolePermissions;
        return response()->json($data, 200);
        //
    }
    


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required',
            'permission' => 'required'
        ]);
        $role = Role::find($id);
        $role->name = $request->name;
        $role->save();
        $role->syncPermissions($request->permission);
        return response()->json(null, 200);
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $role = Role::find($id);
        if ($role) {
            $role->delete();
            return $this->sendResponse(null, "Success", 200);

        }
        return $this->sendError(null, "Error delete", 404);

        //
    }
}
