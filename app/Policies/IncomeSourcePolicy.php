<?php

namespace App\Policies;

use App\Models\User;
use App\Models\IncomeSource;
use Illuminate\Auth\Access\Response;

class IncomeSourcePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true; // Tất cả người dùng đều có thể xem danh sách nguồn thu nhập của mình
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, IncomeSource $incomeSource): bool
    {
        return $user->id === $incomeSource->user_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true; // Tất cả người dùng đều có thể tạo nguồn thu nhập
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, IncomeSource $incomeSource): bool
    {
        return $user->id === $incomeSource->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, IncomeSource $incomeSource): bool
    {
        return $user->id === $incomeSource->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, IncomeSource $incomeSource): bool
    {
        return $user->id === $incomeSource->user_id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, IncomeSource $incomeSource): bool
    {
        return $user->id === $incomeSource->user_id;
    }
}