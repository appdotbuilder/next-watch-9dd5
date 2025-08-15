import React from 'react';
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
    user_rating: 'liked' | 'disliked';
}

interface Props {
    watchedMovies: Movie[];
    [key: string]: unknown;
}

export default function Watched({ watchedMovies }: Props) {
    const likedMovies = watchedMovies.filter(movie => movie.user_rating === 'liked');
    const dislikedMovies = watchedMovies.filter(movie => movie.user_rating === 'disliked');

    const getPosterUrl = (movie: Movie) => {
        return movie.poster_path 
            ? `https://image.tmdb.org/t/p/w500${movie.poster_path}`
            : '/images/no-poster.jpg';
    };

    const MovieGrid = ({ movies, emptyMessage }: { movies: Movie[], emptyMessage: string }) => (
        <div>
            {movies.length === 0 ? (
                <div className="text-center py-8">
                    <p className="text-gray-400">{emptyMessage}</p>
                </div>
            ) : (
                <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    {movies.map((movie) => (
                        <Card key={movie.id} className="bg-white/10 border-white/20 backdrop-blur-sm overflow-hidden">
                            <div className="relative">
                                <img
                                    src={getPosterUrl(movie)}
                                    alt={movie.title}
                                    className="w-full aspect-[2/3] object-cover"
                                    onError={(e) => {
                                        (e.target as HTMLImageElement).src = '/images/no-poster.jpg';
                                    }}
                                />
                                <div className="absolute top-2 right-2">
                                    <span className={`px-2 py-1 rounded-full text-xs font-medium ${
                                        movie.user_rating === 'liked' 
                                            ? 'bg-green-600 text-white' 
                                            : 'bg-red-600 text-white'
                                    }`}>
                                        {movie.user_rating === 'liked' ? '❤️ Liked' : '👎 Disliked'}
                                    </span>
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
        </div>
    );

    return (
        <AppShell>
            <Head title="Watched Movies - Next Watch" />

            <div className="min-h-screen bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900">
                <div className="container mx-auto px-4 py-8">
                    {/* Header */}
                    <div className="flex items-center justify-between mb-8">
                        <div>
                            <h1 className="text-4xl font-bold text-white mb-2">
                                👀 Watched Movies & Shows
                            </h1>
                            <p className="text-gray-300">
                                {watchedMovies.length} {watchedMovies.length === 1 ? 'item' : 'items'} watched
                                {likedMovies.length > 0 && ` • ${likedMovies.length} liked`}
                                {dislikedMovies.length > 0 && ` • ${dislikedMovies.length} disliked`}
                            </p>
                        </div>
                        <Button onClick={() => router.get('/')} variant="outline">
                            ← Back to Recommendations
                        </Button>
                    </div>

                    {watchedMovies.length === 0 ? (
                        <div className="text-center py-16">
                            <div className="text-6xl mb-4">🎬</div>
                            <h2 className="text-2xl font-bold text-white mb-4">
                                No movies watched yet
                            </h2>
                            <p className="text-gray-300 mb-8">
                                Start rating movies and shows to build your preferences
                            </p>
                            <Button onClick={() => router.get('/')}>
                                🔍 Discover Movies
                            </Button>
                        </div>
                    ) : (
                        <div className="space-y-12">
                            {/* Liked Movies */}
                            {likedMovies.length > 0 && (
                                <section>
                                    <div className="flex items-center gap-3 mb-6">
                                        <h2 className="text-2xl font-bold text-white">❤️ Movies You Liked</h2>
                                        <span className="bg-green-600 text-white px-3 py-1 rounded-full text-sm">
                                            {likedMovies.length}
                                        </span>
                                    </div>
                                    <MovieGrid 
                                        movies={likedMovies} 
                                        emptyMessage="No liked movies yet" 
                                    />
                                </section>
                            )}

                            {/* Disliked Movies */}
                            {dislikedMovies.length > 0 && (
                                <section>
                                    <div className="flex items-center gap-3 mb-6">
                                        <h2 className="text-2xl font-bold text-white">👎 Movies You Disliked</h2>
                                        <span className="bg-red-600 text-white px-3 py-1 rounded-full text-sm">
                                            {dislikedMovies.length}
                                        </span>
                                    </div>
                                    <MovieGrid 
                                        movies={dislikedMovies} 
                                        emptyMessage="No disliked movies yet" 
                                    />
                                </section>
                            )}
                        </div>
                    )}

                    {/* Navigation */}
                    <div className="flex justify-center gap-4 mt-12">
                        <Button 
                            variant="outline" 
                            onClick={() => router.get('/')}
                        >
                            🎬 Get More Recommendations
                        </Button>
                        <Button 
                            variant="outline" 
                            onClick={() => router.get(route('movies.watchlist'))}
                        >
                            📋 View Watch List
                        </Button>
                    </div>
                </div>
            </div>
        </AppShell>
    );
}