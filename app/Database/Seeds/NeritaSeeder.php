<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class NeritaSeeder extends Seeder
{
    public function run(): void
    {
        $db = $this->db;

        $db->query('SET FOREIGN_KEY_CHECKS = 0');
        $db->table('bookmarks')->truncate();
        $db->table('likes')->truncate();
        $db->table('comments')->truncate();
        $db->table('articles')->truncate();
        $db->table('categories')->truncate();
        $db->table('users')->truncate();
        $db->query('SET FOREIGN_KEY_CHECKS = 1');

        $now = date('Y-m-d H:i:s');

        $db->table('users')->insertBatch([
            [
                'id' => 1,
                'name' => 'Alya Rahman',
                'email' => 'creator@nerita.app',
                'password' => password_hash('password123', PASSWORD_DEFAULT),
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 2,
                'name' => 'Rina Putri',
                'email' => 'reader@nerita.app',
                'password' => password_hash('password123', PASSWORD_DEFAULT),
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 3,
                'name' => 'Budi Saputra',
                'email' => 'budi@nerita.app',
                'password' => password_hash('password123', PASSWORD_DEFAULT),
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        $db->table('categories')->insertBatch([
            [
                'id' => 1,
                'name' => 'Teknologi',
                'slug' => 'teknologi',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 2,
                'name' => 'Desain Produk',
                'slug' => 'desain-produk',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 3,
                'name' => 'Produktivitas',
                'slug' => 'produktivitas',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 4,
                'name' => 'Karier',
                'slug' => 'karier',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        $articleContentA = '<p>Workflow menulis yang baik bukan soal alat paling mahal, tetapi struktur paling disiplin.</p><h2>Prinsip Dasar</h2><p>Bagi proses menjadi riset, outline, drafting, lalu editing terpisah agar konteks tidak campur.</p><pre><code>// contoh pseudo workflow\ncollectReferences();\nwriteOutline();\ndraftFast();\neditSlow();</code></pre>';
        $articleContentB = '<p>Neobrutalism menolak efek palsu. Gunakan kontras kuat, border tebal, dan shadow keras yang fungsional.</p><h2>Checklist UI</h2><p>Pastikan hierarchy visual terlihat dalam 3 detik pertama ketika halaman dibuka.</p>';
        $articleContentC = '<p>Reader retention naik saat artikel punya ritme: hook, konteks, inti, lalu closure.</p><blockquote>Konten bagus harus terasa ringan, bukan dangkal.</blockquote><p>Gunakan heading pendek dan paragraf yang tidak panjang.</p>';

        $db->table('articles')->insertBatch([
            [
                'id' => 1,
                'user_id' => 1,
                'category_id' => 1,
                'title' => 'Menyusun Pipeline Menulis untuk Tim Konten Kecil',
                'slug' => 'pipeline-menulis-tim-konten-kecil',
                'content' => $articleContentA,
                'cover_image' => 'https://images.unsplash.com/photo-1515879218367-8466d910aaa4?auto=format&fit=crop&w=1280&q=80',
                'status' => 'published',
                'created_at' => date('Y-m-d H:i:s', strtotime('-5 days')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-5 days')),
            ],
            [
                'id' => 2,
                'user_id' => 1,
                'category_id' => 2,
                'title' => 'Mendesain Landing Page Neobrutalism yang Tetap Nyaman Dibaca',
                'slug' => 'landing-page-neobrutalism-nyaman-dibaca',
                'content' => $articleContentB,
                'cover_image' => 'https://images.unsplash.com/photo-1558655146-d09347e92766?auto=format&fit=crop&w=1280&q=80',
                'status' => 'published',
                'created_at' => date('Y-m-d H:i:s', strtotime('-4 days')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-4 days')),
            ],
            [
                'id' => 3,
                'user_id' => 3,
                'category_id' => 3,
                'title' => 'Strategi Deep Work 90 Menit untuk Developer Product',
                'slug' => 'strategi-deep-work-90-menit',
                'content' => $articleContentC,
                'cover_image' => 'https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?auto=format&fit=crop&w=1280&q=80',
                'status' => 'published',
                'created_at' => date('Y-m-d H:i:s', strtotime('-3 days')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-3 days')),
            ],
            [
                'id' => 4,
                'user_id' => 3,
                'category_id' => 4,
                'title' => 'Cara Bangun Portofolio yang Relevan untuk Posisi Product Engineer',
                'slug' => 'cara-bangun-portofolio-product-engineer',
                'content' => $articleContentA,
                'cover_image' => 'https://images.unsplash.com/photo-1545239351-1141bd82e8a6?auto=format&fit=crop&w=1280&q=80',
                'status' => 'published',
                'created_at' => date('Y-m-d H:i:s', strtotime('-2 days')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-2 days')),
            ],
            [
                'id' => 5,
                'user_id' => 1,
                'category_id' => 1,
                'title' => 'Menyusun Struktur Artikel Teknis Supaya Tidak Membosankan',
                'slug' => 'struktur-artikel-teknis-tidak-membosankan',
                'content' => $articleContentC,
                'cover_image' => 'https://images.unsplash.com/photo-1498050108023-c5249f4df085?auto=format&fit=crop&w=1280&q=80',
                'status' => 'published',
                'created_at' => date('Y-m-d H:i:s', strtotime('-1 days')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-1 days')),
            ],
        ]);

        $db->table('comments')->insertBatch([
            [
                'user_id' => 2,
                'article_id' => 1,
                'content' => 'Strukturnya jelas dan langsung bisa diterapkan untuk tim kecil.',
                'created_at' => date('Y-m-d H:i:s', strtotime('-4 days')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-4 days')),
            ],
            [
                'user_id' => 3,
                'article_id' => 1,
                'content' => 'Bagian pseudo workflow sangat membantu untuk briefing penulis baru.',
                'created_at' => date('Y-m-d H:i:s', strtotime('-3 days')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-3 days')),
            ],
            [
                'user_id' => 2,
                'article_id' => 2,
                'content' => 'Setuju, kontras kuat bisa tetap nyaman selama spacing dijaga.',
                'created_at' => date('Y-m-d H:i:s', strtotime('-2 days')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-2 days')),
            ],
            [
                'user_id' => 1,
                'article_id' => 3,
                'content' => 'Teknik 90 menit ini efektif kalau notifikasi dimatikan total.',
                'created_at' => date('Y-m-d H:i:s', strtotime('-1 days')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-1 days')),
            ],
        ]);

        $db->table('likes')->insertBatch([
            ['user_id' => 2, 'article_id' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['user_id' => 3, 'article_id' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['user_id' => 2, 'article_id' => 2, 'created_at' => $now, 'updated_at' => $now],
            ['user_id' => 1, 'article_id' => 3, 'created_at' => $now, 'updated_at' => $now],
            ['user_id' => 2, 'article_id' => 3, 'created_at' => $now, 'updated_at' => $now],
            ['user_id' => 1, 'article_id' => 4, 'created_at' => $now, 'updated_at' => $now],
            ['user_id' => 2, 'article_id' => 5, 'created_at' => $now, 'updated_at' => $now],
        ]);

        $db->table('bookmarks')->insertBatch([
            ['user_id' => 2, 'article_id' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['user_id' => 2, 'article_id' => 2, 'created_at' => $now, 'updated_at' => $now],
            ['user_id' => 2, 'article_id' => 5, 'created_at' => $now, 'updated_at' => $now],
            ['user_id' => 1, 'article_id' => 3, 'created_at' => $now, 'updated_at' => $now],
        ]);
    }
}
