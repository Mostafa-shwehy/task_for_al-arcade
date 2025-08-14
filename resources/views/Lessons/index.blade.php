@extends('app')

@section('title', 'Video Lessons')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <div class="gradient-bg rounded-2xl p-8 text-white">
            <h1 class="text-4xl font-bold mb-4">
                <i class="fas fa-play-circle mr-3"></i>
                Video Lessons
            </h1>
            <p class="text-purple-100 text-lg">
                Explore our interactive video lessons with smart checkpoints and engaging content
            </p>
        </div>
    </div>

    <!-- Lessons Grid -->
    @if($lessons->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
            @foreach($lessons as $lesson)
                <div class="lesson-card bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="relative">
                        <!-- Video Thumbnail -->
                        <div class="bg-gradient-to-br from-purple-400 to-blue-500 h-48 flex items-center justify-center">
                            <i class="fas fa-play-circle text-white text-4xl opacity-80"></i>
                        </div>

                        <!-- Duration Badge -->
                        <div class="absolute top-3 right-3 bg-black bg-opacity-70 text-white px-2 py-1 rounded-md text-sm">
                            <i class="fas fa-clock mr-1"></i>
                            {{ gmdate('i:s', $lesson->duration_seconds) }}
                        </div>

                        <!-- Checkpoints Count -->
                        @if($lesson->checkpoints_count > 0)
                            <div class="absolute top-3 left-3 bg-green-500 text-white px-2 py-1 rounded-full text-xs font-medium">
                                <i class="fas fa-map-marker-alt mr-1"></i>
                                {{ $lesson->checkpoints_count }}
                            </div>
                        @endif
                    </div>

                    <div class="p-6">
                        <h3 class="font-semibold text-lg text-gray-800 mb-2 line-clamp-2">
                            {{ $lesson->title }}
                        </h3>

                        <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                            <span>
                                <i class="fas fa-calendar-alt mr-1"></i>
                                {{ $lesson->created_at->format('M d, Y') }}
                            </span>
                        </div>

                        <a href="{{ route('lessons.show', $lesson->id) }}"
                           class="w-full bg-gradient-to-r from-purple-500 to-blue-500 text-white py-3 px-6 rounded-lg font-medium hover:from-purple-600 hover:to-blue-600 transition duration-300 flex items-center justify-center group">
                            <i class="fas fa-play mr-2 group-hover:scale-110 transition-transform duration-300"></i>
                            Start Learning
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="flex justify-center">
            {{ $lessons->links('pagination::tailwind') }}
        </div>
    @else
        <!-- Empty State -->
        <div class="text-center py-16">
            <div class="bg-white rounded-2xl p-12 shadow-lg max-w-md mx-auto">
                <i class="fas fa-video text-gray-300 text-6xl mb-6"></i>
                <h3 class="text-2xl font-semibold text-gray-600 mb-4">No Lessons Available</h3>
                <p class="text-gray-500">
                    There are no video lessons available at the moment. Check back later!
                </p>
            </div>
        </div>
    @endif
</div>
@endsection
