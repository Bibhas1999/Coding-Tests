<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserHasWallet extends Model
{
    use HasFactory;
    protected $fillable = ['wallet_id', 'wallet_balance','coins','unit_coin_value','user_id']; 
}
