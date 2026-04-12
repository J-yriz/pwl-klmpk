<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<section class="neo-shell p-5 md:p-8">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <span class="neo-chip">Dashboard Kreator</span>
            <h1 class="neo-title mt-3 text-3xl md:text-4xl"><?= esc($creator['name'] ?? 'Kreator') ?></h1>
            <p class="mt-2 text-sm">Ringkasan performa artikel berdasarkan data interaksi pembaca.</p>
        </div>
        <a href="<?= site_url('kreator/editor') ?>" class="neo-btn-primary">Tulis Artikel Baru</a>
    </div>

    <div class="mt-6 grid gap-3 md:grid-cols-4">
        <div class="neo-card p-3">
            <p class="font-mono text-xs uppercase">Artikel</p>
            <p class="neo-title text-2xl"><?= esc((string) $totals['articles']) ?></p>
        </div>
        <div class="neo-card p-3">
            <p class="font-mono text-xs uppercase">Like</p>
            <p class="neo-title text-2xl"><?= esc((string) $totals['likes']) ?></p>
        </div>
        <div class="neo-card p-3">
            <p class="font-mono text-xs uppercase">Komentar</p>
            <p class="neo-title text-2xl"><?= esc((string) $totals['comments']) ?></p>
        </div>
        <div class="neo-card p-3">
            <p class="font-mono text-xs uppercase">Bookmark</p>
            <p class="neo-title text-2xl"><?= esc((string) $totals['bookmarks']) ?></p>
        </div>
    </div>
</section>

<section class="mt-8 space-y-3">
    <?php if ($articles === []): ?>
        <div class="neo-card p-8 text-center">
            <p class="neo-title text-2xl">Kamu belum punya artikel.</p>
        </div>
    <?php else: ?>
        <?php foreach ($articles as $article): ?>
            <div class="neo-card p-4 md:p-5">
                <div class="flex flex-wrap items-start justify-between gap-4">
                    <div>
                        <p class="neo-chip"><?= esc($article['category']['name']) ?></p>
                        <h2 class="neo-title mt-3 text-xl md:text-2xl"><?= esc($article['title']) ?></h2>
                        <p class="mt-2 font-mono text-xs uppercase">Dipublikasi <?= esc($article['created_label']) ?></p>
                    </div>

                    <div class="grid grid-cols-3 gap-2 text-center text-xs font-mono uppercase">
                        <span class="neo-card px-2 py-2"><?= esc((string) $article['likes_count']) ?> Like</span>
                        <span class="neo-card px-2 py-2"><?= esc((string) $article['comments_count']) ?> Komentar</span>
                        <span class="neo-card px-2 py-2"><?= esc((string) $article['bookmarks_count']) ?> Simpan</span>
                    </div>
                </div>

                <div class="mt-4 flex flex-wrap gap-2">
                    <a href="<?= site_url('artikel/' . $article['slug']) ?>" class="neo-btn-ghost">Lihat Artikel</a>
                    <a href="<?= site_url('kreator/editor') ?>" class="neo-btn-accent">Edit Draft</a>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</section>
<?= $this->endSection() ?>
