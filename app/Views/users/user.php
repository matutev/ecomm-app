
<?php if(session()->has('logged')): ?>
<div class="row">
   <div class="col">
        Hola <b><?= session()->user; ?></b>
   </div>
   <div class="col">
        <a href="<?= base_url('auth/users/logout'); ?>" class="btn btn-danger float-end">Cerrar sesion</a>
   </div>
</div>
<?php endif; ?>