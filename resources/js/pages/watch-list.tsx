import React, { useState } from 'react';
import { Head, router } from '@inertiajs/react';
import { AppShell } from '@/components/app-shell';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader } from '@/components/ui/card';

interface Movie {
    id: number;
    title: string;
    overview: string;
    poster_path: string | null;
    genres: string[];
    vote_average: number;
    type: string;
    release_date: string;
}

interface Props {
    watchList: Movie[];
    [key: string]: unknown;
}

export default function WatchList({ watchList }: Props) {
    const [movies, setMovies] = useState<Movie[]>(watchList);

    const handleRemoveFromWatchList = async (movieId: number) => {
        try {
            await fetch(route('movies.watchlist.destroy'), {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                },
                body: JSON.stringify({
                    movie_id: movieId,
                }),
            });

            setMovies(prev => prev.filter(movie => movie.id !== movieId));
        } catch (error) {
            console.error('Failed to remove from watch list:', error);
        }
    };

    const getPosterUrl = (movie: Movie) => {
        return movie.poster_path 
            ? `https://image.tmdb.org/t/p/w500${movie.poster_path}`
            : '/images/no-poster.jpg';
    };

    return (
        <AppShell>
            <Head title="My Watch List - Next Watch" />

            <div className="min-h-screen bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900">
                <div className="container mx-auto px-4 py-8">
                    {/* Header */}
                    <div className="flex items-center justify-between mb-8">
                        <div>
                            <h1 className="text-4xl font-bold text-white mb-2">
                                📋 My Watch List
                            </h1>
                            <p className="text-gray-300">
                                {movies.length} {movies.length === 1 ? 'item' : 'items'} to watch later
                            </p>
                        </div>
                        <Button onClick={() => router.get('/')} variant="outline">
                            ← Back to Recommendations
                        </Button>
                    </div>

                    {/* Watch List Grid */}
                    {movies.length === 0 ? (
                        <div className="text-center py-16">
                            <div className="text-6xl mb-4">📋</div>
                            <h2 className="text-2xl font-bold text-white mb-4">
                                Your watch list is empty
                            </h2>
                            <p className="text-gray-300 mb-8">
                                Start adding movies and shows you want to watch later
                            </p>
                            <Button onClick={() => router.get('/')}>
                                🔍 Discover Movies
                            </Button>
                        </div>
                    ) : (
                        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                            {movies.map((movie) => (
                                <Card key={movie.id} className="bg-white/10 border-white/20 backdrop-blur-sm overflow-hidden">
                                    <div className="relative group">
                                        <img
                                            src={getPosterUrl(movie)}
                                            alt={movie.title}
                                            className="w-full aspect-[2/3] object-cover"
                                            onError={(e) => {
                                                (e.target as HTMLImageElement).src = '/images/no-poster.jpg';
                                            }}
                                        />
                                        <div className="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                            <Button
                                                onClick={() => handleRemoveFromWatchList(movie.id)}
                                                variant="destructive"
                                                size="sm"
                                            >
                                                ❌ Remove
                                            </Button>
                                        </div>
                                    </div>

                                    <CardHeader className="pb-2">
                                        <h3 className="text-lg font-semibold text-white line-clamp-2">
                                            {movie.title}
                                        </h3>
                                        <div className="flex items-center gap-4 text-sm">
                                            <span className="text-yellow-400">
                                                ⭐ {movie.vote_average?.toFixed(1) || 'N/A'}
                                            </span>
                                            <span className="text-gray-300">
                                                {movie.type === 'movie' ? '🎬' : '📺'}
                                            </span>
                                        </div>
                                    </CardHeader>

                                    <CardContent className="pt-0">
                                        {movie.genres && movie.genres.length > 0 && (
                                            <div className="flex flex-wrap gap-1 mb-3">
                                                {movie.genres.slice(0, 2).map((genre, index) => (
                                                    <span 
                                                        key={index}
                                                        className="px-2 py-1 bg-purple-600/30 text-white text-xs rounded-full"
                                                    >
                                                        {genre}
                                                    </span>
                                                ))}
                                            </div>
                                        )}
                                        <p className="text-gray-300 text-sm line-clamp-3">
                                            {movie.overview || 'No description available.'}
                                        </p>
                                    </CardContent>
                                </Card>
                            ))}
                        </div>
                    )}

                    {/* Navigation */}
                    <div className="flex justify-center gap-4 mt-8">
                        <Button 
                            variant="outline" 
                            onClick={() => router.get('/')}
                        >
                            🎬 Get More Recommendations
                        </Button>
                        <Button 
                            variant="outline" 
                            onClick={() => router.get(route('movies.watched'))}
                        >
                            👀 View Watched Movies
                        </Button>
                    </div>
                </div>
            </div>
        </AppShell>
    );
}