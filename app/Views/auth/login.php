<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<section class="mx-auto max-w-2xl neo-shell p-5 md:p-8">
    <div class="mb-6">
        <span class="neo-chip">Autentikasi</span>
        <h1 class="neo-title mt-3 text-3xl md:text-4xl">Masuk ke akun Nerita</h1>
        <p class="mt-2 text-sm">Gunakan email dan password untuk mengakses fitur interaksi pembaca serta dashboard kreator.</p>
    </div>

    <form action="<?= site_url('masuk') ?>" method="post" class="space-y-4">
        <?= csrf_field() ?>
        <div>
            <label class="neo-label" for="email">Email</label>
            <input id="email" name="email" type="email" class="neo-input" placeholder="nama@domain.com" value="<?= old('email') ?>" required>
        </div>

        <div>
            <label class="neo-label" for="password">Password</label>
            <input id="password" name="password" type="password" class="neo-input" placeholder="Minimal 8 karakter" required>
        </div>

        <button type="submit" class="neo-btn-primary w-full">Masuk</button>
    </form>

    <div class="neo-card mt-5 p-3 text-xs">
        <p class="font-mono uppercase">Akun demo:</p>
        <p class="mt-1">creator@nerita.app / password123</p>
        <p>reader@nerita.app / password123</p>
    </div>

    <p class="mt-5 text-sm">
        Belum punya akun?
        <a href="<?= site_url('daftar') ?>" class="neo-link">Daftar sekarang</a>
    </p>
</section>
<?= $this->endSection() ?>
