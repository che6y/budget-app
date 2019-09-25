<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MonthlySpending extends Model
{
    protected $fillable = [
        'month',
        'year',
        'amount'
    ];

}
