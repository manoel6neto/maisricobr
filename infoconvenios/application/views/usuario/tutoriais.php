<?php $permissoes = $this->permissoes_usuario->get_by_usuario_id($this->session->userdata('id_usuario'));?>

<style type="text/css">
.table_tuto td{
	width: 150px; 
	height: 30px; 
	text-align: center;
}
.table_tuto table{
	border-collapse: collapse;
}
</style>

<div id="content" class="bg-white innerAll">

<h1 class="bg-white content-heading border-bottom"><i class="fa fa-list"></i> Tutoriais Autoexplicativos</h1>
 
<table class="table_tuto" style="margin-left: 20px;">
	<tr>
		<td>
                    <a href="http://www.info-convenios.com/#!inicio-tutorial/c1noy" target="_blank"><img width="250" src="<?php echo base_url('layout/assets/images/tutoriais_sistema.png')?>" style="border: gray; border-width: 4px; border-style: solid;"></a>
		</td>
		<td style="width: 20px;"></td>
	</tr>

	<tr>
		<td>
			Tutoriais
		</td>
	</tr>
</table>
 
<!--<h1 class="bg-white content-heading border-bottom"><i class="fa fa-file"></i> Tutoriais em Arquivo PDF</h1>

<table class="table_tuto">
       <tr>
<?php if ($permissoes->criar_projeto): ?>
                   <td><a target="_blank" href="<?php echo base_url('tutoriais/nova_proposta.pdf'); ?>"><img src="<?php echo base_url('tutoriais/img_nova_proposta.png'); ?>" height="120" width="100"></a></td>
<?php endif; ?>
               
<?php if ($permissoes->visualiza_minhas_propostas): ?>
                   <td><a target="_blank" href="<?php echo base_url('tutoriais/minhas_propostas.pdf'); ?>"><img src="<?php echo base_url('tutoriais/img_minhas_propostas.png'); ?>" height="120" width="100"></a></td>
<?php endif; ?>
               
<?php if ($permissoes->utilizar_padrao): ?>
                   <td><a target="_blank" href="<?php echo base_url('tutoriais/banco_de_proposta.pdf'); ?>"><img src="<?php echo base_url('tutoriais/img_banco_proposta.png'); ?>" height="120" width="100"></a></td>
<?php endif; ?>
               
<?php if ($permissoes->consultar_programa): ?>
                   <td><a target="_blank" href="<?php echo base_url('tutoriais/buscar_programas.pdf'); ?>"><img src="<?php echo base_url('tutoriais/img_buscar_programas.png'); ?>" height="120" width="100"></a></td>
<?php endif; ?>
               
               <td><a target="_blank" href="<?php echo base_url('tutoriais/propostas_pareceres.pdf'); ?>"><img src="<?php echo base_url('tutoriais/img_propostas_pareceres.png'); ?>" height="120" width="100"></a></td>
       </tr>
       
       <tr>
<?php if ($permissoes->criar_projeto): ?>
                   <td>Nova Proposta</td>
<?php endif; ?>
               
<?php if ($permissoes->visualiza_minhas_propostas): ?>
                   <td>Minhas Propostas</td>
<?php endif; ?>
               
<?php if ($permissoes->utilizar_padrao): ?>
                   <td>Banco de Propostas</td>
<?php endif; ?>
               
<?php if ($permissoes->consultar_programa): ?>
                   <td>Buscar Programas</td>
<?php endif; ?>
               
               <td>Propostas e Pareceres</td>
       </tr>
       <tr><td colspan="5"></td></tr>
       <tr>
<?php if ($permissoes->relatorio_proposta): ?>
                   <td><a target="_blank" href="<?php echo base_url('tutoriais/relatorios.pdf'); ?>"><img src="<?php echo base_url('tutoriais/img_relatorios.png'); ?>" height="120px" width="100px"></a></td>
<?php endif; ?>
               
               <td><a target="_blank" href="<?php echo base_url('tutoriais/ajuda.pdf'); ?>"><img src="<?php echo base_url('tutoriais/img_ajuda.png'); ?>" height="120" width="100"></a></td>
               
<?php if ($permissoes->criar_usuario): ?>
                   <td><a target="_blank" href="<?php echo base_url('tutoriais/area_do_cliente.pdf'); ?>"><img src="<?php echo base_url('tutoriais/img_area_do_cliente.png'); ?>" height="120" width="100"></a></td>
<?php endif; ?>
               
               <td></td>
       </tr>
       
       <tr>
<?php if ($permissoes->relatorio_proposta): ?>
                   <td>Relatórios</td>
<?php endif; ?>
               
               <td>Ajuda</td>
               
<?php if ($permissoes->criar_usuario): ?>
                   <td>Área do Cliente</td>
<?php endif; ?>
               <td></td>
       </tr>
</table>-->

</div>