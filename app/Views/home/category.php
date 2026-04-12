<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<section class="neo-shell p-5 md:p-8">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <span class="neo-chip">Kategori Aktif</span>
            <h1 class="neo-title mt-3 text-3xl md:text-4xl"><?= esc($category['name']) ?></h1>
            <p class="mt-2 text-sm md:text-base">Menampilkan artikel berdasarkan kategori yang dipilih.</p>
        </div>
        <a href="<?= site_url('') ?>" class="neo-btn-accent">Kembali ke Beranda</a>
    </div>
</section>

<section class="mt-8 neo-shell p-5 md:p-6">
    <div class="mb-4 flex items-center justify-between">
        <h2 class="neo-title text-xl">Kategori Lain</h2>
    </div>

    <div class="flex flex-wrap gap-2">
        <?php foreach ($categories as $item): ?>
            <?php $isActive = $item['slug'] === $active_category; ?>
            <a href="<?= site_url('kategori/' . $item['slug']) ?>" class="neo-btn-ghost <?= $isActive ? 'bg-neo-black text-neo-white' : '' ?>">
                <?= esc($item['name']) ?>
            </a>
        <?php endforeach; ?>
    </div>
</section>

<section class="mt-8">
    <?php if ($articles === []): ?>
        <div class="neo-card p-8 text-center">
            <p class="neo-title text-2xl">Belum ada artikel di kategori ini.</p>
        </div>
    <?php else: ?>
        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
            <?php foreach ($articles as $article): ?>
                <?= view('partials/article_card', ['article' => $article]) ?>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>
<?= $this->endSection() ?>
