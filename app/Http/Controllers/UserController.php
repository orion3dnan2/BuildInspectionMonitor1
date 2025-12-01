<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Log;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        Gate::authorize('viewAny', User::class);
        
        $users = User::query()
            ->when($request->search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%")
                    ->orWhere('office', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('settings.users.index', compact('users'));
    }

    public function create()
    {
        Gate::authorize('create', User::class);
        
        $roles = Role::orderBy('level', 'desc')->get();
        
        return view('settings.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        Gate::authorize('create', User::class);
        
        $roleIds = Role::pluck('id')->toArray();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'nullable|email|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'role_id' => 'required|in:' . implode(',', $roleIds),
            'system_access' => 'nullable|array',
            'system_access.*' => 'string|in:' . implode(',', array_keys(User::availableSystems())),
            'rank' => 'nullable|string|max:255',
            'office' => 'nullable|string|max:255',
        ]);

        $role = Role::find($validated['role_id']);
        $isAdmin = $role && $role->slug === 'admin';

        $permissions = $isAdmin 
            ? array_keys(User::availablePermissions()) 
            : [];

        $systemAccess = $isAdmin 
            ? array_keys(User::availableSystems()) 
            : ($validated['system_access'] ?? ['block_system']);

        $user = User::create([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'] ?? null,
            'password' => Hash::make($validated['password']),
            'role' => $role->slug,
            'permissions' => $permissions,
            'system_access' => $systemAccess,
            'rank' => $validated['rank'] ?? null,
            'office' => $validated['office'] ?? null,
        ]);

        $user->roles()->sync([$validated['role_id']]);

        Log::record('create_user', 'إنشاء مستخدم جديد: ' . $user->name);

        return redirect()
            ->route('settings.users.index')
            ->with('success', 'تم إنشاء المستخدم بنجاح');
    }

    public function show(User $user)
    {
        Gate::authorize('view', $user);
        
        return view('settings.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        Gate::authorize('update', $user);
        
        $roles = Role::orderBy('level', 'desc')->get();
        $userRoleIds = $user->roles->pluck('id')->toArray();
        
        return view('settings.users.edit', compact('user', 'roles', 'userRoleIds'));
    }

    public function update(Request $request, User $user)
    {
        Gate::authorize('update', $user);
        
        $roleIds = Role::pluck('id')->toArray();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
            'email' => ['nullable', 'email', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:6|confirmed',
            'role_id' => 'required|in:' . implode(',', $roleIds),
            'system_access' => 'nullable|array',
            'system_access.*' => 'string|in:' . implode(',', array_keys(User::availableSystems())),
            'rank' => 'nullable|string|max:255',
            'office' => 'nullable|string|max:255',
        ]);

        $role = Role::find($validated['role_id']);
        $isAdmin = $role && $role->slug === 'admin';

        $permissions = $isAdmin 
            ? array_keys(User::availablePermissions()) 
            : ($user->permissions ?? []);

        $systemAccess = $isAdmin 
            ? array_keys(User::availableSystems()) 
            : ($validated['system_access'] ?? ['block_system']);

        $data = [
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'] ?? null,
            'role' => $role->slug,
            'permissions' => $permissions,
            'system_access' => $systemAccess,
            'rank' => $validated['rank'] ?? null,
            'office' => $validated['office'] ?? null,
        ];

        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $user->update($data);
        $user->roles()->sync([$validated['role_id']]);

        Log::record('update_user', 'تعديل مستخدم: ' . $user->name);

        return redirect()
            ->route('settings.users.index')
            ->with('success', 'تم تحديث المستخدم بنجاح');
    }

    public function destroy(User $user)
    {
        Gate::authorize('delete', $user);
        
        if ($user->id === auth()->id()) {
            return back()->with('error', 'لا يمكنك حذف حسابك الخاص');
        }

        Log::record('delete_user', 'حذف مستخدم: ' . $user->name);
        $user->delete();

        return redirect()
            ->route('settings.users.index')
            ->with('success', 'تم حذف المستخدم بنجاح');
    }
}
