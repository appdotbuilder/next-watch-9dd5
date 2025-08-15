<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Movie
 *
 * @property int $id
 * @property int $tmdb_id
 * @property string $type
 * @property string $title
 * @property string|null $overview
 * @property string|null $poster_path
 * @property string|null $backdrop_path
 * @property array|null $genres
 * @property float|null $vote_average
 * @property int|null $vote_count
 * @property \Illuminate\Support\Carbon|null $release_date
 * @property int|null $runtime
 * @property string|null $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\UserPreference> $preferences
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\WatchList> $watchLists
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|Movie newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Movie newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Movie query()
 * @method static \Illuminate\Database\Eloquent\Builder|Movie whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Movie whereTmdbId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Movie whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Movie whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Movie whereOverview($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Movie wherePosterPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Movie whereBackdropPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Movie whereGenres($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Movie whereVoteAverage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Movie whereVoteCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Movie whereReleaseDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Movie whereRuntime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Movie whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Movie whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Movie whereUpdatedAt($value)
 * @method static \Database\Factories\MovieFactory factory($count = null, $state = [])
 * 
 * @mixin \Eloquent
 */
class Movie extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'tmdb_id',
        'type',
        'title',
        'overview',
        'poster_path',
        'backdrop_path',
        'genres',
        'vote_average',
        'vote_count',
        'release_date',
        'runtime',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'genres' => 'array',
        'vote_average' => 'decimal:1',
        'vote_count' => 'integer',
        'release_date' => 'date',
        'runtime' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user preferences for this movie.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function preferences(): HasMany
    {
        return $this->hasMany(UserPreference::class);
    }

    /**
     * Get the watch lists containing this movie.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function watchLists(): HasMany
    {
        return $this->hasMany(WatchList::class);
    }

    /**
     * Get the full poster URL.
     *
     * @return string|null
     */
    public function getPosterUrlAttribute(): ?string
    {
        return $this->poster_path ? "https://image.tmdb.org/t/p/w500{$this->poster_path}" : null;
    }

    /**
     * Get the full backdrop URL.
     *
     * @return string|null
     */
    public function getBackdropUrlAttribute(): ?string
    {
        return $this->backdrop_path ? "https://image.tmdb.org/t/p/w1280{$this->backdrop_path}" : null;
    }
}