<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanOrInsurenceRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'request_type_id',
        'request_type',
        'user_id',
        'status',
    ];
}
