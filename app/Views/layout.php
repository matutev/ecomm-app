<!DOCTYPE html>
<html lang="es" class="h-100">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ecomm App</title>
    <link href="<?= base_url('vendor/twbs/bootstrap/dist/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('public/assets/css/styles.css'); ?>" rel="stylesheet">
</head>

<body class="d-flex flex-column h-100">

    <!-- Begin page content -->
    <main class="flex-shrink-0">
        <div class="container">
            <?= $this->include('users/user'); ?>
            <?= $this->renderSection('content'); ?>
        </div>
    </main>

    <footer class="footer mt-auto py-3 bg-body-tertiary">
        <div class="container">
            <span class="text-body-secondary"> 2024 | Ecomm App</span>
        </div>
    </footer>

    <script src="<?= base_url('vendor/components/jquery/jquery.min.js') ?>"></script>
    <script src="<?= base_url('vendor/twbs/bootstrap/dist/js/bootstrap.bundle.min.js') ?>"></script>
    <script src="<?= base_url('public/assets/js/base.js'); ?>"></script>
    <script src="<?= service('router')->controllerName() == '\App\Controllers\Users' ? base_url('public/assets/js/users.js'): base_url('public/assets/js/productos.js') ; ?>"></script>

</body>

</html>