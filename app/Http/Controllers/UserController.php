<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ActivityLog;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(User::class, 'user');
    }

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

        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(StoreUserRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'rank' => $request->rank,
            'office' => $request->office,
        ]);

        ActivityLog::log(
            'create',
            'User',
            $user->id,
            'إنشاء مستخدم جديد: ' . $user->name,
            null,
            $user->makeHidden(['password', 'remember_token'])->toArray()
        );

        return redirect()
            ->route('users.index')
            ->with('success', 'تم إنشاء المستخدم بنجاح');
    }

    public function show(User $user)
    {
        $user->load('reports');
        return view('users.show', compact('user'));
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $oldValues = $user->makeHidden(['password', 'remember_token'])->toArray();

        $data = [
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'role' => $request->role,
            'rank' => $request->rank,
            'office' => $request->office,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        ActivityLog::log(
            'update',
            'User',
            $user->id,
            'تعديل مستخدم: ' . $user->name,
            $oldValues,
            $user->fresh()->makeHidden(['password', 'remember_token'])->toArray()
        );

        return redirect()
            ->route('users.index')
            ->with('success', 'تم تحديث المستخدم بنجاح');
    }

    public function destroy(User $user)
    {
        ActivityLog::log(
            'delete',
            'User',
            $user->id,
            'حذف مستخدم: ' . $user->name,
            $user->makeHidden(['password', 'remember_token'])->toArray(),
            null
        );

        $user->delete();

        return redirect()
            ->route('users.index')
            ->with('success', 'تم حذف المستخدم بنجاح');
    }
}
