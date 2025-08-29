@extends('template.layout')
@section('main-content')
    <main class="pt-32 pb-16 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
        <div class="flex justify-between items-center mb-12">
            <h1 class="text-3xl font-bold bg-gradient-to-r from-cyan-400 to-blue-500 bg-clip-text text-transparent">
                User Management
            </h1>
            <button data-modal-target="addUserModal" data-modal-toggle="addUserModal"
                class="text-white bg-cyan-500 hover:bg-cyan-600 focus:ring-4 focus:outline-none focus:ring-cyan-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center transition-transform hover:scale-105">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6">
                    </path>
                </svg>
                Add User
            </button>
        </div>

        <div class="mb-8">
            <a href="{{ url('/') }}" class="text-cyan-400 hover:text-cyan-300 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Homepage
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @if (!empty($userData))
                @foreach ($userData as $userItem)
                    <a href="{{ url('users/information/' . $userItem['uid']) }}"
                        class="bg-gray-800/50 p-6 rounded-xl border border-gray-700/50 hover:border-cyan-400/30 transition-all group transform hover:-translate-y-1">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3
                                    class="text-xl font-semibold text-gray-100 mb-1 group-hover:text-cyan-400 transition-colors">
                                    {{ $userItem['name'] }}
                                </h3>
                                <p class="text-gray-400 text-sm">
                                    Member since {{ $userItem['created_at'] }}
                                </p>
                            </div>
                            <div class="bg-cyan-400/10 p-3 rounded-lg group-hover:bg-cyan-400/20 transition-colors">
                                <svg class="w-6 h-6 text-cyan-400 group-hover:scale-110 transition-transform" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                </svg>
                            </div>
                        </div>
                    </a>
                @endforeach
            @else
                <div data-modal-target="addUserModal" data-modal-toggle="addUserModal"
                    class="col-span-full bg-gray-800/50 p-8 rounded-2xl border border-gray-700/50 text-center transform hover:scale-[1.01] transition-all">
                    <div class="text-cyan-400 mb-4">
                        <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-100 mb-2">No Users Found</h3>
                    <p class="text-gray-400">Create your first user to get started</p>
                </div>
            @endif
        </div>
    </main>

    <!-- Flowbite Modal -->
    <div id="addUserModal" tabindex="-1" aria-hidden="true"
        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full backdrop-blur-sm">
        <div class="relative p-4 w-full max-w-md max-h-full">
            <div class="relative bg-gray-800 rounded-xl border border-gray-700/50 shadow-2xl overflow-hidden">
                <!-- Gradient Header -->
                <div class="bg-gradient-to-r from-cyan-500/30 to-blue-600/30 p-6 flex items-center justify-between">
                    <div>
                        <h3 class="text-2xl font-bold text-white">
                            <span class="bg-gradient-to-r from-cyan-400 to-blue-500 bg-clip-text text-transparent">
                                Create New User
                            </span>
                        </h3>
                        <p class="text-sm text-cyan-100/80 mt-1">Add a new member to your system</p>
                    </div>
                    <!-- Close Button -->
                    <button type="button" data-modal-hide="addUserModal"
                        class="text-gray-400 hover:text-cyan-400 transition-colors bg-transparent rounded-lg text-sm w-8 h-8 inline-flex justify-center items-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>

                <div class="p-6 space-y-6">
                    <form class="space-y-6" action="{{ url('users/create') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <!-- Name Input -->
                        <div class="relative group">
                            <div
                                class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-cyan-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <input type="text" name="name" id="name"
                                class="bg-gray-700/50 border border-gray-600/50 text-white text-sm rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 block w-full pl-10 p-3.5 hover:border-cyan-400/50 transition-all"
                                placeholder="Full Name">
                        </div>

                        <!-- Email Input -->
                        <div class="relative group">
                            <div
                                class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-cyan-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <input type="email" name="email" id="email"
                                class="bg-gray-700/50 border border-gray-600/50 text-white text-sm rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 block w-full pl-10 p-3.5 hover:border-cyan-400/50 transition-all"
                                placeholder="name@company.com">
                        </div>

                        <!-- File Upload with Preview -->
                        <div class="relative group">
                            <label
                                class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-gray-600/50 rounded-lg cursor-pointer bg-gray-700/50 hover:border-cyan-400/50 hover:bg-gray-700/70 transition-all">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6 px-4 text-center">
                                    <svg class="w-8 h-8 mb-3 text-cyan-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <p class="text-sm text-gray-400">
                                        <span class="font-semibold text-cyan-400">Click to upload</span> or drag and
                                        drop
                                    </p>
                                    <p class="text-xs text-gray-500 mt-1">PNG, JPG (MAX. 2MB)</p>
                                </div>
                                <input type="file" name="profile_picture" id="profile_picture" class="hidden"
                                    accept="image/*">
                            </label>
                            <!-- Image Preview -->
                            <div id="imagePreview" class="mt-4 hidden flex flex-col gap-2">
                                <img id="previewImage"
                                    class="w-full max-h-48 object-contain rounded-lg border border-cyan-400/50 bg-gray-700/50"
                                    src="#" alt="Image Preview">
                                <p id="fileName" class="text-sm text-cyan-400 text-center truncate"></p>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit"
                            class="w-full py-3.5 px-6 bg-gradient-to-r from-cyan-500 to-blue-600 hover:from-cyan-600 hover:to-blue-700 text-white font-semibold rounded-lg transition-all transform hover:scale-[1.02] shadow-lg shadow-cyan-500/20 hover:shadow-cyan-500/30">
                            Create User
                            <svg class="w-4 h-4 ml-2 inline-block" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // File Upload Preview
        const fileInput = document.getElementById('profile_picture');
        const imagePreview = document.getElementById('imagePreview');
        const previewImage = document.getElementById('previewImage');
        const fileNameDisplay = document.getElementById('fileName');

        fileInput.addEventListener('change', function(e) {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                    imagePreview.classList.remove('hidden');
                    fileNameDisplay.textContent = file.name;
                };
                reader.readAsDataURL(file);
            } else {
                imagePreview.classList.add('hidden');
                fileNameDisplay.textContent = '';
            }
        });
    </script>
@endsection
