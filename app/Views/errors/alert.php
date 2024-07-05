<div class="alert alert-danger <?= $error ? 'd-block' : '' ?>" id="dialog">

    <?php if($error) : ?>
        <ul>
        <?php foreach($messages as $message) :?>
            <li><?= $message; ?></li>
        <?php endforeach;?>
        </ul>  
    <?php endif; ?>
</div>