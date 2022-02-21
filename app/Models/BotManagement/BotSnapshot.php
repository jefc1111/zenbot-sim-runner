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
        return $this->created_at->diffForHumans(Carbon::now());
    }
}
