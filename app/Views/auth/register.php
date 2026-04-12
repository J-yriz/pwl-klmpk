<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<section class="mx-auto max-w-2xl neo-shell p-5 md:p-8">
    <div class="mb-6">
        <span class="neo-chip">Registrasi</span>
        <h1 class="neo-title mt-3 text-3xl md:text-4xl">Buat akun baru</h1>
        <p class="mt-2 text-sm">Daftar sebagai pembaca atau kreator untuk mulai menulis dan berinteraksi di Nerita.</p>
    </div>

    <form action="<?= site_url('daftar') ?>" method="post" class="space-y-4">
        <?= csrf_field() ?>
        <div>
            <label class="neo-label" for="name">Nama</label>
            <input id="name" name="name" type="text" class="neo-input" placeholder="Nama lengkap" value="<?= old('name') ?>" required>
        </div>

        <div>
            <label class="neo-label" for="register-email">Email</label>
            <input id="register-email" name="email" type="email" class="neo-input" placeholder="nama@domain.com" value="<?= old('email') ?>" required>
        </div>

        <div>
            <label class="neo-label" for="register-password">Password</label>
            <input id="register-password" name="password" type="password" class="neo-input" placeholder="Minimal 8 karakter" required>
        </div>

        <div>
            <label class="neo-label" for="register-password-confirm">Konfirmasi Password</label>
            <input id="register-password-confirm" name="password_confirm" type="password" class="neo-input" placeholder="Ulangi password" required>
        </div>

        <button type="submit" class="neo-btn-primary w-full">Daftar</button>
    </form>

    <p class="mt-5 text-sm">
        Sudah punya akun?
        <a href="<?= site_url('masuk') ?>" class="neo-link">Masuk di sini</a>
    </p>
</section>
<?= $this->endSection() ?>
