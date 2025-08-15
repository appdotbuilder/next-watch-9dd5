<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\UserPreference
 *
 * @property int $id
 * @property int $user_id
 * @property int $movie_id
 * @property string $rating
 * @property bool $watched
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @property-read \App\Models\Movie $movie
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|UserPreference newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserPreference newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserPreference query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserPreference whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserPreference whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserPreference whereMovieId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserPreference whereRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserPreference whereWatched($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserPreference whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserPreference whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserPreference liked()
 * @method static \Illuminate\Database\Eloquent\Builder|UserPreference disliked()
 * @method static \Database\Factories\UserPreferenceFactory factory($count = null, $state = [])
 * 
 * @mixin \Eloquent
 */
class UserPreference extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'movie_id',
        'rating',
        'watched',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'watched' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that owns the preference.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the movie that this preference is for.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function movie(): BelongsTo
    {
        return $this->belongsTo(Movie::class);
    }

    /**
     * Scope a query to only include liked preferences.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeLiked($query)
    {
        return $query->where('rating', 'liked');
    }

    /**
     * Scope a query to only include disliked preferences.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDisliked($query)
    {
        return $query->where('rating', 'disliked');
    }
}