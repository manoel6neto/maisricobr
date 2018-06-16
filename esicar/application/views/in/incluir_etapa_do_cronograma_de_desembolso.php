<script type="text/javascript" language="Javascript1.1"
src="<?php echo base_url(); ?>configuracoes/js/dimmingdiv.js"></script>
<script type="text/javascript" language="Javascript1.1"
src="<?php echo base_url(); ?>configuracoes/js/layout-common.js"></script>
<script type="text/javascript" language="Javascript1.1"
src="<?php echo base_url(); ?>configuracoes/js/key-events.js"></script>
<script type="text/javascript" language="Javascript1.1"
src="<?php echo base_url(); ?>configuracoes/js/scripts.js"></script>
<script type="text/javascript" language="Javascript1.1"
src="<?php echo base_url(); ?>configuracoes/js/cpf.js"></script>
<script type="text/javascript" language="Javascript1.1"
src="<?php echo base_url(); ?>configuracoes/js/moeda.js"></script>
<script type="text/javascript" language="Javascript1.1"
src="<?php echo base_url(); ?>configuracoes/js/textCounter.js"></script>
<script type="text/javascript" language="Javascript1.1"
src="<?php echo base_url(); ?>configuracoes/js/calculaValor.js"></script>
<script type="text/javascript" language="Javascript1.1"
src="<?php echo base_url(); ?>configuracoes/js/form-validation.js"></script>
<script type="text/javascript" language="Javascript1.1">
    var HINTS_ITEMS = {
        'manter.programa.proposta.valores.do.programa.salvar.param.id.programa.title': 'idPrograma',
        'manter.programa.proposta.valores.do.programa.salvar.param.id.proposta.programa.title': 'idPropostaPrograma',
        'manter.programa.proposta.valores.do.programa.salvar.param.codigo.programa.title': 'codigoPrograma',
        'manter.programa.proposta.valores.do.programa.salvar.param.nome.programa.title': 'nomePrograma',
        'manter.programa.proposta.valores.do.programa.salvar.param.qualificacao.proponente.title': 'qualificacaoProponente is required',
        'manter.programa.proposta.valores.do.programa.salvar.param.valor.global.title': 'valorGlobal is required',
        'manter.programa.proposta.valores.do.programa.salvar.param.valor.repasse.title': 'valorRepasse is required',
        'manter.programa.proposta.valores.do.programa.salvar.param.valor.contrapartida.title': 'valorContrapartida is required',
        'manter.programa.proposta.valores.do.programa.salvar.param.valor.contrapartida.financeira.title': 'valorContrapartidaFinanceira is required',
        'manter.programa.proposta.valores.do.programa.salvar.param.valor.contrapartida.bens.servicos.title': 'valorContrapartidaBensServicos',
        'manter.programa.proposta.valores.do.programa.salvar.param.objetos.title': 'objetos is required',
        'manter.programa.proposta.valores.do.programa.salvar.param.aceita.despesa.administrativa.title': 'aceitaDespesaAdministrativa',
        'manter.programa.proposta.valores.do.programa.salvar.param.percentual.maximo.contrapartida.bens.title': 'percentualMaximoContrapartidaBens',
        'manter.programa.proposta.valores.do.programa.salvar.param.percentual.minimo.contrapartida.title': 'percentualMinimoContrapartida',
        'manter.programa.proposta.valores.do.programa.salvar.param.valor.max.repasse.title': 'valorMaxRepasse',
        'manter.programa.proposta.valores.do.programa.salvar.param.valor.maximo.repasse.title': 'valorMaximoRepasse',
        'manter.programa.proposta.valores.do.programa.salvar.param.id.title': 'id',
        'salvar': 'Salvar',
        'salvar_no': 'You are not allowed to call this action',
        'salvar_reset': 'Reset',
        'salvar_noreset': 'You are not allowed to reset',
        'cancelar': 'Cancelar',
        'cancelar_no': 'You are not allowed to call this action',
        'cancelar_reset': 'Reset',
        'cancelar_noreset': 'You are not allowed to reset',
        'calendar.popup': 'Clique aqui para abrir um calend\u00E1rio e escolher uma data'
    };

    var HINTS_COMBO_ITEMS = {

    };

    var hints = new THints(HINTS_CFG, HINTS_ITEMS);

    var HINTS_COMBO_CFG = {
        'top': 5, 'left': 30, 'css': 'hintsClass',
        'show_delay': 400, 'hide_delay': 3000, 'wise': false,
        'follow': false, 'z-index': 110
    };

    var combohints = new THints(HINTS_COMBO_CFG, HINTS_COMBO_ITEMS);

    function getComboHintTitle(combo, title) {
        return title + combo.selectedIndex;
    }

    var comboTimerDelay = 10;

    function handleComboMouseOut(event) {
        if (event.target.type === 'select-one')
            window.setTimeout('combohints.hide()', comboTimerDelay);
    }
    //-->
</script>
<script type="text/javascript">
    function UpdateDoubleSelect(combo, valor) {
        eval('combo = document.' +
                combo + ';');
        for (index = 0;
                index < combo.length;
                index++) {
            if (combo[index].value === valor)
                combo.selectedIndex = index;
        }
    }

    function descriptionDestination(key, componenteNome) {


        var HINTS_DESTINATION = {

        };



        var componenteDestino = document.getElementsByName(componenteNome);

        if (componenteDestino[0] !== null)
        {

            componenteDestino[0].value = HINTS_DESTINATION[key];
        }
    }
</script>
<div class="innerAll col-md-12 col-sm-12" style="margin-bottom: 60px !important;">

    <table class="table">
        <thead>
            <tr><th style="color: red; font-size: 16px;">Valor de Referência</th></tr>
            <tr>
                <td>Especificação da meta</td>
                <td>Valor associado à meta</td>
                <td style="color: red;">Valor Cadastrado</td>
                <td style="color: green;">Valor a Cadastrar</td>
            </tr>
        </thead>

        <tbody>
            <tr>
                <td>
                    <?php if (isset($meta->especificacao) !== false) echo $meta->especificacao; ?>
                </td>
                <td>
                    <?php if (isset($meta_cronograma->valor) !== false) echo number_format($meta_cronograma->valor, 2, ",", "."); ?>
                </td>
                <td style="color: red;">
                    <?php
                    $valor = 0;
                    $total = 0;
                    foreach ($etapas as $etapa)
                        $total += $trabalho_model->obter_etapa_cronograma_valor($meta_cronograma->idCronograma_meta, $etapa->idEtapa);

                    if (isset($meta_cronograma->valor))
                        $valor = $meta_cronograma->valor;
                    echo number_format(round($total, 2), 2, ",", ".");
                    ?>
                </td>
                <td style="color: green;" id="valorRestante">
                    <?php
                    $v = round($valor, 2) - round($total, 2);
                    echo number_format($v, 2, ",", ".")
                    ?>
                </td>
            </tr>
        </tbody>
    </table>
    <h1 class="bg-white content-heading border-bottom" style="color: #428bca;">Associar Etapa</h1>
    <table class="table">
        <thead>
            <tr>
                <th class="numero">Especificação da etapa</th>
                <th class="especificacao">Valor Total da etapa</th>
                <th class="especificacao">Valor vinculado à etapa</th>
                <th class="especificacao">Valor disponível</th>
                <th class="especificacao">Valor a ser associado</th>
                <th></th>
            </tr>
        </thead>
        <tbody id="tbodyrow">
            <?php
            foreach ($etapas as $etapa) {
                ?>
            <form name="" method="post" enctype="multipart/form-data">
                <input type="hidden" name="idEtapa" value="<?php echo $etapa->idEtapa ?>"> 
                <input type="hidden" name="meta_cronograma_id" value="<?php echo $meta_cronograma->idCronograma_meta ?>">

                <tr class="odd">
                    <td style="width: 40%;">
                        <div class="numero"><?php echo $etapa->especificacao ?></div>
                    </td>
                    <td>
                        <div class="especificacao">R$ <?php echo number_format($etapa->total, 2, ",", "."); ?></div>
                    </td>
                    <td>
                        <div class="especificacao">R$ <?php echo number_format($trabalho_model->obter_restante_etapa($etapa->idEtapa, true), 2, ",", "."); ?></div>
                    </td>
                    <td>
                        <div class="especificacao">R$ <?php echo number_format(round($etapa->total, 2) - round($trabalho_model->obter_restante_etapa($etapa->idEtapa, true), 2), 2, ",", "."); ?></div>
                    </td>
                    <td>
                        <div class="especificacao">  
                            <?php
                            if ($leitura_pessoa == true) {
                                ?>
                            </div>
                        </td>
                        <td>

                            <?php
                        } else {
                            ?>
                            <input class='form-control' type="text"
                                   value="<?php
                                   $valor = $trabalho_model->obter_etapa_cronograma_valor($meta_cronograma->idCronograma_meta, $etapa->idEtapa);
                                   if (isset($valor) !== false)
                                       echo number_format($valor, 2, ",", ".");
                                   ?>"
                                   name="valor" onkeypress="reais(this, event)" maxlength="14"
                                   onkeydown="backspace(this, event)">
                            </div>
                        </td>
                        <td>
                            <?php $disabled = $valor >= 0 ? "" : "disabled"; ?>
                            <?php $class = $valor > 0 ? "success" : "primary"; ?>
                            <input class="btn btn-sm btn-primary" type="submit" name="Associar" value="Associar" id="Associar">
                            <input class="btn btn-sm btn-<?php echo $class; ?>" type="submit" name="Desassociar" value="Desassociar" id="Desassociar" <?php echo $disabled; ?>>
                            <?php
                        }
                        ?>
            </form>
            </td>
            </tr>
            <?php
        }
        ?>
        </tbody>
    </table>
    <a class="btn btn-primary" id="btnVoltar" href="<?php echo base_url(); ?>index.php/in/usuario/incluir_meta_do_cronograma_de_desembolso?cronograma=<?php echo $cronograma_id; ?>&id=<?php echo $id; ?>&edita_gestor=<?php echo $edita_gestor ?>">Voltar</a>
</div>


<script>
    $(document).ready(function () {
        $("#btnVoltar").click(function () {
            if (parseInt($("#valorRestante").html()) > 0) {
                if (confirm("Existem valores dessa meta a serem associados.\r\nDeseja realmente sair?"))
                    return true;
                else
                    return false;
            }
        });
    });
</script>