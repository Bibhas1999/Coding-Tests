<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    public $table='transactions';
    protected $fillable = [
        'amount',
        'time',
        'from_user_wallet_id',
        'to_user_wallet_id',
    ];
}
