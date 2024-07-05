<div class="nav-bar">
    <nav arial-label="Page navigation">
        <ul class="pagination justify-content-center">
            <li class="page-item btn-arrow <?= $pagination['pageNumber'] <= 1 ?'disabled':'' ?>" id="btnPrev">
                <a class="page-link" href="" data-page="<?= $pagination['pageNumber'] -1 ?>"><span aria-hidden="true">&laquo;</span></a>
            </li>
            <?php for($i=0; $i < $pagination['totalPages']; $i++){ ?>
            <li class="page-item page-number-item <?= $pagination['pageNumber'] == $i+1 ?'active':'' ?>">
                <a class="page-link link-number" href="" data-page="<?= $i+1?>"><?= $i+1 ?></a>
            </li>
            <?php } ?>
            <li class="page-item <?= $pagination['pageNumber'] >= $pagination['totalPages'] ?'disabled':'' ?>" id="btnNext">
                <a class="page-link" href="" data-page="<?= $pagination['pageNumber'] + 1 ?>"><span aria-hidden="true">&raquo;</span></a>
            </li>
        </ul>
    </nav>
</div> 
<div class="float-end">Total de registros <b><span id="totalRows" ><?= $pagination['totalRows'] ?></span></b></div>


