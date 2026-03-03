<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    /**
     * Hiển thị bảng phân quyền
     */
    public function index()
    {
        $roles = Role::all();

        // Định nghĩa các module và permissions
        $modules = [
            'users' => [
                'name' => 'Quản Lý Người Dùng',
                'permissions' => [
                    'view' => 'Xem danh sách',
                    'create' => 'Tạo mới',
                    'edit' => 'Chỉnh sửa',
                    'delete' => 'Xóa',
                ]
            ],
            'roles' => [
                'name' => 'Quản Lý Vai Trò',
                'permissions' => [
                    'view' => 'Xem danh sách',
                    'create' => 'Tạo mới',
                    'edit' => 'Chỉnh sửa',
                    'delete' => 'Xóa',
                ]
            ],
            'farms' => [
                'name' => 'Quản Lý Trang Trại',
                'permissions' => [
                    'view' => 'Xem danh sách',
                    'create' => 'Tạo mới',
                    'edit' => 'Chỉnh sửa',
                    'delete' => 'Xóa',
                ]
            ],
            'greenhouses' => [
                'name' => 'Quản Lý Nhà Kính',
                'permissions' => [
                    'view' => 'Xem danh sách',
                    'create' => 'Tạo mới',
                    'edit' => 'Chỉnh sửa',
                    'delete' => 'Xóa',
                ]
            ],
            'products' => [
                'name' => 'Quản Lý Sản Phẩm',
                'permissions' => [
                    'view' => 'Xem danh sách',
                    'create' => 'Tạo mới',
                    'edit' => 'Chỉnh sửa',
                    'delete' => 'Xóa',
                ]
            ],
            'audits' => [
                'name' => 'Kiểm Soát Chất Lượng',
                'permissions' => [
                    'view_all' => 'Xem tất cả',
                    'view_own' => 'Xem của mình',
                    'create' => 'Tạo mới',
                    'edit_own' => 'Chỉnh sửa của mình',
                    'edit_all' => 'Chỉnh sửa của người khác',
                    'delete_own' => 'Xóa của mình',
                    'delete_all' => 'Xóa của người khác',
                ]
            ],
            'admin_panel' => [
                'name' => 'Admin Panel',
                'permissions' => [
                    'access' => 'Truy cập',
                    'view_dashboard' => 'Xem Dashboard',
                ]
            ],
        ];

        // Định nghĩa quyền cho từng role
        $rolePermissions = [
            'admin' => [
                'users' => ['view', 'create', 'edit', 'delete'],
                'roles' => ['view', 'create', 'edit', 'delete'],
                'farms' => ['view', 'create', 'edit', 'delete'],
                'greenhouses' => ['view', 'create', 'edit', 'delete'],
                'products' => ['view', 'create', 'edit', 'delete'],
                'audits' => ['view_all', 'view_own', 'create', 'edit_own', 'edit_all', 'delete_own', 'delete_all'],
                'admin_panel' => ['access', 'view_dashboard'],
            ],
            'moderator' => [
                'users' => [],
                'roles' => [],
                'farms' => ['view', 'create', 'edit'],
                'greenhouses' => ['view', 'create', 'edit'],
                'products' => ['view', 'create', 'edit'],
                'audits' => ['view_all', 'view_own', 'create', 'edit_own', 'edit_all', 'delete_own'],
                'admin_panel' => [],
            ],
            'user' => [
                'users' => [],
                'roles' => [],
                'farms' => [],
                'greenhouses' => [],
                'products' => [],
                'audits' => ['view_own', 'create', 'edit_own', 'delete_own'],
                'admin_panel' => [],
            ],
        ];

        return view('admin.permissions.index', compact('roles', 'modules', 'rolePermissions'));
    }

    /**
     * Hiển thị form chỉnh sửa phân quyền cho một role
     */
    public function edit(Role $role)
    {
        $modules = $this->getModules();
        $rolePermissions = $this->getRolePermissions();

        return view('admin.permissions.edit', compact('role', 'modules', 'rolePermissions'));
    }

    /**
     * Cập nhật phân quyền cho role
     */
    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'permissions' => 'nullable|array',
            'permissions.*' => 'string',
        ]);

        // Lưu permissions vào JSON column (nếu có)
        // Hoặc lưu vào mảng associative
        $permissions = [];
        $modules = $this->getModules();

        foreach ($modules as $moduleKey => $module) {
            $permissions[$moduleKey] = [];
            foreach ($module['permissions'] as $permKey => $permName) {
                $permissionKey = "{$moduleKey}.{$permKey}";
                if (isset($validated['permissions']) && in_array($permissionKey, $validated['permissions'])) {
                    $permissions[$moduleKey][] = $permKey;
                }
            }
        }

        // Lưu vào session hoặc database
        // Cách 1: Lưu tạm thời vào session
        session()->put("role_permissions.{$role->id}", $permissions);

        return redirect()->route('admin.permissions.index')
            ->with('success', "Cập nhật phân quyền cho role '{$role->display_name}' thành công!");
    }

    /**
     * Lấy danh sách các module và permissions
     */
    private function getModules()
    {
        return [
            'users' => [
                'name' => 'Quản Lý Người Dùng',
                'permissions' => [
                    'view' => 'Xem danh sách',
                    'create' => 'Tạo mới',
                    'edit' => 'Chỉnh sửa',
                    'delete' => 'Xóa',
                ]
            ],
            'roles' => [
                'name' => 'Quản Lý Vai Trò',
                'permissions' => [
                    'view' => 'Xem danh sách',
                    'create' => 'Tạo mới',
                    'edit' => 'Chỉnh sửa',
                    'delete' => 'Xóa',
                ]
            ],
            'farms' => [
                'name' => 'Quản Lý Trang Trại',
                'permissions' => [
                    'view' => 'Xem danh sách',
                    'create' => 'Tạo mới',
                    'edit' => 'Chỉnh sửa',
                    'delete' => 'Xóa',
                ]
            ],
            'greenhouses' => [
                'name' => 'Quản Lý Nhà Kính',
                'permissions' => [
                    'view' => 'Xem danh sách',
                    'create' => 'Tạo mới',
                    'edit' => 'Chỉnh sửa',
                    'delete' => 'Xóa',
                ]
            ],
            'products' => [
                'name' => 'Quản Lý Sản Phẩm',
                'permissions' => [
                    'view' => 'Xem danh sách',
                    'create' => 'Tạo mới',
                    'edit' => 'Chỉnh sửa',
                    'delete' => 'Xóa',
                ]
            ],
            'audits' => [
                'name' => 'Kiểm Soát Chất Lượng',
                'permissions' => [
                    'view_all' => 'Xem tất cả',
                    'view_own' => 'Xem của mình',
                    'create' => 'Tạo mới',
                    'edit_own' => 'Chỉnh sửa của mình',
                    'edit_all' => 'Chỉnh sửa của người khác',
                    'delete_own' => 'Xóa của mình',
                    'delete_all' => 'Xóa của người khác',
                ]
            ],
            'admin_panel' => [
                'name' => 'Admin Panel',
                'permissions' => [
                    'access' => 'Truy cập',
                    'view_dashboard' => 'Xem Dashboard',
                ]
            ],
        ];
    }

    /**
     * Lấy danh sách quyền của từng role
     */


    /**
     * Helper: Lấy danh sách modules và permissions
     */

    /**
     * Helper: Lấy danh sách quyền cho từng role
     */
    private function getRolePermissions()
    {
        return [
            'admin' => [
                'users' => ['view', 'create', 'edit', 'delete'],
                'roles' => ['view', 'create', 'edit', 'delete'],
                'farms' => ['view', 'create', 'edit', 'delete'],
                'greenhouses' => ['view', 'create', 'edit', 'delete'],
                'products' => ['view', 'create', 'edit', 'delete'],
                'audits' => ['view_all', 'view_own', 'create', 'edit_own', 'edit_all', 'delete_own', 'delete_all'],
                'admin_panel' => ['access', 'view_dashboard'],
            ],
            'moderator' => [
                'users' => [],
                'roles' => [],
                'farms' => ['view', 'create', 'edit'],
                'greenhouses' => ['view', 'create', 'edit'],
                'products' => ['view', 'create', 'edit'],
                'audits' => ['view_all', 'view_own', 'create', 'edit_own', 'edit_all', 'delete_own'],
                'admin_panel' => [],
            ],
            'user' => [
                'users' => [],
                'roles' => [],
                'farms' => [],
                'greenhouses' => [],
                'products' => [],
                'audits' => ['view_own', 'create', 'edit_own', 'delete_own'],
                'admin_panel' => [],
            ],
        ];
    }
}
