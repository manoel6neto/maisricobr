<?php $permissoes = $this->permissoes_usuario->get_by_usuario_id($this->session->userdata('id_usuario')); ?>
<?php $filtro = $this->session->userdata('filtros'); ?>
<div id="content" class="innerAll bg-white">
    <h1 class="bg-white content-heading border-bottom">Propostas por Objeto</h1>

    <style>
        .panel-heading:hover {
            background-color: #DDD !important;
        }
    </style>


    <form class="form-horizontal" role="form" name="carrega_dados" method="post" id="carrega_dados" action="visualiza_propostas_por_objeto"></form>

    <div class="input-group input-lg ">
        <input name="pesquisa" type="text" class="form-control"
               placeholder="Pesquisar" form="carrega_dados"
               <?php
               if (isset($filtro['pesquisa'])) {
                   echo "value=\"{$filtro['pesquisa']}\"";
               }
               ?> />
        <div class="input-group-btn">
            <button class="btn btn-info btnPesquisa" type="submit" form="carrega_dados" id="pesquisa_dados">
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

    <form method="post" action="<?php echo base_url('index.php/in/get_propostas/atualiza_pareceres'); ?>" id="atualiza_pareceres">
    </form>

    <h1 class="bg-white" style="text-align: left;">
        <p>
            <span style="color: #428bca; font-size: 14px;">Status</span>
            <select name="status_prop" form="carrega_dados">
                <option value="1" <?php
                if (isset($filtro['status_prop']) && $filtro['status_prop'] == "1") {
                    echo "selected='selected'";
                }
                ?>>Enviadas e aprovadas</option>
                <option value="2" <?php
                if (isset($filtro['status_prop']) && $filtro['status_prop'] == "2") {
                    echo "selected='selected'";
                }
                ?>>Enviadas para análise</option>
            </select>
            <input type="hidden" value="<?php
            if (isset($filtro['status_prop'])) {
                echo $filtro['status_prop'];
            }
            ?>" name="statusProp" form="gera_pdf">
            <br/>
            <input type="submit" id="pesquisar" value="Pesquisar" class="btn btn-primary">
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

        $("#pesquisar").click(function () {
            $("#carrega_dados").submit();
        });
    });
</script>