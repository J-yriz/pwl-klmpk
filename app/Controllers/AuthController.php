<?php

namespace App\Controllers;

use App\Libraries\NeritaRepository;
use App\Models\UserModel;
use CodeIgniter\HTTP\RedirectResponse;

class AuthController extends BaseController
{
    public function login(): string|RedirectResponse
    {
        if ($this->getCurrentUserId() !== null) {
            return redirect()->to(site_url(''));
        }

        return view('auth/login', [
            'pageTitle' => 'Masuk | Nerita',
        ]);
    }

    public function register(): string|RedirectResponse
    {
        if ($this->getCurrentUserId() !== null) {
            return redirect()->to(site_url(''));
        }

        return view('auth/register', [
            'pageTitle' => 'Daftar | Nerita',
        ]);
    }

    public function attemptLogin(): RedirectResponse
    {
        $rules = [
            'email' => 'required|valid_email',
            'password' => 'required|min_length[8]|max_length[72]',
        ];

        if (! $this->validateData($this->request->getPost(), $rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $email = strtolower(trim((string) $this->request->getPost('email')));
        $password = (string) $this->request->getPost('password');

        $user = (new UserModel())->where('email', $email)->first();

        if ($user === null) {
            return redirect()->back()->withInput()->with('error', 'Email atau password salah.');
        }

        if (! password_verify($password, (string) $user['password'])) {
            return redirect()->back()->withInput()->with('error', 'Email atau password salah.');
        }

        $this->session->set([
            'user_id' => (int) $user['id'],
        ]);

        return redirect()->to(site_url(''))->with('success', 'Berhasil masuk. Selamat datang kembali.');
    }

    public function registerUser(): RedirectResponse
    {
        $rules = [
            'name' => 'required|min_length[3]|max_length[120]',
            'email' => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[8]|max_length[72]',
            'password_confirm' => 'required|matches[password]',
        ];

        if (! $this->validateData($this->request->getPost(), $rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $name = trim((string) $this->request->getPost('name'));
        $email = strtolower(trim((string) $this->request->getPost('email')));
        $passwordHash = password_hash((string) $this->request->getPost('password'), PASSWORD_DEFAULT);

        $repository = new NeritaRepository();
        $newUserId = $repository->createUser($name, $email, $passwordHash);

        $this->session->set([
            'user_id' => $newUserId,
        ]);

        return redirect()->to(site_url(''))->with('success', 'Akun berhasil dibuat dan kamu sudah login.');
    }

    public function logout(): RedirectResponse
    {
        $this->session->remove('user_id');

        return redirect()->to(site_url(''))->with('success', 'Sesi login sudah berakhir.');
    }
}
