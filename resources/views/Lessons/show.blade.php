@extends('app')

@section('title', $lesson->title)

@section('content')
    <div class="max-w-6xl mx-auto">
        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ route('lessons.index') }}"
                class="inline-flex items-center text-gray-600 hover:text-purple-600 transition duration-300">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Lessons
            </a>
        </div>

        <!-- Video Player Section -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden mb-8">
            <div class="relative">
                <!-- Video Player -->
                <div class="bg-black relative">
                    @if (str_contains($lesson->video_url, 'youtube.com') || str_contains($lesson->video_url, 'youtu.be'))
                        <!-- YouTube Embed -->
                        <div class="relative w-full" style="padding-bottom: 56.25%; height: 0;">
                            <iframe id="youtubePlayer" class="absolute top-0 left-0 w-full h-full"
                                src="{{ $lesson->getYouTubeEmbedUrl() }}" frameborder="0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen>
                            </iframe>
                        </div>
                    @else
                        <!-- Regular Video Player -->
                        <video id="videoPlayer" class="w-full h-auto" controls preload="metadata"
                            style="min-height: 400px;">
                            <source src="{{ $lesson->video_url }}" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                        <div id="checkoutOverlay"
                            class="absolute inset-0 bg-black bg-opacity-60 flex items-center justify-center text-white z-20"
                            style="display:none;">
                            <div class="text-center">
                                <h3 class="text-2xl font-bold mb-2">Get Full Access</h3>
                                <p class="mb-4">Purchase to continue watching.</p>
                                <a href="{{ route('checkpoints.next', $lesson->id) }}"
                                    class="bg-purple-500 hover:bg-purple-600 text-white px-6 py-2 rounded-lg transition">
                                    Checkout Now
                                </a>
                            </div>
                        </div>
                        <!-- Loading Spinner -->
                        <div id="videoLoader"
                            class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center">
                            <div class="animate-spin rounded-full h-16 w-16 border-b-2 border-white"></div>
                        </div>
                    @endif
                </div>

                <!-- Video Progress Bar with Checkpoints -->
                <div class="absolute bottom-16 left-0 right-0 px-4">
                    <div class="relative h-1 bg-gray-600 rounded-full overflow-hidden">
                        <div id="progressBar" class="h-full bg-purple-500 rounded-full transition-all duration-300"
                            style="width: 0%"></div>

                        <!-- Checkpoint Markers -->
                        @foreach ($lesson->checkpoints as $checkpoint)
                            <div class="checkpoint-marker absolute top-0 w-3 h-3 bg-yellow-400 rounded-full border-2 border-white transform -translate-y-1 cursor-pointer hover:scale-125 transition-transform"
                                style="left: {{ ($checkpoint->timestamp_seconds / $lesson->duration_seconds) * 100 }}%"
                                data-timestamp="{{ $checkpoint->timestamp_seconds }}"
                                data-type="{{ $checkpoint->event_type }}"
                                title="{{ ucfirst($checkpoint->event_type) }} at {{ gmdate('i:s', $checkpoint->timestamp_seconds) }}">
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Video Info -->
            <div class="p-6">
                <h1 class="text-3xl font-bold text-gray-800 mb-4">{{ $lesson->title }}</h1>

                <div class="flex items-center space-x-6 text-sm text-gray-600 mb-4">
                    <span>
                        <i class="fas fa-clock mr-2"></i>
                        Duration: {{ gmdate('H:i:s', $lesson->duration_seconds) }}
                    </span>
                    <span>
                        <i class="fas fa-map-marker-alt mr-2"></i>
                        {{ $lesson->checkpoints->count() }} Checkpoints
                    </span>
                    <span>
                        <i class="fas fa-calendar-alt mr-2"></i>
                        {{ $lesson->created_at->format('M d, Y') }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Checkpoints List -->
        @if ($lesson->checkpoints->count() > 0)
            <div class="bg-white rounded-2xl shadow-xl p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">
                    <i class="fas fa-list-ul mr-3"></i>
                    Checkpoints
                </h2>

                <div class="space-y-4">
                    @foreach ($lesson->checkpoints->sortBy('timestamp_seconds') as $checkpoint)
                        <div class="checkpoint-item flex items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition duration-300 cursor-pointer"
                            data-timestamp="{{ $checkpoint->timestamp_seconds }}"
                            data-checkpoint-id="{{ $checkpoint->id }}">
                            <div
                                class="flex-shrink-0 w-12 h-12 rounded-full flex items-center justify-center mr-4
                                    {{ $checkpoint->event_type === 'quiz'
                                        ? 'bg-blue-100 text-blue-600'
                                        : ($checkpoint->event_type === 'note'
                                            ? 'bg-green-100 text-green-600'
                                            : 'bg-yellow-100 text-yellow-600') }}">
                                <i
                                    class="fas {{ $checkpoint->event_type === 'quiz'
                                        ? 'fa-question-circle'
                                        : ($checkpoint->event_type === 'note'
                                            ? 'fa-sticky-note'
                                            : 'fa-bell') }}"></i>
                            </div>

                            <div class="flex-grow">
                                <div class="flex items-center justify-between">
                                    <h3 class="font-semibold text-gray-800">
                                        {{ ucfirst($checkpoint->event_type) }}
                                    </h3>
                                    <span class="text-sm text-gray-500">
                                        {{ gmdate('i:s', $checkpoint->timestamp_seconds) }}
                                    </span>
                                </div>

                                @php
                                    $eventData = is_array($checkpoint->event_data)
                                        ? $checkpoint->event_data
                                        : json_decode($checkpoint->event_data, true);
                                @endphp

                                @if (isset($eventData['title']))
                                    <p class="text-gray-600 text-sm mt-1">
                                        {{ $eventData['title'] }}
                                    </p>
                                @endif
                            </div>

                            <div class="flex-shrink-0 ml-4">
                                <i class="fas fa-play text-purple-500"></i>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <!-- Checkpoint Popup -->
    <div id="checkpointPopup" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="checkpoint-popup bg-white rounded-2xl p-8 max-w-lg w-full mx-4 shadow-2xl">
            <div class="flex items-center justify-between mb-6">
                <h3 id="popupTitle" class="text-2xl font-bold text-gray-800"></h3>
                <button id="closePopup" class="text-gray-400 hover:text-gray-600 text-2xl">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div id="popupContent" class="text-gray-600 mb-6"></div>

            <div class="flex justify-end space-x-4">
                <button id="continueVideo"
                    class="bg-purple-500 hover:bg-purple-600 text-white px-6 py-2 rounded-lg transition duration-300">
                    <i class="fas fa-play mr-2"></i>
                    Continue
                </button>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script src="https://www.youtube.com/iframe_api"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const video = document.getElementById('videoPlayer');
        const youtubeIframe = document.getElementById('youtubePlayer');
        const progressBar = document.getElementById('progressBar');
        const videoLoader = document.getElementById('videoLoader');
        const checkpointPopup = document.getElementById('checkpointPopup');
        const popupTitle = document.getElementById('popupTitle');
        const popupContent = document.getElementById('popupContent');
        const closePopup = document.getElementById('closePopup');
        const continueVideo = document.getElementById('continueVideo');

        const lessonId = {{ $lesson->id }};
        const checkpoints = @json($lesson->checkpoints);
        const isYouTube = {{ $lesson->isYouTubeVideo() ? 'true' : 'false' }};
        let checkpointShown = new Set();
        let player; // A variable to hold the YouTube player object
        let intervalId;

        // --- YouTube Specific Logic ---
        if (isYouTube) {
            window.onYouTubeIframeAPIReady = function() {
                player = new YT.Player('youtubePlayer', {
                    events: {
                        'onReady': onPlayerReady,
                        'onStateChange': onPlayerStateChange
                    }
                });
            };

            function onPlayerReady(event) {
                // Remove the "YouTube notice" if you want to.
                console.log('YouTube Player is ready.');
            }

            function onPlayerStateChange(event) {
                if (event.data == YT.PlayerState.PLAYING) {
                    // Start checking for checkpoints every second
                    intervalId = setInterval(checkForCheckpoints, 1000);
                } else {
                    // Pause checking when the video is paused or ended
                    clearInterval(intervalId);
                }
            }

            function checkForCheckpoints() {
                const currentTime = Math.floor(player.getCurrentTime());

                checkpoints.forEach((checkpoint) => {
                    if (currentTime === checkpoint.timestamp_seconds && !checkpointShown.has(checkpoint.id)) {
                        player.pauseVideo();
                        showCheckpoint(checkpoint);
                        checkpointShown.add(checkpoint.id);
                    }
                });
            }

            // Hide the progress bar and show the notice for YouTube videos.
            if (document.querySelector('.absolute.bottom-16')) {
                document.querySelector('.absolute.bottom-16').style.display = 'none';
            }
            const videoInfo = document.querySelector('.p-6');
            if (videoInfo) {
                const notice = document.createElement('div');
                notice.className = 'bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded mb-4';
                notice.innerHTML = `
                    <div class="flex">
                        <i class="fas fa-info-circle text-yellow-400 mr-2 mt-1"></i>
                        <p class="text-sm text-yellow-700">
                            <strong>Note:</strong> This is a YouTube video. Automatic checkpoint triggers are now active!
                        </p>
                    </div>
                `;
                videoInfo.insertBefore(notice, videoInfo.firstChild);
            }

        } else {
            // --- Regular Video Player Logic ---
            if (videoLoader && video) {
                video.addEventListener('loadeddata', function() {
                    videoLoader.style.display = 'none';
                });

                video.addEventListener('timeupdate', function() {
                    const progress = (video.currentTime / video.duration) * 100;
                    progressBar.style.width = progress + '%';
                    checkForCheckpoints();
                });

                document.querySelectorAll('.checkpoint-marker').forEach(marker => {
                    marker.addEventListener('click', function() {
                        const timestamp = parseInt(this.dataset.timestamp);
                        video.currentTime = timestamp;
                    });
                });
            }

            // This block is for handling the checkout overlay.
            @if (isset($lesson->checkpoints) && $lesson->checkpoints->count() > 0)
                let checkpointTimes = @json($lesson->checkpoints->pluck('timestamp_seconds'));
                if (video) {
                    video.addEventListener('timeupdate', function() {
                        checkpointTimes.forEach(function(time) {
                            if (video.currentTime >= time && !video.paused) {
                                // Your original checkout logic seems to be tied here
                                // This might be an error in your original logic, as it will trigger for ALL checkpoints.
                                // You might want to move this into the showCheckpoint function with a specific condition.
                                // For now, let's just make sure it doesn't conflict.
                                // video.pause();
                                // checkoutOverlay.style.display = 'flex';
                            }
                        });
                    });
                }
            @endif

            function checkForCheckpoints() {
                if (!video) return;
                const currentTime = Math.floor(video.currentTime);
                checkpoints.forEach((checkpoint) => {
                    if (currentTime >= checkpoint.timestamp_seconds && currentTime < checkpoint.timestamp_seconds + 2 && !checkpointShown.has(checkpoint.id)) {
                        video.pause();
                        showCheckpoint(checkpoint);
                        checkpointShown.add(checkpoint.id);
                    }
                });
            }
        }

        // --- Common Logic for Both Video Types ---

        // Checkpoint list items click
        document.querySelectorAll('.checkpoint-item').forEach(item => {
            item.addEventListener('click', function() {
                const timestamp = parseInt(this.dataset.timestamp);
                const checkpointId = this.dataset.checkpointId;
                const checkpoint = checkpoints.find(c => c.id == checkpointId);

                if (isYouTube && player) {
                    player.seekTo(timestamp);
                    if (checkpoint) showCheckpoint(checkpoint); // Optional: show popup on click
                } else if (video) {
                    video.currentTime = timestamp;
                    // For regular video, the timeupdate listener will trigger the popup
                }
            });
        });

        // Close popup events
        closePopup.addEventListener('click', closeCheckpointPopup);
        continueVideo.addEventListener('click', closeCheckpointPopup);
        checkpointPopup.addEventListener('click', function(e) {
            if (e.target === checkpointPopup) {
                closeCheckpointPopup();
            }
        });

        function showCheckpoint(checkpoint) {
            if (isYouTube) {
                player.pauseVideo();
            } else if (video) {
                video.pause();
            }

            const eventData = checkpoint.event_data;
            let iconClass, colorClass, titleText, contentHtml;

            switch (checkpoint.event_type) {
                case 'quiz':
                    iconClass = 'fa-question-circle';
                    colorClass = 'text-blue-500';
                    titleText = 'Quiz Question';
                    contentHtml = `
                        <div class="mb-4">
                            <p class="font-semibold mb-3">${eventData.question || 'Quiz question'}</p>
                            ${eventData.options ? eventData.options.map((option, index) =>
                                `<label class="flex items-center mb-2 cursor-pointer">
                                    <input type="radio" name="quiz" value="${index}" class="mr-2">
                                    <span>${option}</span>
                                </label>`
                            ).join('') : ''}
                        </div>
                    `;
                    break;
                case 'note':
                    iconClass = 'fa-sticky-note';
                    colorClass = 'text-green-500';
                    titleText = 'Important Note';
                    contentHtml = `
                        <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded">
                            <p>${eventData.text || eventData.content || eventData.title || 'Important information to remember'}</p>
                        </div>
                    `;
                    break;
                case 'popup':
                    iconClass = 'fa-bell';
                    colorClass = 'text-yellow-500';
                    titleText = 'Information';
                    contentHtml = `
                        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded">
                            <p>${eventData.message || eventData.content || eventData.title || 'Important information'}</p>
                        </div>
                    `;
                    break;
            }

            popupTitle.innerHTML = `<i class="fas ${iconClass} ${colorClass} mr-2"></i>${titleText}`;
            popupContent.innerHTML = contentHtml;
            checkpointPopup.classList.remove('hidden');
            setTimeout(() => {
                document.querySelector('.checkpoint-popup').classList.add('show');
            }, 10);
        }

        function closeCheckpointPopup() {
            document.querySelector('.checkpoint-popup').classList.remove('show');
            setTimeout(() => {
                checkpointPopup.classList.add('hidden');
                if (isYouTube && player) {
                    player.playVideo();
                } else if (video) {
                    video.play();
                }
            }, 300);
        }

        document.addEventListener('keydown', function(e) {
            if (e.code === 'Space' && e.target.tagName !== 'INPUT') {
                e.preventDefault();
                if (isYouTube && player) {
                    const state = player.getPlayerState();
                    if (state === YT.PlayerState.PLAYING) {
                        player.pauseVideo();
                    } else {
                        player.playVideo();
                    }
                } else if (video) {
                    if (video.paused) {
                        video.play();
                    } else {
                        video.pause();
                    }
                }
            }

            if (e.code === 'Escape' && !checkpointPopup.classList.contains('hidden')) {
                closeCheckpointPopup();
            }
        });
    });
</script>
@endsection
