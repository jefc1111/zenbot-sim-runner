<?php

namespace App\Models\BotManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class BotSnapshot extends Model
{
    use HasFactory;

    protected $guarded = [
        'id'
    ];

    public function age()
    {
        return $this->created_at->diffForHumans(Carbon::now(), true);
    }

    public function asset_pct()
    {
        return 100 - $this->currency_pct();
    }

    public function currency_pct()
    {
        return $this->asset_capital ? ($this->currency_amount / ($this->asset_capital + $this->currency_amount)) * 100 : 0;
    }
}
