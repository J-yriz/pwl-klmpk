<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<article class="space-y-8">
    <header class="neo-shell p-5 md:p-8">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div class="space-y-4">
                <a class="neo-chip" href="<?= site_url('kategori/' . $article['category']['slug']) ?>">
                    <?= esc($article['category']['name']) ?>
                </a>
                <h1 class="neo-title max-w-4xl text-3xl leading-none md:text-5xl"><?= esc($article['title']) ?></h1>
                <div class="flex flex-wrap gap-2 text-xs font-mono uppercase">
                    <span class="neo-card px-3 py-2">Penulis: <?= esc($article['author']['name']) ?></span>
                    <span class="neo-card px-3 py-2"><?= esc($article['created_label']) ?></span>
                    <span class="neo-card px-3 py-2"><?= esc((string) $article['reading_minutes']) ?> Menit Baca</span>
                </div>
            </div>

            <div class="grid gap-2 text-sm">
                <?php if ($current_user === null): ?>
                    <a href="<?= site_url('masuk') ?>" class="neo-btn-ghost">Masuk untuk Like</a>
                    <a href="<?= site_url('masuk') ?>" class="neo-btn-ghost">Masuk untuk Bookmark</a>
                <?php else: ?>
                    <form action="<?= site_url('artikel/' . $article['slug'] . '/like') ?>" method="post">
                        <?= csrf_field() ?>
                        <button type="submit" class="w-full <?= $article['liked_by_current_user'] ? 'neo-btn-primary' : 'neo-btn-ghost' ?>">
                            Like (<?= esc((string) $article['likes_count']) ?>)
                        </button>
                    </form>
                    <form action="<?= site_url('artikel/' . $article['slug'] . '/bookmark') ?>" method="post">
                        <?= csrf_field() ?>
                        <button type="submit" class="neo-btn-ghost w-full <?= $article['bookmarked_by_current_user'] ? 'bg-gray-300 text-neo-black' : '' ?>">
                            Bookmark (<?= esc((string) $article['bookmarks_count']) ?>)
                        </button>
                    </form>
                <?php endif; ?>
                <span class="neo-btn-ghost"><?= esc((string) $article['comments_count']) ?> Komentar</span>
            </div>
        </div>
    </header>

    <section class="neo-shell overflow-hidden">
        <img src="<?= esc($article['cover_image']) ?>" alt="Cover <?= esc($article['title']) ?>" class="h-64 w-full border-b-4 border-neo-black object-cover md:h-96">
        <div class="article-content p-5 md:p-8">
            <?= $article['content'] ?>
        </div>
    </section>

    <section id="diskusi" class="neo-shell p-5 md:p-6">
        <h2 class="neo-title mb-4 text-2xl">Diskusi Pembaca</h2>

        <?php if ($current_user === null): ?>
            <div class="neo-card mb-6 p-4">
                <p class="text-sm">Masuk dulu untuk ikut berdiskusi di artikel ini.</p>
                <a href="<?= site_url('masuk') ?>" class="neo-btn-primary mt-3">Masuk</a>
            </div>
        <?php else: ?>
            <form action="<?= site_url('artikel/' . $article['slug'] . '/komentar') ?>" method="post" class="mb-6 space-y-3">
                <?= csrf_field() ?>
                <label class="neo-label" for="comment-input">Tulis komentar sebagai <?= esc($current_user['name']) ?></label>
                <textarea id="comment-input" name="content" rows="4" class="neo-input" placeholder="Bagikan pendapatmu tentang artikel ini" required><?= old('content') ?></textarea>
                <button type="submit" class="neo-btn-primary">Kirim Komentar</button>
            </form>
        <?php endif; ?>

        <div class="space-y-3">
            <?php foreach ($comments as $comment): ?>
                <div class="neo-card p-4">
                    <div class="mb-2 flex flex-wrap items-center justify-between gap-2 border-b-3 border-neo-black pb-2">
                        <p class="font-display text-sm uppercase"><?= esc($comment['author']['name'] ?? 'Pengguna') ?></p>
                        <p class="font-mono text-xs uppercase"><?= esc($comment['created_label']) ?></p>
                    </div>
                    <p class="text-sm md:text-base"><?= esc($comment['content']) ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <section class="space-y-4">
        <h2 class="neo-title text-2xl md:text-3xl">Artikel Terkait</h2>
        <?php if ($related_articles === []): ?>
            <div class="neo-card p-6">
                <p class="font-mono text-sm uppercase">Belum ada artikel terkait di kategori ini.</p>
            </div>
        <?php else: ?>
            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                <?php foreach ($related_articles as $item): ?>
                    <?= view('partials/article_card', ['article' => $item]) ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>
</article>
<?= $this->endSection() ?>
