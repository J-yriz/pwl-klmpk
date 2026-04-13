<?php

namespace App\Controllers;

use App\Libraries\NeritaRepository;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\Files\UploadedFile;
use CodeIgniter\HTTP\RedirectResponse;

class CreatorController extends BaseController
{
    public function dashboard(): string|RedirectResponse
    {
        $userId = $this->getCurrentUserId();

        if ($userId === null) {
            return redirect()->to(site_url('masuk'))->with('error', 'Masuk dulu untuk membuka dashboard kreator.');
        }

        $repository = new NeritaRepository();
        $data = $repository->getDashboardPageData($userId);

        $data['pageTitle'] = 'Dashboard Kreator | Nerita';

        return view('creator/dashboard', $data);
    }

    public function editor(?string $slug = null): string|RedirectResponse
    {
        $userId = $this->getCurrentUserId();

        if ($userId === null) {
            return redirect()->to(site_url('masuk'))->with('error', 'Masuk dulu untuk menulis artikel.');
        }

        $repository = new NeritaRepository();
        $article = null;
        if ($slug !== null && $slug !== '') {
            $article = $repository->findUserArticleBySlug($userId, $slug);
            if ($article === null) {
                throw PageNotFoundException::forPageNotFound('Artikel tidak ditemukan.');
            }
        }
        $data = $repository->getEditorPageData($userId, $article);

        $data['pageTitle'] = 'Editor Artikel | Nerita';

        return view('creator/editor', $data);
    }

    public function store(): RedirectResponse
    {
        $userId = $this->getCurrentUserId();

        if ($userId === null) {
            return redirect()->to(site_url('masuk'))->with('error', 'Masuk dulu untuk mempublikasikan artikel.');
        }

        $saveAction = (string) $this->request->getPost('save_action');
        if (! in_array($saveAction, ['publish', 'draft'], true)) {
            $saveAction = 'publish';
        }

        $wantsDraft = $saveAction === 'draft';

        $rules = [
            'category_id' => 'required|integer|is_not_unique[categories.id]',
        ];

        if ($wantsDraft) {
            $rules['title'] = 'required|min_length[3]|max_length[200]';
            $rules['content'] = 'permit_empty|max_length[16777215]';
        } else {
            $rules['title'] = 'required|min_length[5]|max_length[200]';
            $rules['content'] = 'required|min_length[30]';
        }

        /** @var UploadedFile|null $coverFile */
        $coverFile = $this->request->getFile('cover_image');
        if ($coverFile !== null && $coverFile->getError() !== UPLOAD_ERR_NO_FILE) {
            $rules['cover_image'] = 'uploaded[cover_image]|max_size[cover_image,5120]|is_image[cover_image]';
        }

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $coverImageUrl = $this->storeCoverUpload($coverFile);
        $articleId = (int) $this->request->getPost('article_id');

        $repository = new NeritaRepository();
        $targetStatus = $wantsDraft ? NeritaRepository::STATUS_DRAFT : NeritaRepository::STATUS_PUBLISHED;

        $existingRow = null;
        if ($articleId > 0) {
            $existingRow = $repository->findUserArticleById($userId, $articleId);
            if ($existingRow === null) {
                return redirect()->back()->withInput()->with('error', 'Artikel tidak ditemukan.');
            }
        }

        if ($articleId > 0) {
            if (($existingRow['status'] ?? NeritaRepository::STATUS_PUBLISHED) === NeritaRepository::STATUS_PUBLISHED && $wantsDraft) {
                return redirect()->back()->withInput()->with('error', 'Artikel terpublikasi tidak bisa disimpan sebagai draft.');
            }

            $article = $repository->updateArticle(
                $articleId,
                $userId,
                (int) $this->request->getPost('category_id'),
                trim((string) $this->request->getPost('title')),
                (string) $this->request->getPost('content'),
                $coverImageUrl,
                $targetStatus,
            );
        } else {
            $article = $repository->createArticle(
                $userId,
                (int) $this->request->getPost('category_id'),
                trim((string) $this->request->getPost('title')),
                (string) $this->request->getPost('content'),
                $coverImageUrl,
                $targetStatus,
            );
        }

        if ($article === null) {
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan artikel. Coba lagi.');
        }

        if ($targetStatus === NeritaRepository::STATUS_DRAFT) {
            $message = $articleId > 0 ? 'Draft diperbarui.' : 'Draft disimpan.';

            return redirect()->to(site_url('kreator/editor/' . $article['slug']))->with('success', $message);
        }

        $message = $articleId > 0 ? 'Artikel berhasil diperbarui.' : 'Artikel berhasil dipublikasikan.';

        return redirect()->to(site_url('artikel/' . $article['slug']))->with('success', $message);
    }

    private function storeCoverUpload(?UploadedFile $coverFile): ?string
    {
        if ($coverFile === null || $coverFile->getError() === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        if (! $coverFile->isValid() || $coverFile->hasMoved()) {
            return null;
        }

        // Simpan di writable (bukan public): bind mount Docker sering membuat public/ tidak bisa ditulis www-data.
        $targetDir = WRITEPATH . 'uploads' . DIRECTORY_SEPARATOR . 'covers';
        if (! is_dir($targetDir) && ! mkdir($targetDir, 0755, true) && ! is_dir($targetDir)) {
            return null;
        }

        $newName = $coverFile->getRandomName();
        $coverFile->move($targetDir, $newName);

        return base_url('uploads/covers/' . $newName);
    }
}
