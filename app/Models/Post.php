<?php

namespace App\Models;

use App\Casts\HtmlSpecialCharsCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Mews\Purifier\Casts\CleanHtml;

class Post extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'title',
        'slug',
        'content',
    ];

    protected $dates = ['created_at', 'updated_at'];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'name' => HtmlSpecialCharsCast::class,
        'slug' => HtmlSpecialCharsCast::class,
        'content' => CleanHtml::class,
    ];


    /**
     * Categories of the post
     *
     * @return BelongsToMany
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'posts_categories');
    }
}
