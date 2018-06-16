<?php
define("LATIN1_UC_CHARS", "ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝ");
define("LATIN1_LC_CHARS", "àáâãäåæçèéêëìíîïðñòóôõöøùúûüý");

function uc_latin1($str) {
    $str = strtoupper(strtr($str, LATIN1_LC_CHARS, LATIN1_UC_CHARS));
    return strtr($str, array("ß" => "SS"));
}

$permissoes = $this->permissoes_usuario->get_by_usuario_id($this->session->userdata('id_usuario'));
?>

<h1 class="bg-white content-heading border-bottom" style="text-align: center">Estrutura - Programas (Oportunidades)</h1>
<!-- Tabs -->
<div class="relativeWrap" >
    <div class="box-generic">

        <div class="tabsbar">
            <ul>
                <li class="glyphicons active"><a href="#estadual" data-toggle="tab">Voluntários <strong></strong></a></li>
                <li class="glyphicons"><a href="#municipal" data-toggle="tab">Emendas Parlamentares <strong></strong></a></li>
                <li class="glyphicons"><a href="#osc" data-toggle="tab">Emendas Espec. Concedente <strong></strong></a></li>
            </ul>
        </div>


        <div class="tab-content">

            <!-- Estadual -->
            <div class="tab-pane active" id="estadual">
                <input type="hidden" id="oculta_prog_pdf" name="oculta_prog_pdf" value="0">

                <?php if (isset($lista_voluntaria) AND $lista_voluntaria != null) { ?>
                    <h3 style="text-align: right;">
                        <span>
                            PROGRAMAS: <span class="num_prog" style="font-size: 22px;"><?php echo $num_rows_voluntaria; ?></span><br>
                            <!--
                            TOTAL: <?php
                            echo "<span style='font-size: 22px;' class='num_prog'>" . $total_rows . "</span>";
                            if (isset($pesquisa) && $pesquisa != "") {
                                echo "Pesquisa por: {$pesquisa}";
                            }
                            ?>
                            -->
                            PROGRAMAS UTILIZADOS: <span class="num_prog" style="font-size: 22px;"><?php echo $num_programas_utilizados_voluntaria; ?></span><br>
                            PROPOSTAS: <span class="num_prog" style="font-size: 22px;"><?php echo $num_propostas_programas_voluntaria; ?></span>
                        </span>

                        <input type="hidden" id="num_prog" value="<?php echo $num_rows_voluntaria; ?>">
                    </h3>

                    <h1 style="text-align: left;">
                        <p><input type="checkbox" class="selecionarTodos">&nbsp;<span style="color: #428bca; font-size: 14px;">Selecionar Todos</span></p>
                    </h1>

                    <div class="widget borders-none">
                        <div class="widget-body ">

                            <?php
                            $i = 1;
                            $xyz = 0;
                            foreach ($lista_voluntaria as $programa) {
                                ?>

                                <div class="innerAll border-bottom tickets" id="<?php echo $programa->codigo . "_ocultar"; ?>">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="pull-left">

                                                <label id="<?php echo explode('&path', explode('?id=', $programa->link)[1])[0]; ?>" class="label label-primary codigo" hidden="true"></label>
                                                <img src="<?php echo base_url(); ?>layout/assets/images/loader.gif" style="width: 20px;" id="<?php echo explode('&path', explode('?id=', $programa->link)[1])[0]; ?>" class="loader_<?php echo explode('&path', explode('?id=', $programa->link)[1])[0]; ?>" hidden="true">
                                                <a class="fa fa-refresh" style="border-radius: 2%; margin-right: 10px;" title="Atualizar Programa" id="<?php echo explode('&path', explode('?id=', $programa->link)[1])[0]; ?>"></a>

                                                <?php
                                                if (in_array($programa->codigo, $codigos_ocultos_voluntaria)) {
                                                    $oc_class = "label-info desoculta_programa ";
                                                    $oc_message = "desocultar";
                                                    $oc_label = "Desocultar";
                                                    $oc_collapse = "collapse";
                                                } else {
                                                    $oc_class = "label-success oculta_programa ";
                                                    $oc_message = "ocultar";
                                                    $oc_label = "Ocultar";
                                                    $oc_collapse = "in";
                                                }
                                                ?>
                                                <!-- Botão para ocultar programa (a opção de busca de programas ocultos na busca avançadaainda está escondida) -->
                                                <?php if ($this->session->userdata('nivel') != 1) { ?>
                                                    <label class="label <?php echo($oc_class) ?> " title="Clique para <?php echo($oc_message) ?> este programa." id="<?php echo $programa->codigo ?>" style="float: right; cursor: pointer; margin-left: 20px"><?php echo($oc_label) ?> Programa</label>
                                                <?php } ?>

                                                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse-<?php echo $programa->codigo . "-oc" ?>">
                                                    <label class="label label-primary codigo"><?php echo $programa->codigo; ?></label>
                                                </a>
                                            </div>
                                            <br><br>
                                            <a title="Abrir os detalhes do programa no SICONV" href="#" class="media-heading"> <?php echo $programa->nome; ?></a>

                                            <div id="collapse-<?php echo $programa->codigo . "-oc"; ?>" class="panel-collapse in <?php echo($oc_collapse) ?>">
                                                <ul class="media-list">
                                                    <li class="media">
                                                        <div class="pull-left">
                                                            <div class="center">
                                                                <div class="checkbox">

                                                                    <input form="gera_pdf" class="checkboxInput" type="checkbox" name="ides[]" value="<?php echo $programa->codigo ?>"/>

                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="media-body">
                                                            <?php
                                                            if (isset($programa->codigo_beneficiario)) {
                                                                $dados_emenda = $programa_model->get_dados_beneficiario($programa->codigo_beneficiario, $filtro['cnpj'], true);
                                                                foreach ($dados_emenda as $d) {
                                                                    echo "<br><label class=\"label label-info\">Emenda:</label> " . $d->emenda_cnpj . "|" . $d->emenda_nome . "|" . $d->emenda_valor;

                                                                    if (isset($d->emenda) && $d->emenda != "")
                                                                        echo "<br><label class=\"label label-info\">Parlementar:</label> " . $programa_model->get_parlamentar_by_emenda($d->emenda) . "|" . $d->emenda;

                                                                    echo "<br>";
                                                                }

                                                                echo "<hr>";
                                                            }
                                                            ?>


                                                            <div class="clearfix"></div>
                                                            <?php echo $programa->descricao; ?>
                                                            <br><br>
                                                            Orgão <label class="label label-info"><?php echo str_replace('MINISTÉRIO DA', '', str_replace('MINISTÉRIO DO', '', str_replace('MINISTERIO DA', '', str_replace('MINISTERIO DO', '', $programa->orgao)))); ?></label> 
                                                            <br>
                                                            <?php if (isset($programa->data_inicio) && strtotime($programa->data_inicio) > 0): ?>
                                                                <span style="color: red;">Proposta Voluntária</span><br>
                                                                <?php if (isset($programa->data_disp) && strtotime($programa->data_disp) > 0): ?>
                                                                    Data de Disponibilização <label class="label label-info" style="background-color: green"><?php echo implode("/", array_reverse(explode("-", $programa->data_disp))); ?></label> | 
                                                                <?php endif; ?>
                                                                Inicio da Vigência <label class="label label-info"><?php echo implode("/", array_reverse(explode("-", $programa->data_inicio))); ?></label>
                                                                | Final da Vigência <label class="label label-info"><?php echo implode("/", array_reverse(explode("-", $programa->data_fim))); ?></label>
                                                            <?php endif; ?>
                                                            <?php if (isset($programa->data_inicio_benef) && strtotime($programa->data_inicio_benef) > 0): ?>
                                                                <span style="color: red;">Proposta de Proponente Específico do Concedente</span><br>
                                                                <?php if (isset($programa->data_disp) && strtotime($programa->data_disp) > 0): ?>
                                                                    Data de Disponibilização <label class="label label-info" style="background-color: green"><?php echo implode("/", array_reverse(explode("-", $programa->data_disp))); ?></label> | 
                                                                <?php endif; ?>
                                                                Inicio da Vigência <label class="label label-info"><?php echo implode("/", array_reverse(explode("-", $programa->data_inicio_benef))); ?></label>
                                                                | Final da Vigência <label class="label label-info"><?php echo implode("/", array_reverse(explode("-", $programa->data_fim_benef))); ?></label>
                                                            <?php endif; ?>
                                                            <?php if (isset($programa->data_inicio_parlam) && strtotime($programa->data_inicio_parlam) > 0): ?>
                                                                <span style="color: red;">Proposta de Proponente de Emenda Parlamentar</span><br>
                                                                <?php if (isset($programa->data_disp) && strtotime($programa->data_disp) > 0): ?>
                                                                    Data de Disponibilização <label class="label label-info" style="background-color: green"><?php echo implode("/", array_reverse(explode("-", $programa->data_disp))); ?></label> | 
                                                                <?php endif; ?>
                                                                Inicio da Vigência <label class="label label-info"><?php echo implode("/", array_reverse(explode("-", $programa->data_inicio_parlam))); ?></label>
                                                                | Final da Vigência <label class="label label-info"><?php echo implode("/", array_reverse(explode("-", $programa->data_fim_parlam))); ?></label>
                                                            <?php endif; ?>
                                                            <?php
                                                            if (!empty($lista_cnpjs)) {
                                                                $propostas = $banco_proposta_model->get_propostas_proponente_programa($lista_cnpjs, $programa->codigo);
                                                                if (!empty($propostas)) {
                                                                    echo "<br><b>Utilizado nas Propostas:</b><br>";
                                                                    foreach ($propostas as $p) {
                                                                        if (isset($p->tipo)) {
                                                                            echo "<a target='_blank' href='" . base_url('index.php/in/dados_siconv/detalha_propostas_pareceres?id=' . $p->id_proposta) . "'>" . $p->codigo_siconv . "</a> - " . $p->tipo . " - " . $p->situacao . " - Valor: " . $p->valor_global . " <br>";
                                                                        } else {
                                                                            echo "<a target='_blank' href='" . base_url('index.php/in/dados_siconv/detalha_propostas_pareceres?id=' . $p->id_proposta) . "'>" . $p->codigo_siconv . "</a> - " . "Não verificado" . " - " . $p->situacao . " - Valor: " . $p->valor_global . " <br>";
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                            ?>
                                                            <br>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                            ?>


                        </div>
                    </div>
                    <?php
                } //fim if lista
                else {
                    ?>
                    <h1 style="text-align: center;">Nenhum resultado foi encontrado.</h1>
                <?php } ?>
            </div>


            <!-- Municipal -->
            <div class="tab-pane" id="municipal">
                                <input type="hidden" id="oculta_prog_pdf" name="oculta_prog_pdf" value="0">

                <?php if (isset($lista_ep) AND $lista_ep != null) { ?>
                    <h3 style="text-align: right;">
                        <span>
                            PROGRAMAS: <span class="num_prog" style="font-size: 22px;"><?php echo $num_rows_ep; ?></span><br>
                            <!--
                            TOTAL: <?php
                            echo "<span style='font-size: 22px;' class='num_prog'>" . $total_rows . "</span>";
                            if (isset($pesquisa) && $pesquisa != "") {
                                echo "Pesquisa por: {$pesquisa}";
                            }
                            ?>
                            -->
                            PROGRAMAS UTILIZADOS: <span class="num_prog" style="font-size: 22px;"><?php echo $num_programas_utilizados_ep; ?></span><br>
                            PROPOSTAS: <span class="num_prog" style="font-size: 22px;"><?php echo $num_propostas_programas_ep; ?></span>
                        </span>

                        <input type="hidden" id="num_prog" value="<?php echo $num_rows_ep; ?>">
                    </h3>

                    <h1 style="text-align: left;">
                        <p><input type="checkbox" class="selecionarTodos">&nbsp;<span style="color: #428bca; font-size: 14px;">Selecionar Todos</span></p>
                    </h1>

                    <div class="widget borders-none">
                        <div class="widget-body ">

                            <?php
                            $i = 1;
                            $xyz = 0;
                            foreach ($lista_ep as $programa) {
                                ?>

                                <div class="innerAll border-bottom tickets" id="<?php echo $programa->codigo . "_ocultar"; ?>">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="pull-left">

                                                <label id="<?php echo explode('&path', explode('?id=', $programa->link)[1])[0]; ?>" class="label label-primary codigo" hidden="true"></label>
                                                <img src="<?php echo base_url(); ?>layout/assets/images/loader.gif" style="width: 20px;" id="<?php echo explode('&path', explode('?id=', $programa->link)[1])[0]; ?>" class="loader_<?php echo explode('&path', explode('?id=', $programa->link)[1])[0]; ?>" hidden="true">
                                                <a class="fa fa-refresh" style="border-radius: 2%; margin-right: 10px;" title="Atualizar Programa" id="<?php echo explode('&path', explode('?id=', $programa->link)[1])[0]; ?>"></a>

                                                <?php
                                                if (in_array($programa->codigo, $codigos_ocultos_ep)) {
                                                    $oc_class = "label-info desoculta_programa ";
                                                    $oc_message = "desocultar";
                                                    $oc_label = "Desocultar";
                                                    $oc_collapse = "collapse";
                                                } else {
                                                    $oc_class = "label-success oculta_programa ";
                                                    $oc_message = "ocultar";
                                                    $oc_label = "Ocultar";
                                                    $oc_collapse = "in";
                                                }
                                                ?>
                                                <!-- Botão para ocultar programa (a opção de busca de programas ocultos na busca avançadaainda está escondida) -->
                                                <?php if ($this->session->userdata('nivel') != 1) { ?>
                                                    <label class="label <?php echo($oc_class) ?> " title="Clique para <?php echo($oc_message) ?> este programa." id="<?php echo $programa->codigo ?>" style="float: right; cursor: pointer; margin-left: 20px"><?php echo($oc_label) ?> Programa</label>
                                                <?php } ?>

                                                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse-<?php echo $programa->codigo . "-oc" ?>">
                                                    <label class="label label-primary codigo"><?php echo $programa->codigo; ?></label>
                                                </a>
                                            </div>
                                            <br><br>
                                            <a title="Abrir os detalhes do programa no SICONV" href="#" class="media-heading"> <?php echo $programa->nome; ?></a>

                                            <div id="collapse-<?php echo $programa->codigo . "-oc"; ?>" class="panel-collapse <?php echo($oc_collapse) ?>">
                                                <ul class="media-list">
                                                    <li class="media">
                                                        <div class="pull-left">
                                                            <div class="center">
                                                                <div class="checkbox">

                                                                    <input form="gera_pdf" class="checkboxInput" type="checkbox" name="ides[]" value="<?php echo $programa->codigo ?>"/>

                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="media-body">
                                                            <?php
                                                            if (isset($programa->codigo_beneficiario)) {
                                                                $dados_emenda = $programa_model->get_dados_beneficiario($programa->codigo_beneficiario, $filtro['cnpj'], true);
                                                                foreach ($dados_emenda as $d) {
                                                                    echo "<br><label class=\"label label-info\">Emenda:</label> " . $d->emenda_cnpj . "|" . $d->emenda_nome . "|" . $d->emenda_valor;

                                                                    if (isset($d->emenda) && $d->emenda != "")
                                                                        echo "<br><label class=\"label label-info\">Parlementar:</label> " . $programa_model->get_parlamentar_by_emenda($d->emenda) . "|" . $d->emenda;

                                                                    echo "<br>";
                                                                }

                                                                echo "<hr>";
                                                            }
                                                            ?>


                                                            <div class="clearfix"></div>
                                                            <?php echo $programa->descricao; ?>
                                                            <br><br>
                                                            Orgão <label class="label label-info"><?php echo str_replace('MINISTÉRIO DA', '', str_replace('MINISTÉRIO DO', '', str_replace('MINISTERIO DA', '', str_replace('MINISTERIO DO', '', $programa->orgao)))); ?></label> 
                                                            <br>
                                                            <?php if (isset($programa->data_inicio) && strtotime($programa->data_inicio) > 0): ?>
                                                                <span style="color: red;">Proposta Voluntária</span><br>
                                                                <?php if (isset($programa->data_disp) && strtotime($programa->data_disp) > 0): ?>
                                                                    Data de Disponibilização <label class="label label-info" style="background-color: green"><?php echo implode("/", array_reverse(explode("-", $programa->data_disp))); ?></label> | 
                                                                <?php endif; ?>
                                                                Inicio da Vigência <label class="label label-info"><?php echo implode("/", array_reverse(explode("-", $programa->data_inicio))); ?></label>
                                                                | Final da Vigência <label class="label label-info"><?php echo implode("/", array_reverse(explode("-", $programa->data_fim))); ?></label>
                                                            <?php endif; ?>
                                                            <?php if (isset($programa->data_inicio_benef) && strtotime($programa->data_inicio_benef) > 0): ?>
                                                                <span style="color: red;">Proposta de Proponente Específico do Concedente</span><br>
                                                                <?php if (isset($programa->data_disp) && strtotime($programa->data_disp) > 0): ?>
                                                                    Data de Disponibilização <label class="label label-info" style="background-color: green"><?php echo implode("/", array_reverse(explode("-", $programa->data_disp))); ?></label> | 
                                                                <?php endif; ?>
                                                                Inicio da Vigência <label class="label label-info"><?php echo implode("/", array_reverse(explode("-", $programa->data_inicio_benef))); ?></label>
                                                                | Final da Vigência <label class="label label-info"><?php echo implode("/", array_reverse(explode("-", $programa->data_fim_benef))); ?></label>
                                                            <?php endif; ?>
                                                            <?php if (isset($programa->data_inicio_parlam) && strtotime($programa->data_inicio_parlam) > 0): ?>
                                                                <span style="color: red;">Proposta de Proponente de Emenda Parlamentar</span><br>
                                                                <?php if (isset($programa->data_disp) && strtotime($programa->data_disp) > 0): ?>
                                                                    Data de Disponibilização <label class="label label-info" style="background-color: green"><?php echo implode("/", array_reverse(explode("-", $programa->data_disp))); ?></label> | 
                                                                <?php endif; ?>
                                                                Inicio da Vigência <label class="label label-info"><?php echo implode("/", array_reverse(explode("-", $programa->data_inicio_parlam))); ?></label>
                                                                | Final da Vigência <label class="label label-info"><?php echo implode("/", array_reverse(explode("-", $programa->data_fim_parlam))); ?></label>
                                                            <?php endif; ?>
                                                            <?php
                                                            if (!empty($lista_cnpjs)) {
                                                                $propostas = $banco_proposta_model->get_propostas_proponente_programa($lista_cnpjs, $programa->codigo);
                                                                if (!empty($propostas)) {
                                                                    echo "<br><b>Utilizado nas Propostas:</b><br>";
                                                                    foreach ($propostas as $p) {
                                                                        if (isset($p->tipo)) {
                                                                            echo "<a target='_blank' href='" . base_url('index.php/in/dados_siconv/detalha_propostas_pareceres?id=' . $p->id_proposta) . "'>" . $p->codigo_siconv . "</a> - " . $p->tipo . " - " . $p->situacao . " - Valor: " . $p->valor_global . " <br>";
                                                                        } else {
                                                                            echo "<a target='_blank' href='" . base_url('index.php/in/dados_siconv/detalha_propostas_pareceres?id=' . $p->id_proposta) . "'>" . $p->codigo_siconv . "</a> - " . "Não verificado" . " - " . $p->situacao . " - Valor: " . $p->valor_global . " <br>";
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                            ?>
                                                            <br>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                            ?>


                        </div>
                    </div>
                    <?php
                } //fim if lista
                else {
                    ?>
                    <h1 style="text-align: center;">Nenhum resultado foi encontrado.</h1>
                <?php } ?>
            </div>

            <!-- OSC -->
            <div class="tab-pane" id="osc">
                                <input type="hidden" id="oculta_prog_pdf" name="oculta_prog_pdf" value="0">

                <?php if (isset($lista_ec) AND $lista_ec != null) { ?>
                    <h3 style="text-align: right;">
                        <span>
                            PROGRAMAS: <span class="num_prog" style="font-size: 22px;"><?php echo $num_rows_ec; ?></span><br>
                            <!--
                            TOTAL: <?php
                            echo "<span style='font-size: 22px;' class='num_prog'>" . $total_rows_ec . "</span>";
                            if (isset($pesquisa) && $pesquisa != "") {
                                echo "Pesquisa por: {$pesquisa}";
                            }
                            ?>
                            -->
                            PROGRAMAS UTILIZADOS: <span class="num_prog" style="font-size: 22px;"><?php echo $num_programas_utilizados_ec; ?></span><br>
                            PROPOSTAS: <span class="num_prog" style="font-size: 22px;"><?php echo $num_propostas_programas_ec; ?></span>
                        </span>

                        <input type="hidden" id="num_prog" value="<?php echo $num_rows_ec; ?>">
                    </h3>

                    <h1 style="text-align: left;">
                        <p><input type="checkbox" class="selecionarTodos">&nbsp;<span style="color: #428bca; font-size: 14px;">Selecionar Todos</span></p>
                    </h1>

                    <div class="widget borders-none">
                        <div class="widget-body ">

                            <?php
                            $i = 1;
                            $xyz = 0;
                            foreach ($lista_ec as $programa) {
                                ?>

                                <div class="innerAll border-bottom tickets" id="<?php echo $programa->codigo . "_ocultar"; ?>">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="pull-left">

                                                <label id="<?php echo explode('&path', explode('?id=', $programa->link)[1])[0]; ?>" class="label label-primary codigo" hidden="true"></label>
                                                <img src="<?php echo base_url(); ?>layout/assets/images/loader.gif" style="width: 20px;" id="<?php echo explode('&path', explode('?id=', $programa->link)[1])[0]; ?>" class="loader_<?php echo explode('&path', explode('?id=', $programa->link)[1])[0]; ?>" hidden="true">
                                                <a class="fa fa-refresh" style="border-radius: 2%; margin-right: 10px;" title="Atualizar Programa" id="<?php echo explode('&path', explode('?id=', $programa->link)[1])[0]; ?>"></a>

                                                <?php
                                                if (in_array($programa->codigo, $codigos_ocultos_ec)) {
                                                    $oc_class = "label-info desoculta_programa ";
                                                    $oc_message = "desocultar";
                                                    $oc_label = "Desocultar";
                                                    $oc_collapse = "collapse";
                                                } else {
                                                    $oc_class = "label-success oculta_programa ";
                                                    $oc_message = "ocultar";
                                                    $oc_label = "Ocultar";
                                                    $oc_collapse = "in";
                                                }
                                                ?>
                                                <!-- Botão para ocultar programa (a opção de busca de programas ocultos na busca avançadaainda está escondida) -->
                                                <?php if ($this->session->userdata('nivel') != 1) { ?>
                                                    <label class="label <?php echo($oc_class) ?> " title="Clique para <?php echo($oc_message) ?> este programa." id="<?php echo $programa->codigo ?>" style="float: right; cursor: pointer; margin-left: 20px"><?php echo($oc_label) ?> Programa</label>
                                                <?php } ?>

                                                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse-<?php echo $programa->codigo . "-oc" ?>">
                                                    <label class="label label-primary codigo"><?php echo $programa->codigo; ?></label>
                                                </a>
                                            </div>
                                            <br><br>
                                            <a title="Abrir os detalhes do programa no SICONV" href="#" class="media-heading"> <?php echo $programa->nome; ?></a>

                                            <div id="collapse-<?php echo $programa->codigo . "-oc"; ?>" class="panel-collapse <?php echo($oc_collapse) ?>">
                                                <ul class="media-list">
                                                    <li class="media">
                                                        <div class="pull-left">
                                                            <div class="center">
                                                                <div class="checkbox">

                                                                    <input form="gera_pdf" class="checkboxInput" type="checkbox" name="ides[]" value="<?php echo $programa->codigo ?>"/>

                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="media-body">
                                                            <?php
                                                            if (isset($programa->codigo_beneficiario)) {
                                                                $dados_emenda = $programa_model->get_dados_beneficiario($programa->codigo_beneficiario, $filtro['cnpj'], true);
                                                                foreach ($dados_emenda as $d) {
                                                                    echo "<br><label class=\"label label-info\">Emenda:</label> " . $d->emenda_cnpj . "|" . $d->emenda_nome . "|" . $d->emenda_valor;

                                                                    if (isset($d->emenda) && $d->emenda != "")
                                                                        echo "<br><label class=\"label label-info\">Parlementar:</label> " . $programa_model->get_parlamentar_by_emenda($d->emenda) . "|" . $d->emenda;

                                                                    echo "<br>";
                                                                }

                                                                echo "<hr>";
                                                            }
                                                            ?>


                                                            <div class="clearfix"></div>
                                                            <?php echo $programa->descricao; ?>
                                                            <br><br>
                                                            Orgão <label class="label label-info"><?php echo str_replace('MINISTÉRIO DA', '', str_replace('MINISTÉRIO DO', '', str_replace('MINISTERIO DA', '', str_replace('MINISTERIO DO', '', $programa->orgao)))); ?></label> 
                                                            <br>
                                                            <?php if (isset($programa->data_inicio) && strtotime($programa->data_inicio) > 0): ?>
                                                                <span style="color: red;">Proposta Voluntária</span><br>
                                                                <?php if (isset($programa->data_disp) && strtotime($programa->data_disp) > 0): ?>
                                                                    Data de Disponibilização <label class="label label-info" style="background-color: green"><?php echo implode("/", array_reverse(explode("-", $programa->data_disp))); ?></label> | 
                                                                <?php endif; ?>
                                                                Inicio da Vigência <label class="label label-info"><?php echo implode("/", array_reverse(explode("-", $programa->data_inicio))); ?></label>
                                                                | Final da Vigência <label class="label label-info"><?php echo implode("/", array_reverse(explode("-", $programa->data_fim))); ?></label>
                                                            <?php endif; ?>
                                                            <?php if (isset($programa->data_inicio_benef) && strtotime($programa->data_inicio_benef) > 0): ?>
                                                                <span style="color: red;">Proposta de Proponente Específico do Concedente</span><br>
                                                                <?php if (isset($programa->data_disp) && strtotime($programa->data_disp) > 0): ?>
                                                                    Data de Disponibilização <label class="label label-info" style="background-color: green"><?php echo implode("/", array_reverse(explode("-", $programa->data_disp))); ?></label> | 
                                                                <?php endif; ?>
                                                                Inicio da Vigência <label class="label label-info"><?php echo implode("/", array_reverse(explode("-", $programa->data_inicio_benef))); ?></label>
                                                                | Final da Vigência <label class="label label-info"><?php echo implode("/", array_reverse(explode("-", $programa->data_fim_benef))); ?></label>
                                                            <?php endif; ?>
                                                            <?php if (isset($programa->data_inicio_parlam) && strtotime($programa->data_inicio_parlam) > 0): ?>
                                                                <span style="color: red;">Proposta de Proponente de Emenda Parlamentar</span><br>
                                                                <?php if (isset($programa->data_disp) && strtotime($programa->data_disp) > 0): ?>
                                                                    Data de Disponibilização <label class="label label-info" style="background-color: green"><?php echo implode("/", array_reverse(explode("-", $programa->data_disp))); ?></label> | 
                                                                <?php endif; ?>
                                                                Inicio da Vigência <label class="label label-info"><?php echo implode("/", array_reverse(explode("-", $programa->data_inicio_parlam))); ?></label>
                                                                | Final da Vigência <label class="label label-info"><?php echo implode("/", array_reverse(explode("-", $programa->data_fim_parlam))); ?></label>
                                                            <?php endif; ?>
                                                            <?php
                                                            if (!empty($lista_cnpjs)) {
                                                                $propostas = $banco_proposta_model->get_propostas_proponente_programa($lista_cnpjs, $programa->codigo);
                                                                if (!empty($propostas)) {
                                                                    echo "<br><b>Utilizado nas Propostas:</b><br>";
                                                                    foreach ($propostas as $p) {
                                                                        if (isset($p->tipo)) {
                                                                            echo "<a target='_blank' href='" . base_url('index.php/in/dados_siconv/detalha_propostas_pareceres?id=' . $p->id_proposta) . "'>" . $p->codigo_siconv . "</a> - " . $p->tipo . " - " . $p->situacao . " - Valor: " . $p->valor_global . " <br>";
                                                                        } else {
                                                                            echo "<a target='_blank' href='" . base_url('index.php/in/dados_siconv/detalha_propostas_pareceres?id=' . $p->id_proposta) . "'>" . $p->codigo_siconv . "</a> - " . "Não verificado" . " - " . $p->situacao . " - Valor: " . $p->valor_global . " <br>";
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                            ?>
                                                            <br>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                            ?>


                        </div>
                    </div>
                    <?php
                } //fim if lista
                else {
                    ?>
                    <h1 style="text-align: center;">Nenhum resultado foi encontrado.</h1>
                <?php } ?>
            </div>



            <!-- padrão 
            <div class="tab-pane" id="tab3-3">
            
            </div>
            -->
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {


        
    });

    $(".selecionarTodos").click(function () {
        $(".checkboxInput").each(function () {
            if ($("#" + $(this).val() + "_ocultar").is(':visible'))
                $(this).attr("checked", $(".selecionarTodos").is(":checked"));
            else
                $(this).attr("checked", false);
        });
    });
</script>