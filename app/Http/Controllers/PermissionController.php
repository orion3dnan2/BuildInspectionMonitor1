<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function index()
    {
        $roles = Role::with('permissions')->get();
        $permissions = Permission::orderBy('module')->orderBy('sort_order')->get();
        $modules = Permission::getModules();
        
        $groupedPermissions = $permissions->groupBy('module');

        return view('settings.permissions.index', compact('roles', 'permissions', 'groupedPermissions', 'modules'));
    }

    public function roles()
    {
        $roles = Role::withCount(['permissions', 'users'])->get();
        
        return view('settings.permissions.roles', compact('roles'));
    }

    public function createRole()
    {
        $permissions = Permission::orderBy('module')->orderBy('sort_order')->get();
        $groupedPermissions = $permissions->groupBy('module');
        $modules = Permission::getModules();

        return view('settings.permissions.create-role', compact('permissions', 'groupedPermissions', 'modules'));
    }

    public function storeRole(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:roles,slug',
            'description' => 'nullable|string|max:500',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role = Role::create([
            'name' => $validated['name'],
            'slug' => $validated['slug'],
            'description' => $validated['description'] ?? null,
            'is_system' => false,
        ]);

        if (!empty($validated['permissions'])) {
            $role->syncPermissions($validated['permissions']);
        }

        return redirect()->route('settings.permissions.roles')
            ->with('success', 'تم إنشاء الدور بنجاح');
    }

    public function editRole(Role $role)
    {
        $permissions = Permission::orderBy('module')->orderBy('sort_order')->get();
        $groupedPermissions = $permissions->groupBy('module');
        $modules = Permission::getModules();
        $rolePermissionIds = $role->permissions->pluck('id')->toArray();

        return view('settings.permissions.edit-role', compact('role', 'permissions', 'groupedPermissions', 'modules', 'rolePermissionIds'));
    }

    public function updateRole(Request $request, Role $role)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:roles,slug,' . $role->id,
            'description' => 'nullable|string|max:500',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role->update([
            'name' => $validated['name'],
            'slug' => $validated['slug'],
            'description' => $validated['description'] ?? null,
        ]);

        $role->syncPermissions($validated['permissions'] ?? []);

        return redirect()->route('settings.permissions.roles')
            ->with('success', 'تم تحديث الدور بنجاح');
    }

    public function destroyRole(Role $role)
    {
        if ($role->is_system) {
            return back()->with('error', 'لا يمكن حذف الأدوار الأساسية');
        }

        $role->delete();

        return redirect()->route('settings.permissions.roles')
            ->with('success', 'تم حذف الدور بنجاح');
    }

    public function userPermissions(User $user)
    {
        $roles = Role::all();
        $permissions = Permission::orderBy('module')->orderBy('sort_order')->get();
        $groupedPermissions = $permissions->groupBy('module');
        $modules = Permission::getModules();
        
        $userRoleIds = $user->roles->pluck('id')->toArray();
        $userPermissions = $user->userPermissions()->get();
        $userPermissionData = [];
        
        foreach ($userPermissions as $permission) {
            $userPermissionData[$permission->id] = $permission->pivot->granted;
        }

        return view('settings.permissions.user-permissions', compact(
            'user', 'roles', 'permissions', 'groupedPermissions', 'modules',
            'userRoleIds', 'userPermissionData'
        ));
    }

    public function updateUserPermissions(Request $request, User $user)
    {
        $validated = $request->validate([
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,id',
            'permissions' => 'nullable|array',
        ]);

        $user->syncRoles($validated['roles'] ?? []);

        $user->userPermissions()->detach();
        
        if (!empty($validated['permissions'])) {
            foreach ($validated['permissions'] as $permissionId => $status) {
                if ($status === 'granted') {
                    $user->userPermissions()->attach($permissionId, ['granted' => true]);
                } elseif ($status === 'denied') {
                    $user->userPermissions()->attach($permissionId, ['granted' => false]);
                }
            }
        }

        return redirect()->route('settings.users.show', $user)
            ->with('success', 'تم تحديث صلاحيات المستخدم بنجاح');
    }

    public function syncPermissions()
    {
        Permission::syncPermissions();
        Role::syncDefaultRoles();
        Role::assignDefaultPermissions();

        return back()->with('success', 'تم مزامنة الصلاحيات بنجاح');
    }
}
