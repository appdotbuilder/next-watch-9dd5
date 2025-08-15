<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\WatchList
 *
 * @property int $id
 * @property int $user_id
 * @property int $movie_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @property-read \App\Models\Movie $movie
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|WatchList newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WatchList newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WatchList query()
 * @method static \Illuminate\Database\Eloquent\Builder|WatchList whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WatchList whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WatchList whereMovieId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WatchList whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WatchList whereUpdatedAt($value)
 * @method static \Database\Factories\WatchListFactory factory($count = null, $state = [])
 * 
 * @mixin \Eloquent
 */
class WatchList extends Model
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
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that owns the watch list entry.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the movie in the watch list.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function movie(): BelongsTo
    {
        return $this->belongsTo(Movie::class);
    }
}