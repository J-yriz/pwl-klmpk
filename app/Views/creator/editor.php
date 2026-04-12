<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<section class="neo-shell p-5 md:p-8">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <span class="neo-chip">Editor Artikel</span>
            <h1 class="neo-title mt-3 text-3xl md:text-4xl">Tulis artikel baru</h1>
            <p class="mt-2 text-sm">Editor ini memakai WYSIWYG dengan dukungan teks, embed video, gambar, dan code block.</p>
        </div>
        <span class="neo-btn-ghost">Kreator: <?= esc($creator['name'] ?? '-') ?></span>
    </div>
</section>

<section class="mt-8 grid gap-6 lg:grid-cols-[1.4fr_1fr]">
    <div class="neo-shell p-5 md:p-6">
        <form action="<?= site_url('kreator/editor') ?>" method="post" class="space-y-4" id="article-editor-form">
            <?= csrf_field() ?>
            <div>
                <label class="neo-label" for="article-title">Judul Artikel</label>
                <input id="article-title" name="title" type="text" class="neo-input" value="<?= esc($draft_example['title']) ?>" required>
            </div>

            <div>
                <label class="neo-label" for="article-cover">Cover Image</label>
                <input id="article-cover" name="cover_image" type="url" class="neo-input" value="<?= esc($draft_example['cover_image']) ?>">
            </div>

            <div>
                <label class="neo-label" for="article-category">Kategori</label>
                <?php $selectedCategoryId = (int) old('category_id', $categories[0]['id'] ?? 0); ?>
                <select id="article-category" name="category_id" class="neo-input" required>
                    <?php foreach ($categories as $category): ?>
                        <?php $isSelected = $selectedCategoryId === (int) $category['id']; ?>
                        <option value="<?= esc((string) $category['id']) ?>" <?= $isSelected ? 'selected' : '' ?>><?= esc($category['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label class="neo-label" for="editor">Konten</label>
                <div id="editor" class="min-h-80 border-3 border-neo-black bg-neo-white"></div>
                <input type="hidden" id="editor-content" name="content" value="<?= esc($draft_example['content']) ?>">
            </div>

            <div class="flex flex-wrap gap-2">
                <button type="submit" class="neo-btn-primary">Publikasi</button>
                <button type="button" class="neo-btn-ghost">Simpan Draft</button>
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
<link href="https://cdn.jsdelivr.net/npm/quill@1.3.7/dist/quill.snow.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/quill@1.3.7/dist/quill.min.js"></script>
<script>
    const quill = new window.Quill('#editor', {
        theme: 'snow',
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

    coverInput.addEventListener('input', () => {
        if (coverInput.value.trim() !== '') {
            coverPreview.src = coverInput.value;
        }
    });

    quill.root.innerHTML = <?= json_encode($draft_example['content']) ?>;
</script>
<?= $this->endSection() ?>
