<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
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
        $roles = User::availableRoles();
        return view('settings.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|in:admin,inspector',
            'rank' => 'nullable|string|max:255',
            'office' => 'nullable|string|max:255',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'rank' => $validated['rank'] ?? null,
            'office' => $validated['office'] ?? null,
        ]);

        Log::record('create_user', 'إنشاء مستخدم جديد: ' . $user->name);

        return redirect()
            ->route('settings.users.index')
            ->with('success', 'تم إنشاء المستخدم بنجاح');
    }

    public function show(User $user)
    {
        return view('settings.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $roles = User::availableRoles();
        return view('settings.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:6|confirmed',
            'role' => 'required|in:admin,inspector',
            'rank' => 'nullable|string|max:255',
            'office' => 'nullable|string|max:255',
        ]);

        $data = [
            'name' => $validated['name'],
            'username' => $validated['username'],
            'role' => $validated['role'],
            'rank' => $validated['rank'] ?? null,
            'office' => $validated['office'] ?? null,
        ];

        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $user->update($data);

        Log::record('update_user', 'تعديل مستخدم: ' . $user->name);

        return redirect()
            ->route('settings.users.index')
            ->with('success', 'تم تحديث المستخدم بنجاح');
    }

    public function destroy(User $user)
    {
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
