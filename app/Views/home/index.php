<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<section class="neo-shell p-5 md:p-8">
    <div class="grid gap-6 md:grid-cols-[2fr_1fr] md:items-end">
        <div class="space-y-4">
            <span class="neo-chip">Platform Publikasi Minimalis</span>
            <h1 class="neo-title text-3xl leading-none md:text-5xl">Baca artikel tajam tanpa distraksi visual.</h1>
            <p class="max-w-2xl text-sm md:text-base">
                Nerita dirancang untuk kreator yang ingin menulis fokus dan pembaca yang ingin konten jelas.
                Seluruh tampilan memakai neobrutalism tanpa gradient dengan ritme baca yang kuat.
            </p>
            <div class="flex flex-wrap gap-2">
                <a href="<?= site_url('kreator/editor') ?>" class="neo-btn-primary">Tulis Artikel</a>
                <a href="<?= site_url('kreator/dashboard') ?>" class="neo-btn-ghost">Lihat Dashboard</a>
            </div>
        </div>

        <div class="grid gap-3 text-sm">
            <div class="neo-card p-3">
                <p class="font-mono text-xs uppercase">Total Artikel</p>
                <p class="neo-title text-2xl"><?= esc((string) $stats['articles_count']) ?></p>
            </div>
            <div class="neo-card p-3">
                <p class="font-mono text-xs uppercase">Total Penulis</p>
                <p class="neo-title text-2xl"><?= esc((string) $stats['authors_count']) ?></p>
            </div>
            <div class="neo-card p-3">
                <p class="font-mono text-xs uppercase">Total Interaksi</p>
                <p class="neo-title text-2xl"><?= esc((string) ($stats['comments_count'] + $stats['bookmarks_count'])) ?></p>
            </div>
        </div>
    </div>
</section>

<section class="mt-8 neo-shell p-5 md:p-6">
    <div class="mb-4 flex items-center justify-between">
        <h2 class="neo-title text-2xl">Kategori</h2>
        <a href="<?= site_url('') ?>" class="neo-link text-xs">Semua Artikel</a>
    </div>
    <div class="flex flex-wrap gap-2">
        <?php foreach ($categories as $category): ?>
            <a href="<?= site_url('kategori/' . $category['slug']) ?>" class="neo-btn-ghost">
                <?= esc($category['name']) ?>
                <span class="ml-2 border-l-3 border-neo-black pl-2 font-mono text-xs"><?= esc((string) $category['articles_count']) ?></span>
            </a>
        <?php endforeach; ?>
    </div>
</section>

<section class="mt-8 space-y-4">
    <div class="flex items-center justify-between">
        <h2 class="neo-title text-2xl md:text-3xl">Artikel Terbaru</h2>
    </div>

    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
        <?php foreach ($latest_articles as $article): ?>
            <?= view('partials/article_card', ['article' => $article]) ?>
        <?php endforeach; ?>
    </div>
</section>

<section class="mt-10 space-y-4">
    <div class="flex items-center justify-between">
        <h2 class="neo-title text-2xl md:text-3xl">Paling Populer</h2>
    </div>

    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
        <?php foreach ($popular_articles as $article): ?>
            <?= view('partials/article_card', ['article' => $article]) ?>
        <?php endforeach; ?>
    </div>
</section>
<?= $this->endSection() ?>
