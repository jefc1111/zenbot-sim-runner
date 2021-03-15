<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SimRunBatch;
use App\Models\Product;

class Exchange extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function sim_run_batches()
    {
        return $this->hasMany(SimRunBatch::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class)->orderBy('name');
    }
}
