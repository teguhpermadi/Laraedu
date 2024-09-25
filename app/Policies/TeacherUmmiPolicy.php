<?php

namespace App\Policies;

use App\Models\TeacherUmmi;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TeacherUmmiPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_teacher::ummi');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, TeacherUmmi $teacherUmmi): bool
    {
        return $user->can('view_teacher::ummi');
        
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_teacher::ummi');
        
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, TeacherUmmi $teacherUmmi): bool
    {
        return $user->can('update_teacher::ummi');
        
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, TeacherUmmi $teacherUmmi): bool
    {
        return $user->can('delete_teacher::ummi');
        
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, TeacherUmmi $teacherUmmi): bool
    {
        return $user->can('restore_teacher::ummi');
        
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, TeacherUmmi $teacherUmmi): bool
    {
        return $user->can('force_delete_teacher::ummi');
        
    }
}
