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

    <div class="mt-6 grid gap-3 md:grid-cols-5">
        <div class="neo-card p-3">
            <p class="font-mono text-xs uppercase">Terpublikasi</p>
            <p class="neo-title text-2xl"><?= esc((string) $totals['published']) ?></p>
        </div>
        <div class="neo-card p-3">
            <p class="font-mono text-xs uppercase">Draft</p>
            <p class="neo-title text-2xl"><?= esc((string) $totals['drafts']) ?></p>
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

<section class="mt-10">
    <h2 class="neo-title text-2xl">Draft</h2>
    <p class="mt-1 text-sm">Simpan banyak draft; lanjutkan kapan saja sebelum dipublikasikan.</p>
    <div class="mt-4 space-y-3">
        <?php if (($draft_articles ?? []) === []): ?>
            <div class="neo-card p-6 text-center text-sm">Belum ada draft. Klik <strong>Tulis Artikel Baru</strong> lalu <strong>Simpan Draft</strong>.</div>
        <?php else: ?>
            <?php foreach ($draft_articles as $article): ?>
                <div class="neo-card p-4 md:p-5">
                    <div class="flex flex-wrap items-start justify-between gap-4">
                        <div>
                            <p class="neo-chip"><?= esc($article['category']['name']) ?></p>
                            <h3 class="neo-title mt-3 text-lg md:text-xl"><?= esc($article['title']) ?></h3>
                            <p class="mt-2 font-mono text-xs uppercase">Diperbarui <?= esc($article['created_label']) ?></p>
                        </div>
                        <a href="<?= site_url('kreator/editor/' . $article['slug']) ?>" class="neo-btn-accent shrink-0">Lanjutkan Draft</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>

<section class="mt-10">
    <h2 class="neo-title text-2xl">Artikel terpublikasi</h2>
    <p class="mt-1 text-sm">Artikel yang sudah live untuk pembaca.</p>
    <div class="mt-4 space-y-3">
        <?php if (($published_articles ?? []) === []): ?>
            <div class="neo-card p-8 text-center">
                <p class="neo-title text-2xl">Belum ada artikel terpublikasi.</p>
            </div>
        <?php else: ?>
            <?php foreach ($published_articles as $article): ?>
                <div class="neo-card p-4 md:p-5">
                    <div class="flex flex-wrap items-start justify-between gap-4">
                        <div>
                            <p class="neo-chip"><?= esc($article['category']['name']) ?></p>
                            <h3 class="neo-title mt-3 text-xl md:text-2xl"><?= esc($article['title']) ?></h3>
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
                        <a href="<?= site_url('kreator/editor/' . $article['slug']) ?>" class="neo-btn-accent">Edit Artikel</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>
<?= $this->endSection() ?>
