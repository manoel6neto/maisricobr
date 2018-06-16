<div class="navbar navbar-fixed-top bg-gray-dark main" role="navigation" style="background: #D9D9D9 !important;">
    <div class="lista" style="float: left !important;">
        <?php if ($num_pag > 1): ?>
            <a href=<?php echo base_url('index.php/apresentacao/page?num_pag=' . ($num_pag - 1)); ?>><h4>&nbsp; << Anterior</h4></a>
        <?php else: ?>
            <h4>&nbsp; << Anterior</h4>
        <?php endif; ?>
    </div>

    <div class="lista_mid" style="float: left !important;">
        <?php echo $titulo; ?>
    </div>

    <div class="lista" style="float: right !important;">
        <?php if ($num_pag < 11): ?>
            <a href=<?php echo base_url('index.php/apresentacao/page?num_pag=' . ($num_pag + 1)); ?>><h4>&nbsp; Próximo >> &nbsp;</h4></a>
        <?php else: ?>
            <h4>&nbsp; Próximo >> &nbsp;</h4>
        <?php endif; ?>
    </div>
</div>