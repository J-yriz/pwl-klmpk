<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<section class="neo-shell p-5 md:p-8">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <span class="neo-chip">Daftar Tersimpan</span>
            <h1 class="neo-title mt-3 text-3xl md:text-4xl">Bookmark <?= esc($current_user['name'] ?? 'User') ?></h1>
            <p class="mt-2 text-sm">Total artikel tersimpan: <strong><?= esc((string) $saved_count) ?></strong></p>
        </div>
        <a href="<?= site_url('') ?>" class="neo-btn-accent">Cari Artikel Baru</a>
    </div>
</section>

<section class="mt-8">
    <?php if ($saved_articles === []): ?>
        <div class="neo-card p-8 text-center">
            <p class="neo-title text-2xl">Belum ada artikel disimpan.</p>
            <p class="mt-2 text-sm">Saat menemukan artikel menarik, klik tombol bookmark di halaman detail.</p>
        </div>
    <?php else: ?>
        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
            <?php foreach ($saved_articles as $article): ?>
                <?= view('partials/article_card', ['article' => $article]) ?>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>
<?= $this->endSection() ?>
