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

    public $weighted_score = false;

    public function strategy()
    {
        return $this->belongsTo(Strategy::class);
    }

    public function getValueAttribute()
    {
        return $this->pivot->value.$this->unit;
    }

    // -1, 0 or 1 to represent none, increasing, decreasing
    // I should make an enum type for this sometime
    public function effect_on_trend()
    {
        // Maybe let this be overridden in .env ... at worst make it a CONST or similar, please...  
        $threshold_for_effect = 0.25; 

        if ($this->weighted_score < (-1 * $threshold_for_effect)) {
            return -1;
        }

        if ($this->weighted_score > $threshold_for_effect) {
            return 1;
        }

        return 0;
    }
}
