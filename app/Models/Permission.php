<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Permission extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'key',
        'module',
        'description',
        'group',
        'sort_order',
    ];

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_permissions')
            ->withTimestamps();
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_permissions')
            ->withPivot('granted')
            ->withTimestamps();
    }

    public static function getModules(): array
    {
        return [
            'dashboard' => 'لوحة التحكم',
            'data_entry' => 'إدخال البيانات',
            'records' => 'السجلات',
            'search' => 'البحث والاستعلام',
            'reports' => 'التقارير والإحصائيات',
            'import' => 'الاستيراد',
            'books' => 'دفتر القيد',
            'settings' => 'الإعدادات',
            'users' => 'المستخدمين',
            'stations' => 'المخافر',
            'ports' => 'المنافذ',
            'departments' => 'الأقسام',
            'employees' => 'الموظفين',
            'attendances' => 'الحضور والانصراف',
            'leave_requests' => 'طلبات الإجازات',
            'documents' => 'المراسلات الداخلية',
            'correspondences' => 'الوارد والصادر',
            'permissions' => 'الصلاحيات',
        ];
    }

    public static function getActions(): array
    {
        return [
            'view' => 'عرض',
            'create' => 'إضافة',
            'update' => 'تعديل',
            'delete' => 'حذف',
            'approve' => 'اعتماد',
            'reject' => 'رفض',
            'import' => 'استيراد',
            'export' => 'تصدير',
            'print' => 'طباعة',
            'manage' => 'إدارة',
        ];
    }

    public static function generateAllPermissions(): array
    {
        $permissions = [];
        $modules = self::getModules();
        $actions = self::getActions();
        $sortOrder = 0;

        foreach ($modules as $moduleKey => $moduleName) {
            $moduleActions = self::getModuleActions($moduleKey);
            
            foreach ($moduleActions as $actionKey => $actionName) {
                $permissions[] = [
                    'name' => $actionName . ' ' . $moduleName,
                    'key' => $moduleKey . '.' . $actionKey,
                    'module' => $moduleKey,
                    'description' => 'صلاحية ' . $actionName . ' في ' . $moduleName,
                    'group' => $moduleName,
                    'sort_order' => $sortOrder++,
                ];
            }
        }

        return $permissions;
    }

    public static function getModuleActions(string $module): array
    {
        $allActions = self::getActions();
        
        $moduleSpecificActions = [
            'dashboard' => ['view'],
            'data_entry' => ['view', 'create', 'update', 'delete'],
            'records' => ['view', 'create', 'update', 'delete', 'print'],
            'search' => ['view'],
            'reports' => ['view', 'print', 'export'],
            'import' => ['view', 'import'],
            'books' => ['view', 'create', 'update', 'delete', 'approve', 'reject', 'print'],
            'settings' => ['view', 'manage'],
            'users' => ['view', 'create', 'update', 'delete', 'manage'],
            'stations' => ['view', 'create', 'update', 'delete'],
            'ports' => ['view', 'create', 'update', 'delete'],
            'departments' => ['view', 'create', 'update', 'delete'],
            'employees' => ['view', 'create', 'update', 'delete'],
            'attendances' => ['view', 'create', 'update', 'delete', 'import'],
            'leave_requests' => ['view', 'create', 'update', 'delete', 'approve', 'reject'],
            'documents' => ['view', 'create', 'update', 'delete', 'approve', 'reject', 'print'],
            'correspondences' => ['view', 'create', 'update', 'delete', 'import', 'print'],
            'permissions' => ['view', 'manage'],
        ];

        $actions = $moduleSpecificActions[$module] ?? ['view'];
        $result = [];
        
        foreach ($actions as $action) {
            if (isset($allActions[$action])) {
                $result[$action] = $allActions[$action];
            }
        }

        return $result;
    }

    public static function syncPermissions(): void
    {
        $permissions = self::generateAllPermissions();
        
        foreach ($permissions as $permission) {
            self::updateOrCreate(
                ['key' => $permission['key']],
                $permission
            );
        }
    }
}
