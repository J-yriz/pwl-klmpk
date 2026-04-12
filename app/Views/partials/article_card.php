<article class="neo-card flex h-full flex-col overflow-hidden">
    <img src="<?= esc($article['cover_image']) ?>" alt="Cover <?= esc($article['title']) ?>" class="h-44 w-full border-b-3 border-neo-black object-cover">

    <div class="flex grow flex-col gap-4 p-4">
        <div class="flex items-center justify-between gap-3">
            <a class="neo-chip" href="<?= site_url('kategori/' . $article['category']['slug']) ?>">
                <?= esc($article['category']['name']) ?>
            </a>
            <span class="font-mono text-xs uppercase"><?= esc($article['created_label']) ?></span>
        </div>

        <h3 class="neo-title text-xl leading-tight"><?= esc($article['title']) ?></h3>

        <div class="mt-auto flex items-center justify-between border-t-3 border-neo-black pt-3">
            <p class="font-mono text-xs uppercase">oleh <?= esc($article['author']['name']) ?></p>
            <a href="<?= site_url('artikel/' . $article['slug']) ?>" class="neo-btn-accent">Baca</a>
        </div>

        <div class="grid grid-cols-3 gap-2 text-center text-xs font-mono uppercase">
            <span class="neo-card px-2 py-1"><?= esc((string) $article['likes_count']) ?> Like</span>
            <span class="neo-card px-2 py-1"><?= esc((string) $article['comments_count']) ?> Komentar</span>
            <span class="neo-card px-2 py-1"><?= esc((string) $article['reading_minutes']) ?> Menit</span>
        </div>

        <?php if (($current_user ?? null) !== null): ?>
            <div class="grid grid-cols-2 gap-2">
                <form action="<?= site_url('artikel/' . $article['slug'] . '/like') ?>" method="post">
                    <?= csrf_field() ?>
                    <button type="submit" class="neo-btn-ghost w-full <?= $article['liked_by_current_user'] ? 'bg-neo-red text-neo-white' : '' ?>">Like</button>
                </form>
                <form action="<?= site_url('artikel/' . $article['slug'] . '/bookmark') ?>" method="post">
                    <?= csrf_field() ?>
                    <button type="submit" class="neo-btn-ghost w-full <?= $article['bookmarked_by_current_user'] ? 'bg-neo-black text-neo-white' : '' ?>">Bookmark</button>
                </form>
            </div>
        <?php endif; ?>
    </div>
</article>
