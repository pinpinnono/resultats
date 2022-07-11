<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Daily extends Model
{
    /**
     * @var string $table
     */
    protected $table = 'daily';
    /**
     * @var array $fillable
     */
    protected $fillable = [
        'account_id',
        'date',
        'balance',
        'pips',
        'lots',
        'floatingPL',
        'profit',
        'growthEquity',
        'floatingPips',
    ];
    use HasFactory;
}
