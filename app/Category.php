<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'title',
        'icon',
        'usage'
    ];

    /**
     * Get the purchases for the category.
     */
    public function purchases()
    {
        return $this->hasMany('App\Purchase');
    }
}
