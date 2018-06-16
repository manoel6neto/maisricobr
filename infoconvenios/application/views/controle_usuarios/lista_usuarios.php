<?php $permissoes = $this->permissoes_usuario->get_by_usuario_id($this->session->userdata('id_usuario'));?>
<div id="content" class="bg-white">

<h1 class="bg-white content-heading border-bottom">Listagem de Usuários</h1>
    <div class="bg-white">
        <div style="padding-top: 1%;">
            <div class="col-md-8 col-sm-8 col-sm-offset-2 bg-white">
            <?php echo form_open(); ?>
          	  <div class="input-group input-lg ">
					<input name="pesquisa" type="text" class="form-control"
						placeholder="Pesquisar"
						<?php if(isset($pesquisa)){echo "value=\"{$pesquisa}\"";}?> />
					<div class="input-group-btn">
						<button class="btn btn-info btnPesquisa" type="submit">
							<i class="fa fa-search"></i>
						</button>
					</div>
				</div>
            <?php echo form_close(); ?>
                <?php if ($lista_usuarios != null): ?>
                    <table class="table">
                        <thead>
                            <tr style="color: #428bca; font-size: 16px;">
                                <th>Nome</th>
                                <th>Aceite Licença</th>
                                <?php if($this->session->userdata('nivel') == 1):?>
                                <th>Cadastrador</th>
                                <th>Município</th>
                                <?php endif;?>
                                <th>Nível</th>
                                <th style="text-align: right;">Edição</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach ($lista_usuarios as $usuario): ?>
                            <?php $dados_municipio = $usuariomodel->get_municipio_by_usuario($usuario->id_usuario); ?>
                                <tr>
                                    <td><?php echo $usuario->nome; ?></td>
                                    <td><?php echo $usuariomodel->busca_data_aceite($usuario->id_usuario); ?></td>
                                    <?php if($this->session->userdata('nivel') == 1):?>
	                                <td><?php echo $usuario->usuario_cadastrou;?></td>
	                                <td><?php echo $dados_municipio == null ? "" : $dados_municipio->municipio."/".$dados_municipio->municipio_uf_sigla;?></td>
	                                <?php endif;?>
                                    <td><?php echo $usuario->nome_nivel; ?></td>
                                    <td style="text-align: right;">
                                    	<?php if(!$confirma_cadastro->verifica_cadastro_confirmado($usuario->email, $usuario->login)):?>
                                    		<a title="Reenviar Email Confirmação" onclick="return confirm('Tem certeza que deseja reenviar o email de confirmação do cadastro?')" class="btn btn-sm btn-info" href="<?php echo base_url(); ?>index.php/controle_usuarios/reenvia_email?id=<?php echo $usuario->id_usuario; ?>"><i class="fa fa-mail-reply"></i></a>
                                    	<?php endif;?>
                                    	
                                        <?php if ($usuario->id_usuario != $this->session->userdata('id_usuario')): ?>
                                            <?php if ($usuario->status === 'A'): ?>
                                            	<?php if($permissoes->desativar_usuario):?>
                                                    <a title="Desativar usuário" onclick="return confirm('Tem certeza que deseja desativar esse usuário?')" class="btn btn-sm btn-success" href="<?php echo base_url(); ?>index.php/controle_usuarios/desativa_usuario?id=<?php echo $usuario->id_usuario; ?>"><i class="fa fa-unlock"></i></a>
                                            	<?php endif;?>
                                            <?php else: ?>
                                            	<?php if($permissoes->ativar_usuario):?>
                                                    <a title="Ativar usuário" onclick="return confirm('Tem certeza que deseja ativar esse usuário?')" class="btn btn-sm btn-primary" href="<?php echo base_url(); ?>index.php/controle_usuarios/ativa_usuario?id=<?php echo $usuario->id_usuario; ?><?php echo $usuario->desativado_gestor == 'S' ? "&desativado_gestor=" . $usuario->desativado_gestor : ""; ?>"><i class="fa fa-lock"></i></a>
                                                    <?php if ($proposta_model->get_count_by_usuario($usuario->id_usuario) == 0 && $usuario->id_nivel != 2 && $usuario->id_nivel != 1): ?>
                                                        <a title="Apagar usuário" onclick="return confirm('Tem certeza que deseja apagar esse usuário?')" class="btn btn-sm btn-primary" href="<?php echo base_url(); ?>index.php/controle_usuarios/apagar_usuario?id=<?php echo $usuario->id_usuario; ?>"><i class="fa fa-unlink"></i></a>
                                                    <?php endif; ?>
                                            	<?php endif; ?>
                                            <?php endif; ?>
                                                <a title="Editar permissões" class="btn btn-sm btn-default" href="<?php echo base_url(); ?>index.php/controle_usuarios/editar_permissoes?id=<?php echo $usuario->id_usuario; ?>"><i class="fa fa-wrench"></i></a>
                                        <?php endif; ?>
                                        
                                        <?php if($permissoes->editar_usuario):?>
                                                <a title="Editar Usuário" class="btn btn-sm btn-default" href="<?php echo base_url(); ?>index.php/controle_usuarios/atualiza_usuario?id=<?php echo $usuario->id_usuario; ?>"><i class="fa fa-edit"></i></a>
                                            <?php if ($usuario->id_nivel == 2): ?>
                                                <a title="Cadastrar Responsáveis" class="btn btn-sm btn-default" href="<?php echo base_url(); ?>index.php/controle_usuarios/atualiza_encarregados?id_usuario=<?php echo $usuario->id_usuario; ?>"><i class="fa fa-user"></i></a>
                                            <?php endif; ?>
                                        <?php endif;?>
                                        
                                        <?php if($usuariomodel->verifica_subgestor($usuario->id_usuario) && ($this->session->userdata('nivel') == 2 || $this->session->userdata('nivel') == 3)):?>
                                        	<a title="CNPJ Vinculado" class="btn btn-info btn-sm btn-default" href="<?php echo base_url(); ?>index.php/controle_usuarios/vincular_cnpj?id=<?php echo $usuario->id_usuario; ?>"><i class="fa fa-link"></i></a>
                                        <?php endif;?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <h1 style="text-align: center;">Nenhum usuário encontrado.</h1>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>