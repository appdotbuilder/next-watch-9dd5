import React from 'react';
import { Head, Link } from '@inertiajs/react';
import { AppShell } from '@/components/app-shell';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';

export default function Welcome() {
    return (
        <AppShell>
            <Head title="Next Watch - Your Personalized Movie & TV Recommendations" />

            <div className="min-h-screen bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900">
                {/* Hero Section */}
                <div className="relative overflow-hidden">
                    <div className="container mx-auto px-6 py-16">
                        <div className="text-center max-w-4xl mx-auto">
                            <div className="mb-8">
                                <h1 className="text-5xl md:text-7xl font-bold text-white mb-6">
                                    🎬 Next Watch
                                </h1>
                                <p className="text-xl md:text-2xl text-gray-300 mb-8">
                                    Discover your next favorite movie or TV show with AI-powered personalized recommendations
                                </p>
                                <div className="flex flex-col sm:flex-row gap-4 justify-center">
                                    <Button asChild size="lg" className="text-lg px-8 py-6">
                                        <Link href="/register">
                                            🚀 Start Discovering
                                        </Link>
                                    </Button>
                                    <Button asChild variant="outline" size="lg" className="text-lg px-8 py-6">
                                        <Link href="/login">
                                            🎭 Sign In
                                        </Link>
                                    </Button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {/* Features Section */}
                <div className="container mx-auto px-6 py-16">
                    <div className="text-center mb-16">
                        <h2 className="text-3xl md:text-4xl font-bold text-white mb-4">
                            ✨ Intelligent Recommendations Just for You
                        </h2>
                        <p className="text-lg text-gray-300 max-w-2xl mx-auto">
                            Our AI learns your preferences and suggests content you'll love while avoiding what you don't
                        </p>
                    </div>

                    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-16">
                        <Card className="bg-white/10 border-white/20 backdrop-blur-sm">
                            <CardHeader>
                                <CardTitle className="text-white flex items-center gap-3">
                                    🤖 AI-Powered Matching
                                </CardTitle>
                            </CardHeader>
                            <CardContent>
                                <p className="text-gray-300">
                                    Advanced algorithms analyze your viewing history and preferences to find perfect matches
                                </p>
                            </CardContent>
                        </Card>

                        <Card className="bg-white/10 border-white/20 backdrop-blur-sm">
                            <CardHeader>
                                <CardTitle className="text-white flex items-center gap-3">
                                    👆 Swipe to Rate
                                </CardTitle>
                            </CardHeader>
                            <CardContent>
                                <p className="text-gray-300">
                                    Like Tinder for movies! Swipe or tap to rate recommendations and improve your feed
                                </p>
                            </CardContent>
                        </Card>

                        <Card className="bg-white/10 border-white/20 backdrop-blur-sm">
                            <CardHeader>
                                <CardTitle className="text-white flex items-center gap-3">
                                    📱 Mobile First
                                </CardTitle>
                            </CardHeader>
                            <CardContent>
                                <p className="text-gray-300">
                                    Optimized for mobile with large buttons, smooth gestures, and offline support
                                </p>
                            </CardContent>
                        </Card>

                        <Card className="bg-white/10 border-white/20 backdrop-blur-sm">
                            <CardHeader>
                                <CardTitle className="text-white flex items-center gap-3">
                                    🔍 Smart Search
                                </CardTitle>
                            </CardHeader>
                            <CardContent>
                                <p className="text-gray-300">
                                    Find any movie or show and mark it as watched to improve your recommendations
                                </p>
                            </CardContent>
                        </Card>

                        <Card className="bg-white/10 border-white/20 backdrop-blur-sm">
                            <CardHeader>
                                <CardTitle className="text-white flex items-center gap-3">
                                    📋 Watch Lists
                                </CardTitle>
                            </CardHeader>
                            <CardContent>
                                <p className="text-gray-300">
                                    Save interesting content to your personal watch list for later viewing
                                </p>
                            </CardContent>
                        </Card>

                        <Card className="bg-white/10 border-white/20 backdrop-blur-sm">
                            <CardHeader>
                                <CardTitle className="text-white flex items-center gap-3">
                                    👤 Guest Mode
                                </CardTitle>
                            </CardHeader>
                            <CardContent>
                                <p className="text-gray-300">
                                    Start immediately without signing up - your preferences are saved locally
                                </p>
                            </CardContent>
                        </Card>
                    </div>

                    {/* Demo Preview */}
                    <div className="bg-white/5 rounded-2xl p-8 backdrop-blur-sm border border-white/10">
                        <div className="text-center mb-8">
                            <h3 className="text-2xl font-bold text-white mb-4">
                                🎯 See It In Action
                            </h3>
                            <p className="text-gray-300">
                                Get personalized recommendations based on what you love
                            </p>
                        </div>

                        <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                            {/* Mock recommendation cards */}
                            <div className="bg-gray-800/50 rounded-lg p-4 border border-gray-700">
                                <div className="aspect-[2/3] bg-gradient-to-b from-blue-500 to-purple-600 rounded-lg mb-3 flex items-center justify-center">
                                    <span className="text-white text-4xl">🎬</span>
                                </div>
                                <h4 className="text-white font-semibold mb-2">Sci-Fi Thriller</h4>
                                <p className="text-gray-400 text-sm mb-3">
                                    Because you liked Inception and Blade Runner 2049
                                </p>
                                <div className="flex gap-2">
                                    <Button size="sm" className="flex-1">❤️ Like</Button>
                                    <Button size="sm" variant="outline" className="flex-1">👎 Pass</Button>
                                </div>
                            </div>

                            <div className="bg-gray-800/50 rounded-lg p-4 border border-gray-700">
                                <div className="aspect-[2/3] bg-gradient-to-b from-green-500 to-teal-600 rounded-lg mb-3 flex items-center justify-center">
                                    <span className="text-white text-4xl">📺</span>
                                </div>
                                <h4 className="text-white font-semibold mb-2">Comedy Series</h4>
                                <p className="text-gray-400 text-sm mb-3">
                                    Trending comedy with 8.7★ rating
                                </p>
                                <div className="flex gap-2">
                                    <Button size="sm" className="flex-1">❤️ Like</Button>
                                    <Button size="sm" variant="outline" className="flex-1">📋 Watch Later</Button>
                                </div>
                            </div>

                            <div className="bg-gray-800/50 rounded-lg p-4 border border-gray-700">
                                <div className="aspect-[2/3] bg-gradient-to-b from-red-500 to-pink-600 rounded-lg mb-3 flex items-center justify-center">
                                    <span className="text-white text-4xl">🎭</span>
                                </div>
                                <h4 className="text-white font-semibold mb-2">Drama Film</h4>
                                <p className="text-gray-400 text-sm mb-3">
                                    Award-winning drama similar to your favorites
                                </p>
                                <div className="flex gap-2">
                                    <Button size="sm" className="flex-1">✅ Watched & Liked</Button>
                                    <Button size="sm" variant="outline" className="flex-1">❌ Didn't Like</Button>
                                </div>
                            </div>
                        </div>
                    </div>

                    {/* CTA Section */}
                    <div className="text-center mt-16">
                        <h3 className="text-3xl font-bold text-white mb-4">
                            🎉 Ready to Discover Your Next Favorite?
                        </h3>
                        <p className="text-lg text-gray-300 mb-8 max-w-2xl mx-auto">
                            Join thousands of users who've found their perfect movies and shows with Next Watch
                        </p>
                        <div className="flex flex-col sm:flex-row gap-4 justify-center">
                            <Button asChild size="lg" className="text-lg px-8 py-6">
                                <Link href="/register">
                                    🌟 Create Free Account
                                </Link>
                            </Button>
                            <Button asChild variant="outline" size="lg" className="text-lg px-8 py-6">
                                <Link href="/recommendations">
                                    👀 Try as Guest
                                </Link>
                            </Button>
                        </div>
                    </div>
                </div>

                {/* Footer */}
                <div className="border-t border-white/10 mt-16">
                    <div className="container mx-auto px-6 py-8">
                        <div className="text-center text-gray-400">
                            <p className="mb-4">
                                🎬 Next Watch - Powered by AI and TMDB
                            </p>
                            <p className="text-sm">
                                Discover • Rate • Watch • Repeat
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </AppShell>
    );
}