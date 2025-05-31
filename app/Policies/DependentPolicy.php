<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Dependent;
use Illuminate\Auth\Access\Response;

class DependentPolicy
{
    public function viewAny(User $user): bool { return true; }
    public function view(User $user, Dependent $dependent): bool { return $user->id === $dependent->user_id; }
    public function create(User $user): bool { return true; }
    public function update(User $user, Dependent $dependent): bool { return $user->id === $dependent->user_id; }
    public function delete(User $user, Dependent $dependent): bool { return $user->id === $dependent->user_id; }
    public function restore(User $user, Dependent $dependent): bool { return $user->id === $dependent->user_id; }
    public function forceDelete(User $user, Dependent $dependent): bool { return $user->id === $dependent->user_id; }
}