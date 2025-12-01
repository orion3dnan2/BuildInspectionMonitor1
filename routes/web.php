<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RecordController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StationController;
use App\Http\Controllers\PortController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\AttendanceController;
use App\Http\Controllers\Admin\LeaveRequestController;
use App\Http\Controllers\Admin\DocumentController;
use App\Http\Controllers\Admin\CorrespondenceController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [LoginController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [LoginController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/counts', [NotificationController::class, 'counts'])->name('notifications.counts');
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
    
    Route::get('/records', [RecordController::class, 'index'])->name('records.index')->middleware('permission:records.view,data_entry.view');
    Route::get('/records/create', [RecordController::class, 'create'])->name('records.create')->middleware('permission:records.create,data_entry.create');
    Route::post('/records', [RecordController::class, 'store'])->name('records.store')->middleware('permission:records.create,data_entry.create');
    Route::get('/records/{record}', [RecordController::class, 'show'])->name('records.show')->middleware('permission:records.view,data_entry.view');
    Route::get('/records/{record}/edit', [RecordController::class, 'edit'])->name('records.edit')->middleware('permission:records.update,data_entry.update');
    Route::put('/records/{record}', [RecordController::class, 'update'])->name('records.update')->middleware('permission:records.update,data_entry.update');
    Route::delete('/records/{record}', [RecordController::class, 'destroy'])->name('records.destroy')->middleware('permission:records.delete,data_entry.delete');
    
    Route::get('/search', [SearchController::class, 'index'])->name('search.index')->middleware('permission:search.view');
    Route::get('/search/{record}', [SearchController::class, 'show'])->name('search.show')->middleware('permission:search.view');
    
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index')->middleware('permission:reports.view');
    Route::get('/reports/print', [ReportController::class, 'print'])->name('reports.print')->middleware('permission:reports.print');
    
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index')->middleware('permission:settings.view,settings.manage');
    
    Route::get('/settings/stations', [StationController::class, 'index'])->name('settings.stations.index')->middleware('permission:stations.view');
    Route::get('/settings/stations/create', [StationController::class, 'create'])->name('settings.stations.create')->middleware('permission:stations.create');
    Route::post('/settings/stations', [StationController::class, 'store'])->name('settings.stations.store')->middleware('permission:stations.create');
    Route::get('/settings/stations/{station}/edit', [StationController::class, 'edit'])->name('settings.stations.edit')->middleware('permission:stations.update');
    Route::put('/settings/stations/{station}', [StationController::class, 'update'])->name('settings.stations.update')->middleware('permission:stations.update');
    Route::delete('/settings/stations/{station}', [StationController::class, 'destroy'])->name('settings.stations.destroy')->middleware('permission:stations.delete');
    
    Route::get('/settings/ports', [PortController::class, 'index'])->name('settings.ports.index')->middleware('permission:ports.view');
    Route::get('/settings/ports/create', [PortController::class, 'create'])->name('settings.ports.create')->middleware('permission:ports.create');
    Route::post('/settings/ports', [PortController::class, 'store'])->name('settings.ports.store')->middleware('permission:ports.create');
    Route::get('/settings/ports/{port}/edit', [PortController::class, 'edit'])->name('settings.ports.edit')->middleware('permission:ports.update');
    Route::put('/settings/ports/{port}', [PortController::class, 'update'])->name('settings.ports.update')->middleware('permission:ports.update');
    Route::delete('/settings/ports/{port}', [PortController::class, 'destroy'])->name('settings.ports.destroy')->middleware('permission:ports.delete');
    
    Route::get('/import', [ImportController::class, 'index'])->name('import.index')->middleware('permission:import.view,import.import');
    Route::post('/import', [ImportController::class, 'store'])->name('import.store')->middleware('permission:import.import');
    Route::get('/import/template', [ImportController::class, 'template'])->name('import.template')->middleware('permission:import.view,import.import');
    
    Route::get('/forbidden', function () {
        return view('errors.forbidden');
    })->name('forbidden');
    
    Route::get('/settings/users', [UserController::class, 'index'])->name('settings.users.index')->middleware('permission:users.view,users.manage');
    Route::get('/settings/users/create', [UserController::class, 'create'])->name('settings.users.create')->middleware('permission:users.create,users.manage');
    Route::post('/settings/users', [UserController::class, 'store'])->name('settings.users.store')->middleware('permission:users.create,users.manage');
    Route::get('/settings/users/{user}', [UserController::class, 'show'])->name('settings.users.show')->middleware('permission:users.view,users.manage');
    Route::get('/settings/users/{user}/edit', [UserController::class, 'edit'])->name('settings.users.edit')->middleware('permission:users.update,users.manage');
    Route::put('/settings/users/{user}', [UserController::class, 'update'])->name('settings.users.update')->middleware('permission:users.update,users.manage');
    Route::delete('/settings/users/{user}', [UserController::class, 'destroy'])->name('settings.users.destroy')->middleware('permission:users.delete,users.manage');
    Route::get('/settings/users/{user}/permissions', [PermissionController::class, 'userPermissions'])->name('settings.users.permissions')->middleware('permission:permissions.manage');
    Route::put('/settings/users/{user}/permissions', [PermissionController::class, 'updateUserPermissions'])->name('settings.users.update-permissions')->middleware('permission:permissions.manage');
    
    Route::get('/settings/permissions', [PermissionController::class, 'index'])->name('settings.permissions.index')->middleware('permission:permissions.view,permissions.manage');
    Route::get('/settings/permissions/roles', [PermissionController::class, 'roles'])->name('settings.permissions.roles')->middleware('permission:permissions.view,permissions.manage');
    Route::get('/settings/permissions/roles/create', [PermissionController::class, 'createRole'])->name('settings.permissions.create-role')->middleware('permission:permissions.manage');
    Route::post('/settings/permissions/roles', [PermissionController::class, 'storeRole'])->name('settings.permissions.store-role')->middleware('permission:permissions.manage');
    Route::get('/settings/permissions/roles/{role}/edit', [PermissionController::class, 'editRole'])->name('settings.permissions.edit-role')->middleware('permission:permissions.manage');
    Route::put('/settings/permissions/roles/{role}', [PermissionController::class, 'updateRole'])->name('settings.permissions.update-role')->middleware('permission:permissions.manage');
    Route::delete('/settings/permissions/roles/{role}', [PermissionController::class, 'destroyRole'])->name('settings.permissions.destroy-role')->middleware('permission:permissions.manage');
    Route::post('/settings/permissions/sync', [PermissionController::class, 'syncPermissions'])->name('settings.permissions.sync')->middleware('permission:permissions.manage');

    Route::prefix('admin')->name('admin.')->middleware('system:admin_system')->group(function () {
        Route::get('departments', [DepartmentController::class, 'index'])->name('departments.index')->middleware('permission:departments.view');
        Route::get('departments/create', [DepartmentController::class, 'create'])->name('departments.create')->middleware('permission:departments.create');
        Route::post('departments', [DepartmentController::class, 'store'])->name('departments.store')->middleware('permission:departments.create');
        Route::get('departments/{department}', [DepartmentController::class, 'show'])->name('departments.show')->middleware('permission:departments.view');
        Route::get('departments/{department}/edit', [DepartmentController::class, 'edit'])->name('departments.edit')->middleware('permission:departments.update');
        Route::put('departments/{department}', [DepartmentController::class, 'update'])->name('departments.update')->middleware('permission:departments.update');
        Route::delete('departments/{department}', [DepartmentController::class, 'destroy'])->name('departments.destroy')->middleware('permission:departments.delete');
        
        Route::get('employees', [EmployeeController::class, 'index'])->name('employees.index')->middleware('permission:employees.view');
        Route::get('employees/create', [EmployeeController::class, 'create'])->name('employees.create')->middleware('permission:employees.create');
        Route::post('employees', [EmployeeController::class, 'store'])->name('employees.store')->middleware('permission:employees.create');
        Route::get('employees/{employee}', [EmployeeController::class, 'show'])->name('employees.show')->middleware('permission:employees.view');
        Route::get('employees/{employee}/edit', [EmployeeController::class, 'edit'])->name('employees.edit')->middleware('permission:employees.update');
        Route::put('employees/{employee}', [EmployeeController::class, 'update'])->name('employees.update')->middleware('permission:employees.update');
        Route::delete('employees/{employee}', [EmployeeController::class, 'destroy'])->name('employees.destroy')->middleware('permission:employees.delete');
        
        Route::get('attendances/bulk-create', [AttendanceController::class, 'bulkCreate'])->name('attendances.bulk-create')->middleware('permission:attendances.create');
        Route::post('attendances/bulk-store', [AttendanceController::class, 'bulkStore'])->name('attendances.bulk-store')->middleware('permission:attendances.create');
        Route::get('attendances/report', [AttendanceController::class, 'report'])->name('attendances.report')->middleware('permission:attendances.view');
        Route::get('attendances', [AttendanceController::class, 'index'])->name('attendances.index')->middleware('permission:attendances.view');
        Route::get('attendances/create', [AttendanceController::class, 'create'])->name('attendances.create')->middleware('permission:attendances.create');
        Route::post('attendances', [AttendanceController::class, 'store'])->name('attendances.store')->middleware('permission:attendances.create');
        Route::get('attendances/{attendance}', [AttendanceController::class, 'show'])->name('attendances.show')->middleware('permission:attendances.view');
        Route::get('attendances/{attendance}/edit', [AttendanceController::class, 'edit'])->name('attendances.edit')->middleware('permission:attendances.update');
        Route::put('attendances/{attendance}', [AttendanceController::class, 'update'])->name('attendances.update')->middleware('permission:attendances.update');
        Route::delete('attendances/{attendance}', [AttendanceController::class, 'destroy'])->name('attendances.destroy')->middleware('permission:attendances.delete');
        
        Route::post('leave-requests/{leaveRequest}/approve', [LeaveRequestController::class, 'approve'])->name('leave-requests.approve')->middleware('permission:leave_requests.approve');
        Route::post('leave-requests/{leaveRequest}/reject', [LeaveRequestController::class, 'reject'])->name('leave-requests.reject')->middleware('permission:leave_requests.reject');
        Route::get('leave-requests', [LeaveRequestController::class, 'index'])->name('leave-requests.index')->middleware('permission:leave_requests.view');
        Route::get('leave-requests/create', [LeaveRequestController::class, 'create'])->name('leave-requests.create')->middleware('permission:leave_requests.create');
        Route::post('leave-requests', [LeaveRequestController::class, 'store'])->name('leave-requests.store')->middleware('permission:leave_requests.create');
        Route::get('leave-requests/{leave_request}', [LeaveRequestController::class, 'show'])->name('leave-requests.show')->middleware('permission:leave_requests.view');
        Route::get('leave-requests/{leave_request}/edit', [LeaveRequestController::class, 'edit'])->name('leave-requests.edit')->middleware('permission:leave_requests.update');
        Route::put('leave-requests/{leave_request}', [LeaveRequestController::class, 'update'])->name('leave-requests.update')->middleware('permission:leave_requests.update');
        Route::delete('leave-requests/{leave_request}', [LeaveRequestController::class, 'destroy'])->name('leave-requests.destroy')->middleware('permission:leave_requests.delete');
        
        Route::get('documents/inbox', [DocumentController::class, 'inbox'])->name('documents.inbox')->middleware('permission:documents.view');
        Route::get('documents/my-documents', [DocumentController::class, 'myDocuments'])->name('documents.my-documents')->middleware('permission:documents.view');
        Route::get('documents/{document}/print', [DocumentController::class, 'print'])->name('documents.print')->middleware('permission:documents.print');
        Route::post('documents/{document}/send-for-review', [DocumentController::class, 'sendForReview'])->name('documents.send-for-review')->middleware('permission:documents.update');
        Route::post('documents/{document}/send-to-manager', [DocumentController::class, 'sendToManager'])->name('documents.send-to-manager')->middleware('permission:documents.update');
        Route::post('documents/{document}/approve', [DocumentController::class, 'approve'])->name('documents.approve')->middleware('permission:documents.approve');
        Route::post('documents/{document}/reject', [DocumentController::class, 'reject'])->name('documents.reject')->middleware('permission:documents.reject');
        Route::post('documents/{document}/request-modification', [DocumentController::class, 'requestModification'])->name('documents.request-modification')->middleware('permission:documents.update');
        Route::get('documents', [DocumentController::class, 'index'])->name('documents.index')->middleware('permission:documents.view');
        Route::get('documents/create', [DocumentController::class, 'create'])->name('documents.create')->middleware('permission:documents.create');
        Route::post('documents', [DocumentController::class, 'store'])->name('documents.store')->middleware('permission:documents.create');
        Route::get('documents/{document}', [DocumentController::class, 'show'])->name('documents.show')->middleware('permission:documents.view');
        Route::get('documents/{document}/edit', [DocumentController::class, 'edit'])->name('documents.edit')->middleware('permission:documents.update');
        Route::put('documents/{document}', [DocumentController::class, 'update'])->name('documents.update')->middleware('permission:documents.update');
        Route::delete('documents/{document}', [DocumentController::class, 'destroy'])->name('documents.destroy')->middleware('permission:documents.delete');
        
        Route::get('correspondences/search', [CorrespondenceController::class, 'searchForm'])->name('correspondences.search-form')->middleware('permission:correspondences.view');
        Route::get('correspondences/search/results', [CorrespondenceController::class, 'search'])->name('correspondences.search')->middleware('permission:correspondences.view');
        Route::get('correspondences/import', [CorrespondenceController::class, 'import'])->name('correspondences.import')->middleware('permission:correspondences.import');
        Route::post('correspondences/import', [CorrespondenceController::class, 'storeImport'])->name('correspondences.store-import')->middleware('permission:correspondences.import');
        Route::get('correspondences/{correspondence}/download', [CorrespondenceController::class, 'download'])->name('correspondences.download')->middleware('permission:correspondences.view');
        Route::get('correspondences', [CorrespondenceController::class, 'index'])->name('correspondences.index')->middleware('permission:correspondences.view');
        Route::get('correspondences/create', [CorrespondenceController::class, 'create'])->name('correspondences.create')->middleware('permission:correspondences.create');
        Route::post('correspondences', [CorrespondenceController::class, 'store'])->name('correspondences.store')->middleware('permission:correspondences.create');
        Route::get('correspondences/{correspondence}', [CorrespondenceController::class, 'show'])->name('correspondences.show')->middleware('permission:correspondences.view');
        Route::get('correspondences/{correspondence}/edit', [CorrespondenceController::class, 'edit'])->name('correspondences.edit')->middleware('permission:correspondences.update');
        Route::put('correspondences/{correspondence}', [CorrespondenceController::class, 'update'])->name('correspondences.update')->middleware('permission:correspondences.update');
        Route::delete('correspondences/{correspondence}', [CorrespondenceController::class, 'destroy'])->name('correspondences.destroy')->middleware('permission:correspondences.delete');
    });
});
