<div id="content" class="bg-white">
    <h1 class="bg-white content-heading border-bottom">Logs do sistema</h1>
    <div class="bg-white">
        <div class="login spacing-x2" style="padding-top: 2%;">
            <div class="col-md-8 col-sm-8 col-sm-offset-2 bg-white">
            	<div>
            	<form action="<?php echo base_url('index.php/in/system_logs_controller/visualizar_logs'); ?>" method="post" id="form_filtro">
	            	<h3>Filtro de Log</h3>
	            	<br/>
	            	<label for="usuario">Usuário</label>
	            	<?php echo form_dropdown('usuario', $all_users, set_value('usuario', $usuario_filtro), 'id="usuario"')?>
            	</form>
            	</div>
            	
            	<div class="clearfix"></div>
            	
                <?php if ($logs != null) { ?>
                <h3 style="text-align: right;"><?php echo $paginas; ?></h3>
                    <table class="table">
                        <thead>
                            <tr style="color: #428bca; font-size: 16px;">
                                <th>Usuário</th>
                                <th>Ação</th>
                                <th>Data / Hora</th>                                
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($logs as $log): ?>
                            <tr>
                            	<td><?php echo $usuariomodel->get_by_id($log->id_usuario)->nome; ?></td>
                                <td><?php echo $log->acao; ?></td>
                                <td><?php echo date("d/m/Y H:i:s", strtotime($log->data)); ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php } else { ?>
                    <h1 style="text-align: center;">Nenhum log no sistema.</h1>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function(){
	$("#usuario").change(function(){
		$("#form_filtro").submit();
	});
});
</script>