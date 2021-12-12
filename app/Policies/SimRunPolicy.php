<?php

namespace App\Policies;

use App\Models\SimRun;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SimRunPolicy
{
    use HandlesAuthorization;

    public function before($user, $ability) {
        if ($user->hasRole('admin')) {
            return true;
        }
    }

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\SimRun  $simRun
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, SimRun $simRun)
    {
        return $user->id === $simRun->sim_run_batch->user_id;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        true;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\SimRun  $simRun
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, SimRun $simRun)
    {
        return $user->id === $simRun->sim_run_batch->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\SimRun  $simRun
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, SimRun $simRun)
    {
        return $user->id === $simRun->sim_run_batch->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\SimRun  $simRun
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, SimRun $simRun)
    {
        return $user->id === $simRun->sim_run_batch->user_id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\SimRun  $simRun
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, SimRun $simRun)
    {
        return $user->id === $simRun->sim_run_batch->user_id;
    }
}
