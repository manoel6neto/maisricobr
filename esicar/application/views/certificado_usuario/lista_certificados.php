<?php $permissoes = $this->permissoes_usuario->get_by_usuario_id($this->session->userdata('id_usuario'));?>
<div id="content" class="bg-white">

<h1 class="bg-white content-heading border-bottom">Listagem de Certificados</h1>
    <div class="bg-white">
    &nbsp;&nbsp;<a class="btn btn-primary" href="<?php echo base_url('index.php/certificado_usuario/criar_certificado'); ?>">Criar Certificados</a>
        <div style="padding-top: 1%;">
            <div class="col-md-8 col-sm-8 col-sm-offset-2 bg-white">
                <?php if ($lista_certificados != null): ?>
                    <table class="table">
                        <thead>
                            <tr style="color: #428bca; font-size: 16px;">
                                <td>Usuário</td>
                                <td>Uf</td>
                                <td>Município</td>
                                <td>Data</td>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach ($lista_certificados as $c): ?>
                                <tr>
                                    <td><?php echo $c->nome; ?></td>
	                                <td><?php echo $c->uf; ?></td>
	                                <td><?php echo $c->municipio_nome; ?></td>
	                                <td><?php echo implode("/", array_reverse(explode("-", $c->data_curso))); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <h1 style="text-align: center;">Nenhum certificado encontrado.</h1>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>