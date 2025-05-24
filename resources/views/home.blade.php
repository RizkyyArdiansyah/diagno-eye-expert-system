<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" href="{{ asset('images/logoSPM.png') }}" type="image/png">
    <title>DiagnoEye - Sistem Pakar Diagnosa Penyakit Mata -</title>
    <script src="//unpkg.com/alpinejs" defer></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            overflow: auto;
        }

        .scrollbar-hide {
            scrollbar-width: none;
            /* Firefox */
            -ms-overflow-style: none;
            /* IE 10+ */
        }

        .bg-design {
            background-color: #ffffff;
            opacity: 1;
            background-image: radial-gradient(#414bff 0.8px, transparent 0.8px), radial-gradient(#414bff 0.8px, #ffffff 0.8px);
            background-size: 32px 32px;
            background-position: 0 0, 16px 16px;
        }

        body::-webkit-scrollbar {
            display: none;
        }

        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body x-cloak x-data="{ ...scrollNav(), menuOpen: false, showLogin: false, showLogoutConfirm: false, showLoginPrompt: false, showRegister: false }">
    <header class="shadow-md fixed w-full z-50">
        <nav class="fixed top-0 left-0 z-50 w-full backdrop-blur-md bg-blue-200/70 shadow-md select-none">
            <div class="container md:max-w-[1200px] px-4 md:px-2 mx-auto py-3">
                <div class="flex justify-between items-center">
                    <div x-data="scrollNav()" @click.prevent="scrollToSection('home')"
                        class="flex flex-row items-center">
                        <a href="#beranda" class="flex items-center text-2xl font-bold">
                            <span class="text-black">Diagn</span>
                            <img src="{{ asset('images/logoSPM.png') }}" alt="O" class="size-6 inline-block">
                            <span class="text-blue-600">Eye</span>
                        </a>
                    </div>

                    <!-- Desktop Nav -->
                    <div class="hidden md:flex items-center space-x-6">
                        <div class="flex flex-row bg bg-slate-50 shadow-sm gap-x-6 px-5 py-2 rounded-full">
                            <a href="#home" @click.prevent="scrollToSection('home')"
                                class="text-black text-sm hover:text-blue-600">Home</a>
                            <a @click.prevent="scrollToSection('edukasi'); menuOpen = false" href="#about"
                                class="text-sm text-black hover:text-blue-600">Edukasi</a>
                            <a href="#diagnose" @click.prevent="scrollToSection('diagnose')"
                                class="text-black text-sm hover:text-blue-600">Diagnosa</a>
                        </div>
                    </div>

                    <!-- Mobile Button -->
                    <div class="md:hidden">
                        <button @click="menuOpen = !menuOpen" class="focus:outline-none">
                            <svg x-show="!menuOpen" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-800"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                            <svg x-show="menuOpen" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-800"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Login/Logout Button -->
                    <div class="hidden md:flex">
                        @auth
                            <button type="button" @click="showLogoutConfirm = true"
                                class="bg-red-600 text-xs text-white px-3 py-2 font-bold rounded-md hover:bg-white hover:text-red-600 transition duration-200 cursor-pointer">
                                Logout
                            </button>
                        @else
                            <button @click="showLogin = true"
                                class="bg-blue-600 text-xs text-white px-2 py-2 font-bold rounded-md hover:bg-white hover:text-blue-800 transition duration-200 cursor-pointer">
                                Login
                            </button>
                        @endauth
                    </div>
                </div>

                <!-- Mobile Nav Menu -->
                <div x-show="menuOpen" class="md:hidden mt-3 space-y-2 flex flex-col text-center items-center">
                    <a @click.prevent="scrollToSection('home'); menuOpen = false" href="#home"
                        class="text-sm text-black hover:text-blue-600">Home</a>
                    <a @click.prevent="scrollToSection('edukasi'); menuOpen = false" href="#about"
                        class="text-sm text-black hover:text-blue-600">Edukasi</a>
                    <a @click.prevent="scrollToSection('diagnose'); menuOpen = false" href="#diagnose"
                        class="text-sm text-black hover:text-blue-600">Diagnosa</a>
                    @auth
                        <!-- Tombol Logout -->
                        <button type="button" @click="showLogoutConfirm = true"
                            class="bg-red-600 text-xs text-white px-3 py-2 font-bold rounded-md hover:bg-white hover:text-red-600 transition duration-200 cursor-pointer">
                            Logout
                        </button>
                    @else
                        <button @click="showLogin = true; menuOpen = false"
                            class="bg-blue-600 text-xs text-white px-2 py-2 font-bold rounded-md hover:bg-white hover:text-blue-800 transition duration-200 cursor-pointer">
                            Login
                        </button>
                    @endauth
                </div>
            </div>
        </nav>
    </header>

    <div id="loading-screen" class="fixed inset-0 bg-white z-50 flex items-center justify-center">
        <div class="text-center">
            <!-- Spinner -->
            <div class="animate-spin rounded-full h-16 w-16 border-b-2 border-blue-600 mx-auto mb-4"></div>

            <!-- Loading text -->
            <p class="text-gray-600 text-lg font-medium">Loading...</p>
        </div>
    </div>


    <!-- Pop up konfirmasi ketika user logout -->
    <div>
        <template x-if="showLogoutConfirm">
            <div
                class="fixed inset-0 backdrop-blur-md bg-black/40 flex items-center justify-center z-50 transition-all duration-300">
                <div class="bg-white rounded-xl shadow-2xl max-w-sm w-[20rem] md:w-full transform transition-all duration-300 scale-100 opacity-100"
                    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95">

                    <div class="bg-gradient-to-r from-red-500 to-red-600 rounded-t-xl p-6 text-white">
                        <div class="flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mr-2" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            <h2 class="text-xl font-semibold">Konfirmasi Logout</h2>
                        </div>
                    </div>
                    <div class="p-6">
                        <p class="text-gray-600 mb-6 text-center">Apakah kamu yakin ingin keluar dari akunmu?</p>
                        <div class="flex justify-center space-x-3">
                            <button @click="showLogoutConfirm = false"
                                class="px-5 py-2 rounded-lg border border-gray-300 text-gray-700 font-medium hover:ring-2 hover:ring-red-300 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-gray-300 cursor-pointer">
                                Batal
                            </button>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="px-5 py-2 rounded-lg bg-gradient-to-r from-red-500 to-red-600 text-white font-medium hover:from-red-600 hover:to-red-700 transition-colors duration-200 shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-red-500 cursor-pointer">
                                    Ya, Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </div>

    <!-- Pop up login form -->
    <div x-show="showLogin" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 transform scale-95"
        x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 transform scale-100"
        x-transition:leave-end="opacity-0 transform scale-95"
        class="fixed inset-0 flex items-center justify-center z-50 backdrop-blur-md bg-black/30">

        <div @click.away="showLogin = false"
            class="bg-white w-[20rem] md:w-full max-w-md rounded-xl shadow-2xl overflow-hidden">
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-6">
                <div class="flex items-center justify-center mb-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                    </svg>
                </div>
                <h2 class="text-center text-xl font-semibold text-white">Login to Your Account</h2>
            </div>

            <!-- Form Login -->
            <div class="p-6">
                <form method="POST" action="{{ route('login.submit') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                                </svg>
                            </div>
                            <input type="email" name="email" required
                                class="w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <input type="password" name="password" required
                                class="w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        </div>
                    </div>

                    <div class="pt-2">
                        <button type="submit"
                            class="w-full bg-gradient-to-r from-blue-500 to-blue-600 text-white py-2.5 px-4 rounded-lg hover:from-blue-600 hover:to-blue-700 transition-colors duration-200 font-medium shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 cursor-pointer">
                            Login
                        </button>
                    </div>
                </form>

                <!-- Register link and Cancel button -->
                <div class="text-center mt-4 flex flex-col space-y-2">
                    <button type="button" @click="showLogin = false"
                        class="text-gray-500 hover:text-red-600 transition-colors duration-200 text-sm font-medium mb-6 cursor-pointer">
                        Cancel
                    </button>
                    <div class="text-sm text-gray-600">
                        Belum punya akun?
                        <button type="button" @click="showLogin = false; showRegister = true"
                            class="text-blue-600 hover:text-green-800 font-medium transition-colors duration-200 cursor-pointer">
                            Daftar Sekarang
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Register -->
    <div x-show="showRegister" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 transform scale-95"
        x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 transform scale-100"
        x-transition:leave-end="opacity-0 transform scale-95"
        class="fixed inset-0 flex items-center justify-center z-50 backdrop-blur-md bg-black/30">

        <div @click.away="showRegister = false"
            class="bg-white w-[20rem] md:w-full max-w-md rounded-xl shadow-2xl overflow-hidden">

            <!-- Header with gradient -->
            <div class="bg-gradient-to-r from-green-500 to-teal-600 p-2 md:p-6">
                <div class="flex items-center justify-center mb-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                    </svg>
                </div>
                <h2 class="text-center text-md md:text-xl font-semibold text-white">Buat Akun Baru</h2>
            </div>

            <!-- Form Section -->
            <div class="p-4 md:p-6">
                <form method="POST" action="{{ route('register.submit') }}" class="space-y-0.5 md:space-y-2">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <input type="text" name="name" required
                                class="w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                                </svg>
                            </div>
                            <input type="email" name="email" required
                                class="w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <input type="password" name="password" required
                                class="w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <input type="password" name="password_confirmation" required
                                class="w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors">
                        </div>
                    </div>

                    <div class="pt-2">
                        <button type="submit"
                            class="w-full bg-gradient-to-r from-green-500 to-teal-600 text-white py-2.5 px-4 rounded-lg hover:from-green-600 hover:to-teal-700 transition-colors duration-200 font-medium shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 cursor-pointer">
                            Daftar
                        </button>
                    </div>
                </form>

                <!-- Login link and Cancel button -->
                <div class="text-center mt-4 flex flex-col space-y-2">
                    <button type="button" @click="showRegister = false"
                        class="text-gray-500 hover:text-red-600 transition-colors duration-200 text-sm font-medium mb-2 cursor-pointer">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Pop up peringatan untuk login dulu -->
    <div>
        <template x-if="showLoginPrompt">
            <div
                class="fixed inset-0 backdrop-blur-md bg-black/30 flex items-center justify-center z-50 transition-all duration-300">
                <div class="bg-white rounded-xl shadow-2xl max-w-sm w-full transform transition-all duration-300"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95">
                    <!-- Header with gradient -->
                    <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-t-xl p-5 text-white">
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mr-3" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                            <h2 class="text-xl font-medium">Login Diperlukan</h2>
                        </div>
                    </div>
                    <div class="p-6">
                        <p class="text-gray-700 mb-6 text-center">
                            Anda harus login terlebih dahulu untuk melakukan diagnosa.
                        </p>
                        <div class="flex justify-center space-x-4">
                            <button @click="showLoginPrompt = false"
                                class="px-5 py-2 rounded-lg border border-gray-300 text-gray-700 font-medium hover:ring-red-400 hover:ring-2 transition-colors duration-200 focus:outline-none focus:ring-2 cursor-pointer focus:ring-gray-300">
                                Batal
                            </button>

                            <button @click="showLogin = true; showLoginPrompt = false"
                                class="px-5 py-2 rounded-lg bg-blue-500 text-white font-medium hover:bg-blue-700 transition-colors duration-200 shadow-md hover:shadow-lg focus:outline-none focus:ring-2 cursor-pointer focus:ring-blue-400">
                                Login Sekarang
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </div>

    <!-- notif success/gagal -->
    @if (session('success'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show"
            class="fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded shadow z-[100]">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)" x-show="show"
            class="fixed top-4 right-4 bg-red-500 text-white px-4 py-2 rounded shadow z-[100] transition-all">
            <ul class="ml-4 text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <section id="home" class="scroll pt-24 pb-18 bg-white">
        <div class="container mx-auto max-w-7xl px-6 select-none">
            <div class="bg-white min-h-[80vh]">
                <div
                    class="flex flex-col md:flex-row items-center justify-around gap-4 h-full px-8 py-8 md:px-14 md:py-20">

                    {{-- Kiri: Teks --}}
                    <div class="md:w-1/2">
                        <h1 data-aos="zoom-in"
                            class="text-4xl text-center md:text-start font-extrabold text-slate-950 mb-4">
                            Cek Mata<span class="text-blue-700 ml-1">Yuk!</span>
                        </h1>
                        <p data-aos="zoom-in" class="text-slate-900 text-sm md:text-lg leading-relaxed">
                            DiagnoEye adalah sistem pakar berbasis web yang membantu pengguna mengidentifikasi
                            potensi penyakit mata berdasarkan gejala yang dialami. Dengan metode Rule-Based System,
                            aplikasi ini dapat memberikan hasil diagnosa dan solusi awal secara cepat dan akurat.
                        </p>
                        <a data-aos="zoom-in" data-aos-delay="400" href="#diagnose"
                            @click.prevent="scrollToSection('diagnose')">
                            <button
                                class="bg-blue-500 px-4 py-2 mt-4 md:ml-0 mx-auto flex rounded-md text-slate-50 font-semibold hover:bg-slate-100 hover:text-black transition duration-200 cursor-pointer">
                                Periksa!
                            </button>
                        </a>
                    </div>

                    {{-- Kanan: Gambar --}}
                    <div data-aos="fade-left" class="items-start md:w-1/2 ">
                        <img src="{{ asset('images/dokter.png') }}" alt="Dokter Mata"
                            class="w-[32em] h-auto drop-shadow-md">
                    </div>

                </div>
            </div>
        </div>

        </div>
    </section>

    <section id="edukasi" class="py-10 bg-gray-100 min-h-screen bg-design select-none">
        <div class="container mx-auto px-10 md:px-20">
            <h2 data-aos="fade-up" class="text-xl md:text-2xl font-bold mt-3 mb-6 text-center text-blue-600">
                Jaga Kesehatan Mata Anda, Mulai dari Sekarang!
            </h2>
            <p data-aos="fade-up" data-aos-delay="200" class="text-md md:text-lg mb-6 text-center text-gray-700">
                Tonton video edukatif seputar mata — kenali gejala, pelajari pencegahan, dan dapatkan tips menjaga
                penglihatan Anda tetap optimal setiap hari.
            </p>

            <div class="grid md:grid-cols-3 gap-5 md:mt-20">
                <!-- Video Card -->
                <div data-aos="flip-left" data-aos-delay="200" class="bg-white rounded-lg shadow-md p-2">
                    <div class="video-thumb relative cursor-pointer aspect-video rounded overflow-hidden"
                        data-id="XvqFAQeG750"></div>
                </div>
                <div data-aos="flip-up" data-aos-delay="200" class="bg-white rounded-lg shadow-md p-2">
                    <div class="video-thumb relative cursor-pointer aspect-video rounded overflow-hidden"
                        data-id="MFA8tDi31LU"></div>
                </div>
                <div data-aos="flip-right" data-aos-delay="200" class="md:mx-auto bg-white rounded-lg shadow-md p-2">
                    <div class="video-thumb relative cursor-pointer aspect-video rounded overflow-hidden"
                        data-id="Qwb21ZbRJeM"></div>
                </div>
            </div>
        </div>
    </section>


    <section id="diagnose" class="scroll-mt-6 md:scroll-mt-14 py-10 bg-gray-100 min-h-[100dvh] select-none"
        x-data="{
            showForm: {{ isset($hasilDiagnosa) ? 'false' : 'true' }},
            isLoading: false,
            selectedCount: 0
        }">
        <div class="container mx-auto max-w-6xl px-6 md:px-3">
            <!-- Form Section -->
            <div x-show="showForm" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform translate-y-4"
                x-transition:enter-end="opacity-100 transform translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 transform translate-y-0"
                x-transition:leave-end="opacity-0 transform -translate-y-4">

                <h2 data-aos="fade-up" class="text-xl md:text-2xl font-bold mb-2 md:mb-3 text-center text-blue-600">
                    Diagnosa Penyakit Mata
                </h2>

                <p data-aos="fade-up" data-aos-delay="200" class="md:text-lg mb-2 md:mb-6 text-center text-gray-700">
                    Silakan centang gejala yang Anda alami, lalu klik tombol diagnosa.
                </p>

                <!-- Form Diagnosa -->
                <form data-aos="fade-up" data-aos-anchor-placement="top-bottom" data-aos-delay="500" method="POST"
                    action="{{ route('diagnosa.proses') }}#diagnose" @submit="isLoading = true">
                    @csrf
                    <div
                        class="overflow-y-auto max-h-[400px] p-4 md:p-6 bg-white rounded-xl text-sm shadow-lg shadow-blue-200 mb-6 transition-all duration-300 hover:shadow-xl">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-1.5 md:gap-3">
                            @foreach ($gejalas as $gejala)
                                <label
                                    class="flex items-center p-2 hover:bg-blue-50 rounded-lg transition-colors duration-200">
                                    <input type="checkbox" name="gejala[]" value="{{ $gejala->kode_gejala }}"
                                        @change="selectedCount = document.querySelectorAll('input[name=&quot;gejala[]&quot;]:checked').length"
                                        class="form-checkbox h-5 w-5 text-blue-600 checked:bg-blue-600 checked:border-transparent rounded transition-all duration-200 cursor-pointer">
                                    <span class="ml-2">{{ $gejala->kode_gejala }} -
                                        {{ $gejala->detail_gejala }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="text-center">
                        @auth
                            <button type="submit"
                                class="bg-blue-500 font-semibold text-white px-6 py-3 rounded-lg shadow-md shadow-blue-300 hover:bg-blue-600 hover:shadow-lg transform transition-all duration-300 hover:-translate-y-1 disabled:opacity-50 cursor-pointer disabled:cursor-not-allowed"
                                :disabled="isLoading || selectedCount === 0">
                                <span x-show="!isLoading">Diagnosa</span>
                                <span x-show="isLoading" class="flex items-center justify-center">
                                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10"
                                            stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                        </path>
                                    </svg>
                                    Memproses...
                                </span>
                            </button>
                        @else
                            <button data-aos="zoom-in" data-aos-anchor-placement="top-bottom" data-aos-delay="400"
                                @click="showLoginPrompt = true" type="button"
                                class="bg-blue-500 cursor-pointer font-semibold text-white px-6 py-3 rounded-lg shadow-md shadow-blue-300 hover:bg-blue-600 hover:shadow-lg transform transition-all duration-300 hover:-translate-y-1">
                                Diagnosa
                            </button>
                        @endauth
                    </div>
                </form>
            </div>

            <!-- Hasil Diagnosa -->
            @if (isset($hasilDiagnosa))
                <div x-show="!showForm" x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 transform translate-y-4"
                    x-transition:enter-end="opacity-100 transform translate-y-0"
                    class="p-6 md:p-8 md:mt-16 bg-white rounded-2xl shadow-xl border-2 border-dashed {{ $hasilDiagnosa ? 'border-blue-400' : 'border-red-400' }} mt-6"
                    x-init="$nextTick(() => {
                        // Scroll to this section with smooth animation when results are shown
                        if (!showForm) {
                            document.getElementById('diagnose').scrollIntoView({
                                behavior: 'smooth',
                                block: 'start'
                            });
                        }
                    })">

                    @if ($hasilDiagnosa)
                        <!-- Ikon & Judul -->
                        <div class="flex items-center mb-3 md:mb-6">
                            <svg class="w-8 h-8 text-blue-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <h3 class="text-xl md:text-2xl font-bold text-blue-600">Hasil Diagnosa</h3>
                        </div>

                        <!-- Detail Penyakit -->
                        <div class="mb-3 md:mb-6">
                            <p class="mb-2 text-md md:text-lg">
                                <span class="text-blue-600 font-semibold">Penyakit:</span>
                                <span
                                    class="text-gray-800 ml-2">{{ $hasilDiagnosa['penyakit']->nama_penyakit }}</span>
                            </p>
                            <div class="bg-blue-50 border-l-4 border-blue-500 rounded-lg p-4 mt-3 shadow-sm">
                                <p class="text-blue-600 text-lg font-semibold mb-2">Solusi:</p>
                                <p class="text-gray-700 text-sm md:text-md leading-relaxed">
                                    {{ $hasilDiagnosa['penyakit']->solusi }}</p>
                            </div>
                        </div>

                        <!-- Gejala yang dialami -->
                        <div class="flex flex-col">
                            <p class="font-semibold text-md md:text-lg mb-3 text-gray-800">Gejala yang Anda alami:</p>
                            <div class="flex flex-wrap gap-2">
                                @foreach ($hasilDiagnosa['gejalas'] as $gejala)
                                    <span
                                        class="px-4 py-2 text-sm md:text-md bg-blue-100 text-blue-800 rounded-full transition-all duration-200 hover:bg-blue-200">
                                        {{ $gejala->detail_gejala }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <!-- Tidak ditemukan -->
                        <div class="text-center py-6">
                            <svg class="w-16 h-16 text-red-500 mx-auto mb-4" fill="none" stroke="currentColor"
                                stroke-width="2" viewBox="0 0 24 24">
                                <path
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"
                                    stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <p class="text-xl font-semibold text-red-600 mb-2">
                                Tidak ditemukan penyakit
                            </p>
                            <p class="text-gray-600">
                                Tidak ada penyakit yang teridentifikasi berdasarkan gejala yang Anda pilih.
                            </p>
                        </div>
                    @endif

                    <!-- Tombol Aksi -->
                    <div class="flex justify-center gap-4 mt-4 md:mt-8">
                        <button
                            @click="showForm = true; $nextTick(() => { document.getElementById('diagnose').scrollIntoView({behavior: 'smooth'}); })"
                            class="w-34 h-14 md:w-auto md:h-auto px-3 py-0.5 md:px-6 md:py-2.5 bg-white border-2 border-blue-500 text-blue-500 font-medium rounded-full transition-all duration-300 hover:bg-blue-100 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 cursor-pointer">
                            <span class="flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                    </path>
                                </svg>
                                Diagnosa Ulang
                            </span>
                        </button>
                        <button @click="window.location.href = window.location.origin + '/';"
                            class="w-28 h-14 md:w-auto md:h-auto px-3 py-0.5 md:px-6 md:py-2.5 bg-white border-2 border-green-500 text-green-500 font-medium rounded-full transition-all duration-300 hover:bg-green-100 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 cursor-pointer">
                            <span class="flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                Selesai
                            </span>
                        </button>
                    </div>
                </div>
            @endif
        </div>
    </section>

    @if (isset($popupStatus) && isset($popupMessage))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show" x-transition
            class="fixed top-6 left-1/2 transform -translate-x-1/2 z-50 px-6 py-3 rounded-xl text-sm font-medium shadow-lg border
            {{ $popupStatus === 'success' ? 'bg-green-50 text-green-700 border-green-300' : '' }}
            {{ $popupStatus === 'error' ? 'bg-red-50 text-red-700 border-red-300' : '' }}"
            style="display: none;">
            {{ $popupMessage }}
        </div>
    @endif


    <section class="bg-wite text-slate-800 py-14 select-none">
        <div class="container mx-auto md:px-6 px-14 max-w-5xl">
            <!-- FAQ Header -->
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-2xl font-bold mb-3" data-aos="fade-up">FAQ</h2>
                <p class="text-gray-800 text-lg md:text-xl" data-aos="fade-up">Pertanyaan yang Sering Diajukan</p>
            </div>

            <!-- FAQ Accordion -->
            <div class="space-y-6 " data-aos="fade-up">

                <!-- FAQ Item 1 -->
                <div class="border-b border-gray-800">
                    <button class="flex items-center justify-between w-full py-5 text-left cursor-pointer"
                        onclick="toggleFaq('faq-1')">
                        <h3 class="text-sm md:text-lg font-medium">Apa itu DiagnoEye dan bagaimana cara kerjanya?</h3>
                        <svg id="faq-1-icon" class="w-4 h-6 transform transition-transform duration-300"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div id="faq-1-content" class="hidden pb-6">
                        <p class="text-gray-800 text-sm">
                            DiagnoEye adalah sistem pakar berbasis web yang membantu mendiagnosa penyakit mata
                            berdasarkan gejala yang dialami pengguna. Sistem ini menggunakan metode forward chaining
                            untuk mencocokkan gejala dengan pengetahuan medis yang tersedia.
                        </p>
                    </div>
                </div>

                <!-- FAQ Item 2 -->
                <div class="border-b border-gray-800">
                    <button class="flex items-center justify-between w-full py-5 text-left cursor-pointer"
                        onclick="toggleFaq('faq-2')">
                        <h3 class="text-sm md:text-lg font-medium">Apakah hasil diagnosa dari DiagnoEye akurat?</h3>
                        <svg id="faq-2-icon" class="w-4 h-6 transform transition-transform duration-300"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div id="faq-2-content" class="hidden pb-6">
                        <p class="text-gray-800 text-sm">
                            Hasil diagnosa yang diberikan berdasarkan data dan aturan yang dimasukkan oleh pakar.
                            Meskipun cukup akurat, tetap disarankan untuk berkonsultasi langsung dengan dokter spesialis
                            mata untuk pemeriksaan lebih lanjut.
                        </p>
                    </div>
                </div>

                <!-- FAQ Item 3 -->
                <div class="border-b border-gray-800">
                    <button class="flex items-center justify-between w-full py-5 text-left cursor-pointer"
                        onclick="toggleFaq('faq-3')">
                        <h3 class="text-sm md:text-lg font-medium">Apakah data pengguna disimpan secara aman?</h3>
                        <svg id="faq-5-icon" class="w-4 h-6 transform transition-transform duration-300"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div id="faq-3-content" class="hidden pb-6">
                        <p class="text-gray-800 text-sm">
                            Ya, semua data yang dimasukkan oleh pengguna akan dijaga kerahasiaannya dan hanya digunakan
                            untuk keperluan diagnosa didalam sistem.
                        </p>
                    </div>
                </div>

                <!-- FAQ Item 4 -->
                <div class="border-b border-gray-800">
                    <button class="flex items-center justify-between w-full py-5 text-left cursor-pointer"
                        onclick="toggleFaq('faq-4')">
                        <h3 class="text-sm md:text-lg font-medium">Bagaimana cara menjaga kesehatan mata sehari-hari?
                        </h3>
                        <svg id="faq-4-icon" class="w-4 h-6 transform transition-transform duration-300"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div id="faq-4-content" class="hidden pb-6">
                        <p class="text-gray-800 text-sm">
                            Menjaga kesehatan mata penting untuk mencegah gangguan penglihatan. Beberapa tips yang bisa
                            diterapkan:
                        <ul class="list-disc list-inside mt-2 text-gray-800 text-sm">
                            <li>Istirahatkan mata setiap 20 menit saat menatap layar (aturan 20-20-20).</li>
                            <li>Konsumsi makanan yang kaya vitamin A, C, dan E seperti wortel, bayam, dan ikan berlemak.
                            </li>
                            <li>Gunakan kacamata pelindung saat berada di bawah sinar matahari atau di tempat berdebu.
                            </li>
                            <li>Hindari menggosok mata dengan tangan kotor.</li>
                            <li>Periksa mata secara rutin ke dokter, minimal satu kali dalam setahun.</li>
                        </ul>
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <footer class="bg-blue-200/70 text-gray-400 text-sm">
        <div class="max-w-7xl mx-auto px-6 py-10">
            <div class="flex flex-row justify-between items-center mb-2 md:mb-6">
                <!-- Logo -->
                <p class="text-2xl font-bold text-blue-600">
                    <span class="text-black">Diagno</span>Eye
                </p>


                <!-- Contact Button -->
                {{-- <div class="mt-4 md:mt-0">
                    <a href=""
                        class="bg-gray-800 hover:bg-white hover:text-black text-white text-xs text-center px-2 py-2 md:px-4 md:py-2 rounded-md transition flex cursor-pointer">
                        <i class="fas fa-envelope"></i>
                        <span>Hubungi Pakar</span>
                    </a>
                </div> --}}
            </div>

            <div
                class="border-t border-gray-700 pt-6 flex flex-row justify-between items-start md:items-center text-xs">
                <!-- Copyright -->
                <div class="flex text-slate-950 items-center space-x-3 mb-2 md:mb-0 text-[0.8rem] md:text-sm">
                    <p>© 2025 DiagnoEye Inc. All rights reserved.</p>
                </div>

            </div>
        </div>
    </footer>

    <script>
        function toggleFaq(id) {
            const content = document.getElementById(`${id}-content`);
            const icon = document.getElementById(`${id}-icon`);

            const allContents = document.querySelectorAll('[id$="-content"]');
            const allIcons = document.querySelectorAll('[id$="-icon"]');

            allContents.forEach(item => {
                if (item.id !== `${id}-content`) item.classList.add('hidden');
            });

            allIcons.forEach(item => {
                if (item.id !== `${id}-icon`) item.classList.remove('rotate-180');
            });

            content.classList.toggle('hidden');
            icon.classList.toggle('rotate-180');
        }
    </script>

    <script>
        function scrollNav() {
            return {
                scrollToSection(id) {
                    const target = document.getElementById(id);
                    const offset = 56; // ganti sesuai tinggi navbar kamu
                    const bodyRect = document.body.getBoundingClientRect().top;
                    const elementRect = target.getBoundingClientRect().top;
                    const elementPosition = elementRect - bodyRect;
                    const offsetPosition = elementPosition - offset;

                    window.scrollTo({
                        top: offsetPosition,
                        behavior: "smooth"
                    });
                }
            }
        }
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const thumbs = document.querySelectorAll('.video-thumb');
            let currentIframe = null;

            thumbs.forEach(thumb => {
                const videoId = thumb.getAttribute('data-id');
                const thumbUrl = `https://img.youtube.com/vi/${videoId}/hqdefault.jpg`;

                // Buat thumbnail
                thumb.innerHTML = `
                <img src="${thumbUrl}" alt="Thumbnail" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center text-white text-4xl">
                    ▶
                </div>
            `;

                // Load iframe saat diklik
                thumb.addEventListener('click', () => {
                    // Hapus iframe sebelumnya
                    if (currentIframe && currentIframe !== thumb) {
                        const oldIframe = currentIframe.querySelector('iframe');
                        oldIframe?.remove();

                        // Ganti ke thumbnail lagi
                        const oldVideoId = currentIframe.getAttribute('data-id');
                        currentIframe.innerHTML = `
                        <img src="https://img.youtube.com/vi/${oldVideoId}/hqdefault.jpg" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center text-white text-4xl">
                            ▶
                        </div>
                    `;
                    }

                    // Load iframe baru
                    thumb.innerHTML = `<iframe class="w-full h-full rounded" src="https://www.youtube.com/embed/${videoId}?autoplay=1&rel=0"
                    frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>`;
                    currentIframe = thumb;
                });
            });
        });
    </script>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>




</body>

</html>
