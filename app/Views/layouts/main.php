<!doctype html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($pageTitle ?? 'Nerita') ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:wght@500;700;800&family=JetBrains+Mono:wght@400;600&family=Manrope:wght@400;500;700&display=swap" rel="stylesheet">
    <?= $this->renderSection('prepend_styles') ?>
    <link rel="stylesheet" href="<?= base_url('css/output.css?v=' . (is_file(FCPATH . 'css/output.css') ? (string) filemtime(FCPATH . 'css/output.css') : (string) time())) ?>">
</head>
<body class="flex min-h-screen flex-col text-neo-black" style="background-color: #e2e4e7;">
    <div aria-hidden="true" class="pointer-events-none fixed inset-0 -z-10 overflow-hidden">
        <div class="absolute -left-8 top-24 h-24 w-24 border-4 border-neo-black bg-neo-blue shadow-neo-sm opacity-70"></div>
        <div class="absolute right-8 top-40 h-16 w-16 border-4 border-neo-black bg-neo-mint shadow-neo-sm opacity-75"></div>
        <div class="absolute bottom-16 left-1/2 h-12 w-12 -translate-x-1/2 border-4 border-neo-black bg-neo-red shadow-neo-sm opacity-70"></div>
    </div>

    <?= view('partials/navbar', [
        'current_user' => $current_user ?? null,
        'nav_categories' => $nav_categories ?? [],
    ]) ?>

    <main class="mx-auto w-full max-w-7xl flex-1 px-4 py-8 md:px-6 md:py-10">
        <?php if (session()->has('error')): ?>
            <div class="neo-alert neo-alert-danger mb-4"><?= esc((string) session('error')) ?></div>
        <?php endif; ?>

        <?php if (session()->has('success')): ?>
            <div class="neo-alert neo-alert-success mb-4"><?= esc((string) session('success')) ?></div>
        <?php endif; ?>

        <?php if (is_array(session('errors'))): ?>
            <?php foreach (session('errors') as $errorMessage): ?>
                <div class="neo-alert neo-alert-danger mb-4"><?= esc((string) $errorMessage) ?></div>
            <?php endforeach; ?>
        <?php endif; ?>

        <?= $this->renderSection('content') ?>
    </main>

    <?= view('partials/footer') ?>
    <?= $this->renderSection('scripts') ?>
</body>
</html>
