<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
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
        if ($request->ajax()) {

            $users = User::with('roles')->get();

            return DataTables::of($users)
                    ->addIndexColumn()
                    ->addColumn('action', function($user){
                        $btn = '<div class="dropdown">
                                    <button class="btn btn-secondary btn-sm cs_bg-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Action
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li data-user-id="'. $user->id .'" class="dropdown-item cs_cursor-pointer" id="'. $user->id .'">View</li>
                                        <li data-user-id="'. $user->id .'" class="editUserBtn dropdown-item cs_cursor-pointer" id="'. $user->id .'">Edit</li>
                                        <li data-user-id="'. $user->id .'" class="deleteUserBtn dropdown-item cs_cursor-pointer" id="'. $user->id .'">Delete</li>
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
        return view('users.index');
    }


    public function store(StoreUserRequest $request)
    {
        $validated = $request->validated();
        
        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('message', 'User created successfully');
    }


    public function show(User $user)
    {
        $this->authorize('view', Auth::user());
        return response()->json($user);
    }


    public function update(UpdateUserRequest $request)
    {
        $user = User::find($request->id);

        $this->authorize('update', User::class);

        $validated = $request->validated();
        // return response()->json(['message' => $validated]);

        $user->update($validated);

        return redirect()->back()->with('message', 'User updated successfully!');
    }


    public function destroy(Request $request)
    {
        $user = User::find($request->id);

        $this->authorize($user);

        $user->delete();

        return back();
    }
}
