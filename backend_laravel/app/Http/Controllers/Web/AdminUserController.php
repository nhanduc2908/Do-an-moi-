<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;

class AdminUserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    /**
     * Danh sách users
     */
    public function index(Request $request)
    {
        $users = User::with('role')
            ->when($request->search, function($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
            })
            ->when($request->role_id, function($query, $roleId) {
                $query->where('role_id', $roleId);
            })
            ->when($request->status, function($query, $status) {
                $query->where('is_active', $status === 'active');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $roles = Role::all();

        return view('admin.users.index', compact('users', 'roles'));
    }

    /**
     * Form tạo user
     */
    public function create()
    {
        $roles = Role::where('is_active', true)->orderBy('level', 'desc')->get();

        return view('admin.users.create', compact('roles'));
    }

    /**
     * Lưu user mới
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
            'is_active' => true,
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully.');
    }

    /**
     * Chi tiết user
     */
    public function show($id)
    {
        $user = User::with('role', 'sessions', 'devices', 'loginHistory')->findOrFail($id);

        return view('admin.users.show', compact('user'));
    }

    /**
     * Form sửa user
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::where('is_active', true)->orderBy('level', 'desc')->get();

        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Cập nhật user
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'role_id' => 'required|exists:roles,id',
            'is_active' => 'nullable|boolean',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role_id' => $request->role_id,
            'is_active' => $request->is_active ?? false,
        ]);

        if ($request->filled('password')) {
            $request->validate([
                'password' => 'string|min:8|confirmed',
            ]);
            $user->update(['password' => Hash::make($request->password)]);
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    /**
     * Xóa user
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->id === auth()->id()) {
            return back()->with('error', 'Cannot delete your own account.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }

    /**
     * Khóa user
     */
    public function lock($id)
    {
        $user = User::findOrFail($id);
        $user->update(['is_locked' => true, 'locked_at' => now()]);

        return back()->with('success', 'User locked successfully.');
    }

    /**
     * Mở khóa user
     */
    public function unlock($id)
    {
        $user = User::findOrFail($id);
        $user->update(['is_locked' => false, 'locked_at' => null]);

        return back()->with('success', 'User unlocked successfully.');
    }

    /**
     * Import users
     */
    public function importForm()
    {
        return view('admin.users.import');
    }

    /**
     * Xử lý import
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,xlsx',
        ]);

        // Xử lý import
        // $import = new UserImport();
        // Excel::import($import, $request->file('file'));

        return redirect()->route('admin.users.index')
            ->with('success', 'Users imported successfully.');
    }

    /**
     * Export users
     */
    public function export(Request $request)
    {
        // Xử lý export
        // return Excel::download(new UserExport, 'users.xlsx');

        return back()->with('success', 'Export started.');
    }
}