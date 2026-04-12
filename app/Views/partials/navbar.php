<?php
$currentPath = (string) service('uri')->getPath();
$currentPath = '/' . trim($currentPath, '/');
$currentPath = $currentPath === '/' ? $currentPath : rtrim($currentPath, '/');
$currentPath = $currentPath === '' ? '/' : $currentPath;

$links = [
    ['label' => 'Beranda', 'href' => '/'],
    ['label' => 'Bookmark', 'href' => '/bookmark'],
    ['label' => 'Dashboard', 'href' => '/kreator/dashboard'],
    ['label' => 'Editor', 'href' => '/kreator/editor'],
];
?>
<header class="sticky top-0 z-30 border-b-4 border-neo-black bg-neo-paper/95 backdrop-blur-sm">
    <div class="mx-auto flex w-full max-w-7xl items-center justify-between px-4 py-4 md:px-6">
        <a href="<?= site_url('') ?>" class="neo-shell inline-flex items-center gap-2 px-3 py-2 text-sm md:text-base">
            <span class="font-display text-base uppercase md:text-lg">Nerita</span>
            <span class="neo-chip">Free to Read</span>
        </a>

        <nav class="hidden items-center gap-2 md:flex">
            <?php foreach ($links as $link): ?>
                <?php $isActive = $currentPath === $link['href']; ?>
                <a href="<?= site_url($link['href']) ?>" class="neo-btn-ghost <?= $isActive ? 'bg-neo-black text-neo-white' : '' ?>">
                    <?= esc($link['label']) ?>
                </a>
            <?php endforeach; ?>
        </nav>

        <?php if ($current_user !== null): ?>
            <div class="flex items-center gap-2">
                <span class="neo-chip hidden md:inline-flex"><?= esc($current_user['name']) ?></span>
                <form action="<?= site_url('keluar') ?>" method="post">
                    <?= csrf_field() ?>
                    <button type="submit" class="neo-btn-primary">Keluar</button>
                </form>
            </div>
        <?php else: ?>
            <div class="flex items-center gap-2">
                <a href="<?= site_url('masuk') ?>" class="neo-btn-ghost hidden md:inline-flex">Masuk</a>
                <a href="<?= site_url('daftar') ?>" class="neo-btn-primary">Daftar</a>
            </div>
        <?php endif; ?>
    </div>

    <div class="mx-auto w-full max-w-7xl px-4 pb-4 md:hidden">
        <div class="grid grid-cols-2 gap-2">
            <?php foreach ($links as $link): ?>
                <?php $isActive = $currentPath === $link['href']; ?>
                <a href="<?= site_url($link['href']) ?>" class="neo-btn-ghost text-center <?= $isActive ? 'bg-neo-black text-neo-white' : '' ?>">
                    <?= esc($link['label']) ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</header>
