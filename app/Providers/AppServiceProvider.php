<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate; // Đảm bảo dòng này đã được thêm
use App\Models\User; // Đảm bảo dòng này đã được thêm
use App\Models\TaxDeclaration; // Đảm bảo các Models liên quan đã được thêm
use App\Models\IncomeSource;
use App\Models\Dependent;
use App\Policies\TaxDeclarationPolicy; // Đảm bảo các Policies đã được thêm
use App\Policies\IncomeSourcePolicy;
use App\Policies\DependentPolicy;
use App\Policies\UserPolicy; // Thêm dòng này


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // 1. Đăng ký Policies (thay thế cho $policies array trong AuthServiceProvider)
        Gate::policy(TaxDeclaration::class, TaxDeclarationPolicy::class);
        Gate::policy(IncomeSource::class, IncomeSourcePolicy::class);
        Gate::policy(Dependent::class, DependentPolicy::class);

        // 2. Định nghĩa Gates (nếu bạn có dùng Gates)
        // Ví dụ về Gate 'access-admin-panel' nếu bạn có cột 'is_admin' trong bảng users
        Gate::define('access-admin-panel', function (User $user) {
            return $user->is_admin;
        });

        // Ví dụ về Gate 'view-system-settings'
        Gate::define('view-system-settings', function (User $user) {
            return $user->is_admin;
        });

        Gate::policy(User::class, UserPolicy::class); // Thêm dòng này
        
        Gate::define('access-admin-panel', function (User $user) {
        return $user->is_admin;
        });

        // ... các định nghĩa Gate khác nếu có
    }
}