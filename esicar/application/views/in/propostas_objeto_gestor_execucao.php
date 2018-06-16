<script type="text/javascript" src="<?php echo base_url(); ?>configuracoes/js/maskedinput.min.js"></script>
<script src="<?php echo base_url('layout/assets/components/library/multiselect/js/bootstrap-multiselect.js'); ?>"></script>
<?php $permissoes = $this->permissoes_usuario->get_by_usuario_id($this->session->userdata('id_usuario')); ?>
<?php $filtro = $this->session->userdata('filtros'); ?>
<div id="content" class="innerAll bg-white ">
    <h1 class="bg-white content-heading border-bottom">Propostas por Objeto</h1>

    <style>
        .panel-heading:hover {
            background-color: #DDD !important;
        }
    </style>


    <form class="form-horizontal" role="form" name="carrega_dados" method="post" id="carrega_dados" action="visualiza_propostas_por_objeto"></form>


    <form method="post" action="<?php echo base_url('index.php/in/get_propostas/atualiza_pareceres'); ?>" id="atualiza_pareceres">
    </form>

    <h1 class="bg-white" style="text-align: left;">
        <p>
            <input type="checkbox" name="anos[]" form="carrega_dados" <?php
            if (isset($filtro['anos']) && in_array("TODOS", $filtro['anos'])) {
                echo "checked='checked'";
            }
            ?> value="TODOS" class="selecionarTodos">&nbsp;<span style="color: #428bca; font-size: 14px;">Todos os anos</span>
                   <?php foreach ($anos as $ano): ?>
                &nbsp;&nbsp;<input type="checkbox" form="carrega_dados" <?php
                if (isset($filtro['anos']) && in_array($ano->ano, $filtro['anos'])) {
                    echo "checked='checked'";
                }
                ?> class="anos" name="anos[]" <?php echo (!isset($filtro['anos']) && $ano->ano == date("Y")) ? "checked='checked'" : ""; ?> value="<?php echo $ano->ano; ?>">&nbsp;<span style="color: #428bca; font-size: 14px;"><?php echo $ano->ano; ?></span>
                               <?php endforeach; ?>
            <br/>
            <span style="color: #000000; font-size: 14px;">Status:</span>
            <br/>
            <input type="checkbox" name="status_prop[]" form="carrega_dados" <?php
            if (isset($filtro['status_prop']) && in_array('1', $filtro['status_prop']) || !isset($filtro['status_prop'])) {
                echo "checked='checked'";
            }
            ?> value="1">
            &nbsp;<span style="color: #428bca; font-size: 14px;">Enviadas e aprovadas</span>
            <input type="checkbox" name="status_prop[]" form="carrega_dados" <?php
            if (isset($filtro['status_prop']) && in_array('2', $filtro['status_prop'])) {
                echo "checked='checked'";
            }
            ?> value="2">
            &nbsp;<span style="color: #428bca; font-size: 14px;">Enviadas para análise</span>
        <div class="row">
            <div class="form-group col-md-4">
                <label for="regiao">Regiões</label>
                <select name="regiao" id="regiao" class="form-control" form="carrega_dados">
                    <?php foreach ($regioes as $value): ?>
                        <option value="<?= $value['sigla'] ?>" <?= isset($filtro['regiao']) && $filtro['regiao'] == $value['sigla'] ? 'selected' : '' ?> <?= isset($value['disabled']) ? $value['disabled'] : '' ?> ><?= $value['nome'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group col-md-4">
                <label for="estado">Estados</label>
                <select name="estado" id="estado" class="form-control" form="carrega_dados">
                    <option value="">TODOS</option>
                </select>
            </div>

            <div class="form-group col-md-4">
                <?php echo form_label('Múnicipio', 'municipio'); ?>
                <?php echo form_dropdown('municipio', array("" => "TODOS"), '', "id='municipio' class='form-control' form='carrega_dados'"); ?>
            </div>

        </div>

        <div class="row">
            <div class="form-group col-md-4">
                <?php echo form_label('Esfera Administrativa', 'esfera'); ?>
                <br>
                <select name="esfera[]" id="esfera" class="form-control" required="true" multiple="multiple" style="display: none;" form="carrega_dados">
                    <?= $esferas ?>
                </select>
            </div>
            <div class="form-group col-md-4">
                <?php echo form_label('Proponente', 'proponente'); ?>
                <br>
                <?php echo form_dropdown('proponente[]', array("" => "- Escolha uma esfera -"), '', "id='proponente' class='form-control'  required='true' multiple='multiple' style='display: none;' form='carrega_dados'"); ?>
            </div>
        </div>

        <div class="input-group ">
            <input name="pesquisa" type="text" class="form-control"
                   placeholder="Pesquisar" form="carrega_dados"
                   <?php
                   if (isset($filtro['pesquisa'])) {
                       echo "value=\"{$filtro['pesquisa']}\"";
                   }
                   ?> />
            <div class="input-group-btn">
                <button class="btn btn-info" type="submit" form="carrega_dados" id="pesquisa_dados">
                    <i class="fa fa-search"></i>
                </button>
            </div>
        </div>
        <?php if (count($dados_propostas) > 0): ?>
            <h1 class="bg-white" style="text-align: right;">
                <form method="post" id="gera_pdf" action="<?php echo base_url(); ?>index.php/in/dados_siconv/gerapdf_lista_propostas_objeto"  target="_blank">
                    <?php if ($permissoes->relatorio_programa): ?>
                        <input type="submit" class="btn btn-primary" id="gerarPdf" style="float: left;" value="Gerar PDF"/>
                    <?php endif; ?>
                </form>
                <br>
            </h1>
        <?php endif; ?>
        <br/>
        <input type="submit" id="pesquisar" value="Pesquisar" form="carrega_dados" for="carrega_dados" class="btn btn-primary">
<!-- 		<a class="btn btn-info" title="Atualizar Informações" id="atualizaParecer"><i class="fa fa-refresh"></i></a> -->

        <img src="<?php echo base_url(); ?>layout/assets/images/loader.gif" style="width: 30px;" id="loader">
        </p>
    </h1>

    <?php if (count($dados_propostas) > 0): ?>
        <table class="table">
            <!--Selecionar todas-->
            <tr>
                <td colspan="14">
                    <p><input type="checkbox" class="selecionarTodosList">&nbsp;<span style="color: #292c2e; font-size: 14px; padding-left: 6px;">   Selecionar todas as propostas abaixo</span></p>
                </td>
            </tr>
            <tr>
                <th></th>
                <th></th>
                <th>Objeto</th>
                <th>Nº Proposta</th>
                <th>Valor Global</th>
                <th></th>
            </tr>

            <?php $indiceProposta = 1; ?>
            <?php foreach ($dados_propostas as $propostas): ?>
                <tr>
                    <td><input form="gera_pdf" class="checkboxInput" type="checkbox" name="ides[]" value="<?php echo $propostas->id_proposta ?>"/></td>
                    <td><?php echo $indiceProposta; ?></td>
                    <td><?php echo $propostas->objeto; ?></td>
                    <td><a href="https://www.convenios.gov.br/siconv/ConsultarProposta/ResultadoDaConsultaDePropostaDetalharProposta.do?idProposta=<?php echo $propostas->id_siconv; ?>&path=/MostraPrincipalConsultarPrograma.do&Usr=guest&Pwd=guest" target="_blank"><?php echo $propostas->codigo_siconv; ?></a></td>
                    <td style="text-align: center;"><?php echo str_replace("R$ ", "", $propostas->valor_global); ?></td>
                    <td style="min-width: 80px;"><a href="detalha_propostas_objeto?id=<?php echo $propostas->id_proposta; ?>">Detalhes e Pareceres</a></td>
                </tr>

                <?php $indiceProposta++; ?>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <h1 style="text-align: center;">Nenhum dado encontrado.</h1>
    <?php endif; ?>
</div>

<script type="text/javascript">

    $('body').toggleClass('sidebar-mini');

    function mascaraData(campoData) {
        var data = campoData.value;
        if (data.length == 2) {
            data = data + '/';
            campoData.value = data;
            return true;
        }
        if (data.length == 5) {
            data = data + '/';
            campoData.value = data;
            return true;
        }
    }

    $(document).ready(function () {
        $("#loader").hide();
        $("#cod_parlamentar").hide();

        $("#atualizaParecer").click(function () {
            var qtdIDs = 0;
            var numIDs = 0;
            $(".ids_siconv").each(function () {
                $(this).attr('form', 'atualiza_pareceres');
//            var urlParecer = '<?php echo base_url() . 'index.php/in/get_propostas/get_parecer_empenho_banco_proposta_siconv/' ?>'+$(this).val();
//             $.when(
//                 $.ajax({
//                     url:urlParecer,
//                     type:'get',
//                     dataType:'html',
//                     beforeSend:function(){
//                         $("#loader").slideDown();
//                     },
//                     success:function(data){

//                     }
//                 });
//             ).done(function(){
//                 qdtIDs++;
//             });

//             numIDs++;
            });

            $("#loader").slideDown();

            $("#atualiza_pareceres").submit();

//         location.href=$(location).attr('href');
            return false;
        });

        $(".selecionarTodos").click(function () {
            $(".anos").each(function () {
                if ($(".selecionarTodos").is(":checked"))
                    $(this).attr("checked", $(".selecionarTodos").is(":checked"));
                else {
                    if ($(this).val() != "<?php echo date("Y"); ?>")
                        $(this).attr("checked", $(".selecionarTodos").is(":checked"));
                }
            });
        });

        $(".anos").click(function () {
            if (!$(this).is(":checked")) {
                if ($(".selecionarTodos").is(":checked"))
                    $(".selecionarTodos").attr("checked", false);
            }
        });

        $(".selecionarTodosList").click(function () {
            $(".checkboxInput").each(function () {
                if ($(".selecionarTodosList").is(":checked")) {
                    $(this).attr("checked", $(".selecionarTodosList").is(":checked"));
                } else {
                    $(this).removeAttr("checked");
                }
            });
        });

        $(".checkboxInput").click(function () {
            if (!$(this).is(":checked")) {
                if ($(".selecionarTodosList").is(":checked")) {
                    $(".selecionarTodosList").attr("checked", false);
                }
            }
        });
    });
</script>

<script>
    $(document).ready(function () {
        if ('<?= $filtro['regiao'] ?>' !== '') {
            carregaEstado('<?= $filtro['regiao'] ?>', 0);
        }

        $(".loader").hide();
        $("#esfera").show();
        //$("#contato_municipio").hide();
        //$("#dados_contato_municipio").hide();

        $("#esfera").multiselect({
            nonSelectedText: "Escolha",
            numberDisplayed: 0,
            nSelectedText: "Selecionados",
            buttonClass: "form-control",
            enableFiltering: true,
            enableCaseInsensitiveFiltering: true,
            allSelectedText: "Todos Selecionados",
            includeSelectAllOption: true,
            selectAllText: "Selecionar Todos"
        });

        //$("#telefone_contato").mask("(99)99999-9999");

        $("#regiao").change(function () {
            carregaEstado($('#regiao').val(), 1);
        });

        function carregaEstado(valorUF, acao) {
            $("#proponente").html("");
            $("#proponente").multiselect("destroy");
            $("#proponente").attr('style', 'display: none;');
            $.ajax({
                url: '<?php echo base_url('index.php/relatorio_siconv_controller/busca_estados') ?>',
                dataType: 'html',
                type: 'post',
                data: {
                    regiao: valorUF,
                    select_estados: acao == 0 ? '<?= isset($filtro['estado']) && $filtro['estado'] !== '' ? $filtro['estado'] : '' ?>' : ''
                },
                beforeSend: function () {
                    $("#estado").html("<option value=''>TODOS</option>");
                    $("#municipio").html("<option value=''>TODOS</option>");
                },
                success: function (data) {
                    $("#estado").html(data);
                    $.ajax({
                        url: '<?php echo base_url('index.php/relatorio_siconv_controller/get_esferas') ?>',
                        dataType: 'html',
                        type: 'post',
                        data: {
                            regiao: valorUF
                        },
                        beforeSend: function () {
                            //$("#esfera").val("");
                            //$("#esfera").multiselect("rebuild");
                        },
                        success: function (data) {
                            $("#esfera").html(data);
                            $("#esfera").multiselect("rebuild");

                            if ('<?= $filtro['estado'] ?>' !== '' && acao == 0) {
                                carregaCidades('<?= $filtro['estado'] ?>', 0);
                            }
                        }
                    });
                }
            });
        }


        $('#estado').change(function () {
            $("#proponente").html("");
            $("#proponente").multiselect("destroy");
            $("#proponente").attr('style', 'display: none;');
            if ($(this).val()) {
                carregaCidades($(this).val(), 1);
            } else {
                $('#municipio').html('<option value="">TODOS</option>');
            }

            $.ajax({
                url: '<?php echo base_url('index.php/relatorio_siconv_controller/get_esferas') ?>',
                dataType: 'html',
                type: 'post',
                data: {
                    estado: $(this).val()
                },
                beforeSend: function () {
                    $("#esfera").val("");
                    $("#esfera").multiselect("rebuild");
                },
                success: function (data) {
                    $("#esfera").html(data);
                    $("#esfera").multiselect("rebuild");
                }
            });
        });

        $("#novo_cnpj").click(function () {
            $("#botoes_padrao").slideDown();
            $("#novo_cnpj").slideUp();
            $("#botao_atualizar").slideUp();

            $("#num_cnpj").val("");
            $('#municipio').val("");

            return false;
        });

        function carregaCidades(valorUF, acao) {
            if (valorUF != "") {
                $('#municipio').html('<option value="">Carregando...</option>');
                $.ajax({
                    url: "<?php echo base_url("index.php/relatorio_siconv_controller/get_lista_cidades"); ?>",
                    dataType: "html",
                    data: {
                        uf: valorUF,
                        selected_city: acao == 0 ? '<?= isset($filtro['municipio']) && $filtro['municipio'] !== '' ? $filtro['municipio'] : '' ?>' : ''
                    },
                    type: "post",
                    beforeSend: function () {
                        //$("#dados_contato_municipio").slideUp();
                    },
                    success: function (data) {
                        $('#municipio').html(data);
                        $("#esfera").val("");
                        $("#proponente").val("");

                        if ('<?= $filtro['municipio'] ?>' !== '' && acao == 0) {
                            carregaEsfera('<?= $filtro['municipio'] ?>', 0);
                        }
                    }
                });
            }
        }

        $("#municipio").change(function () {
            carregaEsfera($(this).val(), 1);
        });

        function carregaEsfera(cidade_id, acao) {
            $("#proponente").html("");
            $("#proponente").multiselect("destroy");
            $("#proponente").attr('style', 'display: none;');

            var selected_esfera = new Array();
<?php if (isset($filtro['municipio']) && $filtro['municipio'] !== ''): ?>
                var i;
    <?php foreach ($filtro['esfera'] as $key => $value) : ?>
                    i = <?= $key ?>;
                    selected_esfera.push('<?= $value ?>');
    <?php endforeach; ?>
<?php endif; ?>
            $.ajax({
                url: '<?php echo base_url('index.php/relatorio_siconv_controller/get_esferas') ?>',
                dataType: 'html',
                type: 'post',
                data: {
                    municipio: cidade_id,
                    selected_esfera: acao == 0 ? selected_esfera : ''
                },
                beforeSend: function () {
                    $("#esfera").val("");
                    $("#esfera").multiselect("rebuild");
                },
                success: function (data) {
                    $("#esfera").html(data);
                    $("#esfera").multiselect("rebuild");

                    if (acao == 0)
                        carregaPorponente(selected_esfera, 0);
                }
            });
        }

        $("#esfera").change(function () {
            carregaPorponente($(this).val(), 1)
        });

        function carregaPorponente(esferas, acao) {

            var selected_proponente = new Array();
<?php if (isset($filtro['proponente']) && $filtro['proponente'] !== ''): ?>
                var i;
    <?php foreach ($filtro['proponente'] as $key => $value) : ?>
                    i = <?= $key ?>;
                    selected_proponente.push('<?= $value ?>');
    <?php endforeach; ?>
<?php endif; ?>

            $('#proponente').html('<option value="">Carregando...</option>');
            $.ajax({
                url: "<?php echo base_url("index.php/relatorio_siconv_controller/get_lista_proponentes"); ?>",
                dataType: "json",
                data: {
                    esfera: esferas,
                    selected_proponente: acao == 0 ? selected_proponente : '',
                    regiao: $("#regiao").val(),
                    uf: $("#estado").val(),
                    municipio: $("#municipio").val()
                },
                type: "post",
                beforeSend: function () {
                    $("#loader").show();
                    //$("#dados_contato_municipio").slideUp();
                },
                success: function (data) {
                    $("#proponente").multiselect({
                        nonSelectedText: "Escolha",
                        numberDisplayed: 0,
                        nSelectedText: "Selecionados",
                        buttonClass: "form-control",
                        enableFiltering: true,
                        enableCaseInsensitiveFiltering: true,
                        allSelectedText: "Todos Selecionados",
                        includeSelectAllOption: true,
                        selectAllText: "Selecionar Todos"
                    });
                    $("#proponente").multiselect("dataprovider", data.option);
                    if (data.cidades != null)
                        document.getElementById("cidades").value = data.cidades;
                    $("#proponente").multiselect("rebuild");
                    $("#loader").hide();
                },
                error: function () {
                    $("#loader").hide();
                    console.log("Erro");
                }
            });
        }

        $("#num_cnpj").keyup(function () {
            if ($(this).val().length == 2) {
                $(this).val($(this).val() + '.');
                return true;
            }
            if ($(this).val().length == 6) {
                $(this).val($(this).val() + '.');
                return true;
            }
            if ($(this).val().length == 10) {
                $(this).val($(this).val() + '/');
                return true;
            }
            if ($(this).val().length == 15) {
                $(this).val($(this).val() + '-');
                return true;
            }
        });

        $("#num_cnpj").focusout(function () {
            formataCNPJ($(this).val());
        });

        function formataCNPJ(value) {
            var cnpjAUX = "";
            if (value.length == 14) {
                for (var i = 0; i < value.length; i++) {
                    cnpjAUX += value[i];
                    if (cnpjAUX.length == 2)
                        cnpjAUX += ".";
                    else if (cnpjAUX.length == 6)
                        cnpjAUX += ".";
                    else if (cnpjAUX.length == 10)
                        cnpjAUX += "/";
                    else if (cnpjAUX.length == 15)
                        cnpjAUX += "-";
                }

                $("#num_cnpj").val(cnpjAUX);
            }
        }

        carregaCidades($('#estado').val());
        //mostraCamposContato($("#visita").is(":checked"));
    });
</script>

<style>
    .loader {
        border: 2px solid #FFFFFF; /* Light whith */
        border-top: 2px solid #800000; /* Blue */
        border-radius: 50%;
        width: 25px;
        height: 25px;
        animation: spin 2s linear infinite;
        margin-left: 25px;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>