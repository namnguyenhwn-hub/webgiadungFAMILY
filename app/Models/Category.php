<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'sort_order',
        'slug',
        'description',
        'image',
    ];

    protected static function boot()
    {
        parent::boot();
        static::saving(function ($category) {
            if (empty($category->slug) && !empty($category->name)) {
                $category->slug = \Illuminate\Support\Str::slug($category->name) . '-' . rand(10, 999);
            }
            if (!isset($category->attributes['sort_order'])) {
                $category->attributes['sort_order'] = 1;
            }
        });
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
