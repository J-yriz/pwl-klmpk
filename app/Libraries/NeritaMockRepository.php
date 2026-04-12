<?php

namespace App\Libraries;

class NeritaMockRepository
{
    private int $readerUserId = 2;
    private int $creatorUserId = 1;

    public function getReaderUserId(): int
    {
        return $this->readerUserId;
    }

    public function getCreatorUserId(): int
    {
        return $this->creatorUserId;
    }

    public function getHomepageData(?int $currentUserId = null): array
    {
        $activeUserId = $currentUserId ?? $this->readerUserId;
        $enrichedArticles = $this->getEnrichedArticles($activeUserId);

        $latestArticles = $enrichedArticles;
        usort($latestArticles, static fn(array $a, array $b): int => strcmp($b['created_at'], $a['created_at']));

        $popularArticles = $enrichedArticles;
        usort(
            $popularArticles,
            static fn(array $a, array $b): int => $b['likes_count'] <=> $a['likes_count'] ?: strcmp($b['created_at'], $a['created_at'])
        );

        return [
            'latest_articles' => array_slice($latestArticles, 0, 6),
            'popular_articles' => array_slice($popularArticles, 0, 6),
            'categories' => $this->getCategoriesWithCount(),
            'active_category' => null,
            'stats' => [
                'articles_count' => count($this->getArticles()),
                'authors_count' => count($this->getUsers()),
                'comments_count' => count($this->getComments()),
                'bookmarks_count' => count($this->getBookmarks()),
            ],
            'current_user' => $this->findUserById($activeUserId),
        ];
    }

    public function getCategoryPageData(string $categorySlug, ?int $currentUserId = null): ?array
    {
        $category = $this->findCategoryBySlug($categorySlug);
        if ($category === null) {
            return null;
        }

        $activeUserId = $currentUserId ?? $this->readerUserId;
        $articles = array_values(
            array_filter(
                $this->getEnrichedArticles($activeUserId),
                static fn(array $article): bool => $article['category']['slug'] === $categorySlug
            )
        );

        usort($articles, static fn(array $a, array $b): int => strcmp($b['created_at'], $a['created_at']));

        return [
            'category' => $category,
            'articles' => $articles,
            'categories' => $this->getCategoriesWithCount(),
            'active_category' => $categorySlug,
            'current_user' => $this->findUserById($activeUserId),
        ];
    }

    public function getArticlePageData(string $articleSlug, ?int $currentUserId = null): ?array
    {
        $activeUserId = $currentUserId ?? $this->readerUserId;
        $articles = $this->getEnrichedArticles($activeUserId);

        $article = null;
        foreach ($articles as $item) {
            if ($item['slug'] === $articleSlug) {
                $article = $item;
                break;
            }
        }

        if ($article === null) {
            return null;
        }

        $comments = $this->getCommentsForArticle($article['id']);

        $relatedArticles = array_values(
            array_filter(
                $articles,
                static fn(array $item): bool => $item['id'] !== $article['id'] && $item['category']['id'] === $article['category']['id']
            )
        );

        usort(
            $relatedArticles,
            static fn(array $a, array $b): int => $b['likes_count'] <=> $a['likes_count'] ?: strcmp($b['created_at'], $a['created_at'])
        );

        return [
            'article' => $article,
            'comments' => $comments,
            'related_articles' => array_slice($relatedArticles, 0, 3),
            'current_user' => $this->findUserById($activeUserId),
        ];
    }

    public function getBookmarksPageData(int $currentUserId): array
    {
        $articlesById = [];
        foreach ($this->getEnrichedArticles($currentUserId) as $article) {
            $articlesById[$article['id']] = $article;
        }

        $bookmarks = array_values(
            array_filter(
                $this->getBookmarks(),
                static fn(array $bookmark): bool => $bookmark['user_id'] === $currentUserId
            )
        );

        usort($bookmarks, static fn(array $a, array $b): int => $b['id'] <=> $a['id']);

        $savedArticles = [];
        foreach ($bookmarks as $bookmark) {
            if (isset($articlesById[$bookmark['article_id']])) {
                $savedArticles[] = $articlesById[$bookmark['article_id']];
            }
        }

        return [
            'saved_articles' => $savedArticles,
            'saved_count' => count($savedArticles),
            'current_user' => $this->findUserById($currentUserId),
        ];
    }

    public function getDashboardPageData(int $creatorUserId): array
    {
        $articles = array_values(
            array_filter(
                $this->getEnrichedArticles($creatorUserId),
                static fn(array $article): bool => $article['user_id'] === $creatorUserId
            )
        );

        usort($articles, static fn(array $a, array $b): int => strcmp($b['created_at'], $a['created_at']));

        return [
            'creator' => $this->findUserById($creatorUserId),
            'articles' => $articles,
            'totals' => [
                'articles' => count($articles),
                'likes' => array_sum(array_column($articles, 'likes_count')),
                'comments' => array_sum(array_column($articles, 'comments_count')),
                'bookmarks' => array_sum(array_column($articles, 'bookmarks_count')),
            ],
        ];
    }

    public function getEditorPageData(int $creatorUserId): array
    {
        return [
            'creator' => $this->findUserById($creatorUserId),
            'categories' => $this->getCategories(),
            'draft_example' => [
                'title' => 'Membuat Dokumentasi Teknis yang Enak Dibaca',
                'cover_image' => 'https://picsum.photos/seed/nerita-editor/1600/900',
                'content' => '<p>Mulai nulis draft kamu di sini.</p>',
            ],
        ];
    }

    private function getEnrichedArticles(int $currentUserId): array
    {
        $usersById = [];
        foreach ($this->getUsers() as $user) {
            $usersById[$user['id']] = $user;
        }

        $categoriesById = [];
        foreach ($this->getCategories() as $category) {
            $categoriesById[$category['id']] = $category;
        }

        $likes = $this->getLikes();
        $bookmarks = $this->getBookmarks();
        $comments = $this->getComments();

        $enriched = [];
        foreach ($this->getArticles() as $article) {
            $likesCount = count(array_filter($likes, static fn(array $like): bool => $like['article_id'] === $article['id']));
            $bookmarksCount = count(
                array_filter($bookmarks, static fn(array $bookmark): bool => $bookmark['article_id'] === $article['id'])
            );
            $commentsCount = count(
                array_filter($comments, static fn(array $comment): bool => $comment['article_id'] === $article['id'])
            );

            $likedByCurrentUser = count(
                array_filter(
                    $likes,
                    static fn(array $like): bool => $like['article_id'] === $article['id'] && $like['user_id'] === $currentUserId
                )
            ) > 0;

            $bookmarkedByCurrentUser = count(
                array_filter(
                    $bookmarks,
                    static fn(array $bookmark): bool => $bookmark['article_id'] === $article['id'] && $bookmark['user_id'] === $currentUserId
                )
            ) > 0;

            $articleWithRelations = $article;
            $articleWithRelations['author'] = $usersById[$article['user_id']] ?? null;
            $articleWithRelations['category'] = $categoriesById[$article['category_id']] ?? null;
            $articleWithRelations['likes_count'] = $likesCount;
            $articleWithRelations['bookmarks_count'] = $bookmarksCount;
            $articleWithRelations['comments_count'] = $commentsCount;
            $articleWithRelations['liked_by_current_user'] = $likedByCurrentUser;
            $articleWithRelations['bookmarked_by_current_user'] = $bookmarkedByCurrentUser;
            $articleWithRelations['created_label'] = $this->formatDateLabel($article['created_at']);
            $articleWithRelations['reading_minutes'] = $this->estimateReadingMinutes($article['content']);

            $enriched[] = $articleWithRelations;
        }

        return $enriched;
    }

    private function getCommentsForArticle(int $articleId): array
    {
        $usersById = [];
        foreach ($this->getUsers() as $user) {
            $usersById[$user['id']] = $user;
        }

        $comments = array_values(
            array_filter(
                $this->getComments(),
                static fn(array $comment): bool => $comment['article_id'] === $articleId
            )
        );

        usort($comments, static fn(array $a, array $b): int => strcmp($a['created_at'], $b['created_at']));

        foreach ($comments as &$comment) {
            $comment['author'] = $usersById[$comment['user_id']] ?? null;
            $comment['created_label'] = $this->formatRelativeTime($comment['created_at']);
        }
        unset($comment);

        return $comments;
    }

    private function getCategoriesWithCount(): array
    {
        $articles = $this->getArticles();
        $counts = [];

        foreach ($articles as $article) {
            $categoryId = $article['category_id'];
            if (! isset($counts[$categoryId])) {
                $counts[$categoryId] = 0;
            }
            $counts[$categoryId]++;
        }

        $categories = $this->getCategories();
        foreach ($categories as &$category) {
            $category['articles_count'] = $counts[$category['id']] ?? 0;
        }
        unset($category);

        return $categories;
    }

    private function findUserById(int $userId): ?array
    {
        foreach ($this->getUsers() as $user) {
            if ($user['id'] === $userId) {
                return $user;
            }
        }

        return null;
    }

    private function findCategoryBySlug(string $slug): ?array
    {
        foreach ($this->getCategories() as $category) {
            if ($category['slug'] === $slug) {
                return $category;
            }
        }

        return null;
    }

    private function estimateReadingMinutes(string $content): int
    {
        $plainText = trim(strip_tags($content));
        if ($plainText === '') {
            return 1;
        }

        $wordCount = str_word_count($plainText);
        return max(1, (int) ceil($wordCount / 180));
    }

    private function formatDateLabel(string $datetime): string
    {
        $timestamp = strtotime($datetime);
        if ($timestamp === false) {
            return $datetime;
        }

        return date('d M Y', $timestamp);
    }

    private function formatRelativeTime(string $datetime): string
    {
        $timestamp = strtotime($datetime);
        if ($timestamp === false) {
            return $datetime;
        }

        $diff = time() - $timestamp;

        if ($diff < 3600) {
            $minutes = max(1, (int) floor($diff / 60));
            return $minutes . ' menit lalu';
        }

        if ($diff < 86400) {
            $hours = max(1, (int) floor($diff / 3600));
            return $hours . ' jam lalu';
        }

        $days = max(1, (int) floor($diff / 86400));
        return $days . ' hari lalu';
    }

    private function getUsers(): array
    {
        return [
            [
                'id' => 1,
                'name' => 'Raka Pratama',
                'email' => 'raka@nerita.dev',
                'password' => '$2y$10$mockhashraka',
            ],
            [
                'id' => 2,
                'name' => 'Dina Larasati',
                'email' => 'dina@nerita.dev',
                'password' => '$2y$10$mockhashdina',
            ],
            [
                'id' => 3,
                'name' => 'Bimo Hadi',
                'email' => 'bimo@nerita.dev',
                'password' => '$2y$10$mockhashbimo',
            ],
            [
                'id' => 4,
                'name' => 'Sinta Nabila',
                'email' => 'sinta@nerita.dev',
                'password' => '$2y$10$mockhashsinta',
            ],
        ];
    }

    private function getCategories(): array
    {
        return [
            ['id' => 1, 'name' => 'Teknologi', 'slug' => 'teknologi'],
            ['id' => 2, 'name' => 'Produktivitas', 'slug' => 'produktivitas'],
            ['id' => 3, 'name' => 'Desain', 'slug' => 'desain'],
            ['id' => 4, 'name' => 'Karier', 'slug' => 'karier'],
            ['id' => 5, 'name' => 'Pemrograman', 'slug' => 'pemrograman'],
        ];
    }

    private function getArticles(): array
    {
        return [
            [
                'id' => 1,
                'user_id' => 1,
                'category_id' => 5,
                'title' => 'Memahami MVC Tanpa Ribet untuk Pemula',
                'slug' => 'memahami-mvc-tanpa-ribet-untuk-pemula',
                'content' => <<<'HTML'
<h2>Kenapa MVC masih relevan?</h2>
<p>MVC memisahkan urusan tampilan, logika, dan data. Dengan pemisahan ini, tim bisa kerja paralel tanpa saling menunggu.</p>
<p>Untuk skala aplikasi konten seperti Nerita, pola ini bikin maintenance jauh lebih ringan.</p>
<h3>Contoh alur request sederhana</h3>
<pre><code>// Routes -> Controller -> Model -> View
$routes->get('/articles', 'ArticleController::index');</code></pre>
<blockquote>Jika bingung mulai dari mana, petakan dulu data yang ditampilkan user.</blockquote>
HTML,
                'cover_image' => 'https://picsum.photos/seed/nerita-1/1400/900',
                'created_at' => '2026-04-03 09:20:00',
            ],
            [
                'id' => 2,
                'user_id' => 3,
                'category_id' => 2,
                'title' => 'Menulis 500 Kata Setiap Hari dengan Sistem 20 Menit',
                'slug' => 'menulis-500-kata-setiap-hari-dengan-sistem-20-menit',
                'content' => <<<'HTML'
<h2>Aturan sprint 20 menit</h2>
<p>Sebelum menulis, tentukan satu pertanyaan utama. Jawab pertanyaan itu dalam 3 paragraf.</p>
<p>Set timer 20 menit, matikan notifikasi, lalu mulai nulis tanpa edit.</p>
<h3>Template sederhana</h3>
<pre><code>1. Masalah pembaca
2. Solusi praktis
3. Aksi yang bisa dilakukan hari ini</code></pre>
HTML,
                'cover_image' => 'https://picsum.photos/seed/nerita-2/1400/900',
                'created_at' => '2026-04-04 14:05:00',
            ],
            [
                'id' => 3,
                'user_id' => 1,
                'category_id' => 3,
                'title' => 'Prinsip Layout Baca Nyaman untuk Artikel Panjang',
                'slug' => 'prinsip-layout-baca-nyaman-untuk-artikel-panjang',
                'content' => <<<'HTML'
<h2>Utamakan ritme baca</h2>
<p>Lebar konten ideal sekitar 60-75 karakter per baris agar mata tidak cepat lelah.</p>
<p>Kontras tinggi dan heading konsisten membantu pembaca scan isi artikel lebih cepat.</p>
<h3>Checklist cepat</h3>
<pre><code>- line-height longgar
- heading jelas
- tidak ada elemen visual berisik</code></pre>
HTML,
                'cover_image' => 'https://picsum.photos/seed/nerita-3/1400/900',
                'created_at' => '2026-04-06 08:15:00',
            ],
            [
                'id' => 4,
                'user_id' => 4,
                'category_id' => 4,
                'title' => 'Menyusun Portofolio Penulis Tech untuk Fresh Graduate',
                'slug' => 'menyusun-portofolio-penulis-tech-untuk-fresh-graduate',
                'content' => <<<'HTML'
<h2>Portofolio bukan daftar sertifikat</h2>
<p>Portofolio yang kuat menjelaskan masalah yang kamu angkat dan dampak tulisanmu bagi pembaca.</p>
<p>Sisipkan 3 tulisan terbaik dengan topik berbeda agar editor melihat range kamu.</p>
HTML,
                'cover_image' => 'https://picsum.photos/seed/nerita-4/1400/900',
                'created_at' => '2026-04-07 10:00:00',
            ],
            [
                'id' => 5,
                'user_id' => 3,
                'category_id' => 1,
                'title' => 'Riset Cepat Topik AI dengan Metode Pertanyaan Berlapis',
                'slug' => 'riset-cepat-topik-ai-dengan-metode-pertanyaan-berlapis',
                'content' => <<<'HTML'
<h2>Mulai dari pertanyaan awam</h2>
<p>Riset yang bagus dimulai dari pertanyaan paling sederhana, lalu diperdalam menjadi 3 level.</p>
<p>Setiap level menghasilkan sudut pandang baru dan referensi yang lebih tajam.</p>
HTML,
                'cover_image' => 'https://picsum.photos/seed/nerita-5/1400/900',
                'created_at' => '2026-04-08 12:40:00',
            ],
            [
                'id' => 6,
                'user_id' => 1,
                'category_id' => 5,
                'title' => 'Syntax Highlighting yang Konsisten untuk Artikel Coding',
                'slug' => 'syntax-highlighting-yang-konsisten-untuk-artikel-coding',
                'content' => <<<'HTML'
<h2>Pilih satu tema dan pakai konsisten</h2>
<p>Warna kode terlalu banyak bisa mengganggu pembaca. Pilih tema dengan kontras tinggi.</p>
<p>Untuk neobrutalism, gunakan background code gelap dan teks terang agar tetap fokus.</p>
<pre><code>function publishArticle(title, content) {
  return { title, content, published: true };
}</code></pre>
HTML,
                'cover_image' => 'https://picsum.photos/seed/nerita-6/1400/900',
                'created_at' => '2026-04-10 09:35:00',
            ],
        ];
    }

    private function getComments(): array
    {
        return [
            [
                'id' => 1,
                'user_id' => 2,
                'article_id' => 1,
                'content' => 'Akhirnya ketemu penjelasan MVC yang tidak muter-muter, makasih kak.',
                'created_at' => '2026-04-10 10:00:00',
            ],
            [
                'id' => 2,
                'user_id' => 4,
                'article_id' => 1,
                'content' => 'Bagian contoh alurnya membantu banget buat saya yang baru masuk CI4.',
                'created_at' => '2026-04-10 12:10:00',
            ],
            [
                'id' => 3,
                'user_id' => 1,
                'article_id' => 2,
                'content' => 'Metode 20 menit ini bisa dipakai juga untuk nulis dokumentasi teknis.',
                'created_at' => '2026-04-09 14:02:00',
            ],
            [
                'id' => 4,
                'user_id' => 2,
                'article_id' => 3,
                'content' => 'Setuju banget soal line length, ini sering diabaikan.',
                'created_at' => '2026-04-09 20:45:00',
            ],
            [
                'id' => 5,
                'user_id' => 3,
                'article_id' => 3,
                'content' => 'Boleh share juga contoh layout mobile-nya di artikel lanjutan.',
                'created_at' => '2026-04-10 08:45:00',
            ],
            [
                'id' => 6,
                'user_id' => 1,
                'article_id' => 5,
                'content' => 'Framework pertanyaan berlapis ini kepake buat riset topik cloud juga.',
                'created_at' => '2026-04-10 15:00:00',
            ],
            [
                'id' => 7,
                'user_id' => 4,
                'article_id' => 6,
                'content' => 'Contoh codenya clean, jadi gampang diikuti.',
                'created_at' => '2026-04-10 17:30:00',
            ],
        ];
    }

    private function getLikes(): array
    {
        return [
            ['id' => 1, 'user_id' => 2, 'article_id' => 1],
            ['id' => 2, 'user_id' => 3, 'article_id' => 1],
            ['id' => 3, 'user_id' => 4, 'article_id' => 1],
            ['id' => 4, 'user_id' => 1, 'article_id' => 2],
            ['id' => 5, 'user_id' => 2, 'article_id' => 2],
            ['id' => 6, 'user_id' => 4, 'article_id' => 2],
            ['id' => 7, 'user_id' => 2, 'article_id' => 3],
            ['id' => 8, 'user_id' => 3, 'article_id' => 3],
            ['id' => 9, 'user_id' => 1, 'article_id' => 3],
            ['id' => 10, 'user_id' => 1, 'article_id' => 4],
            ['id' => 11, 'user_id' => 2, 'article_id' => 5],
            ['id' => 12, 'user_id' => 3, 'article_id' => 5],
            ['id' => 13, 'user_id' => 4, 'article_id' => 5],
            ['id' => 14, 'user_id' => 2, 'article_id' => 6],
            ['id' => 15, 'user_id' => 3, 'article_id' => 6],
            ['id' => 16, 'user_id' => 4, 'article_id' => 6],
        ];
    }

    private function getBookmarks(): array
    {
        return [
            ['id' => 1, 'user_id' => 2, 'article_id' => 1],
            ['id' => 2, 'user_id' => 2, 'article_id' => 5],
            ['id' => 3, 'user_id' => 2, 'article_id' => 6],
            ['id' => 4, 'user_id' => 3, 'article_id' => 2],
            ['id' => 5, 'user_id' => 4, 'article_id' => 3],
            ['id' => 6, 'user_id' => 1, 'article_id' => 4],
        ];
    }
}
