<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Post extends Model
{
    protected  $table = "posts";

    protected $fillable = [
        'title','category_id','image','status','content','description'
    ];


    public function getCreatedAtAttribute($value)
    {
        return date('d/m/y',strtotime($value));
    }

    public function getUpdatedAtAttribute($value)
    {
        return date('d/m/y',strtotime($value));
    }

    protected $attributes = [
        'link' => null,
    ];

    /**
     * Get the user that owns the Post
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    use HasFactory;

}
