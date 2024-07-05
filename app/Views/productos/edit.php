<?= $this->extend('layout'); ?>

<?= $this->section('content'); ?>

<h3 class="my-3">Editar Producto</h3>

<div class="alert alert-danger" id="dialog"></div>

<div class="col-md-8">
    <label for="title" class="form-label">Titulo</label>
    <input type="text" class="form-control" id="title" name="title" value="<?= $data['title'] ?>" required>
</div>

<div class="col-md-6 mb-4">
    <label for="price" class="form-label">Precio</label>
    <input type="text" class="form-control" id="price" name="price" value="<?= $data['price'] ?>" required>
</div>

<div class="col-12">
    <button type="button" class="btn btn-primary" id="update-product" data-id-product="<?= $data['id'] ?>">Guardar</button>
    <a href="<?= base_url('productos'); ?>" class="btn btn-secondary" id="btnBack">Volver</a>
</div>
<?= csrf_field('csrf') ?>

<?= $this->endSection(); ?>