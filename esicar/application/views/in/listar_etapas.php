<div class="innerALl" style="margin-bottom: 60px !important;">
    <div class="col-md-12 col-sm-12" style="margin-bottom: 60px !important;">

        <table class="table">
            <thead>
                <tr><th style="color: red; font-size: 16px;">Valor de Referência</th></tr>
                <tr>
                    <th>Especificação</th>
                    <th>Valor da Meta</th>
                    <th>Soma das Etapas</th>
                    <th>Valor Restante</th>
                </tr>

                <tr>
                    <td><?php echo $dadosMeta->especificacao; ?></td>
                    <td><?php echo "R$ " . number_format($dadosMeta->total, 2, ",", "."); ?></td>
                    <td><?php echo "<span style='color:red;'>R$ " . number_format($valorTotalEtapa, 2, ",", ".") . "</span>"; ?></td>
                    <td><?php echo "<span style='color:green;'>R$ " . number_format(round($dadosMeta->total, 2) - round($valorTotalEtapa, 2), 2, ",", ".") . "</span>"; ?></td>
                </tr>
            </thead>     
        </table>

        <form name="manterProgramaPropostaValoresDoProgramaSalvarForm" method="post" enctype="multipart/form-data">
            <h1 class="bg-white content-heading border-bottom" style="color: #428bca;">Incluir Etapas</h1>
            <div>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Especificação</th>
                            <th>Valor Total</th>
                            <th>Data de Início</th>
                            <th>Data de Término</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="tbodyrow">
                        <?php
                        foreach ($etapas as $etapa) {
                            ?>
                            <tr class="odd">
                                <td style="width: 40%;">
                                    <div><?php echo $etapa->especificacao; ?></div>
                                </td>
                                <td>
                                    <div>R$ <?php echo number_format($etapa->total, 2, ",", "."); ?></div>
                                </td>
                                <td>
                                    <div>
                                        <?php echo implode("/", array_reverse(explode("-", $etapa->data_inicio))); ?>
                                    </div>
                                </td>
                                <td>
                                    <div><?php echo implode("/", array_reverse(explode("-", $etapa->data_termino))); ?></div>
                                </td>
                                <td>

                                    <a class="btn btn-sm btn-default" class="buttonLink" href="incluir_etapa_da_meta?meta=<?php echo $meta; ?>&etapa=<?php echo $etapa->idEtapa ?>&id=<?php echo $id; ?>&edita_gestor=<?php echo $edita_gestor; ?>"><i class="fa fa-edit"></i> Editar</a>
                                    <a class="btn btn-sm btn-primary excluiEtapa" class="buttonLink" href="apaga_etapa?meta=<?php echo $meta; ?>&etapa=<?php echo $etapa->idEtapa ?>&id=<?php echo $id; ?>&edita_gestor=<?php echo $edita_gestor; ?>"><i class="fa fa-trash-o"></i> Excluir</a></nobr>

                                </td></tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </form>

        <?php $corBotao = (round($dadosMeta->total, 2) - round($valorTotalEtapa, 2) > 0) ? "primary" : "success"; ?>

        <a class="btn btn-primary" href="<?php echo base_url(); ?>index.php/in/usuario/listar_metas?id=<?php echo $id; ?>&edita_gestor=<?php echo $edita_gestor ?>">Voltar</a>

        <a class="btn btn-<?php echo $corBotao; ?>" href="<?php echo base_url(); ?>index.php/in/usuario/incluir_etapa_da_meta?meta=<?php echo $meta; ?>&id=<?php echo $id; ?>&edita_gestor=<?php echo $edita_gestor ?>">Adicionar Etapa</a>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $(".excluiEtapa").click(function () {
            if (confirm("Deseja realmente excluir essa etapa?"))
                return true;
            return false;
        });
    });
</script>