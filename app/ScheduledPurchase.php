<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ScheduledPurchase extends Model
{
    protected $fillable = [
        'title',
        'cost',
        'user_id',
        'is_income',
        'category_id',
        'created_at'
    ];

    /**
     * Get the category that owns the purchase.
     */
    public function category()
    {
        return $this->belongsTo('App\Category');
    }

}
