<div id="content" class="bg-white">
    <h1 style="color: #428bca;" class="bg-white content-heading border-bottom">Permissões do usuário: <?php echo $usuario->nome ?></h1>
	<?php $action = isset($_GET['id']) ? "controle_usuarios/editar_permissoes?id=".$_GET['id'] : "" ; ?>
    <?php echo form_open($action); ?>

    <div class="bg-white">
        <div style="padding-top: 1%;">
			<?php
			$verificaPermissao = (array)$permissoes;

			unset($verificaPermissao['id_permissoes_usuario']);
			unset($verificaPermissao['id_usuario']);
			?>
        	<div class="form-group">
        		<?php echo form_checkbox('nenhuma_permissao', 'nenhuma_permissao', in_array('1', $verificaPermissao) ? false : true, "id='ativaTodos'"); ?>
                <?php echo form_label('Somente Visualização', 'nenhuma_permissao_lb'); ?>
            </div>
            
            <!-- Operações que só podem ser definidas por um administrador -->
            <?php if ($this->session->userdata('nivel') == 1): ?> 
                <div class="form-group">
                    <?php echo form_checkbox('criar_usuario', 1, $permissoes->criar_usuario, "class='ativo'"); ?>
                    <?php echo form_label('Cadastrar usuário', 'criar_usuario_lb'); ?>
                </div>

                <div class="form-group">
                    <?php echo form_checkbox('editar_usuario', 1, $permissoes->editar_usuario, "class='ativo'"); ?>
                    <?php echo form_label('Editar usuário', 'editar_usuario_lb'); ?>
                </div>

                <div class="form-group">
                    <?php echo form_checkbox('ativar_usuario', 1, $permissoes->ativar_usuario, "class='ativo'"); ?>
                    <?php echo form_label('Ativar usuário', 'ativar_usuario_lb'); ?>
                </div>

                <div class="form-group">
                    <?php echo form_checkbox('desativar_usuario', 1, $permissoes->desativar_usuario, "class='ativo'"); ?>
                    <?php echo form_label('Desativar usuário', 'desativar_usuario_lb'); ?>
                </div>

                <div class="form-group">
                    <?php echo form_checkbox('tornar_proj_padrao', 1, $permissoes->tornar_proj_padrao, "class='ativo'"); ?>
                    <?php echo form_label('Tornar projeto padrão', 'tornar_proj_padrao_lb'); ?>
                </div>
                
                <div class="form-group">
                    <?php echo form_checkbox('apagar_projeto_padrao', 1, $permissoes->apagar_projeto_padrao, "class='ativo'"); ?>
                    <?php echo form_label('Apagar projeto padrão', 'apagar_projeto_padrao_lb'); ?>
                </div>

                <div class="form-group">
                    <?php echo form_checkbox('vincular_cnpj_usuario', 1, $permissoes->vincular_cnpj_usuario, "class='ativo'"); ?>
                    <?php echo form_label('Vincular cnpj à usuário', 'vincular_cnpj_usuario_lb'); ?>
                </div>

                <div class="form-group">
                    <?php echo form_checkbox('editar_cnpj_usuario', 1, $permissoes->editar_cnpj_usuario, "class='ativo'"); ?>
                    <?php echo form_label('Editar cnpj de usuário', 'editar_cnpj_usuario_lb'); ?>
                </div>
            <?php endif; ?>

            <!-- Operações que podem ser definidas por vendedor e gestor -->
            <div class="form-group">                
                <?php echo form_checkbox('consultar_programa', 1, $permissoes->consultar_programa, "class='ativo'"); ?>
                <?php echo form_label('Consultar programas siconv', 'consultar_programa_lb'); ?>
            </div>

            <div class="form-group">
                <?php echo form_checkbox('relatorio_programa', 1, $permissoes->relatorio_programa, "class='ativo'"); ?>
                <?php echo form_label('Gerar relatório programas siconv', 'relatorio_programa_lb'); ?>
            </div>
            
            <div class="form-group">
                <?php echo form_checkbox('visualiza_emendas', 1, $permissoes->visualiza_emendas, "class='ativo'"); ?>
                <?php echo form_label('Visualizar Emendas Disponíveis', 'relatorio_programa_lb'); ?>
            </div>
            
            <div class="form-group">
                <?php echo form_checkbox('visualiza_prop_parecer', 1, $permissoes->visualiza_prop_parecer, "class='ativo'"); ?>
                <?php echo form_label('Visualizar Propostas e Pareceres', 'relatorio_programa_lb'); ?>
            </div>

            <div class="form-group">
                <?php echo form_checkbox('criar_projeto', 1, $permissoes->criar_projeto, "class='ativo'"); ?>
                <?php echo form_label('Criar projeto', 'criar_projeto_lb'); ?>
            </div>

            <div class="form-group">
                <?php echo form_checkbox('editar_projeto', 1, $permissoes->editar_projeto, "class='ativo'"); ?>
                <?php echo form_label('Editar projeto', 'editar_projeto_lb'); ?>
            </div>

            <div class="form-group">
                <?php echo form_checkbox('apagar_projeto', 1, $permissoes->apagar_projeto, "class='ativo'"); ?>
                <?php echo form_label('Apagar projeto', 'apagar_projeto_lb'); ?>
            </div>

            <div class="form-group">
                <?php echo form_checkbox('alterar_end_projeto', 1, $permissoes->alterar_end_projeto, "class='ativo'"); ?>
                <?php echo form_label('Alterar endereço de projeto', 'alterar_end_projeto_lb'); ?>
            </div>

            <div class="form-group">
                <?php echo form_checkbox('duplicar_projeto', 1, $permissoes->duplicar_projeto, "class='ativo'"); ?>
                <?php echo form_label('Duplicar projeto', 'duplicar_projeto_lb'); ?>
            </div>

            <div class="form-group">
                <?php echo form_checkbox('utilizar_padrao', 1, $permissoes->utilizar_padrao, "class='ativo'"); ?>
                <?php echo form_label('Utilizar projeto padrão', 'utilizar_padrao_lb'); ?>
            </div>

            <div class="form-group">
                <?php echo form_checkbox('exportar_siconv', 1, $permissoes->exportar_siconv, "class='ativo'"); ?>
                <?php echo form_label('Exportar projeto para siconv', 'exportar_siconv_lb'); ?>
            </div>

            <div class="form-group">
                <?php echo form_checkbox('consultar_proposta', 1, $permissoes->consultar_proposta, "class='ativo'"); ?>
                <?php echo form_label('Consultar propostas siconv', 'consultar_proposta_lb'); ?>
            </div>

            <div class="form-group">
                <?php echo form_checkbox('relatorio_proposta', 1, $permissoes->relatorio_proposta, "class='ativo'"); ?>
                <?php echo form_label('Gerar relatório propostas siconv', 'relatorio_proposta_lb'); ?>
            </div>

            <div class="form-group">
                <?php echo form_checkbox('status_proposta', 1, $permissoes->status_proposta, "class='ativo'"); ?>
                <?php echo form_label('Consultar status de propostas exportadas', 'status_proposta_lb'); ?>
            </div>

            <div class="form-group">
                <?php echo form_checkbox('parecer_proposta', 1, $permissoes->parecer_proposta, "class='ativo'"); ?>
                <?php echo form_label('Consultar pareceres de propostas exportadas', 'parecer_proposta_lb'); ?>
            </div>

        </div>
    </div>

    <div class="form-group">
        <input type="submit" class="btn btn-primary" name="cadastra" value="Salvar" id="cadastrar">
        <?php if($usuario->id_nivel != 9):?>
        	<input class="btn btn-primary" type="button" value="Voltar" onclick="location.href = '<?php echo base_url(); ?>index.php/controle_usuarios';">
        <?php else:?>
        	<input class="btn btn-primary" type="button" value="Voltar" onclick="location.href = '<?php echo base_url('index.php/controle_usuarios/lista_usuario_avulso'); ?>';">
        <?php endif;?>
        <input class="btn btn-primary" type="button" value="Restaurar Padrões" onclick="document.location.reload(true);">
    </div>

    <?php echo form_close(); ?>
</div>

<script type="text/javascript">
$(document).ready(function(){
	$("#ativaTodos").click(function(){
		mudaStatus(".ativo", !$(this).is(":checked"));
	});

	$(".ativo").click(function(){
		var num = 0;
		
		$(".ativo").each(function(){
			if($(this).is(":checked"))
				num++;
		});

		mudaStatus("#ativaTodos", !(num > 0));
	});

	function mudaStatus(attr, valor){
		$(attr).each(function(){
			$(this).attr("checked", valor);
		});
	}
});
</script>