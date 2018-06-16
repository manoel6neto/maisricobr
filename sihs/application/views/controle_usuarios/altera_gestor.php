<style type="text/css">
    .error{
        color: red;
    }
</style>

<div id="content" class="innerAll bg-white">

    <h3>Alterar Gestor para usuário: <?php echo $usuario->nome; ?></h3>

    <?php echo form_open(); ?>
        <input type="hidden" id="id_usuario" name="id_usuario" value="<?php echo $usuario->id_usuario; ?>"/>
        <div class="form-group" id="mostraGestor">
            <?php echo form_label('Gestor *', 'id_subgestor', array('class' => (form_error('id_subgestor') != "" ? "error" : ""))); ?>
            <?php echo form_dropdown('id_subgestor', $subGestores, set_value('id_subgestor', (isset($subGestor) && $subGestor != NULL) ? $subGestor->id_usuario : ''), 'class="form-control" id="id_subgestor"'); ?>
        </div>

        <div class="form-group">
            <input type="submit" class="btn btn-primary" name="cadastra" value="Salvar" id="cadastrar">
        </div>
    <?php echo form_close(); ?>

</div>
