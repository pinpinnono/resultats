<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class History extends Model
{
     /**
     * @var string $table
     */
    protected $table = 'history';
    /**
     * @var array $fillable
     */
    protected $fillable = [
        'account_id',
        'opentime',
        'closetime',
        'action',
        'commission',
        'interest',
        'profit',
        'comment',
        'balance',
    ];
    use HasFactory;
}
