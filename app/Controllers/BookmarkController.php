<?php

namespace App\Controllers;

use App\Libraries\NeritaRepository;
use CodeIgniter\HTTP\RedirectResponse;

class BookmarkController extends BaseController
{
    public function index(): string|RedirectResponse
    {
        $userId = $this->getCurrentUserId();

        if ($userId === null) {
            return redirect()->to(site_url('masuk'))->with('error', 'Masuk dulu untuk melihat bookmark.');
        }

        $repository = new NeritaRepository();
        $data = $repository->getBookmarksPageData($userId);

        $data['pageTitle'] = 'Bookmark Saya | Nerita';

        return view('bookmarks/index', $data);
    }
}
