<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\SimRunBatch;
use App\Models\Shop\SimTimeOrder;

class User extends \TCG\Voyager\Models\User implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'available_seconds'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function sim_run_batches()
    {
        return $this->hasMany(SimRunBatch::class);
    }

    public function sim_time_orders()
    {
        return $this->hasMany(SimTimeOrder::class);
    }

    public function available_sim_time()
    {
        $t = $this->available_seconds;
        $f = ':';

        return ($t< 0 ? '-' : '') . sprintf("%02d%s%02d%s%02d", floor(abs($t)/3600), $f, (abs($t)/60)%60, $f, abs($t)%60);
    }

    public function available_sim_time_class()
    {
        if ($this->available_seconds < 0) {
            return 'text-danger';
        }

        if ($this->available_seconds >= 0 && $this->available_seconds < 60) {
            return 'text-danger';
        }

        return 'text-success';
    }

    public function has_sim_time()
    {
        return $this->available_seconds > 60;
    }
}
