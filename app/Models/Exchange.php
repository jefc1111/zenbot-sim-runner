<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SimRunBatch;

class Exchange extends Model
{
    use HasFactory;

    public function sim_run_batches()
    {
        return $this->hasMany(SimRunBatch::class);
    }
}
