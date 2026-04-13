<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddArticleStatus extends Migration
{
    public function up(): void
    {
        $this->forge->addColumn('articles', [
            'status' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'default'    => 'published',
                'after'      => 'cover_image',
            ],
        ]);

        $this->db->table('articles')->update(['status' => 'published']);
    }

    public function down(): void
    {
        $this->forge->dropColumn('articles', 'status');
    }
}
