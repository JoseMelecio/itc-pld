<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\Permission;
use App\Models\User;
use App\Services\MenuBuilderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): \Inertia\Response
    {
        $users = User::orderBy('last_name')->get();

        return Inertia::render('user/Index', [
            'users' => UserResource::collection($users),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): \Inertia\Response
    {
        $permissions = MenuBuilderService::allPermissionsTable();

        return Inertia::render('user/Create', [
            'permissions' => $permissions
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserCreateRequest $request): \Illuminate\Http\RedirectResponse
    {
        $data = $request->validated();
        $newUser = User::create($data);

        $newPermissions = [];
        foreach ($data['permissions'] as $permission) {
            if ($permission['selected']) {
                $newPermissions[] = $permission['id'];
            }
        }

        $newUser->permissions()->sync($newPermissions);

        return redirect()->route('users.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user): \Inertia\Response
    {
        $permissionsTable = MenuBuilderService::allPermissionsTable($user->getIdPermissions());
        return Inertia::render('user/Create', [
            'user' => $user,
            'permissions' => $permissionsTable
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserUpdateRequest $request, User $user): \Illuminate\Http\RedirectResponse
    {
        $data = $request->validated();
        $user->update($data);
        $newPermissions = [];

        if ($user->user_type != 'admin') {
            foreach ($data['permissions'] as $permission) {
                if ($permission['selected']) {
                    $newPermissions[] = $permission['id'];
                }
            }
        } else {
            $newPermissions = Permission::all()->select('id')->pluck('id')->toArray();
        }

        $user->syncPermissions($newPermissions);

        return redirect()->route('users.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
