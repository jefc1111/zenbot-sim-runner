<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Strategy;

class StrategyOption extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'description',
        'default',
        'unit',
        'step'
    ];

    public function strategy()
    {
        return $this->belongsTo(Strategy::class);
    }
}
