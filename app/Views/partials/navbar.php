<?php
$currentPath = (string) service('uri')->getPath();
$currentPath = '/' . trim($currentPath, '/');
$currentPath = $currentPath === '/' ? $currentPath : rtrim($currentPath, '/');
$currentPath = $currentPath === '' ? '/' : $currentPath;

$publicLinks = [
    ['label' => 'Beranda', 'href' => '/'],
];

$userLinks = [
    ['label' => 'Bookmark', 'href' => '/bookmark'],
    ['label' => 'Dashboard', 'href' => '/kreator/dashboard'],
];

$navCategories = is_array($nav_categories ?? null) ? $nav_categories : [];
$isCategoryPath = str_starts_with($currentPath, '/kategori');
?>
<input id="mobile-sidebar-toggle" type="checkbox" class="peer sr-only md:hidden" />

<header class="sticky top-0 z-30 border-b-4 border-neo-black bg-neo-paper/95 backdrop-blur-sm">

    <div class="mx-auto flex w-full max-w-7xl items-center justify-between px-4 py-4 md:px-6">
        <a href="<?= site_url('') ?>" class="inline-flex items-center gap-2 border-3 border-neo-black bg-neo-cream px-3 py-2 text-sm shadow-neo-sm transition duration-150 md:text-base">
            <span class="font-display text-base uppercase md:text-lg">Nerita</span>
            <span class="neo-chip">Free to Read</span>
        </a>

        <?php if ($current_user !== null): ?>
            <div class="hidden items-center gap-2 md:flex">
                <?php $isHomeActive = $currentPath === '/'; ?>
                <a href="<?= site_url('/') ?>" class="neo-btn-ghost hidden md:inline-flex <?= $isHomeActive ? 'bg-neo-black text-neo-white' : '' ?>">
                    Beranda
                </a>
                <details class="relative hidden md:block" data-nav-dropdown="desktop">
                    <summary class="neo-btn-ghost cursor-pointer list-none <?= $isCategoryPath ? 'bg-neo-black text-neo-white' : '' ?>">Category</summary>
                    <div class="neo-shell absolute right-0 mt-2 flex max-h-80 min-w-64 flex-col gap-2 overflow-y-auto p-2">
                        <?php foreach ($navCategories as $category): ?>
                            <a href="<?= site_url('kategori/' . $category['slug']) ?>" class="neo-btn-ghost w-full justify-between text-left <?= $currentPath === '/kategori/' . $category['slug'] ? 'bg-neo-black text-neo-white' : '' ?>">
                                <span><?= esc($category['name']) ?></span>
                                <span class="ml-2 border-l-3 border-neo-black pl-2 font-mono text-xs <?= $currentPath === '/kategori/' . $category['slug'] ? 'border-neo-white' : '' ?>">
                                    <?= esc((string) $category['articles_count']) ?>
                                </span>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </details>
                <details class="relative hidden md:block" data-nav-dropdown="desktop">
                    <summary class="inline-flex cursor-pointer list-none items-center gap-2 border-3 border-neo-black bg-neo-white px-3 py-2 shadow-neo-sm transition duration-150 select-none" aria-label="Menu pengguna">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5" aria-hidden="true">
                            <path fill-rule="evenodd" d="M12 2a5 5 0 1 0 0 10 5 5 0 0 0 0-10ZM4 19a8 8 0 1 1 16 0 .75.75 0 0 1-.75.75h-14.5A.75.75 0 0 1 4 19Z" clip-rule="evenodd" />
                        </svg>
                        <span class="text-sm"><?= esc($current_user['name']) ?></span>
                        <span class="sr-only">Menu <?= esc($current_user['name']) ?></span>
                    </summary>
                    <div class="neo-shell absolute right-0 mt-2 flex min-w-48 flex-col gap-2 p-2">
                        <?php foreach ($userLinks as $link): ?>
                            <?php $isActive = $currentPath === $link['href']; ?>
                            <a href="<?= site_url($link['href']) ?>" class="neo-btn-ghost text-left <?= $isActive ? 'bg-neo-black text-neo-white' : '' ?>">
                                <?= esc($link['label']) ?>
                            </a>
                        <?php endforeach; ?>
                        <form action="<?= site_url('keluar') ?>" method="post">
                            <?= csrf_field() ?>
                            <button type="submit" class="neo-btn-primary w-full">Keluar</button>
                        </form>
                    </div>
                </details>
            </div>
        <?php else: ?>
            <div class="hidden items-center gap-2 md:flex">
                <?php $isHomeActive = $currentPath === '/'; ?>
                <a href="<?= site_url('/') ?>" class="neo-btn-ghost <?= $isHomeActive ? 'bg-neo-black text-neo-white' : '' ?>">
                    Beranda
                </a>
                <details class="relative hidden md:block" data-nav-dropdown="desktop">
                    <summary class="neo-btn-ghost cursor-pointer list-none <?= $isCategoryPath ? 'bg-neo-black text-neo-white' : '' ?>">Category</summary>
                    <div class="neo-shell absolute right-0 mt-2 flex max-h-80 min-w-64 flex-col gap-2 overflow-y-auto p-2">
                        <?php foreach ($navCategories as $category): ?>
                            <a href="<?= site_url('kategori/' . $category['slug']) ?>" class="neo-btn-ghost w-full justify-between text-left <?= $currentPath === '/kategori/' . $category['slug'] ? 'bg-neo-black text-neo-white' : '' ?>">
                                <span><?= esc($category['name']) ?></span>
                                <span class="ml-2 border-l-3 border-neo-black pl-2 font-mono text-xs <?= $currentPath === '/kategori/' . $category['slug'] ? 'border-neo-white' : '' ?>">
                                    <?= esc((string) $category['articles_count']) ?>
                                </span>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </details>
                <a href="<?= site_url('masuk') ?>" class="neo-btn-ghost hidden md:inline-flex">Masuk</a>
                <a href="<?= site_url('daftar') ?>" class="neo-btn-primary">Daftar</a>
            </div>
        <?php endif; ?>

        <label for="mobile-sidebar-toggle" class="neo-btn-ghost md:hidden select-none peer-checked:hidden">Menu</label>
    </div>

</header>

<label for="mobile-sidebar-toggle" class="fixed inset-0 z-40 hidden bg-black/50 backdrop-blur-sm peer-checked:block md:hidden" aria-label="Tutup sidebar"></label>

<aside class="fixed top-0 left-0 z-50 h-dvh w-[84vw] max-w-xs -translate-x-full bg-transparent transition-transform duration-150 peer-checked:translate-x-0 md:hidden">
    <div class="flex h-full flex-col border-r-4 border-neo-black bg-neo-paper p-4 shadow-neo">
        <div class="mb-4 flex items-center justify-between">
            <span class="font-display text-lg uppercase select-none">Menu</span>
            <label for="mobile-sidebar-toggle" class="neo-btn-ghost px-3" aria-label="Tutup menu">X</label>
        </div>

        <div class="flex flex-1 flex-col gap-2 overflow-y-auto pr-1">
            <?php foreach ($publicLinks as $link): ?>
                <?php $isActive = $currentPath === $link['href']; ?>
                <a href="<?= site_url($link['href']) ?>" class="neo-btn-ghost w-full justify-start <?= $isActive ? 'bg-neo-black text-neo-white' : '' ?>">
                    <?= esc($link['label']) ?>
                </a>
            <?php endforeach; ?>

            <?php if ($navCategories !== []): ?>
                <div class="my-2 border-t-3 border-neo-black pt-3">
                    <p class="mb-2 font-display text-sm uppercase">Category</p>
                    <div class="flex flex-col gap-2">
                        <?php foreach ($navCategories as $category): ?>
                            <a href="<?= site_url('kategori/' . $category['slug']) ?>" class="neo-btn-ghost w-full justify-between text-left <?= $currentPath === '/kategori/' . $category['slug'] ? 'bg-neo-black text-neo-white' : '' ?>">
                                <span><?= esc($category['name']) ?></span>
                                <span class="ml-2 border-l-3 border-neo-black pl-2 font-mono text-xs <?= $currentPath === '/kategori/' . $category['slug'] ? 'border-neo-white' : '' ?>">
                                    <?= esc((string) $category['articles_count']) ?>
                                </span>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($current_user !== null): ?>
                <div class="my-2 border-t-3 border-neo-black pt-3">
                    <div class="inline-flex max-w-full items-center gap-2 border-3 border-neo-black bg-neo-white px-3 py-2 shadow-neo-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5 shrink-0" aria-hidden="true">
                            <path fill-rule="evenodd" d="M12 2a5 5 0 1 0 0 10 5 5 0 0 0 0-10ZM4 19a8 8 0 1 1 16 0 .75.75 0 0 1-.75.75h-14.5A.75.75 0 0 1 4 19Z" clip-rule="evenodd" />
                        </svg>
                        <span class="truncate text-sm"><?= esc($current_user['name']) ?></span>
                    </div>
                </div>

                <?php foreach ($userLinks as $link): ?>
                    <?php $isActive = $currentPath === $link['href']; ?>
                    <a href="<?= site_url($link['href']) ?>" class="neo-btn-ghost w-full justify-start <?= $isActive ? 'bg-neo-black text-neo-white' : '' ?>">
                        <?= esc($link['label']) ?>
                    </a>
                <?php endforeach; ?>

                <form action="<?= site_url('keluar') ?>" method="post" class="mt-2">
                    <?= csrf_field() ?>
                    <button type="submit" class="neo-btn-primary w-full justify-center">Keluar</button>
                </form>
            <?php else: ?>
                <a href="<?= site_url('masuk') ?>" class="neo-btn-ghost w-full justify-start">Masuk</a>
                <a href="<?= site_url('daftar') ?>" class="neo-btn-primary w-full justify-center">Daftar</a>
            <?php endif; ?>
        </div>
    </div>
</aside>

<script>
(() => {
    const toggle = document.getElementById('mobile-sidebar-toggle');
    if (!toggle) {
        return;
    }

    const desktopDropdowns = Array.from(document.querySelectorAll('[data-nav-dropdown="desktop"]'));
    desktopDropdowns.forEach((dropdown) => {
        dropdown.addEventListener('toggle', () => {
            if (!dropdown.open) {
                return;
            }

            desktopDropdowns.forEach((other) => {
                if (other !== dropdown) {
                    other.removeAttribute('open');
                }
            });
        });
    });

    const syncBodyScroll = () => {
        if (window.matchMedia('(min-width: 768px)').matches) {
            toggle.checked = false;
            document.body.style.overflow = '';
            return;
        }

        document.body.style.overflow = toggle.checked ? 'hidden' : '';
    };

    toggle.addEventListener('change', syncBodyScroll);
    window.addEventListener('resize', syncBodyScroll);
    syncBodyScroll();
})();
</script>
