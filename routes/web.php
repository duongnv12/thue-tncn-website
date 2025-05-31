<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IncomeSourceController;
use App\Http\Controllers\DependentController;
use App\Http\Controllers\TaxCalculationController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    // Routes cho quản lý nguồn thu nhập, chỉ cho người dùng đã đăng nhập
    Route::get('/income-sources', [IncomeSourceController::class, 'index'])->name('income_sources.index');
    Route::get('/income-sources/create', [IncomeSourceController::class, 'create'])->name('income_sources.create');
    Route::post('/income-sources', [IncomeSourceController::class, 'store'])->name('income_sources.store');
    Route::get('/income-sources/{incomeSource}/edit', [IncomeSourceController::class, 'edit'])->name('income_sources.edit');
    Route::put('/income-sources/{incomeSource}', [IncomeSourceController::class, 'update'])->name('income_sources.update');
    Route::delete('/income-sources/{incomeSource}', [IncomeSourceController::class, 'destroy'])->name('income_sources.destroy');
    // Routes cho quản lý người phụ thuộc, chỉ cho người dùng đã đăng nhập
    Route::get('/dependents', [DependentController::class, 'index'])->name('dependents.index');
    Route::get('/dependents/create', [DependentController::class, 'create'])->name('dependents.create');
    Route::post('/dependents', [DependentController::class, 'store'])->name('dependents.store');
    Route::get('/dependents/{dependent}/edit', [DependentController::class, 'edit'])->name('dependents.edit');
    Route::put('/dependents/{dependent}', [DependentController::class, 'update'])->name('dependents.update');
    Route::delete('/dependents/{dependent}', [DependentController::class, 'destroy'])->name('dependents.destroy');
    // Routes cho chức năng tính toán thuế, chỉ cho người dùng đã đăng nhập
    Route::get('/tax-calculation', [TaxCalculationController::class, 'index'])->name('tax_calculation.index');
    Route::post('/tax-calculation', [TaxCalculationController::class, 'calculate'])->name('tax_calculation.calculate');
    Route::get('/tax-declarations', [TaxCalculationController::class, 'declarations'])->name('tax_declarations.index'); // Xem lịch sử khai báo
    Route::get('/tax-declarations/{taxDeclaration}', [TaxCalculationController::class, 'showDeclaration'])->name('tax_declarations.show'); // Xem chi tiết khai báo
    Route::get('/tax-declarations/{taxDeclaration}/pdf', [TaxCalculationController::class, 'generatePdfDeclaration'])->name('tax_declarations.pdf'); 
    Route::get('/tax-statistics', [TaxCalculationController::class, 'statistics'])->name('tax_calculation.statistics');
});

require __DIR__.'/auth.php';
