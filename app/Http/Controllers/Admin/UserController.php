<?php

namespace App\Http\Controllers\Admin;

use App\Exports\UsersTemplateExport;
use App\Http\Controllers\Controller;
use App\Imports\UsersImport;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('role');

        if ($request->filled('search')) {
            $search = $request->search;
            $searchTerm = "%{$search}%";

            $query->where(function ($q) use ($searchTerm) {
                $q->where('user_code', 'like', $searchTerm)
                    ->orWhere('fullname', 'like', $searchTerm)
                    ->orWhere('email', 'like', $searchTerm);
            });
        }

        if ($request->filled('role_id')) {
            $query->where('role_id', $request->role_id);
        }

        $users = $query->paginate(15);
        $roles = Role::all();

        return view('admin.users.index', compact('users', 'roles'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'fullname' => 'required|string|max:255',
            'user_code' => 'required|string|max:255|unique:users',
            'email' => 'required|email:rfc|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'role_id' => 'required|exists:roles,id',
        ]);

        $validated['password'] = bcrypt($validated['password']);
        User::create($validated);

        return redirect()->route('admin.users.index')->with('success', 'Tạo người dùng thành công');
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'fullname' => 'required|string|max:255',
            'user_code' => 'required|string|max:255|unique:users,user_code,' . $user->id,
            'email' => 'required|email:rfc|unique:users,email,' . $user->id,
            'role_id' => 'required|exists:roles,id',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('admin.users.index')->with('success', 'Cập nhật người dùng thành công');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls|max:10240',
        ]);

        try {
            $import = new UsersImport();
            Excel::import($import, $request->file('file'));

            $stats = $import->getStats();

            return redirect()->back()->with(
                'success',
                "Import thành công! Tạo mới: {$stats['created']}, Cập nhật: {$stats['updated']}, Tổng: {$stats['total']} người dùng"
            );
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'Import thất bại: ' . $e->getMessage());
        }
    }

    public function downloadTemplate()
    {
        return Excel::download(new UsersTemplateExport(), 'users_template.xlsx');
    }

    public function bulkUpdateRole(Request $request)
    {
        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
            'role_id' => 'required|exists:roles,id',
        ]);

        $count = User::whereIn('id', $request->user_ids)->update(['role_id' => $request->role_id]);

        return redirect()->route('admin.users.index')->with('success', "Đã cập nhật vai trò cho {$count} người dùng");
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
        ]);

        $count = User::whereIn('id', $request->user_ids)->delete();

        return redirect()->route('admin.users.index')->with('success', "Đã xóa {$count} người dùng");
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Xóa người dùng thành công');
    }
}
