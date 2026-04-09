<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Kasir\OrderController;
use App\Http\Controllers\Kasir\ShiftController;

// Redirect root to login
Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/laporan', [\App\Http\Controllers\Admin\LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/{id}', [\App\Http\Controllers\Admin\LaporanController::class, 'show'])->name('laporan.show');
    Route::resource('menus', MenuController::class);
    Route::patch('/menus/{menu}/quick-stock', [MenuController::class, 'quickStock'])->name('menus.quickStock');
    Route::patch('/menus/{menu}/toggle-status', [MenuController::class, 'toggleStatus'])->name('menus.toggleStatus');
    Route::resource('users', UserController::class);
    Route::resource('pengeluarans', \App\Http\Controllers\Admin\PengeluaranController::class);
    Route::post('/users/assign-kasir', [UserController::class, 'assignKasir'])->name('users.assignKasir');
    Route::resource('shifts', \App\Http\Controllers\Admin\ShiftController::class)->names([
        'index' => 'shifts.index',
        'create' => 'shifts.create',
        'store' => 'shifts.store',
        'show' => 'shifts.show',
        'edit' => 'shifts.edit',
        'update' => 'shifts.update',
        'destroy' => 'shifts.destroy',
    ]);
    
    // Settings
    Route::get('/settings', [\App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings/profile', [\App\Http\Controllers\Admin\SettingController::class, 'updateProfile'])->name('settings.profile');
    Route::post('/settings/password', [\App\Http\Controllers\Admin\SettingController::class, 'updatePassword'])->name('settings.password');

    // Notifications
    Route::get('/notifications/mark-read', function () {
        auth()->user()->unreadNotifications->markAsRead();
        return response()->json(['success' => true]);
    })->name('notifications.markRead');

    Route::get('/notifications/clear-all', function () {
        auth()->user()->notifications()->delete();
        return response()->json(['success' => true]);
    })->name('notifications.clearAll');
});

use App\Http\Controllers\Api\MidtransController;

// Midtrans Notification Webook
Route::post('/midtrans/notification', [MidtransController::class, 'handleNotification'])->name('midtrans.notification');

// Kasir Routes
Route::middleware(['auth', 'kasir', 'shift.active'])->prefix('kasir')->name('kasir.')->group(function () {
    Route::get('/order', [OrderController::class, 'index'])->name('order');
    Route::get('/riwayat-transaksi', [App\Http\Controllers\Kasir\TransaksiController::class, 'index'])->name('transaksi.index');
    Route::post('/transaksi', [App\Http\Controllers\Kasir\TransaksiController::class, 'store'])->name('transaksi.store');
    Route::get('/shift', [ShiftController::class, 'index'])->name('shift');
    Route::post('/shift/start', [ShiftController::class, 'startShift'])->name('shift.start');
    Route::post('/shift/end', [ShiftController::class, 'endShift'])->name('shift.end');
    // Add other kasir routes here
});
