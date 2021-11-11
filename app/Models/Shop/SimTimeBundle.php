<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Model;

class SimTimeBundle extends Model
{
    public function orders()
    {
        return $this->hasMany(\App\Models\Shop\SimTimeOrder::class);
    }

    public function get_discount()
    {
        $base_bundle = SimTimeBundle::where('base_option', '=', 1)->first();

        $cost_if_no_discount = ($this->qty_hours / $base_bundle->qty_hours) * $base_bundle->cost;

        return 100 - ($this->cost / $cost_if_no_discount * 100);
    }

    public function cost_per_hour() 
    {
        return $this->cost / $this->qty_hours;
    }
}
