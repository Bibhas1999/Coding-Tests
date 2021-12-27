<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;
    
    public $table='loans';

    protected $fillable = [
        'amount',
        'type',
        'agent_name',
        'rate_of_interest',
        'duration',
    ];
}
