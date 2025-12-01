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
    
    Route::middleware('role:admin,supervisor')->group(function () {
        Route::resource('records', RecordController::class);
    });
    
    Route::get('/search', [SearchController::class, 'index'])->name('search.index');
    Route::get('/search/{record}', [SearchController::class, 'show'])->name('search.show');
    
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/print', [ReportController::class, 'print'])->name('reports.print');
    
    Route::middleware('role:admin,supervisor')->group(function () {
        Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
        
        Route::get('/settings/stations', [StationController::class, 'index'])->name('settings.stations.index');
        Route::get('/settings/stations/create', [StationController::class, 'create'])->name('settings.stations.create');
        Route::post('/settings/stations', [StationController::class, 'store'])->name('settings.stations.store');
        Route::get('/settings/stations/{station}/edit', [StationController::class, 'edit'])->name('settings.stations.edit');
        Route::put('/settings/stations/{station}', [StationController::class, 'update'])->name('settings.stations.update');
        Route::delete('/settings/stations/{station}', [StationController::class, 'destroy'])->name('settings.stations.destroy');
        
        Route::get('/settings/ports', [PortController::class, 'index'])->name('settings.ports.index');
        Route::get('/settings/ports/create', [PortController::class, 'create'])->name('settings.ports.create');
        Route::post('/settings/ports', [PortController::class, 'store'])->name('settings.ports.store');
        Route::get('/settings/ports/{port}/edit', [PortController::class, 'edit'])->name('settings.ports.edit');
        Route::put('/settings/ports/{port}', [PortController::class, 'update'])->name('settings.ports.update');
        Route::delete('/settings/ports/{port}', [PortController::class, 'destroy'])->name('settings.ports.destroy');
        
        Route::get('/import', [ImportController::class, 'index'])->name('import.index');
        Route::post('/import', [ImportController::class, 'store'])->name('import.store');
        Route::get('/import/template', [ImportController::class, 'template'])->name('import.template');
    });
    
    Route::get('/forbidden', function () {
        return view('errors.forbidden');
    })->name('forbidden');
    
    Route::middleware('admin')->group(function () {
        Route::get('/settings/users', [UserController::class, 'index'])->name('settings.users.index');
        Route::get('/settings/users/create', [UserController::class, 'create'])->name('settings.users.create');
        Route::post('/settings/users', [UserController::class, 'store'])->name('settings.users.store');
        Route::get('/settings/users/{user}', [UserController::class, 'show'])->name('settings.users.show');
        Route::get('/settings/users/{user}/edit', [UserController::class, 'edit'])->name('settings.users.edit');
        Route::put('/settings/users/{user}', [UserController::class, 'update'])->name('settings.users.update');
        Route::delete('/settings/users/{user}', [UserController::class, 'destroy'])->name('settings.users.destroy');
        Route::get('/settings/users/{user}/permissions', [PermissionController::class, 'userPermissions'])->name('settings.users.permissions');
        Route::put('/settings/users/{user}/permissions', [PermissionController::class, 'updateUserPermissions'])->name('settings.users.update-permissions');
        
        Route::get('/settings/permissions', [PermissionController::class, 'index'])->name('settings.permissions.index');
        Route::get('/settings/permissions/roles', [PermissionController::class, 'roles'])->name('settings.permissions.roles');
        Route::get('/settings/permissions/roles/create', [PermissionController::class, 'createRole'])->name('settings.permissions.create-role');
        Route::post('/settings/permissions/roles', [PermissionController::class, 'storeRole'])->name('settings.permissions.store-role');
        Route::get('/settings/permissions/roles/{role}/edit', [PermissionController::class, 'editRole'])->name('settings.permissions.edit-role');
        Route::put('/settings/permissions/roles/{role}', [PermissionController::class, 'updateRole'])->name('settings.permissions.update-role');
        Route::delete('/settings/permissions/roles/{role}', [PermissionController::class, 'destroyRole'])->name('settings.permissions.destroy-role');
        Route::post('/settings/permissions/sync', [PermissionController::class, 'syncPermissions'])->name('settings.permissions.sync');
    });

    Route::prefix('admin')->name('admin.')->middleware(['role:admin,supervisor', 'system:admin_system'])->group(function () {
        Route::resource('departments', DepartmentController::class);
        
        Route::resource('employees', EmployeeController::class);
        
        Route::get('attendances/bulk-create', [AttendanceController::class, 'bulkCreate'])->name('attendances.bulk-create');
        Route::post('attendances/bulk-store', [AttendanceController::class, 'bulkStore'])->name('attendances.bulk-store');
        Route::get('attendances/report', [AttendanceController::class, 'report'])->name('attendances.report');
        Route::resource('attendances', AttendanceController::class);
        
        Route::post('leave-requests/{leaveRequest}/approve', [LeaveRequestController::class, 'approve'])->name('leave-requests.approve');
        Route::post('leave-requests/{leaveRequest}/reject', [LeaveRequestController::class, 'reject'])->name('leave-requests.reject');
        Route::resource('leave-requests', LeaveRequestController::class);
        
        Route::get('documents/inbox', [DocumentController::class, 'inbox'])->name('documents.inbox');
        Route::get('documents/my-documents', [DocumentController::class, 'myDocuments'])->name('documents.my-documents');
        Route::get('documents/{document}/print', [DocumentController::class, 'print'])->name('documents.print');
        Route::post('documents/{document}/send-for-review', [DocumentController::class, 'sendForReview'])->name('documents.send-for-review');
        Route::post('documents/{document}/send-to-manager', [DocumentController::class, 'sendToManager'])->name('documents.send-to-manager');
        Route::post('documents/{document}/approve', [DocumentController::class, 'approve'])->name('documents.approve');
        Route::post('documents/{document}/reject', [DocumentController::class, 'reject'])->name('documents.reject');
        Route::post('documents/{document}/request-modification', [DocumentController::class, 'requestModification'])->name('documents.request-modification');
        Route::resource('documents', DocumentController::class);
        
        Route::get('correspondences/search', [CorrespondenceController::class, 'searchForm'])->name('correspondences.search-form');
        Route::get('correspondences/search/results', [CorrespondenceController::class, 'search'])->name('correspondences.search');
        Route::get('correspondences/import', [CorrespondenceController::class, 'import'])->name('correspondences.import');
        Route::post('correspondences/import', [CorrespondenceController::class, 'storeImport'])->name('correspondences.store-import');
        Route::get('correspondences/{correspondence}/download', [CorrespondenceController::class, 'download'])->name('correspondences.download');
        Route::resource('correspondences', CorrespondenceController::class);
    });
});
