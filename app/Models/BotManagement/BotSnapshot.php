<?php

namespace App\Models\BotManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BotSnapshot extends Model
{
    use HasFactory;

    protected $guarded = [
        'id'
    ];

    public function age()
    {
        return "5 minutes ago lol";
    }
}
