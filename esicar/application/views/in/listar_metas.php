<div class="innerALl" style="margin-bottom: 60px;">
    <div class="col-md-12 col-sm-12" style="margin-bottom: 60px !important;">

        <table class="table">
            <thead>
                <tr><th style="color: red; font-size: 16px;">Valor de Referência</th></tr>
                <tr>
                    <th>Valor Global</th>
                    <th>Valor Cadastrado</th>
                    <th>Valor a Cadastrar</th>
                </tr>

                <tr>
                    <td><?php echo "R$ " . number_format($valorGlobal, 2, ",", "."); ?></td>
                    <td><?php
                        $valorAssociado = 0;
                        foreach ($metas as $meta)
                            $valorAssociado += $meta->total;
                        echo "<span style='color:green;'>R$ " . number_format($valorAssociado, 2, ",", ".") . "</span>";
                        ?></td>
                    <td><?php echo "<span style='color:red;'>R$ " . number_format(round($valorGlobal, 2) - round($valorAssociado, 2), 2, ",", ".") . "</span>"; ?></td>
                </tr>
            </thead>     
        </table>

        <h1 class="bg-white content-heading border-bottom" style="color: #428bca; padding-bottom: 10px;">Incluir Metas</h1>
        <div id="metas">
            <table class="table">
                <thead>
                    <tr>
                        <th>Especificação</th>
                        <th>Valor Total</th>
                        <th>Data de Início</th>
                        <th>Data de Término</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $corLabelMeta = 'primary';
                    if (round($valorGlobal, 2) - round($valorAssociado, 2) == round(0, 2))
                        $corLabelMeta = 'success';
                    foreach ($metas as $meta) {
                        $etapas = $trabalho->obter_etapas_meta_proposta($meta->idMeta);
                        $valorEtapas = 0;
                        $status = '<i title="Pendente" class="btn-sm btn-primary fa fa-warning"></i>';
                        $corLabel = 'default';
                        foreach ($etapas as $etapa)
                            $valorEtapas += $etapa->total;
                        if (number_format(round($valorEtapas, 2), 2, ",", ".") === number_format(round($meta->total, 2), 2, ",", ".") && $meta->id_programa != NULL) {
                            $status = '<i title="Completo" class="btn-sm btn-success fa fa-check-square"></i>';
                            $corLabel = 'success';
                        }
                        ?>			
                        <tr>
                            <td style="width: 40%;">
                                <div class="numero"><?php echo $meta->especificacao ?></div>
                            </td>
                            <td>
                                <div >R$ <?php echo number_format($meta->total, 2, ",", "."); ?></div>
                            </td>
                            <td>
                                <div >
                                    <?php echo implode("/", array_reverse(explode("-", $meta->data_inicio))) ?>
                                </div>
                            </td>
                            <td>
                                <div ><?php echo implode("/", array_reverse(explode("-", $meta->data_termino))) ?></div>
                            </td>
                            <td>
                                <div>
                                    <?php echo $status; ?>
                                </div>
                            </td>
                            <td>
                                <div class="pull-right">
                                    <a class="btn btn-sm btn-default" href="<?php echo base_url(); ?>index.php/in/usuario/meta?meta=<?php echo $meta->idMeta ?>&id=<?php echo $id ?>&edita_gestor=<?php echo $edita_gestor ?>"><i class="fa fa-edit"></i> Editar</a>
                                    <a class="btn btn-sm btn-default" href="<?php echo base_url(); ?>index.php/in/usuario/incluir_etapa_da_meta?meta=<?php echo $meta->idMeta ?>&id=<?php echo $id; ?>&edita_gestor=<?php echo $edita_gestor ?>"><i class="fa fa-plus"></i> Inserir Etapa</a>
                                    <a class="btn btn-sm btn-<?php echo $corLabel; ?>" href="<?php echo base_url(); ?>index.php/in/usuario/listar_etapas?meta=<?php echo $meta->idMeta ?>&id=<?php echo $id ?>&edita_gestor=<?php echo $edita_gestor ?>"><i class="fa fa-eye"></i> Ver Etapas</a>
                                    <a class="btn btn-sm btn-primary excluiMeta" href="<?php echo base_url(); ?>index.php/in/usuario/apaga_meta?meta=<?php echo $meta->idMeta ?>&id=<?php echo $id ?>&edita_gestor=<?php echo $edita_gestor ?>"><i class="fa fa-trash-o"></i> Excluir</a>
                                </div>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        <div style="margin-bottom: 60px !important;">
            <a class="btn btn-primary" href="<?php echo base_url() . 'index.php/in/usuario/incluir_justificativa?id=' . $_GET['id'] . '&edita_gestor=1'; ?>">Voltar</a>
            <a class="btn btn-<?php echo $corLabelMeta; ?>" href="<?php echo base_url(); ?>index.php/in/usuario/meta?id=<?php echo $id ?>">Adicionar Meta</a>
            <a class="btn btn-primary" href="<?php echo base_url(); ?>index.php/in/usuario/listar_cronograma?id=<?php echo $id ?>">Avançar</a>
        </div>
        </form>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $(".excluiMeta").click(function () {
            if (confirm("Deseja realmente excluir essa meta?"))
                return true;
            return false;
        });
    });
</script>
