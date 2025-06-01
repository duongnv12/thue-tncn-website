<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController; // Đảm bảo đã import
use App\Http\Controllers\IncomeSourceController; // Đảm bảo đã import
use App\Http\Controllers\DependentController; // Đảm bảo đã import
use App\Http\Controllers\TaxCalculationController; // Đảm bảo đã import
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController; // Đổi tên để tránh trùng lặp
use App\Http\Controllers\Admin\TaxSettingsController as AdminTaxSettingsController; // Đổi tên để tránh trùng lặp
use App\Http\Controllers\TaxDeclarationController; // Đảm bảo đã import
use Illuminate\Support\Facades\Route;

// Trang chủ mặc định (có thể là trang giới thiệu hoặc chuyển hướng)
Route::get('/', function () {
    return view('welcome');
});

// Các Routes dành cho người dùng đã đăng nhập (authenticated users)
Route::middleware(['auth', 'verified'])->group(function () {
    // Routes Profile (Mặc định của Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Dashboard chung cho người dùng (có thể là điểm đến mặc định sau khi login nếu không phải admin)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Quản lý Nguồn Thu nhập
    Route::resource('income-sources', IncomeSourceController::class)->except(['show']);
    Route::get('/income-sources', [IncomeSourceController::class, 'index'])->name('income_sources.index');
    Route::get('/income-sources/create', [IncomeSourceController::class, 'create'])->name('income_sources.create');
    Route::post('/income-sources', [IncomeSourceController::class, 'store'])->name('income_sources.store');
    Route::get('/income-sources/{incomeSource}/edit', [IncomeSourceController::class, 'edit'])->name('income_sources.edit');
    Route::put('/income-sources/{incomeSource}', [IncomeSourceController::class, 'update'])->name('income_sources.update');
    Route::delete('/income-sources/{incomeSource}', [IncomeSourceController::class, 'destroy'])->name('income_sources.destroy');


    // Quản lý Người phụ thuộc
    Route::resource('dependents', DependentController::class)->except(['show']);
    Route::get('/dependents', [DependentController::class, 'index'])->name('dependents.index');
    Route::get('/dependents/create', [DependentController::class, 'create'])->name('dependents.create');
    Route::post('/dependents', [DependentController::class, 'store'])->name('dependents.store');
    Route::get('/dependents/{dependent}/edit', [DependentController::class, 'edit'])->name('dependents.edit');
    Route::put('/dependents/{dependent}', [DependentController::class, 'update'])->name('dependents.update');
    Route::delete('/dependents/{dependent}', [DependentController::class, 'destroy'])->name('dependents.destroy');

    // Tính toán Thuế TNCN
    Route::match(['get', 'post'], '/tax-calculation', [TaxCalculationController::class, 'index'])->name('tax_calculation.index');
    Route::post('/tax-calculation/calculate-and-save', [TaxCalculationController::class, 'calculateAndSave'])->name('tax_calculation.calculate_and_save');
    Route::get('/tax-declarations/{declaration}/export-pdf', [TaxDeclarationController::class, 'exportPdf'])->name('tax_declarations.export_pdf');


    // Lịch sử Khai báo Thuế
    Route::get('/tax-declarations', [TaxDeclarationController::class, 'index'])->name('tax_declarations.index');
    Route::get('/tax-declarations/statistics', [TaxDeclarationController::class, 'statistics'])->name('tax_declarations.statistics');
    Route::get('/tax-declarations/{declaration}', [TaxDeclarationController::class, 'show'])->name('tax_declarations.show');
    Route::delete('/tax-declarations/{declaration}', [TaxDeclarationController::class, 'destroy'])->name('tax_declarations.destroy');
});


// Routes dành riêng cho Admin
// Sử dụng middleware 'is_admin' để chỉ Admin mới có thể truy cập
// Đặt tiền tố 'admin' cho URL và tên route để dễ quản lý
Route::middleware(['auth', 'verified', 'is_admin'])->prefix('admin')->name('admin.')->group(function () {
    // Admin Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Quản lý Người dùng (trong Admin Panel)
    Route::get('/users', [AdminDashboardController::class, 'users'])->name('users.index');
    Route::get('/users/{user}/edit', [AdminDashboardController::class, 'editUser'])->name('users.edit');
    Route::put('/users/{user}', [AdminDashboardController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{user}', [AdminDashboardController::class, 'deleteUser'])->name('users.destroy');

    // Cài đặt Thuế (Admin Panel)
    Route::get('/tax-settings', [AdminTaxSettingsController::class, 'index'])->name('tax_settings.index');
    Route::put('/tax-settings/update', [AdminTaxSettingsController::class, 'updateSettings'])->name('tax_settings.update');

    // Quản lý Bậc thuế
    Route::get('/tax-brackets/create', [AdminTaxSettingsController::class, 'createBracket'])->name('tax_brackets.create');
    Route::post('/tax-brackets', [AdminTaxSettingsController::class, 'storeBracket'])->name('tax_brackets.store');
    Route::get('/tax-brackets/{taxBracket}/edit', [AdminTaxSettingsController::class, 'editBracket'])->name('tax_brackets.edit');
    Route::put('/tax-brackets/{taxBracket}', [AdminTaxSettingsController::class, 'updateBracket'])->name('tax_brackets.update');
    Route::delete('/tax-brackets/{taxBracket}', [AdminTaxSettingsController::class, 'deleteBracket'])->name('tax_brackets.destroy');
});


// Routes xác thực (Authentication Routes - Mặc định của Breeze)
require __DIR__.'/auth.php';