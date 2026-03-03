<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AuditController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\FamilyController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\PermissionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Redirect root to dashboard (will require auth)
Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/welcome', function () {
    return view('welcome');
});

// Routes không cần đăng nhập
Route::middleware('guest')->group(function () {
    // Đăng nhập
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    // Quên mật khẩu
    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');

    // Reset mật khẩu
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
});

// Routes cần đăng nhập
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // API routes
    Route::get('/api/workers/search', function () {
        $workers = \App\Models\User::whereHas('role', function ($query) {
            $query->where('name', 'dailyworker');
        })
            ->select('id', 'user_code', 'fullname', 'email')
            ->get()
            ->map(function ($worker) {
                return [
                    'id' => $worker->id,
                    'user_code' => $worker->user_code,
                    'fullname' => $worker->fullname,
                    'email' => $worker->email
                ];
            });

        return response()->json($workers);
    })->name('api.workers.search');

    // Dashboard (tạm thời)
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/photo', [ProfileController::class, 'editPhoto'])->name('profile.photo.edit');
    Route::post('/profile/photo', [ProfileController::class, 'updatePhoto'])->name('profile.photo.update');
    Route::get('/profile/password', [ProfileController::class, 'editPassword'])->name('profile.password.edit');
    Route::post('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');

    // Audit routes
    Route::get('/audits/export', [AuditController::class, 'export'])->name('audits.export');
    Route::get('/audits/import-template', [AuditController::class, 'downloadTemplate'])->name('audits.import-template');
    Route::post('/audits/import', [AuditController::class, 'import'])->name('audits.import');
    Route::resource('audits', AuditController::class);

    // Reports routes
    Route::get('/reports', [ReportsController::class, 'index'])->name('reports.index');
    Route::get('/reports/worker-trend', [ReportsController::class, 'workerTrend'])->name('reports.worker-trend');
    Route::get('/reports/worker-audits', [ReportsController::class, 'getWorkerAudits'])->name('reports.worker-audits');
    Route::get('/reports/export-worker-checks', [ReportsController::class, 'exportWorkerChecks'])->name('reports.export-worker-checks');
});

// Admin routes - chỉ admin mới có quyền truy cập
Route::middleware(['auth', 'is.admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::get('users/template', [UserController::class, 'downloadTemplate'])->name('users.template');
    Route::post('users/import', [UserController::class, 'import'])->name('users.import');
    Route::post('users/bulk-update-role', [UserController::class, 'bulkUpdateRole'])->name('users.bulk-update-role');
    Route::post('users/bulk-delete', [UserController::class, 'bulkDelete'])->name('users.bulk-delete');
    Route::resource('users', UserController::class);
    Route::resource('contacts', ContactController::class);
    Route::post('families/{family}/chart-layout', [FamilyController::class, 'saveChartLayout'])->name('families.chart-layout.save');
    Route::delete('families/{family}/chart-layout', [FamilyController::class, 'resetChartLayout'])->name('families.chart-layout.reset');
    Route::resource('families', FamilyController::class);
    Route::get('events/calendar', [EventController::class, 'calendar'])->name('events.calendar');
    Route::post('events/calendar', [EventController::class, 'calendarStore'])->name('events.calendar.store');
    Route::put('events/{event}/calendar', [EventController::class, 'calendarUpdate'])->name('events.calendar.update');
    Route::resource('events', EventController::class);
    Route::resource('roles', RoleController::class);

    // Permissions
    Route::get('permissions', [PermissionController::class, 'index'])->name('permissions.index');
    Route::get('permissions/{role}/edit', [PermissionController::class, 'edit'])->name('permissions.edit');
    Route::put('permissions/{role}', [PermissionController::class, 'update'])->name('permissions.update');

});
