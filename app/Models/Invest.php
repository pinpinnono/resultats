<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invest extends Model
{

    /**
     * @var array $fillable
     */
    protected $fillable = [
        'type',
        'account_id',
        'data',
        'user_id',
        'compte_id'
    ];

    use HasFactory;
}
