<?= $this->extend('layout'); ?>

<?= $this->section('content'); ?>

<h3 class="my-3 text-center mt-5" id="titulo">Login</h3>

<div id="logear">
    <div class="col-6 mx-auto">
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text" id="basic-addon1">@</span>
            </div>
            <input type="text" class="form-control" id="user" placeholder="Usuario" aria-label="Usuario" aria-describedby="basic-addon1" autocomplete="off" required>
        </div>
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text" id="basic-addon2">#</span>
            </div>
            <input type="password" class="form-control" id="pass" placeholder="Contraseña" aria-label="Contraseña" aria-describedby="basic-addon2" autocomplete="off" required>
        </div>
        <div class="alert alert-danger" id="dialog"></div>
        <div class="text-center">              
            <button type="button" class="btn btn-primary" id="btn-login" data-url="<?= base_url('productos'); ?>">ENTRAR</button>
        </div>
    </div>
</div>

<?= csrf_field('csrf') ?>

<?= $this->endSection(); ?>