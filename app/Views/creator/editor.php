<?= $this->extend('layouts/main') ?>

<?= $this->section('prepend_styles') ?>
<link href="https://cdn.jsdelivr.net/npm/quill@1.3.7/dist/quill.snow.css" rel="stylesheet">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php $editorMode = $editor_mode ?? 'new'; ?>
<section class="neo-shell p-5 md:p-8">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <span class="neo-chip"><?= $editorMode === 'draft' ? 'Editor Draft' : 'Editor Artikel' ?></span>
            <h1 class="neo-title mt-3 text-3xl md:text-4xl"><?php
                if ($editorMode === 'new') {
                    echo 'Tulis artikel baru';
                } elseif ($editorMode === 'draft') {
                    echo 'Edit draft';
                } else {
                    echo 'Edit artikel';
                }
            ?></h1>
            <p class="mt-2 text-sm">Susun artikel di sini: format teks, sisipkan gambar atau video, dan tambahkan blok kode jika diperlukan.</p>
        </div>
        <span class="neo-btn-ghost">Kreator: <?= esc($creator['name'] ?? '-') ?></span>
    </div>
</section>

<section class="mt-8 grid gap-6 lg:grid-cols-[1.4fr_1fr]">
    <div class="neo-shell p-5 md:p-6">
        <form action="<?= site_url('kreator/editor') ?>" method="post" enctype="multipart/form-data" class="space-y-4" id="article-editor-form">
            <?= csrf_field() ?>
            <input type="hidden" name="article_id" value="<?= esc((string) ($draft_example['id'] ?? '')) ?>">
            <input type="hidden" name="article_status" value="<?= esc((string) ($draft_example['status'] ?? '')) ?>">
            <div>
                <label class="neo-label" for="article-title">Judul Artikel</label>
                <input id="article-title" name="title" type="text" class="neo-input" value="<?= esc($draft_example['title']) ?>" required>
            </div>

            <div>
                <label class="neo-label" for="article-cover">Cover Image</label>
                <input id="article-cover" name="cover_image" type="file" accept="image/jpeg,image/png,image/gif,image/webp" class="neo-input file:mr-3 file:rounded file:border-3 file:border-neo-black file:bg-neo-white file:px-3 file:py-1 file:font-mono file:text-sm">
                <p class="mt-1 text-xs text-gray-600">Opsional. JPG, PNG, GIF, atau WebP, maks. 5 MB. Tanpa file, dipakai gambar default.</p>
            </div>

            <div>
                <label class="neo-label" for="article-category">Kategori</label>
                <?php $selectedCategoryId = (int) ($draft_example['category_id'] ?? ($categories[0]['id'] ?? 0)); ?>
                <select id="article-category" name="category_id" class="neo-input" required>
                    <?php foreach ($categories as $category): ?>
                        <?php $isSelected = $selectedCategoryId === (int) $category['id']; ?>
                        <option value="<?= esc((string) $category['id']) ?>" <?= $isSelected ? 'selected' : '' ?>><?= esc($category['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label class="neo-label" for="editor">Konten</label>
                <div class="neo-quill-wrap min-h-80">
                    <div id="editor"></div>
                </div>
                <input type="hidden" id="editor-content" name="content" value="<?= esc($draft_example['content']) ?>">
            </div>

            <div class="flex flex-wrap gap-2">
                <?php if ($editorMode === 'new'): ?>
                    <button type="submit" name="save_action" value="publish" class="neo-btn-primary">Publikasi</button>
                    <button type="submit" name="save_action" value="draft" class="neo-btn-ghost">Simpan Draft</button>
                <?php elseif ($editorMode === 'draft'): ?>
                    <button type="submit" name="save_action" value="draft" class="neo-btn-primary">Perbarui Draft</button>
                    <button type="submit" name="save_action" value="publish" class="neo-btn-accent">Publikasikan</button>
                <?php else: ?>
                    <button type="submit" name="save_action" value="publish" class="neo-btn-primary">Update Artikel</button>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <aside class="space-y-4">
        <div class="neo-card p-4">
            <h2 class="neo-title text-xl">Checklist Artikel</h2>
            <ul class="mt-3 space-y-2 text-sm">
                <li class="neo-card p-2">Judul jelas dan spesifik</li>
                <li class="neo-card p-2">Pilih kategori sesuai topik</li>
                <li class="neo-card p-2">Gunakan heading untuk struktur</li>
                <li class="neo-card p-2">Tambahkan code snippet jika perlu</li>
            </ul>
        </div>

        <div class="neo-card p-4">
            <h2 class="neo-title text-xl">Preview Cover</h2>
            <img src="<?= esc($draft_example['cover_image']) ?>" alt="Preview cover" class="mt-3 h-48 w-full border-3 border-neo-black object-cover" id="cover-preview">
        </div>
    </aside>
</section>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/quill@1.3.7/dist/quill.min.js"></script>
<script>
    const quill = new window.Quill('#editor', {
        theme: 'snow',
        placeholder: 'Tulis artikelmu di sini...',
        modules: {
            toolbar: [
                [{ header: [1, 2, 3, false] }],
                ['bold', 'italic', 'underline', 'blockquote'],
                [{ list: 'ordered' }, { list: 'bullet' }],
                ['link', 'image', 'video', 'code-block'],
                ['clean']
            ]
        }
    });

    const editorForm = document.getElementById('article-editor-form');
    const editorContentInput = document.getElementById('editor-content');
    const coverInput = document.getElementById('article-cover');
    const coverPreview = document.getElementById('cover-preview');

    editorForm.addEventListener('submit', () => {
        editorContentInput.value = quill.root.innerHTML;
    });

    coverInput.addEventListener('change', () => {
        const file = coverInput.files && coverInput.files[0];
        if (!file) {
            return;
        }
        const reader = new FileReader();
        reader.onload = (e) => {
            if (e.target && typeof e.target.result === 'string') {
                coverPreview.src = e.target.result;
            }
        };
        reader.readAsDataURL(file);
    });

    const initialContent = <?= json_encode($draft_example['content']) ?>;
    if (initialContent) {
        quill.root.innerHTML = initialContent;
    }
</script>
<?= $this->endSection() ?>
