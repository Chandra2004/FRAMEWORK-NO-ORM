<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Framework Warning</title>
    <meta name="robots" content="noindex, nofollow" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        warning: '#f59e0b',
                        overlayBg: 'rgba(17, 24, 39, 0.95)',
                        accent: '#10b981',
                        secondary: '#3b82f6',
                    },
                    fontFamily: {
                        mono: ['Fira Code', 'monospace'],
                        sans: ['Inter', 'system-ui', 'sans-serif'],
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.5s ease-out',
                        'slide-up': 'slideUp 0.5s ease-out',
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': {
                                opacity: '0'
                            },
                            '100%': {
                                opacity: '1'
                            },
                        },
                        slideUp: {
                            '0%': {
                                transform: 'translateY(20px)',
                                opacity: '0'
                            },
                            '100%': {
                                transform: 'translateY(0)',
                                opacity: '1'
                            },
                        },
                    },
                },
            },
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');

        html,
        body {
            margin: 0;
            padding: 0;
            overflow: hidden;
            font-family: 'Inter', sans-serif;
            height: 100%;
            background: linear-gradient(135deg, #111827 0%, #1f2937 100%);
        }

        .error-overlay {
            position: fixed;
            inset: 0;
            z-index: 999999;
            background: rgba(17, 24, 39, 0.4);
            backdrop-filter: blur(20px);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            overflow-y: auto;
            /* Enable vertical scrolling */
            -webkit-overflow-scrolling: touch;
            /* Smooth scrolling on mobile */
            animation: fade-in 0.5s ease-out;
        }

        .glass-panel {
            background: rgba(31, 41, 55, 0.65);
            backdrop-filter: blur(24px);
            border: 1px solid rgba(255, 255, 255, 0.15);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
            border-radius: 24px;
            overflow: hidden;
            max-height: 95vh;
            /* Limit height to allow scrolling */
            width: 100%;
            max-width: 4xl;
            display: flex;
            flex-direction: column;
            animation: slide-up 0.5s ease-out;
        }

        .glass-panel>div {
            flex: 1 0 auto;
            /* Allow content to grow but not shrink beyond content */
        }

        .glass-panel>div:last-child {
            flex-shrink: 0;
            /* Prevent footer from shrinking */
        }

        .code-container {
            background: #1a202c;
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .code-header {
            background: linear-gradient(90deg, #2d3748 0%, #1a202c 100%);
            padding: 1rem 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }

        .code-line {
            display: flex;
            font-family: 'Fira Code', monospace;
            font-size: 0.9rem;
            line-height: 1.6;
            transition: background 0.2s ease;
        }

        .error-line {
            background: rgba(245, 158, 11, 0.2);
            position: relative;
        }

        .error-line::before {
            content: "";
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 5px;
            background: linear-gradient(to bottom, #f59e0b, #f97316);
        }

        .line-number {
            width: 3.5rem;
            min-width: 3.5rem;
            padding: 0.5rem 1rem;
            text-align: right;
            color: #6b7280;
            user-select: none;
            background: rgba(26, 32, 44, 0.9);
        }

        .line-content {
            color: #e5e7eb;
            padding: 0.5rem 1.5rem;
            white-space: pre-wrap;
            word-break: break-word;
            flex-grow: 1;
            overflow-wrap: anywhere;
        }

        .line-content::selection {
            background-color: rgba(245, 158, 11, 0.4);
        }

        .glow-text {
            text-shadow: 0 0 12px rgba(245, 158, 11, 0.5), 0 0 24px rgba(245, 158, 11, 0.3);
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 120px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            transform: translateY(0);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
        }

        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3);
        }

        .header-icon {
            filter: drop-shadow(0 0 8px rgba(245, 158, 11, 0.4));
        }

        /* Ensure actions are always visible */
        .actions-container {
            min-height: 80px;
            padding-top: 1rem;
            padding-bottom: 1rem;
        }
    </style>
</head>

<body>
    <div class="error-overlay">
        <div class="w-full max-w-4xl glass-panel">
            <!-- Header -->
            <div class="bg-gradient-to-r from-amber-600 to-orange-700 p-6 flex items-center gap-4 text-white">
                <svg class="w-8 h-8 header-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <div>
                    <h1 class="text-3xl font-extrabold glow-text tracking-tight">Framework Warning</h1>
                    <p class="text-amber-100 text-sm opacity-90 mt-1.5 font-medium">Non-critical issue detected</p>
                </div>
            </div>

            <div class="p-8 space-y-8 overflow-y-auto" style="max-height: calc(95vh - 200px);">
                <!-- Warning Message -->
                <div class="bg-amber-500/10 border border-amber-500/25 p-6 rounded-2xl">
                    <div class="flex items-start gap-4">
                        <svg class="w-7 h-7 text-amber-400 flex-shrink-0 mt-1" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <div>
                            <h2 class="text-sm text-amber-400 font-bold uppercase mb-3 tracking-widest">Warning Message
                            </h2>
                            <p class="text-lg text-amber-100 font-medium break-words leading-relaxed">
                                {{ $message }}</p>
                        </div>
                    </div>
                </div>

                <!-- File and Line Info -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- File -->
                    <div class="bg-gray-800/40 border border-cyan-500/15 p-5 rounded-2xl">
                        <div class="flex items-center gap-3 mb-3">
                            <svg class="w-6 h-6 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <h2 class="text-sm text-cyan-400 font-bold uppercase tracking-widest">File Location</h2>
                        </div>
                        <div class="ml-9">
                            <p class="text-cyan-300 text-base font-semibold truncate" title="{{ $file }}">
                                {{ basename($file) }}</p>
                            <p class="text-gray-400 text-sm mt-1.5 truncate" title="{{ $file }}">
                                {{ $file }}</p>
                        </div>
                    </div>

                    <!-- Line -->
                    <div class="bg-gray-800/40 border border-amber-500/15 p-5 rounded-2xl">
                        <div class="flex items-center gap-3 mb-3">
                            <svg class="w-6 h-6 text-amber-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            <h2 class="text-sm text-amber-400 font-bold uppercase tracking-widest">Line Number</h2>
                        </div>
                        <div class="ml-9">
                            <p class="text-amber-300 text-3xl font-extrabold">{{ $line }}</p>
                        </div>
                    </div>
                </div>

                <!-- Code Snippet -->
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <svg class="w-6 h-6 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                        </svg>
                        <h2 class="text-amber-400 text-sm font-bold uppercase tracking-widest">Code Snippet</h2>
                    </div>

                    <div class="code-container">
                        <div class="code-header">
                            <span class="text-sm text-gray-200 font-medium">{{ basename($file) }}</span>
                            <span class="text-sm text-amber-400 font-semibold">Line {{ $line }}</span>
                        </div>
                        <div class="max-h-[350px] overflow-y-auto">
                            @php
                                // Pastikan file ada dan bisa dibaca
                                if (file_exists($file) && is_readable($file)) {
                                    $lines = file($file);
                                    $start = max(0, $line - 5);
                                    $end = min(count($lines), $line + 4);
                                } else {
                                    $lines = [];
                                    $start = $end = 0;
                                }
                            @endphp
                            <div class="text-sm">
                                @for ($i = $start; $i <= $end; $i++)
                                    <div class="code-line {{ $i + 1 == $line ? 'error-line' : '' }}">
                                        <span class="line-number">{{ $i + 1 }}</span>
                                        <span class="line-content">{!! htmlspecialchars($lines[$i] ?? '') !!}</span>
                                    </div>
                                @endfor
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="actions-container flex flex-wrap justify-center gap-4 pt-6 pb-6 bg-gray-800/20">
                <button onclick="history.back()"
                    class="btn px-6 py-3 bg-gray-700 hover:bg-gray-600 text-white rounded-xl font-medium text-sm transition-all duration-300">
                    <svg class="w-5 h-5 inline mr-2 -mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Go Back
                </button>
                <button onclick="location.reload()"
                    class="btn px-6 py-3 bg-blue-600 hover:bg-blue-500 text-white rounded-xl font-medium text-sm transition-all duration-300">
                    <svg class="w-5 h-5 inline mr-2 -mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    Reload
                </button>
                <a href="/"
                    class="btn px-6 py-3 bg-gradient-to-r from-amber-500 to-orange-600 hover:from-amber-600 hover:to-orange-700 text-white rounded-xl font-medium text-sm transition-all duration-300">
                    <svg class="w-5 h-5 inline mr-2 -mt-0.5" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Home
                </a>
            </div>

            <!-- Footer -->
            <div class="text-center text-gray-400 text-sm border-t border-gray-700/40 py-5 bg-gray-800/20">
                <div class="flex items-center justify-center gap-2">
                    <span class="mx-2">•</span>
                    <span>© {{ date('Y') }} THE FRAMEWORK</span>
                    <span class="mx-2">•</span>
                    <span class="text-amber-400 font-medium">Crafted by Chandra Tri A</span>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
