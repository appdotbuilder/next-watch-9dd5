import React, { useState, useCallback } from 'react';
import { Head, router } from '@inertiajs/react';
import { AppShell } from '@/components/app-shell';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader } from '@/components/ui/card';

interface Movie {
    id: number;
    tmdb_id: number;
    title: string;
    overview: string;
    poster_path: string | null;
    backdrop_path: string | null;
    genres: string[];
    vote_average: number;
    vote_count: number;
    release_date: string;
    type: string;
    recommendation_reason?: string;
    poster_url?: string;
    backdrop_url?: string;
}

interface User {
    id: number;
    name: string;
    email: string;
}

interface Props {
    recommendations: Movie[];
    watchListIds: number[];
    preferenceIds: number[];
    user: User | null;
    [key: string]: unknown;
}

export default function Recommendations({ recommendations, watchListIds, preferenceIds, user }: Props) {
    const [currentIndex, setCurrentIndex] = useState(0);
    const [watchList, setWatchList] = useState<number[]>(watchListIds);
    const [preferences, setPreferences] = useState<number[]>(preferenceIds);
    const [isLoading, setIsLoading] = useState(false);

    const currentMovie = recommendations[currentIndex];

    const handlePreference = useCallback(async (rating: 'liked' | 'disliked', markAsWatched = true) => {
        if (!currentMovie || !user) return;

        setIsLoading(true);
        
        try {
            await fetch(route('movies.preference'), {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                },
                body: JSON.stringify({
                    movie_id: currentMovie.id,
                    rating: rating,
                }),
            });

            if (markAsWatched) {
                setPreferences(prev => [...prev, currentMovie.id]);
            }
            
            // Move to next recommendation
            setCurrentIndex(prev => Math.min(prev + 1, recommendations.length - 1));
        } catch (error) {
            console.error('Failed to save preference:', error);
        } finally {
            setIsLoading(false);
        }
    }, [currentMovie, user, recommendations.length]);

    const handleWatchList = useCallback(async (action: 'add' | 'remove') => {
        if (!currentMovie || !user) return;

        setIsLoading(true);

        try {
            if (action === 'add') {
                await fetch(route('watchlist.store'), {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                    },
                    body: JSON.stringify({
                        movie_id: currentMovie.id,
                    }),
                });
                setWatchList(prev => [...prev, currentMovie.id]);
            } else {
                await fetch(route('movies.watchlist.destroy'), {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                    },
                    body: JSON.stringify({
                        movie_id: currentMovie.id,
                    }),
                });
                setWatchList(prev => prev.filter(id => id !== currentMovie.id));
            }

            // Move to next recommendation after adding to watch list
            if (action === 'add') {
                setCurrentIndex(prev => Math.min(prev + 1, recommendations.length - 1));
            }
        } catch (error) {
            console.error('Failed to update watch list:', error);
        } finally {
            setIsLoading(false);
        }
    }, [currentMovie, user, recommendations.length]);

    const handleSkip = useCallback(() => {
        setCurrentIndex(prev => Math.min(prev + 1, recommendations.length - 1));
    }, [recommendations.length]);

    const getPosterUrl = (movie: Movie) => {
        return movie.poster_path 
            ? `https://image.tmdb.org/t/p/w500${movie.poster_path}`
            : '/images/no-poster.jpg';
    };

    if (!currentMovie) {
        return (
            <AppShell>
                <Head title="No More Recommendations - Next Watch" />
                <div className="container mx-auto px-4 py-8">
                    <div className="text-center">
                        <h1 className="text-3xl font-bold mb-4">🎉 You've seen it all!</h1>
                        <p className="text-lg text-gray-600 mb-8">
                            Check back later for more personalized recommendations
                        </p>
                        <Button onClick={() => router.get('/')}>
                            🔄 Refresh Recommendations
                        </Button>
                    </div>
                </div>
            </AppShell>
        );
    }

    const isInWatchList = watchList.includes(currentMovie.id);
    const isWatched = preferences.includes(currentMovie.id);

    return (
        <AppShell>
            <Head title="Next Watch - Your Personalized Recommendations" />

            <div className="min-h-screen bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900">
                <div className="container mx-auto px-4 py-8">
                    {/* Header */}
                    <div className="text-center mb-8">
                        <h1 className="text-4xl font-bold text-white mb-2">
                            🎬 Next Watch
                        </h1>
                        <p className="text-gray-300">
                            {user ? `Hey ${user.name}! ` : 'Welcome! '}
                            Recommendation {currentIndex + 1} of {recommendations.length}
                        </p>
                    </div>

                    {/* Progress Bar */}
                    <div className="w-full bg-gray-700 rounded-full h-2 mb-8">
                        <div 
                            className="bg-gradient-to-r from-purple-500 to-pink-500 h-2 rounded-full transition-all duration-300"
                            style={{ width: `${((currentIndex + 1) / recommendations.length) * 100}%` }}
                        />
                    </div>

                    {/* Movie Card */}
                    <div className="max-w-2xl mx-auto">
                        <Card className="bg-white/10 border-white/20 backdrop-blur-lg overflow-hidden">
                            <div className="relative">
                                {currentMovie.backdrop_path && (
                                    <div 
                                        className="h-48 bg-cover bg-center"
                                        style={{ 
                                            backgroundImage: `url(https://image.tmdb.org/t/p/w1280${currentMovie.backdrop_path})` 
                                        }}
                                    >
                                        <div className="absolute inset-0 bg-black/50" />
                                    </div>
                                )}
                                
                                <CardHeader className="relative">
                                    <div className="flex items-start gap-4">
                                        <img
                                            src={getPosterUrl(currentMovie)}
                                            alt={currentMovie.title}
                                            className="w-24 h-36 object-cover rounded-lg shadow-lg"
                                            onError={(e) => {
                                                (e.target as HTMLImageElement).src = '/images/no-poster.jpg';
                                            }}
                                        />
                                        <div className="flex-1">
                                            <h2 className="text-2xl font-bold text-white mb-2">
                                                {currentMovie.title}
                                            </h2>
                                            <div className="flex items-center gap-4 mb-3">
                                                <span className="text-yellow-400 font-semibold">
                                                    ⭐ {currentMovie.vote_average?.toFixed(1) || 'N/A'}
                                                </span>
                                                <span className="text-gray-300 text-sm">
                                                    {currentMovie.type === 'movie' ? '🎬 Movie' : '📺 TV Show'}
                                                </span>
                                            </div>
                                            {currentMovie.genres && currentMovie.genres.length > 0 && (
                                                <div className="flex flex-wrap gap-2 mb-3">
                                                    {currentMovie.genres.slice(0, 3).map((genre, index) => (
                                                        <span 
                                                            key={index}
                                                            className="px-3 py-1 bg-purple-600/30 text-white text-sm rounded-full"
                                                        >
                                                            {genre}
                                                        </span>
                                                    ))}
                                                </div>
                                            )}
                                        </div>
                                    </div>
                                </CardHeader>
                            </div>

                            <CardContent className="space-y-4">
                                {/* AI Recommendation Reason */}
                                {currentMovie.recommendation_reason && (
                                    <div className="bg-purple-600/20 p-4 rounded-lg border border-purple-500/30">
                                        <p className="text-purple-200 text-sm font-medium">
                                            🤖 {currentMovie.recommendation_reason}
                                        </p>
                                    </div>
                                )}

                                {/* Overview */}
                                <p className="text-gray-300 text-sm leading-relaxed">
                                    {currentMovie.overview || 'No description available.'}
                                </p>

                                {/* Action Buttons */}
                                {!user ? (
                                    <div className="text-center py-4">
                                        <p className="text-gray-300 mb-4">Sign in to rate and save movies!</p>
                                        <div className="flex gap-2 justify-center">
                                            <Button onClick={() => router.get('/login')}>
                                                Sign In
                                            </Button>
                                            <Button variant="outline" onClick={handleSkip}>
                                                Skip
                                            </Button>
                                        </div>
                                    </div>
                                ) : (
                                    <div className="grid grid-cols-2 gap-3">
                                        {/* Top Row - Main Actions */}
                                        <Button
                                            onClick={() => handlePreference('liked')}
                                            disabled={isLoading || isWatched}
                                            className="bg-green-600 hover:bg-green-700 text-white"
                                        >
                                            ❤️ Like
                                        </Button>
                                        <Button
                                            onClick={() => handlePreference('disliked')}
                                            disabled={isLoading || isWatched}
                                            variant="destructive"
                                        >
                                            👎 Dislike
                                        </Button>

                                        {/* Bottom Row - Secondary Actions */}
                                        <Button
                                            onClick={() => handlePreference('liked', true)}
                                            disabled={isLoading || isWatched}
                                            className="bg-blue-600 hover:bg-blue-700 text-white text-sm"
                                        >
                                            ✅ Watched & Liked
                                        </Button>
                                        <Button
                                            onClick={() => handlePreference('disliked', true)}
                                            disabled={isLoading || isWatched}
                                            className="bg-red-600 hover:bg-red-700 text-white text-sm"
                                        >
                                            ❌ Watched & Disliked
                                        </Button>

                                        {/* Watch List Button */}
                                        <Button
                                            onClick={() => handleWatchList(isInWatchList ? 'remove' : 'add')}
                                            disabled={isLoading || isWatched}
                                            variant="outline"
                                            className="col-span-2"
                                        >
                                            {isInWatchList ? '📋 Remove from Watch List' : '📋 Add to Watch List'}
                                        </Button>

                                        {/* Skip Button */}
                                        <Button
                                            onClick={handleSkip}
                                            disabled={isLoading}
                                            variant="ghost"
                                            className="col-span-2 text-gray-400 hover:text-white"
                                        >
                                            ⏭️ Skip for Now
                                        </Button>
                                    </div>
                                )}

                                {isWatched && (
                                    <p className="text-center text-yellow-400 text-sm">
                                        ✨ Already in your watched list
                                    </p>
                                )}
                            </CardContent>
                        </Card>
                    </div>

                    {/* Navigation */}
                    {user && (
                        <div className="flex justify-center gap-4 mt-8">
                            <Button 
                                variant="outline" 
                                onClick={() => router.get(route('watchlist.index'))}
                            >
                                📋 My Watch List
                            </Button>
                            <Button 
                                variant="outline" 
                                onClick={() => router.get(route('movies.watched'))}
                            >
                                👀 Watched Movies
                            </Button>
                        </div>
                    )}
                </div>
            </div>
        </AppShell>
    );
}