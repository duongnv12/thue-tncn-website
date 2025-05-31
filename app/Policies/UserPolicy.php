<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    /**
     * Cho phép admin truy cập tất cả các quyền trên User.
     */
    public function before(User $user, string $ability): ?bool
    {
        if ($user->is_admin) {
            return true;
        }
        return null;
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Chỉ admin mới được xem danh sách tất cả người dùng
        return $user->is_admin;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $model): bool
    {
        // Admin có thể xem mọi người dùng, người dùng có thể xem chính họ
        return $user->is_admin || $user->id === $model->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Chỉ admin mới có thể tạo người dùng mới
        return $user->is_admin;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $model): bool
    {
        // Admin có thể cập nhật mọi người dùng, người dùng có thể cập nhật chính họ
        // Không cho phép admin tự thay đổi vai trò admin của mình qua policy này (nếu cần)
        return $user->is_admin || $user->id === $model->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model): bool
    {
        // Admin có thể xóa mọi người dùng trừ chính mình
        return $user->is_admin && $user->id !== $model->id;
    }

    // ... restore, forceDelete
}