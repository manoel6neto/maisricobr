<div id="content" class="bg-white">
    <h1 class="bg-white content-heading border-bottom">Listagem de Responsáveis Para Recebimento de Email</h1>
    <div class="bg-white">
        <div style="padding-top: 1%;">
            <div class="col-md-8 col-sm-8 col-sm-offset-2 bg-white">
                <?php if ($encarregados != null): ?>
                    <table class="table">
                        <thead>
                            <tr style="color: #428bca; font-size: 16px;">
                                <th>Nome</th>
                                <th>Função</th>
                                <th>Email</th>
                                <th style="text-align: right;">Edição</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach ($encarregados as $encarregado): ?>
                                <tr>
                                    <td><?php echo $encarregado->nome; ?></td>
                                    <td><?php echo $encarregado->funcao; ?></td>
                                    <td><?php echo $encarregado->email; ?></td>
                                    <td style="text-align: right;">
                                        <a title="Editar Gestor" class="btn btn-sm btn-default" href="<?php echo base_url(); ?>index.php/controle_usuarios/editar_encarregado?id=<?php echo $encarregado->id_encarregado; ?>&id_gestor=<?php echo $encarregado->id_gestor; ?>"><i class="fa fa-edit"></i></a>
                                        <a title="Excluir Gestor" onclick="return confirm('Tem certeza que deseja excluir esse gestor?')" class="btn btn-sm btn-primary" href="<?php echo base_url(); ?>index.php/controle_usuarios/excluir_encarregado?id_encarregado=<?php echo $encarregado->id_encarregado; ?>&id_gestor=<?php echo $encarregado->id_gestor; ?>"><i class="fa fa-trash-o"></i></a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <h1 style="text-align: center;">Nenhum responsável encontrado.</h1>
                <?php endif; ?>
                <a title="Adicionar encarregado" class="btn btn-primary" href="<?php echo base_url(); ?>index.php/controle_usuarios/novo_encarregado?id_gestor=<?php echo $id_gestor; ?>">Adicionar</a>
            </div>
        </div>
    </div>
</div>