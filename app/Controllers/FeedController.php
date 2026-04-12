<?php

namespace App\Controllers;

use App\Libraries\NeritaRepository;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\RedirectResponse;

class FeedController extends BaseController
{
    public function index(): string
    {
        $repository = new NeritaRepository();
        $data = $repository->getHomepageData($this->getCurrentUserId());

        $data['pageTitle'] = 'Nerita | Baca Tanpa Distraksi';

        return view('home/index', $data);
    }

    public function category(string $slug): string
    {
        $repository = new NeritaRepository();
        $data = $repository->getCategoryPageData($slug, $this->getCurrentUserId());

        if ($data === null) {
            throw PageNotFoundException::forPageNotFound('Kategori tidak ditemukan.');
        }

        $data['pageTitle'] = 'Kategori ' . $data['category']['name'] . ' | Nerita';

        return view('home/category', $data);
    }

    public function show(string $slug): string
    {
        $repository = new NeritaRepository();
        $data = $repository->getArticlePageData($slug, $this->getCurrentUserId());

        if ($data === null) {
            throw PageNotFoundException::forPageNotFound('Artikel tidak ditemukan.');
        }

        $data['pageTitle'] = $data['article']['title'] . ' | Nerita';

        return view('articles/show', $data);
    }

    public function storeComment(string $slug): RedirectResponse
    {
        $userId = $this->getCurrentUserId();

        if ($userId === null) {
            return redirect()->to(site_url('masuk'))->with('error', 'Masuk dulu untuk menulis komentar.');
        }

        $repository = new NeritaRepository();
        $article = $repository->findArticleBySlug($slug);

        if ($article === null) {
            throw PageNotFoundException::forPageNotFound('Artikel tidak ditemukan.');
        }

        $rules = [
            'content' => 'required|min_length[3]|max_length[2000]',
        ];

        if (! $this->validateData($this->request->getPost(), $rules)) {
            return redirect()
                ->to(site_url('artikel/' . $slug) . '#diskusi')
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $repository->createComment(
            $userId,
            (int) $article['id'],
            trim((string) $this->request->getPost('content')),
        );

        return redirect()->to(site_url('artikel/' . $slug) . '#diskusi')->with('success', 'Komentar berhasil dikirim.');
    }

    public function toggleLike(string $slug): RedirectResponse
    {
        $userId = $this->getCurrentUserId();

        if ($userId === null) {
            return redirect()->to(site_url('masuk'))->with('error', 'Masuk dulu untuk menyukai artikel.');
        }

        $repository = new NeritaRepository();
        $article = $repository->findArticleBySlug($slug);

        if ($article === null) {
            throw PageNotFoundException::forPageNotFound('Artikel tidak ditemukan.');
        }

        $isLiked = $repository->toggleLike($userId, (int) $article['id']);

        return redirect()->back()->with('success', $isLiked ? 'Artikel disukai.' : 'Like dibatalkan.');
    }

    public function toggleBookmark(string $slug): RedirectResponse
    {
        $userId = $this->getCurrentUserId();

        if ($userId === null) {
            return redirect()->to(site_url('masuk'))->with('error', 'Masuk dulu untuk menyimpan artikel.');
        }

        $repository = new NeritaRepository();
        $article = $repository->findArticleBySlug($slug);

        if ($article === null) {
            throw PageNotFoundException::forPageNotFound('Artikel tidak ditemukan.');
        }

        $isBookmarked = $repository->toggleBookmark($userId, (int) $article['id']);

        return redirect()->back()->with('success', $isBookmarked ? 'Artikel disimpan ke bookmark.' : 'Bookmark dilepas.');
    }
}
