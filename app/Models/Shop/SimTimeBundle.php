<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Model;

class SimTimeBundle extends Model
{
    public function get_discount()
    {
        $base_bundle = SimTimeBundle::where('base_option', '=', 1)->first();

        return '??? ??';
    }

    private function cost_per_hour() 
    {
        return $this->cost / $this->qty_hours;
    }
}
