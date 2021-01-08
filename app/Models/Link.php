<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'link',
        'category_id',
        'name'
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['category'];

    public function category()
    {
        $this->hasOne(Category::class);
    }

    public function user()
    {
        $this->belongsTo(User::class);
    }
}
