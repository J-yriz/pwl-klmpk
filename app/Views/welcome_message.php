<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Welcome to CodeIgniter 4!</title>
    <meta name="description" content="The small framework with powerful features">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/png" href="/favicon.ico">

    <link rel="stylesheet" href="<?= base_url('css/output.css?v=' . (is_file(FCPATH . 'css/output.css') ? (string) filemtime(FCPATH . 'css/output.css') : (string) time())) ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-950 text-gray-100 min-h-screen antialiased">

    <!-- HEADER / NAVBAR -->
    <nav class="sticky top-0 z-50 border-b border-white/10 bg-gray-950/80 backdrop-blur-xl">
        <div class="max-w-6xl mx-auto px-6 py-4 flex items-center justify-between">
            <!-- Logo -->
            <a href="https://codeigniter.com" target="_blank" class="flex items-center gap-3 group">
                <div class="w-10 h-10 rounded-xl bg-linear-to-br from-orange-500 to-red-600 flex items-center justify-center shadow-lg shadow-orange-500/20 group-hover:shadow-orange-500/40 transition-shadow duration-300">
                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                    </svg>
                </div>
                <span class="text-lg font-semibold text-white hidden sm:block">CodeIgniter</span>
            </a>

            <!-- Desktop Menu -->
            <div class="hidden md:flex items-center gap-1">
                <a href="#" class="px-4 py-2 rounded-lg text-sm font-medium text-gray-300 hover:text-white hover:bg-white/10 transition-all duration-200">Home</a>
                <a href="https://codeigniter.com/user_guide/" target="_blank" class="px-4 py-2 rounded-lg text-sm font-medium text-gray-300 hover:text-white hover:bg-white/10 transition-all duration-200">Docs</a>
                <a href="https://forum.codeigniter.com/" target="_blank" class="px-4 py-2 rounded-lg text-sm font-medium text-gray-300 hover:text-white hover:bg-white/10 transition-all duration-200">Community</a>
                <a href="https://codeigniter.com/contribute" target="_blank" class="px-4 py-2 ml-2 rounded-lg text-sm font-medium text-white bg-linear-to-r from-orange-500 to-red-600 hover:from-orange-400 hover:to-red-500 shadow-lg shadow-orange-500/20 hover:shadow-orange-500/40 transition-all duration-300">Contribute</a>
            </div>

            <!-- Mobile Menu Toggle -->
            <button id="menuToggle" class="md:hidden p-2 rounded-lg text-gray-400 hover:text-white hover:bg-white/10 transition-all duration-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
        </div>

        <!-- Mobile Menu -->
        <div id="mobileMenu" class="hidden md:hidden border-t border-white/10 bg-gray-900/95 backdrop-blur-xl">
            <div class="px-6 py-4 flex flex-col gap-2">
                <a href="#" class="px-4 py-3 rounded-lg text-sm font-medium text-gray-300 hover:text-white hover:bg-white/10 transition-all duration-200">Home</a>
                <a href="https://codeigniter.com/user_guide/" target="_blank" class="px-4 py-3 rounded-lg text-sm font-medium text-gray-300 hover:text-white hover:bg-white/10 transition-all duration-200">Docs</a>
                <a href="https://forum.codeigniter.com/" target="_blank" class="px-4 py-3 rounded-lg text-sm font-medium text-gray-300 hover:text-white hover:bg-white/10 transition-all duration-200">Community</a>
                <a href="https://codeigniter.com/contribute" target="_blank" class="px-4 py-3 rounded-lg text-sm font-medium text-white bg-linear-to-r from-orange-500 to-red-600 text-center hover:from-orange-400 hover:to-red-500 transition-all duration-300">Contribute</a>
            </div>
        </div>
    </nav>

    <!-- HERO SECTION -->
    <section class="relative overflow-hidden">
        <!-- Background glow effects -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute top-1/4 left-1/2 -translate-x-1/2 w-150 h-150 bg-orange-500/10 rounded-full blur-[128px]"></div>
            <div class="absolute top-1/2 left-1/4 w-100 h-100 bg-red-500/8 rounded-full blur-[96px]"></div>
        </div>

        <div class="relative max-w-6xl mx-auto px-6 pt-24 pb-20 text-center">
            <!-- Badge -->
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-orange-500/10 border border-orange-500/20 text-orange-400 text-sm font-medium mb-8">
                <span class="w-2 h-2 rounded-full bg-orange-400 animate-pulse"></span>
                Version <?= CodeIgniter\CodeIgniter::CI_VERSION ?>
            </div>

            <h1 class="text-5xl sm:text-6xl lg:text-7xl font-bold tracking-tight mb-6">
                <span class="text-white">Welcome to</span><br>
                <span class="bg-linear-to-r from-orange-400 via-red-500 to-pink-500 bg-clip-text text-transparent">CodeIgniter</span>
            </h1>

            <p class="text-lg sm:text-xl text-gray-400 max-w-2xl mx-auto mb-12 leading-relaxed">
                The small framework with powerful features. Build fast, lightweight, and secure PHP applications with ease.
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="https://codeigniter.com/user_guide/" target="_blank" class="w-full sm:w-auto px-8 py-3.5 rounded-xl text-white font-semibold bg-linear-to-r from-orange-500 to-red-600 hover:from-orange-400 hover:to-red-500 shadow-lg shadow-orange-500/25 hover:shadow-orange-500/40 transition-all duration-300 hover:-translate-y-0.5">
                    Get Started
                </a>
                <a href="https://github.com/codeigniter4/CodeIgniter4" target="_blank" class="w-full sm:w-auto px-8 py-3.5 rounded-xl text-gray-300 font-semibold bg-white/5 border border-white/10 hover:bg-white/10 hover:border-white/20 hover:text-white transition-all duration-300 hover:-translate-y-0.5">
                    View on GitHub
                </a>
            </div>
        </div>
    </section>

    <!-- ABOUT SECTION -->
    <section class="border-t border-white/5">
        <div class="max-w-6xl mx-auto px-6 py-20">
            <div class="text-center mb-16">
                <h2 class="text-3xl sm:text-4xl font-bold text-white mb-4">About This Page</h2>
                <p class="text-gray-400 max-w-xl mx-auto">This page is generated dynamically by CodeIgniter. Here's where to find the source files.</p>
            </div>

            <div class="grid sm:grid-cols-2 gap-6 max-w-3xl mx-auto">
                <!-- View File -->
                <div class="group p-6 rounded-2xl bg-white/3 border border-white/10 hover:border-orange-500/30 hover:bg-white/5 transition-all duration-300">
                    <div class="w-12 h-12 rounded-xl bg-orange-500/10 flex items-center justify-center mb-4 group-hover:bg-orange-500/20 transition-colors duration-300">
                        <svg class="w-6 h-6 text-orange-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-white font-semibold mb-2">View File</h3>
                    <code class="text-sm text-orange-400/80 bg-orange-500/10 px-3 py-1.5 rounded-lg inline-block">app/Views/welcome_message.php</code>
                </div>

                <!-- Controller File -->
                <div class="group p-6 rounded-2xl bg-white/3 border border-white/10 hover:border-orange-500/30 hover:bg-white/5 transition-all duration-300">
                    <div class="w-12 h-12 rounded-xl bg-red-500/10 flex items-center justify-center mb-4 group-hover:bg-red-500/20 transition-colors duration-300">
                        <svg class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 6.75L22.5 12l-5.25 5.25m-10.5 0L1.5 12l5.25-5.25m7.5-3l-4.5 16.5"/>
                        </svg>
                    </div>
                    <h3 class="text-white font-semibold mb-2">Controller</h3>
                    <code class="text-sm text-red-400/80 bg-red-500/10 px-3 py-1.5 rounded-lg inline-block">app/Controllers/Home.php</code>
                </div>
            </div>
        </div>
    </section>

    <!-- GO FURTHER SECTION -->
    <section class="border-t border-white/5 bg-linear-to-b from-gray-950 to-gray-900">
        <div class="max-w-6xl mx-auto px-6 py-20">
            <div class="text-center mb-16">
                <h2 class="text-3xl sm:text-4xl font-bold text-white mb-4">Go Further</h2>
                <p class="text-gray-400 max-w-xl mx-auto">Explore the resources available to help you build amazing applications.</p>
            </div>

            <div class="grid sm:grid-cols-3 gap-6">
                <!-- Learn -->
                <div class="group relative p-8 rounded-2xl bg-white/3 border border-white/10 hover:border-orange-500/30 transition-all duration-300 hover:-translate-y-1">
                    <div class="absolute inset-0 rounded-2xl bg-linear-to-b from-orange-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="relative">
                        <div class="w-14 h-14 rounded-2xl bg-linear-to-br from-orange-500/20 to-orange-600/10 flex items-center justify-center mb-6 group-hover:from-orange-500/30 group-hover:to-orange-600/20 transition-all duration-300">
                            <svg class="w-7 h-7 text-orange-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-white mb-3">Learn</h3>
                        <p class="text-gray-400 text-sm leading-relaxed mb-6">The User Guide contains an introduction, tutorial, and reference documentation for all framework components.</p>
                        <a href="https://codeigniter.com/user_guide/" target="_blank" class="inline-flex items-center gap-2 text-orange-400 text-sm font-medium hover:text-orange-300 transition-colors duration-200">
                            Read the Docs
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Discuss -->
                <div class="group relative p-8 rounded-2xl bg-white/3 border border-white/10 hover:border-pink-500/30 transition-all duration-300 hover:-translate-y-1">
                    <div class="absolute inset-0 rounded-2xl bg-linear-to-b from-pink-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="relative">
                        <div class="w-14 h-14 rounded-2xl bg-linear-to-br from-pink-500/20 to-pink-600/10 flex items-center justify-center mb-6 group-hover:from-pink-500/30 group-hover:to-pink-600/20 transition-all duration-300">
                            <svg class="w-7 h-7 text-pink-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 8.511c.884.284 1.5 1.128 1.5 2.097v4.286c0 1.136-.847 2.1-1.98 2.193-.34.027-.68.052-1.02.072v3.091l-3-3c-1.354 0-2.694-.055-4.02-.163a2.115 2.115 0 01-.825-.242m9.345-8.334a2.126 2.126 0 00-.476-.095 48.64 48.64 0 00-8.048 0c-1.131.094-1.976 1.057-1.976 2.192v4.286c0 .837.46 1.58 1.155 1.951m9.345-8.334V6.637c0-1.621-1.152-3.026-2.76-3.235A48.455 48.455 0 0011.25 3c-2.115 0-4.198.137-6.24.402-1.608.209-2.76 1.614-2.76 3.235v6.226c0 1.621 1.152 3.026 2.76 3.235.577.075 1.157.14 1.74.194V21l4.155-4.155"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-white mb-3">Discuss</h3>
                        <p class="text-gray-400 text-sm leading-relaxed mb-6">Join the community! Exchange ideas on the forums or chat with fellow developers on Slack.</p>
                        <a href="https://forum.codeigniter.com/" target="_blank" class="inline-flex items-center gap-2 text-pink-400 text-sm font-medium hover:text-pink-300 transition-colors duration-200">
                            Join the Forum
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Contribute -->
                <div class="group relative p-8 rounded-2xl bg-white/3 border border-white/10 hover:border-violet-500/30 transition-all duration-300 hover:-translate-y-1">
                    <div class="absolute inset-0 rounded-2xl bg-linear-to-b from-violet-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="relative">
                        <div class="w-14 h-14 rounded-2xl bg-linear-to-br from-violet-500/20 to-violet-600/10 flex items-center justify-center mb-6 group-hover:from-violet-500/30 group-hover:to-violet-600/20 transition-all duration-300">
                            <svg class="w-7 h-7 text-violet-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 3.104v5.714a2.25 2.25 0 01-.659 1.591L5 14.5M9.75 3.104c-.251.023-.501.05-.75.082m.75-.082a24.301 24.301 0 014.5 0m0 0v5.714c0 .597.237 1.17.659 1.591L19.8 15.3M14.25 3.104c.251.023.501.05.75.082M19.8 15.3l-1.57.393A9.065 9.065 0 0112 15a9.065 9.065 0 00-6.23.693L5 14.5m14.8.8l1.402 1.402c1.232 1.232.65 3.318-1.067 3.611A48.309 48.309 0 0112 21c-2.773 0-5.491-.235-8.135-.687-1.718-.293-2.3-2.379-1.067-3.61L5 14.5"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-white mb-3">Contribute</h3>
                        <p class="text-gray-400 text-sm leading-relaxed mb-6">CodeIgniter is community-driven and accepts contributions of code and documentation. Join us!</p>
                        <a href="https://codeigniter.com/contribute" target="_blank" class="inline-flex items-center gap-2 text-violet-400 text-sm font-medium hover:text-violet-300 transition-colors duration-200">
                            Start Contributing
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="border-t border-white/5">
        <div class="max-w-6xl mx-auto px-6 py-10 text-center">
            <div class="mb-6">
                <p class="text-gray-400 text-sm">
                    Page rendered in <span class="text-orange-400 font-medium">{elapsed_time}</span> seconds using <span class="text-orange-400 font-medium">{memory_usage}</span> MB of memory.
                </p>
                <p class="text-gray-500 text-sm mt-1">
                    Environment: <span class="text-gray-400 font-medium"><?= ENVIRONMENT ?></span>
                </p>
            </div>
            <div class="pt-6 border-t border-white/5">
                <p class="text-gray-600 text-xs">
                    &copy; <?= date('Y') ?> CodeIgniter Foundation. CodeIgniter is an open source project released under the MIT open source licence.
                </p>
            </div>
        </div>
    </footer>

    <!-- SCRIPTS -->
    <script>
        document.getElementById('menuToggle').addEventListener('click', function () {
            const menu = document.getElementById('mobileMenu');
            menu.classList.toggle('hidden');
        });
    </script>

</body>
</html>