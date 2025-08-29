<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Primary Meta Tags -->
    <title>Fatal Error - The Framework</title>
    <meta name="description" content="A fatal error occurred">
    <meta name="keywords" content="Fatal Error, Server Error, The Framework">
    <meta name="author" content="Chandra Tri Antomo">

    <!-- Styles -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.5/flowbite.min.js"></script>

    <style>
        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
                opacity: 1;
            }

            50% {
                transform: scale(1.05);
                opacity: 0.7;
            }
        }

        .fatal-pulse {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        .glass-panel {
            background: rgba(31, 41, 55, 0.6);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        }

        .glow-text {
            text-shadow: 0 0 10px rgba(239, 68, 68, 0.7);
        }
    </style>
</head>

<body class="bg-gradient-to-br from-gray-900 to-gray-800 min-h-screen font-sans">
    <!-- Navigation -->
    <nav class="glass-panel fixed w-full top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center">
                    <span
                        class="text-2xl font-bold bg-gradient-to-r from-cyan-400 to-blue-500 bg-clip-text text-transparent">
                        The Framework
                    </span>
                </div>
                <div class="flex items-center space-x-8">
                    <a href="https://github.com/Chandra2004/FRAMEWORK" target="_blank"
                        class="text-gray-400 hover:text-cyan-400 transition-all flex items-center">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path fill-rule="evenodd"
                                d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z"
                                clip-rule="evenodd" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="pt-32 pb-16 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
        <div class="text-center">
            <div class="max-w-3xl mx-auto">
                <!-- Animated Skull Icon -->
                <div class="fatal-pulse mb-12 text-red-500">
                    <svg class="w-32 h-32 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M20.618 5.984A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016zM12 9v2m0 4h.01" />
                    </svg>
                </div>

                <!-- Error Message -->
                <div class="mb-10">
                    <h1
                        class="text-8xl font-bold bg-gradient-to-r from-red-500 to-pink-600 bg-clip-text text-transparent mb-3 drop-shadow-lg glow-text">
                        Fatal Error
                    </h1>
                    <div class="w-24 h-1 bg-gradient-to-r from-red-500 to-pink-600 mx-auto rounded-full mb-6"></div>
                    <h2 class="text-3xl font-semibold text-gray-100 mb-4">
                        Critical System Failure
                    </h2>
                    <p class="text-xl text-red-300 mb-8 max-w-xl mx-auto bg-red-900/30 px-4 py-3 rounded-lg">
                        {{ $message }}
                    </p>
                </div>

                <!-- Error Details -->
                <div class="glass-panel rounded-xl mb-8 text-left">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-6">
                        <div class="border-l-2 border-red-500 pl-4">
                            <h3 class="text-gray-100 font-medium mb-2 flex items-center gap-2">
                                <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Source Location
                            </h3>
                            <p class="text-gray-400 font-mono text-sm break-all">
                                {{ $file }}:{{ $line }}
                            </p>
                        </div>

                        <div class="border-l-2 border-red-500 pl-4">
                            <h3 class="text-gray-100 font-medium mb-2 flex items-center gap-2">
                                <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Occurred At
                            </h3>
                            <p class="text-gray-400">
                                {{ date('Y-m-d H:i:s') }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Action Steps -->
                <div class="glass-panel rounded-xl mb-12 text-left">
                    <div class="p-6">
                        <div class="flex items-start gap-4">
                            <div class="bg-red-500/10 p-3 rounded-lg mt-1">
                                <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-gray-100 font-medium mb-3 text-xl">Immediate Actions</h3>
                                <ul class="space-y-3">
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 mt-1 mr-3 text-red-500">•</span>
                                        <span class="text-gray-300">Check server error logs for detailed
                                            information</span>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 mt-1 mr-3 text-red-500">•</span>
                                        <span class="text-gray-300">Review recent code changes and deployments</span>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 mt-1 mr-3 text-red-500">•</span>
                                        <span class="text-gray-300">Restart application services and clear caches</span>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 mt-1 mr-3 text-red-500">•</span>
                                        <span class="text-gray-300">Contact server administrator for immediate
                                            support</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- CTA Buttons -->
                <div class="flex flex-col sm:flex-row justify-center gap-4">
                    <a href="{{ url('/') }}"
                        class="bg-gradient-to-r from-cyan-500 to-blue-600 text-white px-6 py-3 rounded-lg font-medium 
                              hover:from-cyan-600 hover:to-blue-700 transition-all flex items-center justify-center gap-2
                              shadow-lg shadow-cyan-500/20 hover:shadow-cyan-500/30">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        Try Homepage
                    </a>

                    <a href="https://github.com/Chandra2004/FRAMEWORK/issues/new" target="_blank"
                        class="bg-gradient-to-r from-red-500 to-pink-600 text-white px-6 py-3 rounded-lg font-medium 
                              hover:from-red-600 hover:to-pink-700 transition-all flex items-center justify-center gap-2
                              shadow-lg shadow-red-500/20 hover:shadow-red-500/30">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        Emergency Support
                    </a>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="glass-panel mt-24 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="md:flex md:items-center md:justify-between">
                <div class="flex justify-center md:justify-start">
                    <span
                        class="text-xl font-bold bg-gradient-to-r from-cyan-400 to-blue-500 bg-clip-text text-transparent">
                        The Framework
                    </span>
                </div>
                <div class="mt-8 md:mt-0 flex justify-center space-x-6">
                    <a href="https://github.com/Chandra2004/FRAMEWORK" target="_blank"
                        class="text-gray-400 hover:text-cyan-400 transition-colors">
                        <span class="sr-only">GitHub</span>
                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                            <path fill-rule="evenodd"
                                d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z"
                                clip-rule="evenodd" />
                        </svg>
                    </a>
                </div>
            </div>
            <div class="mt-8 text-center md:text-left">
                <p class="text-sm text-gray-500">
                    &copy; {{ date('Y') }} The Framework. All rights reserved.
                </p>
                <p class="mt-1 text-xs text-gray-500">
                    Created with ❤️ by <a href="https://github.com/Chandra2004" target="_blank"
                        class="text-cyan-400 hover:underline">Chandra Tri Antomo</a>
                </p>
            </div>
        </div>
    </footer>
</body>

</html>
