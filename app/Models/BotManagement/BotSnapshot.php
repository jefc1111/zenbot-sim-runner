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
        return Carbon::now()->diffForHumans($this->created_at);;
    }
}
