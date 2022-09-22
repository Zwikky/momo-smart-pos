<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payer extends Model
{
    use HasFactory;
    
    protected $fillable = ['name', 'cellphone', 'momo_balance', 'uuid', 'verified'];
}