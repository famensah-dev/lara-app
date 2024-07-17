<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', User::class);
        $roles = Role::all();

        if ($request->ajax()) {

            $users = User::with('roles')->get();

            return DataTables::of($users)
                    ->addIndexColumn()
                    ->addColumn('action', function($user){
                        $btn = '<div class="dropdown cs_dropdown">
                                    <button class="btn btn-sm cs_btn-primary-light dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Action
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li data-user-id="'. $user->id .'" class="dropdown-item cs_hover-pointer" id="'. $user->id .'"><i class="uil uil-eye"></i> View</li>
                                        <li data-user-id="'. $user->id .'" class="editUserBtn dropdown-item  cs_hover-pointer" id="'. $user->id .'"><i class="uil uil-edit-alt"></i> Edit User</li>
                                        <li data-user-id="'. $user->id .'" class="editUserPasswordBtn dropdown-item  cs_hover-pointer" id="'. $user->id .'"><i class="uil uil-unlock-alt"></i> Change Password</li>
                                        <li data-user-id="'. $user->id .'" class="editUserRoleBtn dropdown-item  cs_hover-pointer" id="'. $user->id .'"><i class="uil uil-user-plus"></i> Edit Role</li>
                                        <li data-user-id="'. $user->id .'" class="deleteUserBtn dropdown-item  cs_hover-pointer" id="'. $user->id .'"><i class="uil uil-trash-alt"></i> Delete</li>
                                    </ul>
                                </div>';
                            return $btn;
                    })
                    ->addColumn('roles', function (User $user) {
                        return $user->roles->pluck('name')->implode(', ');
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }
        return view('users.index', compact('roles'));
    }


    public function store(StoreUserRequest $request)
    {
        // $this->authorize('create', User::class);

        $validated = $request->validated();
        
        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        return response()->json(['message' => 'User created successfully'], 200);
    }


    public function show(User $user)
    {
        $this->authorize('view', $user);
        // $this->authorize('view', User::class);
        return response()->json($user);
    }


    public function getRoles(User $user)
    {
        $this->authorize('view', $user);
    
        $user->load('roles');
        
        return response()->json($user);
    }


    public function update(UpdateUserRequest $request)
    {
        $user = User::find($request->id);

        $this->authorize('update', User::class);

        $validated = $request->validated();
        // return response()->json(['message' => $validated]);

        $user->update($validated);

        return response()->json(['message' => 'User updated successfully!'], 200);
    }


    public function destroy(Request $request)
    {
        $user = User::find($request->id);

        $this->authorize($user);

        $user->delete();

        return back();
    }


    public function updateUserPassword(Request $request)
    {
        $user = User::find($request->id);

        $this->authorize('update', $user);

        $validated = $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user->password = Hash::make($validated['password']);
        $user->save(['password']);

        return redirect()->back()->with('message', 'Password updated successfully!');
    }


    // public function getUserRoles(User $user)
    // {
    //     $roles = Role::all();
    //     // $userRoles = $user->roles->pluck('id')->toArray();

    //     // return response()->json([
    //     //     'roles' => $roles,
    //     //     'userRoles' => $userRoles,
    //     // ]);

    //     return response()->json($user);
    // }


    public function updateUserRoles(Request $request)
    {
        $user = User::find($request->id);

        $this->authorize('update', $user);

        $validated = $request->validate([
            'roles' => 'array',
            'roles.*' => 'exists:roles,id',
        ]);

        $user->roles()->sync($validated['roles']);

        return response()->json(['message' => 'User roles updated successfully!'], 200);
    }

}
