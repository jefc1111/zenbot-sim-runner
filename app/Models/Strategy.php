<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\StrategyOption;

class Strategy extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function options()
    {
        return $this->hasMany(StrategyOption::class);
    }

    public function sim_runs()
    {
        return $this->hasMany(SimRun::class);
    }
}
