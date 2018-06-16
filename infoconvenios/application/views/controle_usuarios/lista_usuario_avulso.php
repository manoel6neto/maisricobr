<?php $permissoes = $this->permissoes_usuario->get_by_usuario_id($this->session->userdata('id_usuario'));?>
<div id="content" class="bg-white">

<h1 class="bg-white content-heading border-bottom">Listagem de Usuários</h1>
    <div class="bg-white">
        <div style="padding-top: 1%;">
            <div class="col-md-8 col-sm-8 col-sm-offset-2 bg-white">
            <?php echo form_open(); ?>
          	  <div class="input-group input-lg ">
					<input name="pesquisa" type="text" class="form-control"
						placeholder="Pesquisar código de referência"
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
                                <th>ID</th>
                                <th>Nome</th>
                                <th>Código Referência</th>
                                <th>Data Compra</th>
                                <th>Status Compra</th>
                                <th>Ativo</th>
                                <th style="text-align: right;">Edição</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach ($lista_usuarios as $usuario): ?>
                            <?php 
                            	$dados_usuario = $usuariomodel->get_by_id($usuario->id_usuario);
                                $dados_gestor = $usuariomodel->get_gestor_by_usuario($usuario->id_usuario);
                            ?>
                                <tr>
                                    <td><?php echo $dados_usuario->id_usuario; ?></td>
                                    <td><?php echo $dados_usuario->nome; ?></td>
                                    <td><?php if(isset($usuario->codigo_ref_compra)) {echo $usuario->codigo_ref_compra;} else {echo "Teste 7 dias";} ?></td>
                                    <td><?php echo implode("/", array_reverse(explode("-", $usuario->data_compra)));?></td>
                                    <td><?php echo $usuario->compra_paga ? "Paga" : "Aberta";?></td>
                                    <td><?php echo $usuario->ativa ? "Ativa" : "Inativa"; ?></td>
                                    <td style="text-align: right;">
                                        <?php if ($usuario->ativa || !$usuario->compra_paga): ?>
                                            <?php if(!$confirma_cadastro->verifica_cadastro_confirmado($dados_usuario->email, $dados_usuario->login)):?>
                                                    <a title="Reenviar Email Confirmação" onclick="return confirm('Tem certeza que deseja reenviar o email de confirmação do cadastro?')" class="btn btn-sm btn-info" href="<?php echo base_url(); ?>index.php/controle_usuarios/reenvia_email?id=<?php echo $usuario->id_usuario; ?>"><i class="fa fa-mail-reply"></i></a>
                                            <?php endif;?>

                                            <a title="Sumário" class="btn btn-sm btn-info" href="<?php echo base_url('index.php/controle_usuarios/sumario?id='.$usuario->id_usuario);?>"><i class="fa fa-search"></i></a>
                                            <a title="Histórico de Compras" class="btn btn-sm btn-default" href="<?php echo base_url('index.php/controle_usuarios/historico_compra?id='.$usuario->id_usuario);?>"><i class="fa fa-refresh"></i></a>
                                                
                                            <?php if($dados_usuario->usuario_novo != "S" && !$usuario->compra_paga):?>
                                                    <a title="Confirmar Pagamento" onclick="return confirm('Confirma o pagamento deste usuário?')" class="btn btn-sm btn-info" href="<?php echo base_url(); ?>index.php/controle_usuarios/confirma_pagamento?id=<?php echo $usuario->id_usuario; ?>&cod_ref=<?php echo $usuario->codigo_ref_compra; ?>"><i class="fa fa-credit-card"></i></a>
                                            <?php endif;?>

                                            <?php if ($dados_usuario->status === 'A' && $dados_usuario->usuario_novo != "S"): ?>
                                                    <?php if($permissoes->desativar_usuario):?>
                                                    <a title="Desativar usuário" onclick="return confirm('Tem certeza que deseja desativar esse usuário?')" class="btn btn-sm btn-success" href="<?php echo base_url(); ?>index.php/controle_usuarios/desativa_usuario?id=<?php echo $usuario->id_usuario; ?>"><i class="fa fa-unlock"></i></a>
                                                <?php endif;?>
                                            <?php else: ?>
                                                <?php if($permissoes->ativar_usuario && $dados_usuario->usuario_novo != "S"):?>
                                                    <a title="Ativar usuário" onclick="return confirm('Tem certeza que deseja ativar esse usuário?')" class="btn btn-sm btn-primary" href="<?php echo base_url(); ?>index.php/controle_usuarios/ativa_usuario_avulso?id=<?php echo $usuario->id_usuario; ?>&cod_ref=<?php echo $usuario->codigo_ref_compra; ?>"><i class="fa fa-lock"></i></a>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        <?php endif; ?>
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