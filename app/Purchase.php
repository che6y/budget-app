<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $fillable = [
        'title',
        'description',
        'amount',
        'cost',
        'user_id',
        'category_id',
    ];

    /**
     * Get the category that owns the purchase.
     */
    public function category()
    {
        return $this->belongsTo('App\Category');
    }

}
