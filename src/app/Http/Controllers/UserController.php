<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Util\PaginatorUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index($elementsPerPage, $actualPage, $searchField = "")
    {
        if (!is_numeric($elementsPerPage) || !is_numeric($actualPage)) {
            return response()->json(['error' => 'Invalid parameters'], 400);
        }
        $query = User::with(['role' => function ($query) {
            $query->select('id', 'name', 'code');
        }])
            ->orderby('created_at', 'desc');

        if ($searchField !== "") {
            $query = $query->where('name', 'like', "%{$searchField}")
                ->orWhere('email', 'like', "%{$searchField}");
        }
        $info = new \stdClass();
        $paginator          = PaginatorUtil::getPaginatorInfo($query, $elementsPerPage, $actualPage);
        $info->totalItems   = $paginator->totalItems;
        $info->totalPages   = $paginator->totalPages;
        $info->hasNext      = $paginator->hasNext;
        $info->hasPrevious  = $paginator->hasPrevious;
        $info->users        = $paginator->data;
        return response()->json($info, 200);
    }
    /**
     * store a newly created resource in storage.
     * @param  Request  $request
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), $this->rulesToStore());
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        if (isset($request->roleCode)) {
            $role = Role::where('code', $request->roleCode)->first(['id']);
            if (!$role) {
                return response()->json(['error' => 'Invalid role code'], 400);
            }
        } else {
            $role = Role::where('code', Role::DEFAULT_ROLE)->first(['id']);
        }
        $user           = new User();
        $user->name     = $request->name;
        $user->email    = $request->email;
        $user->password = bcrypt($request->password);
        $user->role_id  = $role->id;
        $user->save();
        return response()->json(["message" => "User save successfully", "user" => $user], 201);
    }
    /**
     * update user
     * @param Request $request: request data
     * @param int $id: user id
     */
    public function update(Request $request, $id)
    {
        //* const
        $role = null;
        $user = User::find($id);
        if (!$user) {
            return response()->json(["message" => "User not found"], 404);
        }
        $validator = Validator::make($request->all(), $this->rulesToUpdate($user->id));
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        if (isset($request->roleCode)) {
            $role = Role::where('code', $request->roleCode)->first(['id']);
            if (!$role) {
                return response()->json(['error' => 'Invalid role code'], 400);
            }
        }
        $user->name     = isset($request->name) ? $request->name : $user->name;
        $user->email    = isset($request->email) ? $request->email : $user->email;
        $user->password = isset($request->password) ? bcrypt($request->password) : $user->password;
        $user->role_id  = $role ? $role->id : $user->role_id;
        $user->save();
        return response()->json(["message" => "User update successfully", "user" => $user], 200);
    }
    /**
     * show user
     * @param int $id: identifier of user
     */
    public function show($id)
    {
        $user = User::where('id', $id)
            ->with(['role' => function ($query) {
                $query->select('id', 'name', 'code');
            }])->first();
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        return response()->json($user, 200);
    }
    /**
     * performs a logical deletion of a user by means of his or her identifier
     * @param int $id: the identifier of the user to be deleted
     */
    public function destroy(int $id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        $user->delete();
        return response()->json(['message' => 'User deleted successfully']);
    }
    /**
     * returns the rules for the store method
     */
    private function rulesToStore(): array
    {
        return [
            'name'      => 'required|string|max:255',
            'email'     => 'required|string|email|max:255|unique:users',
            'password'  => 'required|string|min:6|max:20',
            'roleCode'  => 'nullable|string|max:255',
        ];
    }
    /**
     * rules to be used when updating a user
     * @param int $id: the identifier of the user to be updated
     */
    private function rulesToUpdate(int $userId): array
    {
        return [
            'name'      => 'nullable|string|max:255',
            'email'     => 'nullable|string|email|max:255|unique:users,email,' . $userId,
            'password'  => 'nullable|string|min:6|max:20',
        ];
    }
}
