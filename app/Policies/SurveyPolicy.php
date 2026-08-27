<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Survey;
use App\Models\User;

class SurveyPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Survey $survey): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->hasRole(UserRole::Admin, UserRole::Gerente);
    }

    public function update(User $user, Survey $survey): bool
    {
        return $user->hasRole(UserRole::Admin, UserRole::Gerente);
    }

    public function delete(User $user, Survey $survey): bool
    {
        return $user->hasRole(UserRole::Admin, UserRole::Gerente);
    }

    public function duplicate(User $user, Survey $survey): bool
    {
        return $this->update($user, $survey);
    }

    public function activate(User $user, Survey $survey): bool
    {
        return $this->update($user, $survey);
    }

    public function close(User $user, Survey $survey): bool
    {
        return $this->update($user, $survey);
    }

    public function export(User $user, Survey $survey): bool
    {
        return true;
    }
}