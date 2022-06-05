<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Util\PaginatorUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    public function index($elementsPerPage, $actualPage, $searchField = "")
    {
        if (!is_numeric($elementsPerPage) || !is_numeric($actualPage)) {
            return response()->json(['error' => 'Invalid parameters'], 400);
        }
        $query = Role::orderby('created_at', 'desc');

        if ($searchField !== "") {
            $query = $query->where('name', 'like', "%{$searchField}")
                ->orWhere('code', 'like', "%{$searchField}");
        }
        $info = new \stdClass();
        $paginator          = PaginatorUtil::getPaginatorInfo($query, $elementsPerPage, $actualPage);
        $info->totalItems   = $paginator->totalItems;
        $info->totalPages   = $paginator->totalPages;
        $info->hasNext      = $paginator->hasNext;
        $info->hasPrevious  = $paginator->hasPrevious;
        $info->roles        = $paginator->data;
        return response()->json($info, 200);
    }
    public function show($id)
    {
        $role = Role::find($id);
        if (!$role) {
            return response()->json(['error' => 'Role not found'], 404);
        }
        return response()->json($role, 200);
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), $this->rulesToStore());
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        $role = new Role();
        $role->name         = $request->name;
        $role->code         = $request->code;
        $role->description  = $request->description;
        $role->save();
        return response()->json($role, 201);
    }
    public function update(Request $request, $id)
    {
        $role = Role::find($id);
        if (!$role) {
            return response()->json(['error' => 'Role not found'], 404);
        }
        $validator = Validator::make($request->all(), $this->rulesToUpdate($id));
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        $role->name         = isset($request->name) ? $request->name : $role->name;
        $role->code         = isset($request->code) ? $request->code : $role->code;
        $role->description  = isset($request->description) ? $request->description : $role->description;
        $role->save();
        return response()->json($role, 200);
    }
    public function destroy($id)
    {
        $role = Role::find($id);
        if (!$role) {
            return response()->json(['error' => 'Role not found'], 404);
        }
        $role->delete();
        return response()->json(["message" => "Role deleted successfully"], 200);
    }
    private function rulesToStore()
    {
        return [
            'name'          => 'required|string|max:255',
            'code'          => 'required|string|max:255|unique:roles',
            'description'   => 'required|string|max:255',
        ];
    }
    public function rulesToUpdate($roleId)
    {
        return [
            'name'          => 'nullable|string|max:255',
            'code'          => 'nullable|string|max:255|unique:roles,code,' . $roleId,
            'description'   => 'nullable|string|max:255',
        ];
    }
}
