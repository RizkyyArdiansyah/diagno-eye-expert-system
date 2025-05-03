<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dashboard Sistem Pakar</title>
    <script src="//unpkg.com/alpinejs" defer></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite('resources/css/app.css')
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        .scrollbar-hide {
            scrollbar-width: none;
            -ms-overflow-style: none;
        }

        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }

        [x-cloak] {
            display: none !important;
        }

        .sidebar-active {
            background-color: #3b82f6;
            color: white;
        }

        .page-item.active button {
            background-color: #3b82f6;
            color: white;
            border-color: #3b82f6;
        }

        /* Mobile sidebar styles */
        @media (max-width: 768px) {
            #sidebar {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                width: 100% !important;
                z-index: 50;
                transform: translateY(-100%);
                transition: transform 0.3s ease;
            }

            #sidebar.open {
                transform: translateY(0);
            }

            .sidebar-toggle-mobile {
                display: block;
            }
        }
    </style>
</head>

<body class="bg-gray-50 text-gray-800">
    <div class="fixed top-5 right-5 z-50 space-y-3">
        @if (session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
                class="px-4 py-3 rounded-lg bg-green-100 text-green-800 border border-green-300 shadow transition-all duration-300">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                class="px-4 py-3 rounded-lg bg-red-100 text-red-800 border border-red-300 shadow transition-all duration-300">
                <ul class="list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <div x-data="{
        open: true,
        activeTab: localStorage.getItem('activeTab') || 'gejala',
        isMobile: window.innerWidth < 768,
        mobileMenuOpen: false,
        showLogoutConfirm: false,
    
        setTab(tab) {
            this.activeTab = tab;
            localStorage.setItem('activeTab', tab);
            if (this.isMobile) {
                this.mobileMenuOpen = false;
            }
        },
    
        toggleMobileMenu() {
            this.mobileMenuOpen = !this.mobileMenuOpen;
        },
    
        init() {
            window.addEventListener('resize', () => {
                this.isMobile = window.innerWidth < 768;
            });
        }
    }" class="flex h-screen relative" x-init="init()">

        <!-- Mobile Menu Toggle Button (visible only on mobile) -->
        <button @click="toggleMobileMenu"
            class="fixed top-2 left-2 z-50 p-2 bg-blue-600 text-white rounded-md md:hidden">
            <i class="bi" :class="mobileMenuOpen ? 'bi-x' : 'bi-list'"></i>
        </button>

        <!-- Sidebar -->
        <div id="sidebar"
            :class="[
                open ? 'w-64' : 'w-16',
                isMobile ? (mobileMenuOpen ? 'open' : '') : '',
            ]"
            class="bg-white shadow-lg flex flex-col transition-all duration-300 ease-in-out select-none z-10">

            <!-- Logo and Toggle -->
            <div class="flex items-center p-4 border-b border-gray-100">
                <button @click="open = !open"
                    class="p-2 rounded-lg text-black hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 hidden md:block cursor-pointer">
                    <i class="bi bi-list text-xl"></i>
                </button>
                <h1 x-show="open || isMobile" x-transition class="ml-3 font-bold text-black text-lg">Diagno<span
                        class="text-blue-600">Eye</span></h1>
            </div>

            <!-- Menu Items -->
            <div class="px-1.5 py-4 flex-1 overflow-y-auto scrollbar-hide">
                <p x-show="open || isMobile" class="text-xs text-gray-500 mb-3 pl-3 uppercase tracking-wider">Menu Utama
                </p>

                <button @click="setTab('gejala')"
                    :class="activeTab === 'gejala' ? 'sidebar-active' : 'text-gray-700 hover:bg-gray-100'"
                    class="flex items-center px-4 py-3 rounded-lg mb-2 w-full transition-colors duration-200 cursor-pointer">
                    <i class="bi bi-bug text-lg" :class="activeTab === 'gejala' ? '' : 'text-gray-500'"></i>
                    <span class="ml-3" x-show="open || isMobile" x-transition>Data Gejala</span>
                </button>

                <button @click="setTab('penyakit')"
                    :class="activeTab === 'penyakit' ? 'sidebar-active' : 'text-gray-700 hover:bg-gray-100'"
                    class="flex items-center px-4 py-3 rounded-lg mb-2 w-full transition-colors duration-200 cursor-pointer">
                    <i class="bi bi-virus2 text-lg" :class="activeTab === 'penyakit' ? '' : 'text-gray-500'"></i>
                    <span class="ml-3" x-show="open || isMobile" x-transition>Data Penyakit</span>
                </button>

                <button @click="setTab('aturan')"
                    :class="activeTab === 'aturan' ? 'sidebar-active' : 'text-gray-700 hover:bg-gray-100'"
                    class="flex items-center px-4 py-3 rounded-lg mb-2 w-full transition-colors duration-200 cursor-pointer">
                    <i class="bi bi-diagram-3-fill text-lg"
                        :class="activeTab === 'aturan' ? '' : 'text-gray-500'"></i>
                    <span class="ml-3" x-show="open || isMobile" x-transition>Data Aturan</span>
                </button>

                <button @click="setTab('user')"
                    :class="activeTab === 'user' ? 'sidebar-active' : 'text-gray-700 hover:bg-gray-100'"
                    class="flex items-center px-4 py-3 rounded-lg mb-2 w-full transition-colors duration-200 cursor-pointer">
                    <i class="bi bi-person-lines-fill text-lg"
                        :class="activeTab === 'user' ? '' : 'text-gray-500'"></i>
                    <span class="ml-3" x-show="open || isMobile" x-transition>Data User</span>
                </button>
            </div>

            <!-- logout -->
            <div class="px-3 py-4 border-t border-gray-100">
                <button type="button" @click="showLogoutConfirm = true"
                    class="flex items-center px-4 py-3 text-red-600 hover:bg-red-100 rounded-lg w-full mt-2 transition-colors duration-200 cursor-pointer">
                    <i class="bi bi-box-arrow-right text-red-500"></i>
                    <span class="ml-3" x-show="open || isMobile" x-transition>Logout</span>
                </button>
            </div>
        </div>

        <!-- Content Area -->
        <div class="flex-1 overflow-hidden flex flex-col bg-gray-50" :class="isMobile ? 'pt-14' : ''">
            <!-- Header -->
            <header class="bg-white shadow-sm">
                <div class="px-6 py-4 flex items-center justify-between">
                    <h1 class="text-2xl font-semibold text-gray-800">Dashboard Sistem Pakar</h1>

                </div>
            </header>

            <!-- Main Content -->
            <main x-cloak x-data="{
                showUserModal: false,
                showGejalaModal: false,
                isEditGejala: false,
                gejala: {
                    id: null,
                    kode_gejala: '',
                    detail_gejala: ''
                },
                openAddModal() {
                    this.isEditGejala = false;
                    this.gejala = { id: null, kode_gejala: '', detail_gejala: '' };
                    this.showGejalaModal = true;
                },
                openEditModal(data) {
                    this.isEditGejala = true;
                    this.gejala = { ...data };
                    this.showGejalaModal = true;
                },
            
                // Penyakit
                showPenyakitModal: false,
                isEditPenyakit: false,
                penyakit: {
                    id: null,
                    kode_penyakit: '',
                    nama_penyakit: '',
                    solusi: ''
                },
                openAddPenyakitModal() {
                    this.isEditPenyakit = false;
                    this.penyakit = { id: null, kode_penyakit: '', nama_penyakit: '', solusi: '' };
                    this.showPenyakitModal = true;
                },
                openEditPenyakitModal(data) {
                    this.isEditPenyakit = true;
                    this.penyakit = { ...data };
                    this.showPenyakitModal = true;
                },
                showAturanModal: false,
                isEditAturan: false,
                aturan: {
                    id: null,
                    kode_penyakit: '',
                    kode_gejala: '',
                },
                openAddAturanModal() {
                    this.isEditAturan = false;
                    this.aturan = { id: null, kode_penyakit: '', kode_gejala: '' };
                    this.showAturanModal = true;
                },
                openEditAturanModal(data) {
                    this.isEditAturan = true;
                    this.aturan = { ...data };
                    this.showAturanModal = true;
                },
                showConfirmDelete: false,
                deleteUrl: '',
                confirmDelete(url) {
                    this.deleteUrl = url;
                    this.showConfirmDelete = true;
                },
                submitDelete() {
                    this.$refs.deleteForm.action = this.deleteUrl;
                    this.$refs.deleteForm.submit();
                },
            
            }" class="flex-1 overflow-y-auto p-1 select-none">

                <!-- Data Gejala -->
                <div x-show="activeTab === 'gejala'" x-transition class="bg-white rounded-lg shadow p-6 mb-10">
                    <div class="flex justify-between items-center mb-0.5 md:mb-4">
                        <h2 class="text-md md:text-lg font-bold text-gray-800">Data Gejala</h2>
                        <button @click="openAddModal"
                            class="px-2 md:px-4 py-2 bg-blue-600 w-24 md:w-auto text-xs md:text-sm text-white rounded-lg hover:bg-blue-700 transition-colors duration-200 flex items-center cursor-pointer">
                            <i class="bi bi-plus mr-2"></i> Tambah Data
                        </button>

                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full border-collapse">
                            <thead>
                                <tr class="bg-gray-50 text-left">
                                    <th
                                        class="px-4 py-3 text-sm md:text-md border-b-2 border-gray-200 text-gray-600 font-medium">
                                        Kode</th>
                                    <th
                                        class="px-4 py-3 text-sm md:text-md border-b-2 border-gray-200 text-gray-600 font-medium text-center">
                                        Nama
                                        Gejala</th>
                                    <th
                                        class="px-4 py-3 text-sm md:text-md border-b-2 border-gray-200 text-gray-600 font-medium text-center">
                                        Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($gejalas as $gejala)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3 border-b border-gray-200 text-xs md:text-[16px]">
                                            {{ $gejala->kode_gejala }}</td>
                                        <td class="px-4 py-3 border-b border-gray-200 text-xs md:text-[16px]">
                                            {{ $gejala->detail_gejala }}</td>
                                        <td class="px-4 py-3 border-b border-gray-200 text-xs md:text-[16px]">
                                            <div class="flex justify-start space-x-0">
                                                <!-- Tombol Edit -->
                                                <button
                                                    @click="openEditModal({ 
                                                    id: {{ $gejala->id }}, 
                                                    kode_gejala: '{{ $gejala->kode_gejala }}', 
                                                    detail_gejala: `{{ $gejala->detail_gejala }}`
                                                })"
                                                    class="px-2 py-1 text-blue-600 hover:text-blue-800 cursor-pointer">
                                                    <i class="bi bi-pencil"></i>
                                                </button>

                                                <!-- Form Delete -->
                                                <button type="button"
                                                    @click="confirmDelete('{{ route('gejala.destroy', $gejala->id) }}')"
                                                    class="px-2 py-1 text-red-600 hover:text-red-800 cursor-pointer">
                                                    <i class="bi bi-trash"></i>
                                                </button>

                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <div class="mt-4">
                                {{ $gejalas->links() }}
                            </div>
                        </table>
                    </div>
                </div>

                <!-- Modal Tambah/Edit Gejala -->
                <div x-show="showGejalaModal" x-transition x-transition.scale.opacity x-transition.duration.100ms
                    class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog"
                    aria-modal="true" style="display: none;">

                    <!-- Background overlay -->
                    <div class="fixed inset-0 backdrop-blur-md" x-transition.opacity></div>

                    <div class="flex items-center justify-center min-h-screen p-4 text-center sm:p-0">
                        <div @click.away="showGejalaModal = false"
                            class="relative bg-white rounded-lg shadow-xl transform transition-all sm:my-8 sm:max-w-lg sm:w-full p-6">

                            <!-- Header with close button -->
                            <div class="flex items-center justify-between mb-5 pb-3 border-b border-gray-100">
                                <h3 class="text-lg font-medium leading-6 text-gray-900" id="modal-title">
                                    <span x-text="isEditGejala ? 'Edit Gejala' : 'Tambah Gejala Baru'"></span>
                                </h3>
                                <button @click="showGejalaModal = false" type="button"
                                    class="text-gray-400 hover:text-gray-500 focus:outline-none">
                                    <span class="sr-only">Close</span>
                                    <i class="bi bi-x-lg"></i>
                                </button>
                            </div>

                            <!-- Form content -->
                            <form
                                x-bind:action="isEditGejala
                                    ?
                                    `/gejala/update/${gejala.id}` :
                                    '{{ route('gejala.store') }}'"
                                method="POST" class="space-y-4">
                                @csrf

                                <template x-if="isEditGejala">
                                    <input type="hidden" name="_method" value="PUT">
                                </template>


                                <div class="space-y-5">
                                    <div>
                                        <label for="kode_gejala"
                                            class="text-start block text-sm font-medium text-gray-700">Kode
                                            Gejala</label>
                                        <div class="mt-1 relative rounded-md shadow-sm">
                                            <div
                                                class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <i class="bi bi-hash text-gray-400"></i>
                                            </div>
                                            <input type="text" name="kode_gejala" id="kode_gejala" required
                                                x-model="gejala.kode_gejala"
                                                class="block w-full pl-10 sm:text-sm border border-gray-500 rounded-md py-2 px-3"
                                                placeholder="Contoh: G01">
                                        </div>
                                    </div>

                                    <div>
                                        <label for="detail_gejala"
                                            class="text-start block text-sm font-medium text-gray-700">Nama
                                            Gejala</label>
                                        <div class="mt-1 relative rounded-md shadow-sm">
                                            <div
                                                class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <i class="bi bi-clipboard2-pulse text-gray-400"></i>
                                            </div>
                                            <input type="text" name="detail_gejala" id="detail_gejala" required
                                                x-model="gejala.detail_gejala"
                                                class="block w-full pl-10 sm:text-sm border border-gray-500 rounded-md py-2 px-3"
                                                placeholder="Masukkan nama gejala">
                                        </div>
                                    </div>
                                </div>

                                <!-- Buttons -->
                                <div class="mt-6 pt-4 border-t border-gray-100 flex items-center justify-end gap-3">
                                    <button type="button" @click="showGejalaModal = false"
                                        class="px-4 py-2 bg-white border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200">
                                        Batal
                                    </button>
                                    <button type="submit"
                                        class="px-4 py-2 bg-gradient-to-r from-blue-600 to-blue-700 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                                        <div class="flex items-center">
                                            <i class="bi bi-check2 mr-2"></i>
                                            <span x-text="isEditGejala ? 'Update' : 'Simpan'"></span>
                                        </div>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>




                <!-- Data Penyakit -->
                <div x-show="activeTab === 'penyakit'" x-transition
                    class="bg-white rounded-lg shadow p-6 mb-10 select-none">
                    <div class="flex justify-between items-center mb-0.5 md:mb-4">
                        <h2 class="text-md md:text-lg font-bold text-gray-800">Data Penyakit</h2>
                        <button @click="openAddPenyakitModal()"
                            class="px-2 md:px-4 py-2 w-24 md:w-auto text-xs md:text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200 flex items-center cursor-pointer">
                            <i class="bi bi-plus mr-2"></i> Tambah Data
                        </button>

                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full border-collapse">
                            <thead>
                                <tr class="bg-gray-50 text-left">
                                    <th
                                        class="px-4 py-3 border-b-2 text-sm md:text-md border-gray-200 text-gray-600 font-medium">
                                        Kode
                                    </th>
                                    <th
                                        class="px-4 py-3 border-b-2 text-sm md:text-md border-gray-200 text-gray-600 font-medium text-left">
                                        Nama
                                        Penyakit</th>
                                    <th
                                        class="px-4 py-3 border-b-2 text-sm md:text-md border-gray-200 text-gray-600 font-medium text-center">
                                        Solusi
                                    </th>
                                    <th
                                        class="px-4 py-3 border-b-2 text-sm md:text-md border-gray-200 text-gray-600 font-medium text-center">
                                        Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($penyakits as $penyakit)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3 border-b border-gray-200 text-xs md:text-[16px]">
                                            {{ $penyakit->kode_penyakit }}
                                        </td>
                                        <td class="px-4 py-3 border-b border-gray-200 text-xs md:text-[16px]">
                                            {{ $penyakit->nama_penyakit }}
                                        </td>
                                        <td class="px-4 py-3 border-b border-gray-200 text-xs md:text-[16px]">
                                            {{ $penyakit->solusi }}</td>
                                        <td
                                            class="px-4 py-3 border-b border-gray-200 text-xs md:text-[16px] text-center">
                                            <button
                                                @click="openEditPenyakitModal({
                                                id: {{ $penyakit->id }},
                                                kode_penyakit: '{{ $penyakit->kode_penyakit }}',
                                                nama_penyakit: `{{ $penyakit->nama_penyakit }}`,
                                                solusi: `{{ $penyakit->solusi }}`})"
                                                class="px-2 py-1 text-blue-600 hover:text-blue-800 cursor-pointer">
                                                <i class="bi bi-pencil"></i>
                                            </button>

                                            <button type="button"
                                                @click="confirmDelete('{{ route('penyakit.destroy', $penyakit->id) }}')"
                                                class="px-2 py-1 text-red-600 hover:text-red-800 cursor-pointer">
                                                <i class="bi bi-trash"></i>
                                            </button>

                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <div class="mt-4">
                                {{ $penyakits->links() }}
                            </div>
                        </table>
                    </div>
                </div>

                <!-- Modal Tambah/Edit Penyakit -->
                <div x-show="showPenyakitModal" x-transition x-transition.scale.opacity x-transition.duration.100ms
                    class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog"
                    aria-modal="true" style="display: none;">

                    <!-- Background overlay -->
                    <div class="fixed inset-0 backdrop-blur-md" x-transition.opacity></div>

                    <!-- Modal content -->
                    <div class="flex items-center justify-center min-h-screen p-4 text-center sm:p-0">
                        <div @click.away="showPenyakitModal = false"
                            class="relative bg-white rounded-lg shadow-xl transform transition-all sm:my-8 sm:max-w-lg sm:w-full p-6">

                            <!-- Header -->
                            <div class="flex items-center justify-between mb-5 pb-3 border-b border-gray-100">
                                <h3 class="text-lg font-medium leading-6 text-gray-900" id="modal-title">
                                    <span x-text="isEditPenyakit ? 'Edit Penyakit' : 'Tambah Penyakit Baru'"></span>
                                </h3>
                                <button @click="showPenyakitModal = false" type="button"
                                    class="text-gray-400 hover:text-gray-500 focus:outline-none">
                                    <i class="bi bi-x-lg"></i>
                                </button>
                            </div>

                            <!-- Form -->
                            <form
                                x-bind:action="isEditPenyakit
                                    ?
                                    `/penyakit/update/${penyakit.id}` :
                                    '{{ route('penyakit.store') }}'"
                                method="POST" class="space-y-4">
                                @csrf

                                <template x-if="isEditPenyakit">
                                    <input type="hidden" name="_method" value="PUT">
                                </template>

                                <div class="space-y-5">
                                    <!-- Kode Penyakit -->
                                    <div>
                                        <label for="kode_penyakit"
                                            class="text-start block text-sm font-medium text-gray-700">Kode
                                            Penyakit</label>
                                        <div class="mt-1 relative border-1 border-gray-500 rounded-md shadow-sm">
                                            <div
                                                class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <i class="bi bi-hash text-gray-400"></i>
                                            </div>
                                            <input type="text" name="kode_penyakit" id="kode_penyakit" required
                                                x-model="penyakit.kode_penyakit"
                                                class="block w-full pl-10 sm:text-sm rounded-md py-2 px-3"
                                                placeholder="Contoh: P01">
                                        </div>
                                    </div>

                                    <!-- Nama Penyakit -->
                                    <div>
                                        <label for="nama_penyakit"
                                            class="text-start block text-sm font-medium text-gray-700">Nama
                                            Penyakit</label>
                                        <div class="mt-1 relative rounded-md shadow-sm">
                                            <div
                                                class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <i class="bi bi-activity text-gray-400"></i>
                                            </div>
                                            <input type="text" name="nama_penyakit" id="nama_penyakit" required
                                                x-model="penyakit.nama_penyakit"
                                                class="border-1 border-gray-500 block w-full pl-10 sm:text-sm rounded-md py-2 px-3"
                                                placeholder="Masukkan nama penyakit">
                                        </div>
                                    </div>

                                    <!-- Solusi -->
                                    <div>
                                        <label for="solusi"
                                            class="text-start block text-sm font-medium text-gray-700">Solusi</label>
                                        <textarea name="solusi" id="solusi" rows="3" required x-model="penyakit.solusi"
                                            class="mt-1 block w-full shadow-sm sm:text-sm border-1 border-gray-500 rounded-md px-3 py-2"
                                            placeholder="Masukkan solusi penanganan penyakit"></textarea>
                                    </div>
                                </div>

                                <!-- Buttons -->
                                <div class="mt-6 pt-4 border-t border-gray-100 flex items-center justify-end gap-3">
                                    <button type="button" @click="showPenyakitModal = false"
                                        class="px-4 py-2 bg-white border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200">
                                        Batal
                                    </button>
                                    <button type="submit"
                                        class="px-4 py-2 bg-gradient-to-r from-blue-600 to-blue-700 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:from-blue-700 hover:to-blue-800">
                                        <div class="flex items-center">
                                            <i class="bi bi-check2 mr-2"></i>
                                            <span x-text="isEditPenyakit ? 'Update' : 'Simpan'"></span>
                                        </div>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>


                <!-- Data Aturan -->
                <div x-show="activeTab === 'aturan'" x-transition class="bg-white rounded-lg shadow p-6 mb-10">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-md md:text-lg font-bold text-gray-800">Data Aturan</h2>
                        <button @click="openAddAturanModal()"
                            class="px-2 md:px-4 py-2 w-24 md:w-auto text-xs md:text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200 flex items-center cursor-pointer">
                            <i class="bi bi-plus mr-2"></i> Tambah Data
                        </button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full border-collapse">
                            <thead>
                                <tr class="bg-gray-50 text-left">
                                    <th
                                        class="px-4 py-3 border-b-2 text-sm md:text-md text-center border-gray-200 text-gray-600 font-medium">
                                        Penyakit
                                    </th>
                                    <th
                                        class="px-4 py-3 border-b-2 text-sm md:text-md text-center border-gray-200 text-gray-600 font-medium">
                                        Gejala
                                    </th>
                                    <th
                                        class="px-4 py-3 border-b-2 text-sm md:text-md text-center border-gray-200 text-gray-600 font-medium">
                                        Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($aturans as $aturan)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3 border-b border-gray-200 text-center">
                                            {{ $aturan->kode_penyakit }}
                                        </td>
                                        <td class="px-4 py-3 border-b border-gray-200 text-center">
                                            {{ $aturan->kode_gejala }}</td>
                                        <td class="px-4 py-3 border-b border-gray-200 text-center">
                                            <!-- Tombol Edit Aturan -->
                                            <button
                                                @click="openEditAturanModal({ 
                                                    id: {{ $aturan->id }}, 
                                                    kode_penyakit: '{{ $aturan->kode_penyakit }}', 
                                                    kode_gejala: '{{ $aturan->kode_gejala }}'
                                                })"
                                                class="px-2 py-1 text-blue-600 hover:text-blue-800 cursor-pointer">
                                                <i class="bi bi-pencil"></i>
                                            </button>

                                            <!-- Tombol Hapus Aturan -->
                                            <button type="button"
                                                @click="confirmDelete('{{ route('aturan.destroy', $aturan->id) }}')"
                                                class="px-2 py-1 text-red-600 hover:text-red-800 cursor-pointer">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <div class="mt-4">
                                {{ $aturans->links() }}
                            </div>
                        </table>
                    </div>
                </div>

                <!-- Modal Tambah data Aturan -->
                <div x-show="showAturanModal" x-transition x-transition.scale.opacity x-transition.duration.100ms
                    class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog"
                    aria-modal="true" style="display: none;">

                    <!-- Background overlay -->
                    <div class="fixed inset-0 backdrop-blur-md" x-transition.opacity></div>

                    <!-- Modal content -->
                    <div class="flex items-center justify-center min-h-screen p-4 text-center sm:p-0">
                        <div @click.away="showAturanModal = false"
                            class="relative bg-white rounded-lg shadow-xl transform transition-all sm:my-8 sm:max-w-lg sm:w-full p-6">

                            <!-- Header -->
                            <div class="flex items-center justify-between mb-5 pb-3 border-b border-gray-100">
                                <h3 class="text-lg font-medium leading-6 text-gray-900" id="modal-title">
                                    <span x-text="isEditAturan ? 'Edit Aturan' : 'Tambah Aturan Baru'"></span>
                                </h3>
                                <button @click="showAturanModal = false" type="button"
                                    class="text-gray-400 hover:text-gray-500 focus:outline-none">
                                    <i class="bi bi-x-lg"></i>
                                </button>
                            </div>

                            <!-- Form -->
                            <form
                                x-bind:action="isEditAturan
                                    ?
                                    `/aturan/update/${aturan.id}` :
                                    '{{ route('aturan.store') }}'"
                                method="POST" class="space-y-4">
                                @csrf

                                <template x-if="isEditAturan">
                                    <input type="hidden" name="_method" value="PUT">
                                </template>

                                <div class="space-y-5">
                                    <!-- Edit Form (Show when isEditAturan is true) -->
                                    <template x-if="isEditAturan">
                                        <div class="space-y-5">
                                            <!-- Kode Penyakit -->
                                            <div>
                                                <label for="kode_penyakit"
                                                    class="text-start block text-sm font-medium text-gray-700">Kode
                                                    Penyakit</label>
                                                <div
                                                    class="mt-1 relative border-1 border-gray-500 rounded-md shadow-sm">
                                                    <div
                                                        class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                        <i class="bi bi-hash text-gray-400"></i>
                                                    </div>
                                                    <input type="text" name="kode_penyakit" id="kode_penyakit"
                                                        required x-model="aturan.kode_penyakit"
                                                        class="block w-full pl-10 sm:text-sm rounded-md py-2 px-3"
                                                        placeholder="Contoh: P01">
                                                </div>
                                            </div>

                                            <!-- Kode Gejala -->
                                            <div>
                                                <label for="kode_gejala"
                                                    class="text-start block text-sm font-medium text-gray-700">Kode
                                                    Gejala</label>
                                                <div
                                                    class="mt-1 relative border-1 border-gray-500 rounded-md shadow-sm">
                                                    <div
                                                        class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                        <i class="bi bi-hash text-gray-400"></i>
                                                    </div>
                                                    <input type="text" name="kode_gejala" id="kode_gejala"
                                                        required x-model="aturan.kode_gejala"
                                                        class="block w-full pl-10 sm:text-sm rounded-md py-2 px-3"
                                                        placeholder="Contoh: G01">
                                                </div>
                                            </div>
                                        </div>
                                    </template>

                                    <!-- Add Form (Show when isEditAturan is false) -->
                                    <template x-if="!isEditAturan">
                                        <div class="space-y-5">
                                            <!-- Dropdown Penyakit -->
                                            <div>
                                                <label for="penyakit_id"
                                                    class="text-start block text-sm font-medium text-gray-700">
                                                    Pilih Penyakit
                                                </label>
                                                <div class="mt-1 relative rounded-md shadow-sm">
                                                    <select name="kode_penyakit" id="kode_penyakit" required
                                                        class="block w-full sm:text-sm border-gray-300 rounded-md py-2 px-3 focus:ring-blue-500 focus:border-blue-500">
                                                        <option value="kode_penyakit">-- Pilih Penyakit --</option>
                                                        @foreach ($allPenyakits as $penyakit)
                                                            <option value="{{ $penyakit->kode_penyakit }}">
                                                                {{ $penyakit->kode_penyakit }} -
                                                                {{ $penyakit->nama_penyakit }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <!-- Checkbox Gejala -->
                                            <div class="mt-4">
                                                <label class="text-start block text-sm font-medium text-gray-700 mb-2">
                                                    Pilih Gejala (bisa lebih dari satu)
                                                </label>
                                                <div
                                                    class="mt-2 max-h-60 overflow-y-auto p-2 border border-gray-300 rounded-md">
                                                    @foreach ($allGejalas as $gejala)
                                                        <div class="flex items-start mb-2">
                                                            <div class="flex items-center h-5">
                                                                <input id="gejala-{{ $gejala->id }}"
                                                                    name="kode_gejala[]" type="checkbox"
                                                                    value="{{ $gejala->kode_gejala }}"
                                                                    class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded">
                                                            </div>
                                                            <div class="ml-3 text-sm">
                                                                <label for="gejala-{{ $gejala->id }}"
                                                                    class="font-medium text-gray-700">
                                                                    {{ $gejala->kode_gejala }} -
                                                                    {{ $gejala->detail_gejala }}
                                                                </label>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                                <div class="mt-2 text-xs text-gray-500">
                                                    <span>* Pilih beberapa gejala yang terkait dengan penyakit yang
                                                        dipilih</span>
                                                </div>
                                            </div>
                                        </div>
                                    </template>

                                    <!-- Buttons -->
                                    <div
                                        class="mt-6 pt-4 border-t border-gray-100 flex items-center justify-end gap-3">
                                        <button type="button" @click="showAturanModal = false"
                                            class="px-4 py-2 bg-white border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200">
                                            Batal
                                        </button>
                                        <button type="submit"
                                            class="px-4 py-2 bg-gradient-to-r from-blue-600 to-blue-700 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:from-blue-700 hover:to-blue-800">
                                            <div class="flex items-center">
                                                <i class="bi bi-check2 mr-2"></i>
                                                <span x-text="isEditAturan ? 'Update' : 'Simpan'"></span>
                                            </div>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Data User -->
                <div x-show="activeTab === 'user'" x-transition class="bg-white rounded-lg shadow p-6 mb-10">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-md md:text-lg font-bold text-gray-800">Data User</h2>
                        <button @click="showUserModal = true"
                            class="px-2 md:px-4 py-2 w-24 md:w-auto text-xs md:text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200 flex items-center cursor-pointer">
                            <i class="bi bi-plus mr-2"></i> Tambah Admin
                        </button>
                    </div>

                    <div class="overflow-x-auto">
                        <!-- TABEL USER YANG SUDAH DITAMBAHKAN KOLOM ROLE -->
                        <table class="w-full border-collapse table-fixed md:table-auto">
                            <thead>
                                <tr class="bg-gray-50 text-left">
                                    <th
                                        class="px-4 py-3 border-b-2 text-sm md:text-md text-start  border-gray-200 text-gray-600 font-medium">
                                        Nama
                                    </th>
                                    <th
                                        class="px-4 py-3 border-b-2 text-sm md:text-md text-start border-gray-200 text-gray-600 font-medium">
                                        Email
                                    </th>
                                    <th
                                        class="px-4 py-3 border-b-2 text-sm md:text-md border-gray-200 text-gray-600 font-medium text-center">
                                        Role
                                    </th> <!-- Baru -->
                                    <th
                                        class="px-4 py-3 border-b-2 text-sm md:text-md text-center border-gray-200 text-gray-600 font-medium">
                                        Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3 border-b border-gray-200 text-xs md:text-[16px]">
                                            {{ $user->name }}</td>
                                        <td class="px-4 py-3 border-b border-gray-200 truncate text-xs md:text-[16px]">
                                            {{ $user->email }}</td>
                                        <td
                                            class="px-4 py-3 border-b border-gray-200 text-xs md:text-[16px] text-center">
                                            {{ $user->is_admin == 1 ? 'Admin' : 'User' }}
                                        </td>
                                        <td class="px-4 py-3 border-b border-gray-200 text-center">
                                            <button type="button"
                                                @click="confirmDelete('{{ route('user.destroy', $user->id) }}')"
                                                class="px-2 py-1 text-red-600 hover:text-red-800 cursor-pointer">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <div class="mt-4">
                                {{ $users->links() }}
                            </div>
                        </table>
                    </div>
                </div>

                <!-- Modal Tambah data Admin -->
                <div x-show="showUserModal" x-transition x-transition.scale.opacity x-transition.duration.100ms
                    class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog"
                    aria-modal="true" style="display: none;">

                    <!-- Background overlay -->
                    <div class="fixed inset-0 backdrop-blur-md" x-transition.opacity></div>

                    <!-- Modal content -->
                    <div class="flex items-center justify-center min-h-screen p-4 text-center sm:p-0">
                        <div @click.away="showUserModal = false"
                            class="relative bg-white rounded-lg shadow-xl transform transition-all sm:my-8 sm:max-w-lg sm:w-full p-6">

                            <!-- Header -->
                            <div class="flex items-center justify-between mb-5 pb-3 border-b border-gray-100">
                                <h3 class="text-lg font-medium leading-6 text-gray-900" id="modal-title">
                                    Tambah Admin Baru
                                </h3>
                                <button @click="showUserModal = false" type="button"
                                    class="text-gray-400 hover:text-gray-500 focus:outline-none">
                                    <i class="bi bi-x-lg"></i>
                                </button>
                            </div>

                            <!-- Form -->
                            <form action="{{ route('user.store') }}" method="POST" class="space-y-4">
                                @csrf

                                <div class="space-y-5">
                                    <!-- Nama -->
                                    <div>
                                        <label for="name"
                                            class="text-start block text-sm font-medium text-gray-700 mb-1">Nama
                                            Lengkap</label>
                                        <input type="text" name="name" id="name" required
                                            class="block w-full border-1 border-gray-500 rounded-md py-2 px-3 sm:text-sm"
                                            placeholder="Masukkan nama admin">
                                    </div>

                                    <!-- Email -->
                                    <div>
                                        <label for="email"
                                            class="text-start block text-sm font-medium text-gray-700 mb-1">Email</label>
                                        <input type="email" name="email" id="email" required
                                            class="block w-full border-1 border-gray-500 rounded-md py-2 px-3 sm:text-sm"
                                            placeholder="admin@email.com">
                                    </div>

                                    <!-- Password -->
                                    <div>
                                        <label for="password"
                                            class="text-start block text-sm font-medium text-gray-700 mb-1">Password</label>
                                        <input type="password" name="password" id="password" required
                                            class="block w-full border-1 border-gray-500 rounded-md py-2 px-3 sm:text-sm"
                                            placeholder="Minimal 6 karakter">
                                    </div>

                                    <!-- Hidden is_admin = 1 -->
                                    <input type="hidden" name="is_admin" value="1">

                                    <!-- Buttons -->
                                    <div
                                        class="mt-6 pt-4 border-t border-gray-100 flex items-center justify-end gap-3">
                                        <button type="button" @click="showUserModal = false"
                                            class="px-4 py-2 bg-white border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200">
                                            Batal
                                        </button>
                                        <button type="submit"
                                            class="px-4 py-2 bg-gradient-to-r from-blue-600 to-blue-700 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:from-blue-700 hover:to-blue-800">
                                            <div class="flex items-center">
                                                <i class="bi bi-check2 mr-2"></i>
                                                <span>Simpan</span>
                                            </div>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>


                <!-- Pop up konfirmasi delete -->
                <div x-show="showConfirmDelete" x-transition:enter="transition ease-out duration-100"
                    x-transition:enter-start="opacity-0 transform scale-95"
                    x-transition:enter-end="opacity-100 transform scale-100"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 transform scale-100"
                    x-transition:leave-end="opacity-0 transform scale-95"
                    class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display: none;">

                    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" @click="showConfirmDelete = false">
                    </div>

                    <div @click.away="showConfirmDelete = false"
                        class="relative bg-white dark:bg-gray-800 w-full max-w-md rounded-xl shadow-xl overflow-hidden"
                        style="box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1), 0 8px 10px -5px rgba(0,0,0,0.04);">

                        <div class="bg-red-500 h-1 w-full"></div>

                        <div class="p-6">
                            <div class="flex items-start">
                                <!-- Warning icon -->
                                <div class="flex-shrink-0 mr-4">
                                    <svg class="h-10 w-10 text-red-500" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                </div>

                                <!-- Text content -->
                                <div>
                                    <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Konfirmasi Hapus
                                    </h2>
                                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">Apakah kamu yakin ingin
                                        menghapus data ini? Tindakan ini tidak bisa dibatalkan.</p>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="mt-6 flex justify-end space-x-3">
                                <button @click="showConfirmDelete = false"
                                    class="px-4 py-2 rounded-lg border border-gray-300 text-gray-700 font-medium hover:ring-2 hover:ring-red-300 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-red-500 cursor-pointer">
                                    Batal
                                </button>
                                <form x-ref="deleteForm" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" @click="submitDelete"
                                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors focus:outline-none focus:ring-2 focus:ring-red-500 cursor-pointer">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pop up konfirmasi ketika user logout -->
                <div>
                    <template x-if="showLogoutConfirm">
                        <div
                            class="fixed inset-0 backdrop-blur-md bg-black/40 flex items-center justify-center z-50 transition-all duration-300">
                            <div class="bg-white rounded-xl shadow-2xl max-w-sm w-[20rem] md:w-full transform transition-all duration-300 scale-100 opacity-100"
                                x-transition:enter="transition ease-out duration-300"
                                x-transition:enter-start="opacity-0 scale-95"
                                x-transition:enter-end="opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-200"
                                x-transition:leave-start="opacity-100 scale-100"
                                x-transition:leave-end="opacity-0 scale-95">

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
                                    <p class="text-gray-600 mb-6 text-center">Apakah kamu yakin ingin keluar dari
                                        akunmu?</p>
                                    <div class="flex justify-center space-x-3">
                                        <button @click="showLogoutConfirm = false"
                                            class="px-5 py-2 rounded-lg border border-gray-300 text-gray-700 font-medium hover:ring-2 hover:ring-red-300 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-red-500 cursor-pointer">
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
                
            </main>

            <!-- Footer -->
            <footer x-cloak class="bg-white border-t border-gray-200 py-3 px-6">
                <p class="text-center text-gray-600 text-sm">© 2025 DiagnoEye. All rights reserved.</p>
            </footer>
        </div>
    </div>
</body>

</html>
