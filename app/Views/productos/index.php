<?= $this->extend('layout'); ?>

<?= $this->section('content'); ?>

<h3 class="my-3" id="titulo">Productos</h3>

<?= $this->include('errors/alert'); ?>

<div class="row">
   <div class="col-9">
        <?= $this->include('search/search'); ?>
   </div>
   <div class="col-3">
        <a href="<?= base_url('productos/new'); ?>" class="btn btn-success float-end">Agregar</a>
   </div>
</div>

<table class="table table-hover table-bordered my-3" aria-describedby="titulo">
    <thead class="table-dark">
        <tr>
            <th scope="col">Id</th>
            <th scope="col">Titulo</th>
            <th scope="col">Precio</th>
            <th scope="col">Acciones</th>
        </tr>
    </thead>

    <tbody id="tbodyProducts">
    <?php foreach($data as $product) : ?>
        <tr id="productRow<?= $product['id']; ?>">
            <td><?= $product['id']; ?></td>
            <td><?= $product['title']; ?></td>
            <td><?= $product['price']; ?></td>
            <td>
                <a href="<?= base_url('productos/'.$product['id'].'/edit') ?>" class="btn btn-primary btn-sm me-2">Editar</a>

                <button type="button" class="btn btn-danger btn-sm btn-delete" data-bs-toggle="modal"
                    data-bs-target="#deleteModal" data-id-product="<?= $product['id'] ?>">Eliminar</button>
            </td>
        </tr>
    <?php endforeach; ?>
    <?php if(!$data) : ?> <tr><td class="text-center" colspan="4">No hay registros</td></tr><?php endif; ?>
    </tbody>
</table>

<?= $this->include('pagination/pagination'); ?>

<?= csrf_field('csrf') ?>

<?= $this->include('modal/modal'); ?>

<?= $this->endSection(); ?>