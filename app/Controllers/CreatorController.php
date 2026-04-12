<?php

namespace App\Controllers;

use App\Libraries\NeritaRepository;
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

    public function editor(): string|RedirectResponse
    {
        $userId = $this->getCurrentUserId();

        if ($userId === null) {
            return redirect()->to(site_url('masuk'))->with('error', 'Masuk dulu untuk menulis artikel.');
        }

        $repository = new NeritaRepository();
        $data = $repository->getEditorPageData($userId);

        $data['pageTitle'] = 'Editor Artikel | Nerita';

        return view('creator/editor', $data);
    }

    public function store(): RedirectResponse
    {
        $userId = $this->getCurrentUserId();

        if ($userId === null) {
            return redirect()->to(site_url('masuk'))->with('error', 'Masuk dulu untuk mempublikasikan artikel.');
        }

        $rules = [
            'title' => 'required|min_length[5]|max_length[200]',
            'category_id' => 'required|integer|is_not_unique[categories.id]',
            'cover_image' => 'permit_empty|valid_url',
            'content' => 'required|min_length[30]',
        ];

        if (! $this->validateData($this->request->getPost(), $rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $repository = new NeritaRepository();
        $article = $repository->createArticle(
            $userId,
            (int) $this->request->getPost('category_id'),
            trim((string) $this->request->getPost('title')),
            (string) $this->request->getPost('content'),
            trim((string) $this->request->getPost('cover_image')),
        );

        if ($article === null) {
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan artikel. Coba lagi.');
        }

        return redirect()->to(site_url('artikel/' . $article['slug']))->with('success', 'Artikel berhasil dipublikasikan.');
    }
}
