<?php

namespace App\Libraries;

use App\Models\ArticleModel;
use App\Models\BookmarkModel;
use App\Models\CategoryModel;
use App\Models\CommentModel;
use App\Models\LikeModel;
use App\Models\UserModel;
use CodeIgniter\Database\BaseConnection;

class NeritaRepository
{
    private BaseConnection $db;

    private UserModel $userModel;

    private CategoryModel $categoryModel;

    private ArticleModel $articleModel;

    private CommentModel $commentModel;

    private LikeModel $likeModel;

    private BookmarkModel $bookmarkModel;

    public function __construct()
    {
        $this->db = db_connect();
        $this->userModel = new UserModel();
        $this->categoryModel = new CategoryModel();
        $this->articleModel = new ArticleModel();
        $this->commentModel = new CommentModel();
        $this->likeModel = new LikeModel();
        $this->bookmarkModel = new BookmarkModel();
    }

    public function findUserById(int $userId): ?array
    {
        $user = $this->userModel->find($userId);

        return $user === null ? null : $this->mapUser($user);
    }

    public function findUserByEmail(string $email): ?array
    {
        $user = $this->userModel->where('email', $email)->first();

        return $user === null ? null : $this->mapUser($user);
    }

    public function createUser(string $name, string $email, string $passwordHash): int
    {
        $this->userModel->insert([
            'name' => $name,
            'email' => $email,
            'password' => $passwordHash,
        ]);

        return (int) $this->userModel->getInsertID();
    }

    public function getHomepageData(?int $currentUserId): array
    {
        $latestArticles = $this->articleBaseBuilder($currentUserId)
            ->orderBy('a.created_at', 'DESC')
            ->limit(6)
            ->get()
            ->getResultArray();

        $popularArticles = $this->articleBaseBuilder($currentUserId)
            ->orderBy('(likes_count * 3) + (comments_count * 2) + bookmarks_count', 'DESC', false)
            ->orderBy('a.created_at', 'DESC')
            ->limit(6)
            ->get()
            ->getResultArray();

        return [
            'stats' => [
                'articles_count' => (int) $this->db->table('articles')->countAllResults(),
                'authors_count' => (int) $this->db->table('articles')->select('user_id')->distinct()->countAllResults(),
                'comments_count' => (int) $this->db->table('comments')->countAllResults(),
                'bookmarks_count' => (int) $this->db->table('bookmarks')->countAllResults(),
            ],
            'categories' => $this->getCategoriesWithCount(),
            'latest_articles' => array_map(fn (array $row): array => $this->mapArticle($row), $latestArticles),
            'popular_articles' => array_map(fn (array $row): array => $this->mapArticle($row), $popularArticles),
            'current_user' => $currentUserId === null ? null : $this->findUserById($currentUserId),
        ];
    }

    public function getCategoryPageData(string $slug, ?int $currentUserId): ?array
    {
        $category = $this->categoryModel->where('slug', $slug)->first();

        if ($category === null) {
            return null;
        }

        $articles = $this->articleBaseBuilder($currentUserId)
            ->where('a.category_id', (int) $category['id'])
            ->orderBy('a.created_at', 'DESC')
            ->get()
            ->getResultArray();

        return [
            'category' => [
                'id' => (int) $category['id'],
                'name' => (string) $category['name'],
                'slug' => (string) $category['slug'],
            ],
            'active_category' => (string) $category['slug'],
            'categories' => $this->getCategoriesWithCount(),
            'articles' => array_map(fn (array $row): array => $this->mapArticle($row), $articles),
            'current_user' => $currentUserId === null ? null : $this->findUserById($currentUserId),
        ];
    }

    public function getArticlePageData(string $slug, ?int $currentUserId): ?array
    {
        $article = $this->articleBaseBuilder($currentUserId)
            ->where('a.slug', $slug)
            ->get()
            ->getRowArray();

        if ($article === null) {
            return null;
        }

        $articleId = (int) $article['id'];

        $comments = $this->db->table('comments cm')
            ->select('cm.id, cm.content, cm.created_at, u.id AS author_id, u.name AS author_name')
            ->join('users u', 'u.id = cm.user_id')
            ->where('cm.article_id', $articleId)
            ->orderBy('cm.created_at', 'DESC')
            ->get()
            ->getResultArray();

        $relatedArticles = $this->articleBaseBuilder($currentUserId)
            ->where('a.category_id', (int) $article['category_id'])
            ->where('a.id !=', $articleId)
            ->orderBy('a.created_at', 'DESC')
            ->limit(3)
            ->get()
            ->getResultArray();

        return [
            'article' => $this->mapArticle($article),
            'comments' => array_map(function (array $row): array {
                return [
                    'id' => (int) $row['id'],
                    'content' => (string) $row['content'],
                    'created_label' => $this->formatDateLabel((string) $row['created_at']),
                    'author' => [
                        'id' => (int) $row['author_id'],
                        'name' => (string) $row['author_name'],
                    ],
                ];
            }, $comments),
            'related_articles' => array_map(fn (array $row): array => $this->mapArticle($row), $relatedArticles),
            'current_user' => $currentUserId === null ? null : $this->findUserById($currentUserId),
        ];
    }

    public function getBookmarksPageData(int $userId): array
    {
        $savedArticles = $this->articleBaseBuilder($userId)
            ->join('bookmarks bm', 'bm.article_id = a.id', 'inner')
            ->where('bm.user_id', $userId)
            ->orderBy('bm.created_at', 'DESC')
            ->get()
            ->getResultArray();

        return [
            'current_user' => $this->findUserById($userId),
            'saved_count' => count($savedArticles),
            'saved_articles' => array_map(fn (array $row): array => $this->mapArticle($row), $savedArticles),
        ];
    }

    public function getDashboardPageData(int $userId): array
    {
        $articles = $this->articleBaseBuilder($userId)
            ->where('a.user_id', $userId)
            ->orderBy('a.created_at', 'DESC')
            ->get()
            ->getResultArray();

        $articleIds = array_map(static fn (array $row): int => (int) $row['id'], $articles);

        return [
            'creator' => $this->findUserById($userId),
            'totals' => [
                'articles' => count($articleIds),
                'likes' => $articleIds === [] ? 0 : (int) $this->db->table('likes')->whereIn('article_id', $articleIds)->countAllResults(),
                'comments' => $articleIds === [] ? 0 : (int) $this->db->table('comments')->whereIn('article_id', $articleIds)->countAllResults(),
                'bookmarks' => $articleIds === [] ? 0 : (int) $this->db->table('bookmarks')->whereIn('article_id', $articleIds)->countAllResults(),
            ],
            'articles' => array_map(fn (array $row): array => $this->mapArticle($row), $articles),
            'current_user' => $this->findUserById($userId),
        ];
    }

    public function getEditorPageData(int $userId): array
    {
        return [
            'creator' => $this->findUserById($userId),
            'categories' => $this->categoryModel->orderBy('name', 'ASC')->findAll(),
            'draft_example' => [
                'title' => old('title', ''),
                'cover_image' => old('cover_image', 'https://images.unsplash.com/photo-1484417894907-623942c8ee29?auto=format&fit=crop&w=1280&q=80'),
                'content' => old('content', '<p>Tulis artikelmu di sini...</p>'),
            ],
            'current_user' => $this->findUserById($userId),
        ];
    }

    public function findArticleBySlug(string $slug): ?array
    {
        return $this->articleModel->where('slug', $slug)->first();
    }

    public function toggleLike(int $userId, int $articleId): bool
    {
        $existing = $this->likeModel
            ->where('user_id', $userId)
            ->where('article_id', $articleId)
            ->first();

        if ($existing !== null) {
            $this->likeModel->delete((int) $existing['id']);

            return false;
        }

        $this->likeModel->insert([
            'user_id' => $userId,
            'article_id' => $articleId,
        ]);

        return true;
    }

    public function toggleBookmark(int $userId, int $articleId): bool
    {
        $existing = $this->bookmarkModel
            ->where('user_id', $userId)
            ->where('article_id', $articleId)
            ->first();

        if ($existing !== null) {
            $this->bookmarkModel->delete((int) $existing['id']);

            return false;
        }

        $this->bookmarkModel->insert([
            'user_id' => $userId,
            'article_id' => $articleId,
        ]);

        return true;
    }

    public function createComment(int $userId, int $articleId, string $content): void
    {
        $this->commentModel->insert([
            'user_id' => $userId,
            'article_id' => $articleId,
            'content' => $content,
        ]);
    }

    public function createArticle(int $userId, int $categoryId, string $title, string $content, ?string $coverImage): ?array
    {
        $slug = $this->generateUniqueArticleSlug($title);

        $this->articleModel->insert([
            'user_id' => $userId,
            'category_id' => $categoryId,
            'title' => $title,
            'slug' => $slug,
            'content' => $content,
            'cover_image' => $coverImage !== null && $coverImage !== ''
                ? $coverImage
                : 'https://images.unsplash.com/photo-1517694712202-14dd9538aa97?auto=format&fit=crop&w=1280&q=80',
        ]);

        $newId = (int) $this->articleModel->getInsertID();

        return $this->articleModel->find($newId);
    }

    private function generateUniqueArticleSlug(string $title): string
    {
        $baseSlug = url_title($title, '-', true);
        $baseSlug = $baseSlug !== '' ? $baseSlug : 'artikel';
        $slug = $baseSlug;
        $counter = 2;

        while ($this->articleModel->where('slug', $slug)->countAllResults() > 0) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    /**
     * @return list<array{id:int,name:string,slug:string,articles_count:int}>
     */
    private function getCategoriesWithCount(): array
    {
        $rows = $this->db->table('categories c')
            ->select('c.id, c.name, c.slug, COUNT(a.id) AS articles_count')
            ->join('articles a', 'a.category_id = c.id', 'left')
            ->groupBy('c.id, c.name, c.slug')
            ->orderBy('c.name', 'ASC')
            ->get()
            ->getResultArray();

        return array_map(static function (array $row): array {
            return [
                'id' => (int) $row['id'],
                'name' => (string) $row['name'],
                'slug' => (string) $row['slug'],
                'articles_count' => (int) $row['articles_count'],
            ];
        }, $rows);
    }

    private function articleBaseBuilder(?int $currentUserId)
    {
        $likedExpr = '0';
        $bookmarkedExpr = '0';

        if ($currentUserId !== null) {
            $safeUserId = (int) $currentUserId;
            $likedExpr = "EXISTS(SELECT 1 FROM likes l2 WHERE l2.article_id = a.id AND l2.user_id = {$safeUserId})";
            $bookmarkedExpr = "EXISTS(SELECT 1 FROM bookmarks b2 WHERE b2.article_id = a.id AND b2.user_id = {$safeUserId})";
        }

        return $this->db->table('articles a')
            ->select(
                "a.id, a.user_id, a.category_id, a.title, a.slug, a.content, a.cover_image, a.created_at, " .
                "u.name AS author_name, c.name AS category_name, c.slug AS category_slug, " .
                "(SELECT COUNT(*) FROM likes l WHERE l.article_id = a.id) AS likes_count, " .
                "(SELECT COUNT(*) FROM comments cm WHERE cm.article_id = a.id) AS comments_count, " .
                "(SELECT COUNT(*) FROM bookmarks b WHERE b.article_id = a.id) AS bookmarks_count, " .
                "{$likedExpr} AS liked_by_current_user, {$bookmarkedExpr} AS bookmarked_by_current_user",
                false
            )
            ->join('users u', 'u.id = a.user_id')
            ->join('categories c', 'c.id = a.category_id');
    }

    private function mapArticle(array $row): array
    {
        $plainContent = trim(strip_tags((string) $row['content']));
        $wordCount = str_word_count($plainContent);
        $readingMinutes = max(1, (int) ceil($wordCount / 200));

        return [
            'id' => (int) $row['id'],
            'title' => (string) $row['title'],
            'slug' => (string) $row['slug'],
            'content' => (string) $row['content'],
            'cover_image' => (string) $row['cover_image'],
            'created_label' => $this->formatDateLabel((string) $row['created_at']),
            'reading_minutes' => $readingMinutes,
            'likes_count' => (int) $row['likes_count'],
            'comments_count' => (int) $row['comments_count'],
            'bookmarks_count' => (int) $row['bookmarks_count'],
            'liked_by_current_user' => (bool) $row['liked_by_current_user'],
            'bookmarked_by_current_user' => (bool) $row['bookmarked_by_current_user'],
            'author' => [
                'id' => (int) $row['user_id'],
                'name' => (string) $row['author_name'],
            ],
            'category' => [
                'id' => (int) $row['category_id'],
                'name' => (string) $row['category_name'],
                'slug' => (string) $row['category_slug'],
            ],
        ];
    }

    private function mapUser(array $row): array
    {
        return [
            'id' => (int) $row['id'],
            'name' => (string) $row['name'],
            'email' => (string) $row['email'],
        ];
    }

    private function formatDateLabel(string $dateTime): string
    {
        $timestamp = strtotime($dateTime);

        if ($timestamp === false) {
            return $dateTime;
        }

        return date('d M Y', $timestamp);
    }
}
