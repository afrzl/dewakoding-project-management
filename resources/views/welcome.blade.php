<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AturKerja by DewaKoding</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* Reveal Animations */
        .reveal {
            opacity: 0;
            transform: translateY(30px);
            transition: opacity 0.8s ease-out, transform 0.8s ease-out;
        }

        .reveal.active {
            opacity: 1;
            transform: translateY(0);
        }

        .reveal-left {
            opacity: 0;
            transform: translateX(-50px);
            transition: opacity 0.8s ease-out, transform 0.8s ease-out;
        }

        .reveal-left.active {
            opacity: 1;
            transform: translateX(0);
        }

        .reveal-right {
            opacity: 0;
            transform: translateX(50px);
            transition: opacity 0.8s ease-out, transform 0.8s ease-out;
        }

        .reveal-right.active {
            opacity: 1;
            transform: translateX(0);
        }

        .reveal-scale {
            opacity: 0;
            transform: scale(0.9);
            transition: opacity 0.8s ease-out, transform 0.8s ease-out;
        }

        .reveal-scale.active {
            opacity: 1;
            transform: scale(1);
        }

        .reveal-fade {
            opacity: 0;
            transition: opacity 1s ease-out;
        }

        .reveal-fade.active {
            opacity: 1;
        }

        /* Stagger delays */
        .delay-100 {
            transition-delay: 0.1s;
        }

        .delay-200 {
            transition-delay: 0.2s;
        }

        .delay-300 {
            transition-delay: 0.3s;
        }

        .delay-400 {
            transition-delay: 0.4s;
        }

        .delay-500 {
            transition-delay: 0.5s;
        }

        .delay-600 {
            transition-delay: 0.6s;
        }
    </style>
</head>

<body class="antialiased bg-white text-black font-sans selection:bg-[#cce9ff] selection:text-black">

    <!-- Header -->
    <header class="fixed top-0 w-full bg-white/80 backdrop-blur-md z-50 border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center gap-2 cursor-pointer">
                    <svg class="w-8 h-8" viewBox="0 0 24 24" fill="currentColor">
                        <path
                            d="M4.459 4.208c.746.606 1.026.56 2.428.466l13.215-.793c.28 0 .047.28.047.653v15.03c0 .373.093.606-.56.606-.513 0-2.983-.233-4.616-.42-2.193-.233-4.29-.373-5.316-.373-.793 0-2.566.233-3.64.513-.933.233-1.4.326-1.4.047 0-.187 0-6.113 0-11.807 0-2.706-.326-3.266-1.26-3.873L2.26 3.462c-.28-.186-.233-.42.14-.42h2.053zM7.893 6.26v11.853c1.306 0 3.36.233 5.6.466 1.446.14 3.266.326 3.733.326.28 0 .326-.093.326-.42V5.28c0-.28-.047-.373-.326-.373-.28 0-1.96.187-4.2.373-2.1.187-4.153.373-5.133.373z" />
                    </svg>
                    <span class="font-semibold text-lg tracking-tight">AturKerja</span>
                </div>

                <!-- Desktop Nav -->
                <nav class="hidden md:flex gap-8 text-sm font-medium text-gray-600">
                    <a href="#features" class="hover:text-black transition-colors">Fitur</a>
                    <a href="#how-it-works" class="hover:text-black transition-colors">Cara Kerja</a>
                    <a href="#about" class="hover:text-black transition-colors">Tentang</a>
                </nav>

                <!-- Actions -->
                <div class="flex items-center gap-4">
                    <a href="{{ route('filament.admin.auth.login') }}"
                        class="text-sm font-medium bg-black text-white px-4 py-2 rounded-md hover:bg-gray-800 transition-colors">Coba
                        AturKerja gratis</a>
                </div>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="pt-32 pb-20 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto text-center">
        <h1
            class="reveal text-4xl sm:text-5xl md:text-7xl font-bold tracking-tight mb-6 max-w-4xl mx-auto leading-[1.1]">
            Rencanakan, Lacak, Selesaikan.<br>
            Project Jadi Lebih Mudah.
        </h1>
        <p class="reveal delay-100 text-lg sm:text-xl md:text-2xl text-gray-600 mb-10 max-w-2xl mx-auto font-medium">
            AturKerja adalah platform project management di mana tim berkolaborasi, melacak progres, dan menyelesaikan tepat waktu.
        </p>
        <div class="reveal delay-200 flex flex-col sm:flex-row justify-center gap-4 mb-10 sm:mb-16">
            <a href="{{ route('filament.admin.auth.login') }}"
                class="bg-black text-white px-6 py-3 rounded-md font-semibold text-base sm:text-lg hover:bg-gray-800 transition-all transform hover:-translate-y-0.5">Coba
                AturKerja gratis</a>
        </div>

        <!-- Hero Image Placeholder (Kanban Board) -->
        <div class="reveal-scale delay-300 relative mx-auto max-w-6xl hidden sm:block">
            <div
                class="bg-gray-50 rounded-xl border border-gray-200 shadow-2xl overflow-hidden aspect-[16/9] flex flex-col group">
                <div class="absolute inset-0 bg-gradient-to-b from-transparent to-white/5 pointer-events-none"></div>

                <!-- App Header -->
                <div class="bg-white border-b border-gray-200 p-4 flex justify-between items-center">
                    <div class="flex items-center gap-4">
                        <h2 class="text-xl font-bold text-gray-800">Project Board</h2>
                    </div>
                    <div class="flex gap-2">
                        <button
                            class="bg-red-600 text-white px-3 py-1.5 rounded text-sm font-medium hover:bg-red-700 transition-colors">+
                            Ticket Baru</button>
                        <button
                            class="bg-yellow-400 text-yellow-900 px-3 py-1.5 rounded text-sm font-medium hover:bg-yellow-500 transition-colors">Segarkan
                            Board</button>
                    </div>
                </div>

                <!-- Kanban Columns -->
                <div class="flex-1 bg-gray-50 p-6 overflow-hidden flex gap-6 text-left">

                    <!-- Backlog Column -->
                    <div class="flex-1 flex flex-col gap-4 min-w-[250px]">
                        <div class="bg-gray-600 text-white px-4 py-2 rounded-t-lg flex justify-between items-center">
                            <span class="font-medium">Backlog</span>
                            <span class="bg-gray-500 px-2 py-0.5 rounded text-xs">4</span>
                        </div>
                        <!-- Ticket 1 -->
                        <div
                            class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition-shadow cursor-pointer group">
                            <div class="flex justify-between items-start mb-2">
                                <span class="text-xs text-gray-400 font-mono">KDID-842</span>
                                <div class="w-2 h-2 rounded-full bg-gray-300"></div>
                            </div>
                            <h4 class="font-bold text-gray-800 mb-2 text-sm">Riset strategi caching baru</h4>
                            <p class="text-xs text-gray-500 mb-3 line-clamp-2">Investigasi Redis vs Memcached untuk
                                optimasi session storage.</p>
                            <div class="flex items-center justify-between">
                                <div class="flex -space-x-2">
                                    <div
                                        class="w-6 h-6 rounded-full bg-purple-500 text-white flex items-center justify-center text-[10px] border-2 border-white">
                                        JD</div>
                                </div>
                                <span
                                    class="px-2 py-0.5 bg-gray-100 text-gray-600 text-[10px] rounded font-medium">Backend</span>
                            </div>
                        </div>
                        <!-- Ticket 2 -->
                        <div
                            class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition-shadow cursor-pointer group">
                            <div class="flex justify-between items-start mb-2">
                                <span class="text-xs text-gray-400 font-mono">KDID-845</span>
                                <div class="w-2 h-2 rounded-full bg-red-400"></div>
                            </div>
                            <h4 class="font-bold text-gray-800 mb-2 text-sm">Perbaiki bug navigasi mobile</h4>
                            <div class="flex items-center justify-between mt-3">
                                <div class="flex -space-x-2">
                                    <div
                                        class="w-6 h-6 rounded-full bg-blue-500 text-white flex items-center justify-center text-[10px] border-2 border-white">
                                        AL</div>
                                </div>
                                <span
                                    class="px-2 py-0.5 bg-red-50 text-red-600 text-[10px] rounded font-medium">Bug</span>
                            </div>
                        </div>
                        <!-- Ticket 3 -->
                        <div
                            class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition-shadow cursor-pointer group">
                            <div class="flex justify-between items-start mb-2">
                                <span class="text-xs text-gray-400 font-mono">KDID-850</span>
                                <div class="w-2 h-2 rounded-full bg-gray-300"></div>
                            </div>
                            <h4 class="font-bold text-gray-800 mb-2 text-sm">Update package dependency</h4>
                            <p class="text-xs text-gray-500 mb-3 line-clamp-2">Persiapan upgrade Laravel 11.</p>
                            <div class="flex items-center justify-between">
                                <div class="flex -space-x-2">
                                    <div
                                        class="w-6 h-6 rounded-full bg-green-500 text-white flex items-center justify-center text-[10px] border-2 border-white">
                                        MK</div>
                                </div>
                                <span
                                    class="px-2 py-0.5 bg-gray-100 text-gray-600 text-[10px] rounded font-medium">Maint</span>
                            </div>
                        </div>
                    </div>

                    <!-- To Do Column -->
                    <div class="flex-1 flex flex-col gap-4 min-w-[250px]">
                        <div class="bg-orange-400 text-white px-4 py-2 rounded-t-lg flex justify-between items-center">
                            <span class="font-medium">To Do</span>
                            <span class="bg-orange-300 px-2 py-0.5 rounded text-xs">3</span>
                        </div>
                        <!-- Ticket 1 -->
                        <div
                            class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition-shadow cursor-pointer group">
                            <div class="flex justify-between items-start mb-2">
                                <span class="text-xs text-gray-400 font-mono">KDID-901</span>
                                <div class="w-2 h-2 rounded-full bg-orange-400"></div>
                            </div>
                            <h4 class="font-bold text-gray-800 mb-2 text-sm">Implementasi Design System</h4>
                            <p class="text-xs text-gray-500 mb-3 line-clamp-2">Buat komponen dasar untuk button,
                                input, dan card.</p>
                            <div class="flex items-center justify-between">
                                <div class="flex -space-x-2">
                                    <div
                                        class="w-6 h-6 rounded-full bg-pink-500 text-white flex items-center justify-center text-[10px] border-2 border-white">
                                        SJ</div>
                                    <div
                                        class="w-6 h-6 rounded-full bg-blue-500 text-white flex items-center justify-center text-[10px] border-2 border-white">
                                        AL</div>
                                </div>
                                <span
                                    class="px-2 py-0.5 bg-purple-50 text-purple-600 text-[10px] rounded font-medium">Design</span>
                            </div>
                        </div>
                        <!-- Ticket 2 -->
                        <div
                            class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition-shadow cursor-pointer group">
                            <div class="flex justify-between items-start mb-2">
                                <span class="text-xs text-gray-400 font-mono">KDID-905</span>
                                <div class="w-2 h-2 rounded-full bg-orange-400"></div>
                            </div>
                            <h4 class="font-bold text-gray-800 mb-2 text-sm">API Authentication Flow</h4>
                            <div class="flex items-center justify-between mt-3">
                                <div class="flex -space-x-2">
                                    <div
                                        class="w-6 h-6 rounded-full bg-indigo-500 text-white flex items-center justify-center text-[10px] border-2 border-white">
                                        DR</div>
                                </div>
                                <span
                                    class="px-2 py-0.5 bg-blue-50 text-blue-600 text-[10px] rounded font-medium">Backend</span>
                            </div>
                        </div>
                        <!-- Ticket 3 -->
                        <div
                            class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition-shadow cursor-pointer group">
                            <div class="flex justify-between items-start mb-2">
                                <span class="text-xs text-gray-400 font-mono">KDID-912</span>
                                <div class="w-2 h-2 rounded-full bg-orange-400"></div>
                            </div>
                            <h4 class="font-bold text-gray-800 mb-2 text-sm">Halaman Profil User</h4>
                            <p class="text-xs text-gray-500 mb-3 line-clamp-2">Implementasi fitur lihat dan edit profil.
                            </p>
                            <div class="flex items-center justify-between">
                                <div class="flex -space-x-2">
                                    <div
                                        class="w-6 h-6 rounded-full bg-teal-500 text-white flex items-center justify-center text-[10px] border-2 border-white">
                                        TM</div>
                                </div>
                                <span
                                    class="px-2 py-0.5 bg-green-50 text-green-600 text-[10px] rounded font-medium">Frontend</span>
                            </div>
                        </div>
                    </div>

                    <!-- In Progress Column -->
                    <div class="flex-1 flex flex-col gap-4 min-w-[250px] hidden md:flex">
                        <div class="bg-blue-500 text-white px-4 py-2 rounded-t-lg flex justify-between items-center">
                            <span class="font-medium">In Progress</span>
                            <span class="bg-blue-400 px-2 py-0.5 rounded text-xs">2</span>
                        </div>
                        <!-- Ticket 1 -->
                        <div
                            class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition-shadow cursor-pointer group">
                            <div class="flex justify-between items-start mb-2">
                                <span class="text-xs text-gray-400 font-mono">KDID-880</span>
                                <div class="w-2 h-2 rounded-full bg-blue-500"></div>
                            </div>
                            <h4 class="font-bold text-gray-800 mb-2 text-sm">Refactor Landing Page</h4>
                            <p class="text-xs text-gray-500 mb-3 line-clamp-2">Implementasi desain baru bergaya Notion dengan
                                Tailwind CSS.</p>
                            <div class="flex items-center justify-between">
                                <div class="flex -space-x-2">
                                    <div
                                        class="w-6 h-6 rounded-full bg-red-500 text-white flex items-center justify-center text-[10px] border-2 border-white">
                                        AF</div>
                                </div>
                                <span
                                    class="px-2 py-0.5 bg-orange-50 text-orange-600 text-[10px] rounded font-medium">Frontend</span>
                            </div>
                        </div>
                        <!-- Ticket 2 -->
                        <div
                            class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition-shadow cursor-pointer group">
                            <div class="flex justify-between items-start mb-2">
                                <span class="text-xs text-gray-400 font-mono">KDID-882</span>
                                <div class="w-2 h-2 rounded-full bg-blue-500"></div>
                            </div>
                            <h4 class="font-bold text-gray-800 mb-2 text-sm">Database Migration</h4>
                            <div class="flex items-center justify-between mt-3">
                                <div class="flex -space-x-2">
                                    <div
                                        class="w-6 h-6 rounded-full bg-gray-700 text-white flex items-center justify-center text-[10px] border-2 border-white">
                                        DB</div>
                                </div>
                                <span
                                    class="px-2 py-0.5 bg-gray-100 text-gray-600 text-[10px] rounded font-medium">DevOps</span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- Mobile Hero Card -->
        <div class="sm:hidden">
            <div class="bg-gray-50 rounded-xl border border-gray-200 shadow-lg overflow-hidden">
                <!-- App Header -->
                <div class="bg-white border-b border-gray-200 p-3 flex justify-between items-center">
                    <span class="text-sm font-bold text-gray-800">Project Board</span>
                    <div class="flex gap-1">
                        <span class="bg-red-600 text-white px-2 py-1 rounded text-xs font-medium">+ Baru</span>
                    </div>
                </div>
                <!-- Simple Kanban Preview -->
                <div class="p-3 space-y-2">
                    <div class="flex gap-2">
                        <div class="flex-1 bg-gray-600 text-white px-2 py-1 rounded text-xs font-medium text-center">
                            Backlog</div>
                        <div class="flex-1 bg-orange-400 text-white px-2 py-1 rounded text-xs font-medium text-center">
                            To Do</div>
                        <div class="flex-1 bg-blue-500 text-white px-2 py-1 rounded text-xs font-medium text-center">
                            Doing</div>
                    </div>
                    <div class="grid grid-cols-3 gap-2">
                        <div class="bg-white p-2 rounded border border-gray-200 shadow-sm">
                            <div class="text-[10px] text-gray-400 font-mono mb-1">KDID-842</div>
                            <div class="text-xs font-medium text-gray-800 line-clamp-2">Riset caching</div>
                        </div>
                        <div class="bg-white p-2 rounded border border-gray-200 shadow-sm">
                            <div class="text-[10px] text-gray-400 font-mono mb-1">KDID-901</div>
                            <div class="text-xs font-medium text-gray-800 line-clamp-2">Design System</div>
                        </div>
                        <div class="bg-white p-2 rounded border border-gray-200 shadow-sm">
                            <div class="text-[10px] text-gray-400 font-mono mb-1">KDID-880</div>
                            <div class="text-xs font-medium text-gray-800 line-clamp-2">Landing Page</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Clients Section -->
    <section class="py-16 border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p class="reveal-fade text-sm font-medium text-gray-500 mb-8">DIPERCAYA OLEH TIM DI</p>
            <div class="reveal-fade delay-200 flex flex-wrap justify-center gap-8 md:gap-16 grayscale opacity-60">
                <!-- Simple text placeholders for logos to keep it clean -->
                <span class="text-xl font-bold font-serif">FIGMA</span>
                <span class="text-xl font-bold font-mono">PIXAR</span>
                <span class="text-xl font-bold">DOORDASH</span>
                <span class="text-xl font-bold font-sans">NIKE</span>
                <span class="text-xl font-bold font-serif italic">AMAZON</span>
                <span class="text-xl font-bold">PINTEREST</span>
            </div>
        </div>
    </section>

    <!-- Features Simplified Section -->
    <section id="features" class="py-24 bg-white border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header & Illustration Split -->
            <div class="flex flex-col md:flex-row items-center justify-between mb-16 gap-12">
                <div class="reveal-left text-left md:w-1/2">
                    <h2 class="text-4xl md:text-6xl font-bold mb-6 tracking-tight text-gray-900 leading-tight">
                        Project Management <br />
                        <span class="text-gray-400">Lebih Simpel.</span>
                    </h2>
                    <p class="text-xl text-gray-600 mb-8 font-medium leading-relaxed">
                        Sederhanakan alur kerja Anda dengan tools project management yang intuitif dan kolaboratif.
                    </p>
                    <a href="#"
                        class="text-blue-600 font-semibold hover:underline inline-flex items-center gap-1 group">
                        Jelajahi semua fitur
                        <span class="group-hover:translate-x-1 transition-transform">→</span>
                    </a>
                </div>
                <div class="reveal-right md:w-1/2 flex justify-center md:justify-end">
                    <!-- Abstract Illustration Placeholder matching the vibe -->
                    <div class="relative w-full max-w-md aspect-[4/3]">
                        <div class="absolute inset-0 bg-gray-100 rounded-full opacity-50 blur-3xl"></div>
                        <div class="relative z-10 grid grid-cols-2 gap-4 p-8">
                            <div
                                class="bg-white p-4 rounded-2xl shadow-lg border border-gray-100 transform rotate-[-6deg] hover:rotate-0 transition-transform duration-500">
                                <div class="w-8 h-8 bg-orange-100 rounded-full mb-3"></div>
                                <div class="h-2 bg-gray-100 rounded w-16 mb-2"></div>
                                <div class="h-2 bg-gray-100 rounded w-10"></div>
                            </div>
                            <div
                                class="bg-white p-4 rounded-2xl shadow-lg border border-gray-100 transform translate-y-8 rotate-[6deg] hover:rotate-0 transition-transform duration-500">
                                <div class="w-8 h-8 bg-blue-100 rounded-full mb-3"></div>
                                <div class="h-2 bg-gray-100 rounded w-16 mb-2"></div>
                                <div class="h-2 bg-gray-100 rounded w-10"></div>
                            </div>
                            <div
                                class="bg-white p-4 rounded-2xl shadow-lg border border-gray-100 transform -translate-y-4 rotate-[3deg] hover:rotate-0 transition-transform duration-500 col-span-2 w-2/3 mx-auto">
                                <div class="flex items-center gap-2 mb-2">
                                    <div class="w-6 h-6 bg-green-100 rounded-full"></div>
                                    <div class="h-2 bg-gray-100 rounded w-20"></div>
                                </div>
                                <div class="h-20 bg-gray-50 rounded-lg"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Feature 1 -->
                <div
                    class="reveal delay-100 bg-white p-6 rounded-xl border border-gray-200 hover:bg-gray-50 transition-all duration-200 cursor-pointer group h-full flex flex-col">
                    <div class="text-gray-400 mb-4 text-2xl group-hover:text-black transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-clipboard-list">
                            <rect width="8" height="4" x="8" y="2" rx="1" ry="1" />
                            <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2" />
                            <path d="M12 11h4" />
                            <path d="M12 16h4" />
                            <path d="M8 11h.01" />
                            <path d="M8 16h.01" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold mb-2 flex items-center gap-2">
                        Task Management
                        <span class="opacity-0 group-hover:opacity-100 transition-opacity text-gray-400">→</span>
                    </h3>
                    <p class="text-gray-500 text-sm leading-relaxed">
                        Buat, tugaskan, dan lacak task dengan mudah.
                    </p>
                </div>

                <!-- Feature 2 -->
                <div
                    class="reveal delay-200 bg-white p-6 rounded-xl border border-gray-200 hover:bg-gray-50 transition-all duration-200 cursor-pointer group h-full flex flex-col">
                    <div class="text-gray-400 mb-4 text-2xl group-hover:text-black transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-users">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                            <circle cx="9" cy="7" r="4" />
                            <path d="M22 21v-2a4 4 0 0 0-3-3.87" />
                            <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold mb-2 flex items-center gap-2">
                        Team Collaboration
                        <span class="opacity-0 group-hover:opacity-100 transition-opacity text-gray-400">→</span>
                    </h3>
                    <p class="text-gray-500 text-sm leading-relaxed">
                        Tingkatkan kerjasama tim dengan update real-time.
                    </p>
                </div>

                <!-- Feature 3 -->
                <div
                    class="reveal delay-300 bg-white p-6 rounded-xl border border-gray-200 hover:bg-gray-50 transition-all duration-200 cursor-pointer group h-full flex flex-col">
                    <div class="text-gray-400 mb-4 text-2xl group-hover:text-black transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-bar-chart-3">
                            <path d="M3 3v18h18" />
                            <path d="M18 17V9" />
                            <path d="M13 17V5" />
                            <path d="M8 17v-3" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold mb-2 flex items-center gap-2">
                        Progress Tracking
                        <span class="opacity-0 group-hover:opacity-100 transition-opacity text-gray-400">→</span>
                    </h3>
                    <p class="text-gray-500 text-sm leading-relaxed">
                        Visualisasi progres project dengan board yang intuitif.
                    </p>
                </div>

                <!-- Feature 4 (Added to balance grid) -->
                <div
                    class="reveal delay-400 bg-white p-6 rounded-xl border border-gray-200 hover:bg-gray-50 transition-all duration-200 cursor-pointer group h-full flex flex-col">
                    <div class="text-gray-400 mb-4 text-2xl group-hover:text-black transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-zap">
                            <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold mb-2 flex items-center gap-2">
                        Cepat & Efisien
                        <span class="opacity-0 group-hover:opacity-100 transition-opacity text-gray-400">→</span>
                    </h3>
                    <p class="text-gray-500 text-sm leading-relaxed">
                        Dioptimalkan untuk kecepatan dan performa.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Feature Grid (Bento Style) -->
    <section id="how-it-works" class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="reveal text-4xl md:text-5xl font-bold mb-16 max-w-3xl">
                Jutaan orang menggunakan AturKerja setiap hari
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Card 1: Project Board -->
                <div
                    class="reveal delay-100 bg-gray-50 rounded-2xl p-8 border border-gray-100 hover:border-gray-300 transition-colors duration-300 group flex flex-col">
                    <div class="mb-6 text-gray-400 group-hover:text-black transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect width="18" height="18" x="3" y="3" rx="2" />
                            <path d="M8 7v7" />
                            <path d="M12 7v4" />
                            <path d="M16 7v9" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold mb-3">Project Board</h3>
                    <p class="text-gray-600 mb-8 leading-relaxed">Visualisasi pekerjaan dengan Kanban board. Pindahkan ticket dari
                        Backlog ke Done dengan mudah.</p>
                    <div
                        class="bg-white rounded-lg shadow-sm p-3 border border-gray-200 group-hover:shadow-md transition-shadow flex-1 relative overflow-hidden">
                        <!-- Skeleton (Default) -->
                        <div
                            class="absolute inset-3 flex gap-2 transition-opacity duration-300 opacity-100 group-hover:opacity-0">
                            <div class="flex-1 bg-gray-50 rounded border border-gray-200 p-2 flex flex-col gap-2">
                                <div class="w-full h-1.5 bg-gray-300 rounded"></div>
                                <div class="w-full h-16 bg-white border border-gray-200 rounded shadow-sm p-2">
                                    <div class="w-full h-1.5 bg-gray-800 rounded mb-2"></div>
                                    <div class="w-2/3 h-1.5 bg-gray-200 rounded"></div>
                                </div>
                                <div class="w-full h-12 bg-white border border-gray-200 rounded shadow-sm p-2">
                                    <div class="w-3/4 h-1.5 bg-gray-800 rounded"></div>
                                </div>
                            </div>
                            <div class="flex-1 bg-gray-50 rounded border border-gray-200 p-2 flex flex-col gap-2">
                                <div class="w-full h-1.5 bg-gray-300 rounded"></div>
                                <div class="w-full h-20 bg-white border border-gray-200 rounded shadow-sm p-2">
                                    <div class="w-full h-1.5 bg-gray-800 rounded mb-2"></div>
                                    <div class="w-full h-1.5 bg-gray-200 rounded mb-1"></div>
                                    <div class="w-1/2 h-1.5 bg-gray-200 rounded"></div>
                                </div>
                            </div>
                            <div class="flex-1 bg-gray-50 rounded border border-gray-200 p-2 flex flex-col gap-2">
                                <div class="w-full h-1.5 bg-gray-300 rounded"></div>
                                <div class="w-full h-14 bg-white border border-gray-200 rounded shadow-sm p-2">
                                    <div class="w-5/6 h-1.5 bg-gray-800 rounded"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Real Content (Hover) -->
                        <div
                            class="absolute inset-3 flex gap-2 transition-opacity duration-300 opacity-0 group-hover:opacity-100">
                            <!-- Backlog -->
                            <div class="flex-1 bg-gray-50 rounded border border-gray-200 p-2 flex flex-col gap-2">
                                <div class="flex justify-between items-center px-1">
                                    <span class="text-[8px] font-bold text-gray-500 uppercase">Backlog</span>
                                    <span class="text-[8px] bg-gray-200 px-1 rounded text-gray-600">2</span>
                                </div>
                                <div
                                    class="bg-white border border-gray-200 rounded shadow-sm p-2 cursor-pointer hover:border-gray-300">
                                    <div class="text-[9px] font-bold text-gray-800 leading-tight mb-1">Redis Caching
                                    </div>
                                    <div class="flex items-center gap-1">
                                        <span
                                            class="text-[8px] px-1 bg-purple-100 text-purple-700 rounded">Backend</span>
                                    </div>
                                </div>
                                <div
                                    class="bg-white border border-gray-200 rounded shadow-sm p-2 cursor-pointer hover:border-gray-300">
                                    <div class="text-[9px] font-bold text-gray-800 leading-tight">Fix Nav Bug</div>
                                </div>
                            </div>
                            <!-- To Do -->
                            <div class="flex-1 bg-gray-50 rounded border border-gray-200 p-2 flex flex-col gap-2">
                                <div class="flex justify-between items-center px-1">
                                    <span class="text-[8px] font-bold text-orange-600 uppercase">To Do</span>
                                    <span class="text-[8px] bg-orange-100 px-1 rounded text-orange-600">1</span>
                                </div>
                                <div
                                    class="bg-white border border-gray-200 rounded shadow-sm p-2 cursor-pointer hover:border-gray-300">
                                    <div class="text-[9px] font-bold text-gray-800 leading-tight mb-1">Design System
                                    </div>
                                    <p class="text-[8px] text-gray-400 leading-tight mb-1">Create base components.</p>
                                    <div class="flex items-center gap-1">
                                        <span class="text-[8px] px-1 bg-pink-100 text-pink-700 rounded">Design</span>
                                    </div>
                                </div>
                            </div>
                            <!-- In Progress -->
                            <div class="flex-1 bg-gray-50 rounded border border-gray-200 p-2 flex flex-col gap-2">
                                <div class="flex justify-between items-center px-1">
                                    <span class="text-[8px] font-bold text-blue-600 uppercase">Doing</span>
                                    <span class="text-[8px] bg-blue-100 px-1 rounded text-blue-600">1</span>
                                </div>
                                <div
                                    class="bg-white border border-gray-200 rounded shadow-sm p-2 cursor-pointer hover:border-gray-300">
                                    <div class="text-[9px] font-bold text-gray-800 leading-tight mb-1">Landing Page
                                    </div>
                                    <div class="flex items-center gap-1">
                                        <span class="text-[8px] px-1 bg-blue-100 text-blue-700 rounded">Frontend</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Spacer to maintain height -->
                        <div class="invisible flex gap-2 h-full min-h-[140px]">
                            <div class="flex-1 p-2 flex flex-col gap-2">
                                <div class="w-full h-1.5"></div>
                                <div class="w-full h-20"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Timeline -->
                <div
                    class="reveal delay-200 bg-gray-50 rounded-2xl p-8 border border-gray-100 hover:border-gray-300 transition-colors duration-300 group flex flex-col">
                    <div class="mb-6 text-gray-400 group-hover:text-black transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect width="18" height="18" x="3" y="4" rx="2" ry="2" />
                            <line x1="16" x2="16" y1="2" y2="6" />
                            <line x1="8" x2="8" y1="2" y2="6" />
                            <line x1="3" x2="21" y1="10" y2="10" />
                            <path d="M8 14h.01" />
                            <path d="M12 14h.01" />
                            <path d="M16 14h.01" />
                            <path d="M8 18h.01" />
                            <path d="M12 18h.01" />
                            <path d="M16 18h.01" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold mb-3">Timeline View</h3>
                    <p class="text-gray-600 mb-8 leading-relaxed">Rencanakan project di timeline. Lihat deadline,
                        dependensi, dan progres dalam sekali pandang.</p>
                    <div
                        class="bg-white rounded-lg shadow-sm p-4 border border-gray-200 group-hover:shadow-md transition-shadow overflow-hidden flex-1 flex flex-col justify-center relative">

                        <!-- Skeleton (Default) -->
                        <div
                            class="absolute inset-4 flex flex-col justify-center space-y-4 transition-opacity duration-300 opacity-100 group-hover:opacity-0">
                            <div class="flex items-center gap-3">
                                <div class="w-1/4 h-2 bg-gray-200 rounded"></div>
                                <div
                                    class="flex-1 h-6 bg-gray-100 rounded relative overflow-hidden border border-gray-200">
                                    <div class="absolute top-0 left-0 h-full w-3/4 bg-gray-400 rounded-l"></div>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="w-1/4 h-2 bg-gray-200 rounded"></div>
                                <div
                                    class="flex-1 h-6 bg-gray-100 rounded relative overflow-hidden border border-gray-200">
                                    <div class="absolute top-0 left-1/4 h-full w-1/2 bg-gray-300 rounded"></div>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="w-1/4 h-2 bg-gray-200 rounded"></div>
                                <div
                                    class="flex-1 h-6 bg-gray-100 rounded relative overflow-hidden border border-gray-200">
                                    <div class="absolute top-0 left-1/2 h-full w-1/4 bg-gray-300 rounded"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Real Content (Hover) -->
                        <div
                            class="absolute inset-4 flex flex-col justify-center space-y-4 transition-opacity duration-300 opacity-0 group-hover:opacity-100">
                            <div class="flex items-center gap-3">
                                <div class="w-1/4 text-[10px] font-bold text-gray-700 truncate">Q1 Goals</div>
                                <div
                                    class="flex-1 h-6 bg-red-50 rounded relative overflow-hidden border border-red-100 group/item cursor-pointer">
                                    <div
                                        class="absolute top-0 left-0 h-full w-3/4 bg-red-500 rounded-l flex items-center px-2">
                                        <span class="text-[8px] text-white font-medium truncate">Planning Phase</span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="w-1/4 text-[10px] font-bold text-gray-700 truncate">Development</div>
                                <div
                                    class="flex-1 h-6 bg-green-50 rounded relative overflow-hidden border border-green-100 group/item cursor-pointer">
                                    <div
                                        class="absolute top-0 left-1/4 h-full w-1/2 bg-green-500 rounded flex items-center px-2">
                                        <span class="text-[8px] text-white font-medium truncate">Core Features</span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="w-1/4 text-[10px] font-bold text-gray-700 truncate">Launch</div>
                                <div
                                    class="flex-1 h-6 bg-blue-50 rounded relative overflow-hidden border border-blue-100 group/item cursor-pointer">
                                    <div
                                        class="absolute top-0 left-1/2 h-full w-1/4 bg-blue-500 rounded flex items-center px-2">
                                        <span class="text-[8px] text-white font-medium truncate">v1.0</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Spacer -->
                        <div class="invisible space-y-4">
                            <div class="flex items-center gap-3">
                                <div class="w-1/4 h-2"></div>
                                <div class="flex-1 h-6"></div>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="w-1/4 h-2"></div>
                                <div class="flex-1 h-6"></div>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="w-1/4 h-2"></div>
                                <div class="flex-1 h-6"></div>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Analytics Card -->
                <div
                    class="reveal delay-300 bg-gray-50 rounded-2xl p-8 border border-gray-100 hover:border-gray-300 transition-colors duration-300 group flex flex-col">
                    <div class="mb-6 text-gray-400 group-hover:text-black transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6" />
                            <path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18" />
                            <path d="M4 22h16" />
                            <path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20.24 7 22" />
                            <path d="M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20.24 17 22" />
                            <path d="M18 2H6v7a6 6 0 0 0 12 0V2Z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold mb-3">Leaderboard</h3>
                    <p class="text-gray-600 mb-8 leading-relaxed">Gamifikasi produktivitas dengan pelacakan kontribusi.</p>

                    <!-- Leaderboard Mockup -->
                    <div
                        class="bg-white rounded-lg shadow-sm p-4 border border-gray-100 group-hover:shadow-md transition-shadow flex-1 flex flex-col gap-3 relative overflow-hidden">

                        <!-- Skeleton (Default) -->
                        <div
                            class="absolute inset-4 flex flex-col gap-3 transition-opacity duration-300 opacity-100 group-hover:opacity-0">
                            <!-- Rank 1 Placeholder -->
                            <div class="bg-gray-50 p-2 rounded-md border border-gray-100 flex items-center gap-3">
                                <div class="w-5 h-5 bg-gray-200 rounded-full flex items-center justify-center">
                                    <div class="w-1.5 h-1.5 bg-gray-400 rounded-full"></div>
                                </div>
                                <div class="w-6 h-6 rounded-full bg-gray-300"></div>
                                <div class="flex-1 min-w-0">
                                    <div class="h-2 bg-gray-800 rounded w-16 mb-1"></div>
                                    <div class="h-1.5 bg-gray-300 rounded w-8"></div>
                                </div>
                                <div class="h-2 bg-gray-200 rounded w-6"></div>
                            </div>

                            <!-- Rank 2 Placeholder -->
                            <div
                                class="bg-gray-50 p-2 rounded-md border border-gray-100 flex items-center gap-3 opacity-80">
                                <div class="w-5 h-5 bg-gray-100 rounded-full flex items-center justify-center">
                                    <div class="w-1.5 h-1.5 bg-gray-300 rounded-full"></div>
                                </div>
                                <div class="w-6 h-6 rounded-full bg-gray-200"></div>
                                <div class="flex-1 min-w-0">
                                    <div class="h-2 bg-gray-600 rounded w-14 mb-1"></div>
                                    <div class="h-1.5 bg-gray-200 rounded w-6"></div>
                                </div>
                                <div class="h-2 bg-gray-200 rounded w-6"></div>
                            </div>

                            <!-- Rank 3 Placeholder -->
                            <div
                                class="bg-gray-50 p-2 rounded-md border border-gray-100 flex items-center gap-3 opacity-60">
                                <div class="w-5 h-5 bg-gray-100 rounded-full flex items-center justify-center">
                                    <div class="w-1.5 h-1.5 bg-gray-300 rounded-full"></div>
                                </div>
                                <div class="w-6 h-6 rounded-full bg-gray-200"></div>
                                <div class="flex-1 min-w-0">
                                    <div class="h-2 bg-gray-500 rounded w-12 mb-1"></div>
                                    <div class="h-1.5 bg-gray-200 rounded w-6"></div>
                                </div>
                                <div class="h-2 bg-gray-200 rounded w-6"></div>
                            </div>
                        </div>

                        <!-- Real Content (Hover) -->
                        <div
                            class="absolute inset-4 flex flex-col gap-3 transition-opacity duration-300 opacity-0 group-hover:opacity-100">
                            <!-- Rank 1 -->
                            <div
                                class="bg-white p-2 rounded-md border border-yellow-100 flex items-center gap-3 shadow-sm ring-1 ring-yellow-50">
                                <div
                                    class="w-5 h-5 bg-yellow-100 rounded-full flex items-center justify-center text-[10px] font-bold text-yellow-700">
                                    1</div>
                                <div
                                    class="w-6 h-6 rounded-full bg-gray-100 flex items-center justify-center text-[10px] font-bold text-gray-600 border border-gray-200">
                                    JD</div>
                                <div class="flex-1 min-w-0">
                                    <div class="text-[10px] font-bold text-gray-900 truncate">John Doe</div>
                                    <div class="text-[8px] text-gray-500 truncate">1,240 pts</div>
                                </div>
                                <div class="text-[10px] font-bold text-green-600">▲</div>
                            </div>

                            <!-- Rank 2 -->
                            <div
                                class="bg-white p-2 rounded-md border border-gray-100 flex items-center gap-3 shadow-sm hover:border-gray-200 transition-colors">
                                <div
                                    class="w-5 h-5 bg-gray-100 rounded-full flex items-center justify-center text-[10px] font-bold text-gray-600">
                                    2</div>
                                <div
                                    class="w-6 h-6 rounded-full bg-gray-100 flex items-center justify-center text-[10px] font-bold text-gray-600 border border-gray-200">
                                    AS</div>
                                <div class="flex-1 min-w-0">
                                    <div class="text-[10px] font-bold text-gray-900 truncate">Alice Smith</div>
                                    <div class="text-[8px] text-gray-500 truncate">980 pts</div>
                                </div>
                                <div class="text-[10px] font-bold text-gray-400">-</div>
                            </div>

                            <!-- Rank 3 -->
                            <div
                                class="bg-white p-2 rounded-md border border-gray-100 flex items-center gap-3 shadow-sm hover:border-gray-200 transition-colors">
                                <div
                                    class="w-5 h-5 bg-orange-50 rounded-full flex items-center justify-center text-[10px] font-bold text-orange-700">
                                    3</div>
                                <div
                                    class="w-6 h-6 rounded-full bg-gray-100 flex items-center justify-center text-[10px] font-bold text-gray-600 border border-gray-200">
                                    MK</div>
                                <div class="flex-1 min-w-0">
                                    <div class="text-[10px] font-bold text-gray-900 truncate">Mike K.</div>
                                    <div class="text-[8px] text-gray-500 truncate">850 pts</div>
                                </div>
                                <div class="text-[10px] font-bold text-green-600">▲</div>
                            </div>
                        </div>

                        <!-- Spacer -->
                        <div class="invisible flex flex-col gap-3">
                            <div class="p-2 flex items-center gap-3">
                                <div class="w-5 h-5"></div>
                                <div class="w-6 h-6"></div>
                                <div class="flex-1">
                                    <div class="h-2 w-16 mb-1"></div>
                                    <div class="h-1.5 w-8"></div>
                                </div>
                                <div class="h-2 w-6"></div>
                            </div>
                            <div class="p-2 flex items-center gap-3">
                                <div class="w-5 h-5"></div>
                                <div class="w-6 h-6"></div>
                                <div class="flex-1">
                                    <div class="h-2 w-14 mb-1"></div>
                                    <div class="h-1.5 w-6"></div>
                                </div>
                                <div class="h-2 w-6"></div>
                            </div>
                            <div class="p-2 flex items-center gap-3">
                                <div class="w-5 h-5"></div>
                                <div class="w-6 h-6"></div>
                                <div class="flex-1">
                                    <div class="h-2 w-12 mb-1"></div>
                                    <div class="h-1.5 w-6"></div>
                                </div>
                                <div class="h-2 w-6"></div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Big Feature Section -->
    <section id="about" class="py-24 bg-gray-50 border-t border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row items-center gap-16">
                <div class="reveal-left flex-1">
                    <h2 class="text-4xl md:text-5xl font-bold mb-6">Building block yang powerful</h2>
                    <p class="text-xl text-gray-600 mb-8">Sesuaikan AturKerja untuk bekerja seperti cara Anda. Drag and drop untuk
                        membuat dashboard, website, atau sistem yang Anda butuhkan.</p>
                </div>
                <div class="reveal-right flex-1 w-full">
                    <div
                        class="bg-white rounded-xl shadow-xl border border-gray-200 p-8 rotate-2 hover:rotate-0 transition-transform duration-500">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-orange-50 p-4 rounded-lg border border-orange-100">
                                <div class="font-bold text-orange-800 mb-2">To Do</div>
                                <div class="space-y-2">
                                    <div class="bg-white p-2 rounded border border-orange-100 shadow-sm text-sm">
                                        Riset kompetitor</div>
                                    <div class="bg-white p-2 rounded border border-orange-100 shadow-sm text-sm">Buat
                                        draft copy</div>
                                </div>
                            </div>
                            <div class="bg-blue-50 p-4 rounded-lg border border-blue-100">
                                <div class="font-bold text-blue-800 mb-2">Doing</div>
                                <div class="space-y-2">
                                    <div class="bg-white p-2 rounded border border-blue-100 shadow-sm text-sm">Design
                                        mockup</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-100 pt-20 pb-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16">
                <div>
                    <div class="flex items-center gap-2 mb-6">
                        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="currentColor">
                            <path
                                d="M4.459 4.208c.746.606 1.026.56 2.428.466l13.215-.793c.28 0 .047.28.047.653v15.03c0 .373.093.606-.56.606-.513 0-2.983-.233-4.616-.42-2.193-.233-4.29-.373-5.316-.373-.793 0-2.566.233-3.64.513-.933.233-1.4.326-1.4.047 0-.187 0-6.113 0-11.807 0-2.706-.326-3.266-1.26-3.873L2.26 3.462c-.28-.186-.233-.42.14-.42h2.053zM7.893 6.26v11.853c1.306 0 3.36.233 5.6.466 1.446.14 3.266.326 3.733.326.28 0 .326-.093.326-.42V5.28c0-.28-.047-.373-.326-.373-.28 0-1.96.187-4.2.373-2.1.187-4.153.373-5.133.373z" />
                        </svg>
                        <span class="font-semibold">AturKerja</span>
                    </div>
                    <p class="text-gray-500 text-sm mb-4">Platform project management oleh DewaKoding</p>
                    <p class="text-gray-500 text-sm">Kelola project tim Anda dengan mudah. Lacak progres, berkolaborasi,
                        dan selesaikan tepat waktu.</p>
                </div>

                <div>
                    <h4 class="font-bold mb-4">Fitur</h4>
                    <ul class="space-y-2 text-gray-500 text-sm">
                        <li><a href="#features" class="hover:text-blue-600 hover:underline">Kanban Board</a></li>
                        <li><a href="#features" class="hover:text-blue-600 hover:underline">Timeline View</a></li>
                        <li><a href="#features" class="hover:text-blue-600 hover:underline">Team Collaboration</a></li>
                        <li><a href="#features" class="hover:text-blue-600 hover:underline">Leaderboard</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-bold mb-4">Kontak</h4>
                    <ul class="space-y-2 text-gray-500 text-sm">
                        <li>Email: dev.dewakoding@gmail.com</li>
                        <li>Telepon: +62 858-1346-5023</li>
                        <li>Lokasi: Bekasi - Purwokerto - Remote</li>
                    </ul>
                    <div class="flex gap-4 mt-4">
                        <a href="https://instagram.com/dewakoding" target="_blank"
                            class="text-gray-400 hover:text-black">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <rect width="20" height="20" x="2" y="2" rx="5" ry="5" />
                                <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z" />
                                <line x1="17.5" x2="17.51" y1="6.5" y2="6.5" />
                            </svg>
                        </a>
                        <a href="https://www.linkedin.com/company/dewakoding-indonesia" target="_blank"
                            class="text-gray-400 hover:text-black">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path
                                    d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z" />
                                <rect width="4" height="12" x="2" y="9" />
                                <circle cx="4" cy="4" r="2" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
            <div
                class="border-t border-gray-100 pt-8 flex flex-col md:flex-row justify-between items-center gap-4 text-sm text-gray-500">
                <div>&copy; 2025 AturKerja by DewaKoding. Hak cipta dilindungi.</div>
                <div class="flex gap-6">
                    <a href="#" class="hover:text-black">Privasi</a>
                    <a href="#" class="hover:text-black">Ketentuan</a>
                    <a href="#" class="hover:text-black">Pengaturan Cookie</a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        // Reveal on scroll animation
        document.addEventListener('DOMContentLoaded', function () {
            const reveals = document.querySelectorAll('.reveal, .reveal-left, .reveal-right, .reveal-scale, .reveal-fade');

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('active');
                    }
                });
            }, {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            });

            reveals.forEach(reveal => {
                observer.observe(reveal);
            });
        });
    </script>
</body>

</html>