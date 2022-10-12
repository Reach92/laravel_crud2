<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Subject;
use Illuminate\Auth\Access\HandlesAuthorization;

class SubjectPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the subject can view any models.
     *
     * @param  App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->hasPermissionTo('list subjects');
    }

    /**
     * Determine whether the subject can view the model.
     *
     * @param  App\Models\User  $user
     * @param  App\Models\Subject  $model
     * @return mixed
     */
    public function view(User $user, Subject $model)
    {
        return $user->hasPermissionTo('view subjects');
    }

    /**
     * Determine whether the subject can create models.
     *
     * @param  App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermissionTo('create subjects');
    }

    /**
     * Determine whether the subject can update the model.
     *
     * @param  App\Models\User  $user
     * @param  App\Models\Subject  $model
     * @return mixed
     */
    public function update(User $user, Subject $model)
    {
        return $user->hasPermissionTo('update subjects');
    }

    /**
     * Determine whether the subject can delete the model.
     *
     * @param  App\Models\User  $user
     * @param  App\Models\Subject  $model
     * @return mixed
     */
    public function delete(User $user, Subject $model)
    {
        return $user->hasPermissionTo('delete subjects');
    }

    /**
     * Determine whether the user can delete multiple instances of the model.
     *
     * @param  App\Models\User  $user
     * @param  App\Models\Subject  $model
     * @return mixed
     */
    public function deleteAny(User $user)
    {
        return $user->hasPermissionTo('delete subjects');
    }

    /**
     * Determine whether the subject can restore the model.
     *
     * @param  App\Models\User  $user
     * @param  App\Models\Subject  $model
     * @return mixed
     */
    public function restore(User $user, Subject $model)
    {
        return false;
    }

    /**
     * Determine whether the subject can permanently delete the model.
     *
     * @param  App\Models\User  $user
     * @param  App\Models\Subject  $model
     * @return mixed
     */
    public function forceDelete(User $user, Subject $model)
    {
        return false;
    }
}
