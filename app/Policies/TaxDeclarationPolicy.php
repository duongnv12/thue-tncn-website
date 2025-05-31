<?php

namespace App\Policies;

use App\Models\User;
use App\Models\TaxDeclaration;
use Illuminate\Auth\Access\Response;

class TaxDeclarationPolicy
{
    public function viewAny(User $user): bool { return true; }
    public function view(User $user, TaxDeclaration $taxDeclaration): bool { return $user->id === $taxDeclaration->user_id; }
    public function create(User $user): bool { return true; } // Việc tạo được xử lý qua TaxCalculationController
    public function update(User $user, TaxDeclaration $taxDeclaration): bool { return $user->id === $taxDeclaration->user_id; }
    public function delete(User $user, TaxDeclaration $taxDeclaration): bool { return $user->id === $taxDeclaration->user_id; }
    public function restore(User $user, TaxDeclaration $taxDeclaration): bool { return $user->id === $taxDeclaration->user_id; }
    public function forceDelete(User $user, TaxDeclaration $taxDeclaration): bool { return $user->id === $taxDeclaration->user_id; }
}