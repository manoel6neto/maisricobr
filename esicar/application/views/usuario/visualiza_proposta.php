<?php $permissoes = $this->permissoes_usuario->get_by_usuario_id($this->session->userdata('id_usuario')); ?>

<style>
    html, body{
        height:100%; /* aqui definimos que o html e o body terão altura de 100% */
    }

    #pop{  
        display:none;
        position:absolute;
        margin-left:-274px;
        margin-top:-174px;
        width:100%;
        height:auto;
        min-height:100%;
        border:10px solid #d0d0d0;
        z-index: 10000;
    }

    .loader {
        width:100px;
        height:100px;
        position:absolute;
        top:35%;
        left:50%;
        margin-top:-50px;
        margin-left:-50px;
    }
</style>  

<div class="col-md-12 bg-white left exporta" style="padding: 10px;">
    <?php if ($permissoes->exportar_siconv && !$proposta->padrao): ?>
        <?php if (!$proposta->enviado): ?>
            <?php
            echo "Status da proposta &nbsp;&nbsp;";
            if ($trabalho_model->verifica_trabalhos($this->input->get_post('id', TRUE)) != true) {
                echo '<i title="Pendente" class="btn-sm btn-primary fa fa-warning"></i>';
                $disabled = "disabled='disabled'";
            } else {
                echo '<i title="Completo" class="btn-sm btn-success fa fa-check-square"></i>';
                $disabled = "";
            }
            ?>
            <br><br>
            <!--<a class="btn btn-lg btn-success" <?php echo $disabled; ?> id="bt" href="<?php echo base_url(); ?>index.php/in/gestor/exporta_siconv?id=<?php echo $proposta->idProposta; ?>"><i class="fa fa-check"></i> Exportar para o siconv</a>-->
            <a class="btn btn-lg btn-success" <?php echo $disabled; ?> id="exporta_siconv"><i class="fa fa-check"></i> Exportar para o siconv</a>
            <img src="<?php echo base_url(); ?>layout/assets/images/loader.gif" style="width: 30px;" id="loader">
        <?php endif; ?>
    <?php else: ?>
        <?php if ($permissoes->utilizar_padrao): ?>
            <a onclick="return confirm('Tem certeza que deseja utilizar esse projeto?')" alt="Utilizar projeto" title="Utilizar projeto"
               class="btn btn-lg btn-success" href="<?php echo base_url(); ?>index.php/in/gestor/escolher_proponente?padrao=1&id=<?php echo $proposta->idProposta; ?>"><i class="fa fa-edit"> Utilizar proposta</i></a>
           <?php endif; ?>
       <?php endif; ?>
</div>
<div class="col-md-12 bg-white innerAll spacing-x2">
    <h3 style="color: #428bca;">Nome da Proposta</h3>
    <p class="bg-white content-heading border-bottom"><?php if (isset($proposta) !== false) echo $proposta->nome; ?></p>
    <br>
    <?php if (!$proposta->padrao): ?>
        <h3 style="color: #428bca;">Programa</h3>
        <?php
        $programas = $programa_proposta_model->get_programas_by_proposta($proposta->idProposta);
        foreach ($programas as $p)
            echo "<p>- " . substr($p->nome_programa, 0, 180) . (strlen($p->nome_programa) > 180 ? "..." : "") . "</p>";
        ?>
        <br>
    <?php endif; ?>
    <h3 style="color: #428bca;">Valores da Proposta</h3>
    <table class="table">

        <tr>
            <td>Percentual da contrapartida</td>
            <td><?php if (isset($proposta) !== false) echo number_format($proposta->percentual, 2, ",", "."); ?></td>
        </tr>
        <tr>
            <td>Valor Global do(s) Objeto(s) (R$)</td>
            <td><?php if (isset($proposta) !== false) echo number_format($proposta->valor_global, 2, ",", "."); ?></td>
        </tr>
        <tr>
            <td>Total de Contrapartida (R$)</td>
            <td><?php if (isset($proposta) !== false) echo number_format($proposta->total_contrapartida, 2, ",", "."); ?></td>
        </tr>
        <tr>
            <td>Contrapartida Financeira (R$)</td>
            <td><?php if (isset($proposta) !== false) echo number_format($proposta->contrapartida_financeira, 2, ",", "."); ?></td>
        </tr>
        <tr>
            <td>Contrapartida em Bens e Serviços (R$)</td>
            <td><?php if (isset($proposta) !== false) echo number_format($proposta->contrapartida_bens, 2, ",", "."); ?></td>
        </tr>
        <tr>
            <td>Valor de Repasse (R$)</td>
            <td><?php if (isset($proposta) !== false) echo number_format($proposta->repasse, 2, ",", "."); ?></td>
        </tr>
        <?php if ($proposta->repasse_voluntario > 0): ?>
            <tr>
                <td>Valor Repasse Voluntário (R$)</td>
                <td><?php if (isset($proposta) !== false) echo number_format($proposta->repasse_voluntario, 2, ",", "."); ?></td>
            </tr>
        <?php elseif ($proposta->repasse_especifico > 0): ?>
            <tr>
                <td>Valor Repasse Específico (R$)</td>
                <td><?php if (isset($proposta) !== false) echo number_format($proposta->repasse_especifico, 2, ",", "."); ?></td>
            </tr>	
        <?php endif; ?>
    </table>

    <h3 style="color: #428bca;">Datas</h3>
    <table class="table">
        <tr>
            <td>Data</td>
            <td>Data Início Vigência</td>
            <td>Data Término Vigência</td>
        </tr>
        <tr>
            <td><?php
                if (isset($proposta) !== false)
                    echo implode("/", array_reverse(explode("-", $proposta->data)));
                else
                    echo date("d/m/Y");
                ?></td>
            <td><?php if (isset($proposta) !== false) echo implode("/", array_reverse(explode("-", $proposta->data_inicio))); ?></td>
            <td><?php if (isset($proposta) !== false) echo implode("/", array_reverse(explode("-", $proposta->data_termino))); ?></td>
        </tr>
    </table>

    <?php
    $obsJustificativa = "";
    if ($proposta->padrao) {
        if (isset($justificativa)) {
            if ($justificativa->necessita_completar) {
                $obsJustificativa = '<span style="background-color: yellow; color: black;">ATENÇÃO - Completar justificativa com dados específicos do seu município referente ao objeto do programa.</span>';
            }
        }
    }
    ?>
    <h3 style="color: #428bca;">Justificativa <?php echo $obsJustificativa; ?></h3>
    <table class="table">
        <tr>
            <td>Justificativa</td>
            <td><?php
                if (isset($justificativa) !== false AND $justificativa != null AND count($justificativa) > 0)
                    echo $justificativa->Justificativa;
                else
                    echo "Não cadastrado";
                ?></td>
        </tr>
        <tr>
            <td>Objeto</td>
            <td><?php
                if (isset($justificativa) !== false AND $justificativa != null AND count($justificativa) > 0)
                    echo $justificativa->objeto;
                else
                    echo "Não cadastrado";
                ?></td>
        </tr>
    </table>

    <h3 style="color: #428bca;">Crono Fisico</h3>
    <h4 style="color: #428bca;">Metas</h4>
    <?php
    $tela3 = $this->trabalho_model->obter_saida_tela3_online($id);
    foreach ($tela3 as $key => $meta) {
        ?>
        <table class="table">
            <thead>
                <tr style="font-size: 14px;">
                    <td><b>Meta</b></td>
                    <td style="width: 50%"><b>Especificação</b></td>
                    <td><b>Valor Total</b></td>
                    <td><b>Data de Início</b></td>
                    <td><b>Data de Término</b></td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?php if (isset($key) !== false) echo $key + 1; ?></td>
                    <td style="width: 50%"><?php
                        if (isset($meta) !== false AND $meta != null AND count($meta) > 0)
                            echo $meta['especificacao'];
                        else
                            echo "Não cadastrado";
                        ?></td>
                    <td><?php
                        if (isset($meta) !== false AND $meta != null AND count($meta) > 0)
                            echo $meta['total'];
                        else
                            echo "Não cadastrado";
                        ?></td>
                    <td><?php
                        if (isset($meta) !== false AND $meta != null AND count($meta) > 0)
                            echo implode("/", array_reverse(explode("-", $meta['data_inicio'])));
                        else
                            echo "Não cadastrado";
                        ?></td>
                    <td><?php
                        if (isset($meta) !== false AND $meta != null AND count($meta) > 0)
                            echo implode("/", array_reverse(explode("-", $meta['data_termino'])));
                        else
                            echo "Não cadastrado";
                        ?></td>
                </tr>
            </tbody>
        </table>

        <table class="table">
            <thead>
                <tr>
                    <td></td>
                    <td>Etapa</td>
                    <td style="width: 47%">Especificação</td>
                    <td>Valor Total</td>
                    <td>Data de Início</td>
                    <td>Data de Término</td>
                </tr>
            </thead>
            <tbody>
                <?php
                $tela3_etapas = $this->trabalho_model->obter_saida_tela3_etapas_online($meta [0]);
                foreach ($tela3_etapas as $key1 => $etapa) {
                    ?>
                    <tr>
                        <td></td>
                        <td><?php if (isset($key1) !== false) echo $key1 + 1; ?></td>
                        <td style="width: 47%"><?php
                            if (isset($etapa) !== false AND $etapa != null AND count($etapa) > 0)
                                echo $etapa['especificacao'];
                            else
                                echo "Não cadastrado";
                            ?></td>
                        <td><?php
                            if (isset($etapa) !== false AND $etapa != null AND count($etapa) > 0)
                                echo $etapa['total'];
                            else
                                echo "Não cadastrado";
                            ?></td>
                        <td><?php
                            if (isset($etapa) !== false AND $etapa != null AND count($etapa) > 0)
                                echo implode("/", array_reverse(explode("-", $etapa['data_inicio'])));
                            else
                                echo "Não cadastrado";
                            ?></td>
                        <td><?php
                            if (isset($etapa) !== false AND $etapa != null AND count($etapa) > 0)
                                echo implode("/", array_reverse(explode("-", $etapa['data_termino'])));
                            else
                                echo "Não cadastrado";
                            ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php } ?>
    <h3 style="color: #428bca;">Crono Desembolso</h3>
    <?php
    $tela4 = $this->trabalho_model->obter_saida_tela4_online($this->input->get_post('id', TRUE));
    foreach ($tela4 as $keyx => $crono) {
        ?>
        <table class="table bg-white">
            <thead class="">
                <tr>
                    <td style="width: 25%">Cronograma</td>
                    <td style="width: 25%">Responsável</td>
                    <td style="width: 25%">Parcela</td>
                    <td>Mês e Ano</td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="width: 25%"><?php if (isset($keyx) !== false) echo $keyx + 1; ?></td>
                    <td style="width: 25%"><?php
                        if (isset($crono) !== false AND $crono != null AND count($crono) > 0)
                            echo $crono['responsavel'];
                        else
                            echo "Não cadastrado";
                        ?></td>
                    <td style="width: 25%"><?php
                        if (isset($crono) !== false AND $crono != null AND count($crono) > 0)
                            echo $crono['parcela'];
                        else
                            echo "Não cadastrado";
                        ?></td>
                    <td><?php
                        if (isset($crono) !== false AND $crono != null AND count($crono) > 0)
                            echo $crono['mes'] . "/" . $crono['ano'];
                        else
                            echo "Não cadastrado";
                        ?></td>
                </tr>
            </tbody>
        </table>

    <?php } ?>

    <h3 style="color: #428bca;">Plano Detalhado</h3>
    <table class="table bg-white">
        <thead class="">
            <tr>
                <td>Descrição</td>
                <td>Valor total</td>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($bens as $bem): ?>
                <tr>
                    <td><?php
                        if (isset($bem) !== false AND $bem != null AND count($bem) > 0)
                            echo $bem->descricao;
                        else
                            echo "Não cadastrado";
                        ?></td>
                    <td><?php
                        if (isset($bem) !== false AND $bem != null AND count($bem) > 0)
                            echo number_format($bem->total, 2, ",", ".");
                        else
                            echo "Não cadastrado";
                        ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>


    <?php if ($proposta->padrao): ?>
        <input class="btn btn-primary" type="button" value="Voltar" onclick="location.href = '<?php echo base_url(); ?>index.php/in/gestor/visualiza_banco_propostas';">
    <?php else: ?>
        <input class="btn btn-primary" type="button" value="Voltar" onclick="location.href = '<?php echo base_url(); ?>index.php/in/gestor/visualiza_propostas';">
    <?php endif; ?>
</div>
<style type="text/css">
    .f-navExp{  /* To fix main menu container */
        z-index: 9998;
        position: fixed;
        top: 116px;
        width: 100%;
        padding: 15px;
    }
</style>

<script type="text/javascript">
    $(function () {
        $("#loader").hide();

        $(window).scroll(function () {
            var topo = $('.exporta').height();

            var scrollTop = $(window).scrollTop();

            if (scrollTop > topo) {
                $('.exporta').addClass('f-navExp');
            } else {
                $('.exporta').removeClass('f-navExp');
            }
        });

        var dialog;

        dialog = $("#dialog-message").dialog({
            height: 320,
            width: 550,
            modal: true,
            buttons: {
                "Aceitar o termo": function () {
                    $(this).dialog("close");
                    $("#pop").css('height', $(document).height());
                    $('#pop').css('display', 'block');
                    $.ajax({
                        url: '<?php echo base_url(); ?>index.php/in/gestor/exporta_siconv',
                        type: 'get',
                        dataType: 'html',
                        data: {
                            id: '<?php echo $id; ?>'
                        },
                        beforeSend: function () {
                            $("#pop").css('height', $(document).height());
                            $('#pop').css('display', 'block');
                            $('#pop').css('background-color', 'rgba(80,80,80,0.5)');
                        },
                        success: function (data) {
                            var msg = data.split("<script type='text/javascript'>alert('");
                            var msgFinal = msg[1].split("');<");
                            alert(msgFinal[0]);
                            location.href = '<?php echo base_url(); ?>index.php/in/gestor/visualiza_propostas';
                        }
                    });
                },
                "Cancelar": function () {
                    $("#exporta_siconv").attr('disabled', false);
                    $(this).dialog("close");
                }
            }
        }).position({
            my: "center",
            at: "center",
            of: window
        });

        dialog.dialog("close");

        $("#exporta_siconv").click(function () {
            $("#exporta_siconv").attr('disabled', true);
            dialog.dialog("open");
        });
    });
</script>

<div id="dialog-message" title="Termo de Responsabilidade">
    <p style="font-size: 16px; text-align: justify;">
        <span class="ui-icon ui-icon-circle-check" style="float:left; "></span>
        Fica aqui configurado TERMO DE RESPONSABILIDADE, a partir desta decisão tomada pelo usuário, 
        que será imprescindível a entrada no Siconv, com respectiva senha de acesso, para inserir em anexo 
        <b>Termo de Capacidade Técnica, Declaração de Contrapartida e outros anexos</b> que se fazem necessários para que a proposta venha a ser considerada apta. Bem como, posteriormente enviar para analise.
    </p>
</div>

<div id="pop" style="border: solid 2px;">
    <div align="center" class="f-navExp">
        <div style="background-color: #fff; width: 700px;">
            <img src="<?php echo base_url('layout/assets/images/exportar_branco.gif'); ?>" style="margin-right: 20px;">
        </div>
    </div>
</div>