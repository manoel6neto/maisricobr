<?php $permissoes = $this->permissoes_usuario->get_by_usuario_id($this->session->userdata('id_usuario'));?>
<div id="content" class="bg-white">

<h1 class="bg-white content-heading border-bottom">Histórico de Compras de <?php echo $usuario->nome; ?></h1>
    <div class="bg-white">
        <div style="padding-top: 1%;">
            <div class="col-md-8 col-sm-8 col-sm-offset-2 bg-white">
            
                
                    <table class="table">
                        <thead>
                            <tr style="color: #428bca; font-size: 16px;">
                                <th>Código Referência</th>
                                <th>Validade</th>
                                <th>Tipo Servico</th>
                                <th>Data Compra</th>
                                <th>Status</th>
                                <th>Ativa</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php $dados_compra = $pagseguro_usuario_model->get_all_compra($usuario->id_usuario);?>
                            <?php foreach ($dados_compra as $d):?>
                                <tr>
                                    <td><?php echo $d->codigo_ref_compra; ?></td>
                                    <td><?php echo $d->validade_plano; ?></td>
                                    <td><?php echo $d->tipo_servico; ?></td>
                                    <td><?php echo implode("/", array_reverse(explode("-", $d->data_compra)));?></td>
                                    <td><?php echo $d->compra_paga ? "Paga" : "Aberta";?></td>
                                    <td><?php echo $d->ativa ? "Ativa" : "Inativa"; ?></td>
                                </tr>
                           <?php endforeach;?>
                        </tbody>
                    </table>
                    <a class="btn btn-primary" href="<?php echo base_url('index.php/controle_usuarios/lista_usuario_avulso');?>">Voltar</a>
            </div>
        </div>
    </div>
</div>