<div id="content" class="innerAll bg-white">

    <h1 class="bg-white content-heading border-bottom">Buscar Entidades</h1>

    <form class="form-horizontal" action="relacao_entidades" method="post" id="carrega_dados" role="form" name="carrega_dados">
        <div class="form-group" style="padding: 25px; padding-top: 0px; padding-bottom: 0px;">
            <?php echo form_label('Estado', 'estado'); ?>
            <?php echo form_dropdown('estado', $proponente_siconv_model->getListaEstadosBuscaProponente(), set_value('estado', isset($filtro_estado) ? $filtro_estado : ''), "id='estado' class='form-control'"); ?>
        </div>

        <div class="form-group" style="padding: 25px; padding-top: 0px; padding-bottom: 0px;">
            <?php echo form_label('Município', 'municipio'); ?>
            <?php echo form_dropdown('municipio', array("" => "– Escolha um estado –"), '', "id='municipio' class='form-control'"); ?>
        </div>

        <div class="form-group" style="padding: 25px; padding-top: 0px; padding-bottom: 0px;">
            <?php echo form_label('Esfera Administrativa', 'esfera'); ?>
            <?php echo form_dropdown('esfera', $proponente_siconv_model->getListaEsferasBuscaProponente(), set_value('estado', isset($filtro_esfera) ? $filtro_esfera : ''), "id='esfera' class='form-control'"); ?>
        </div>

        <div id="situation" class="form-group" style="padding: 25px; padding-top: 0px; padding-bottom: 0px;">
            <?php echo form_label('Filtro Situação', 'situacao'); ?>
            <?php echo form_dropdown('situacao', array("" => "Todas", "aprovadas" => "Cadastro Aprovado", "reprovadas" => "Cadastro não aprovado"), set_value('situacao', isset($filtro_situacao) ? $filtro_situacao : ''), "id='situacao' class='form-control'"); ?>
        </div>

        <label id="info" style="color: #428bca;"><b>Processando...</b></label>

        <br>

        <div class="input-group input-lg ">
            <input type="text" class="form-control" placeholder="Pesquisa" name="nome_entidade" id="nome_entidade" value="<?php echo isset($filtro) ? $filtro : ""; ?>">

            <div class="input-group-btn">
                <button class="btn btn-info btnPesquisa" type="submit" id="gera_pesquisa">
                    <i class="fa fa-search"></i>
                </button>
            </div>
        </div>

    </form>

    <div class="bg-white">
        <div style="padding-top: 1%;">
            <div class="col-md-12 col-sm-12 bg-white">
                <?php if ($lista_proponentes != null): ?>
                    <form method="post" id="gera_pdf" action="gerar_pdf_rel"  target="_blank">
                        <input type="submit" class="btn btn-primary" id="gerarPdf" style="float: left;" value="Gerar PDF"/>
                    </form>

                    <table class="table">
                        <tr><th colspan="10" style='color: red; text-align:center; font-size: 16px;'><?php echo "<span>Total de Registros: " . count($lista_proponentes) . "</span>"; ?></th></tr>

                        <tr style="color: #428bca; font-size: 16px;">
                            <th><input type="checkbox" class="marcaTodos" checked="checked"></th>
                            <th>Nome Entidade</th>
                            <th>CNPJ</th>
                            <th>Esfera Admin</th>
                            <th>Município</th>
                            <th>UF</th>
                            <th>Responsável</th>
                            <th>Email</th>
                            <th>Situação</th>
                            <th>Datas</th>
                        </tr>

                        <?php foreach ($lista_proponentes as $p): ?>

                            <tr>
                                <td><input type="checkbox" value="<?php echo $p->id_proponente_siconv; ?>" name="id_proponente[]" class="id_proponente" checked="checked"></td>
                                <td><?php echo $p->nome; ?></td>
                                <td><?php echo $p->cnpj ?></td>
                                <td><?php echo $p->esfera_administrativa; ?></td>
                                <td><?php echo $p->municipio; ?></td>
                                <td><?php echo $p->municipio_uf_nome; ?></td>
                                <td><?php echo $p->nome_responsavel; ?></td>
                                <td><?php echo $p->email; ?></td>
                                <!-- Situacao das entidades privadas -->
                                <?php if ($p->esfera_administrativa != 'PRIVADA'): ?>
                                    <td><?php echo $p->situacao; ?></td>
                                    <td style="width: 180px; text-align: center;"></td>
                                <?php else: ?>
                                    <td><?php
                                        if ($p->situacao_aprovacao == null) {
                                            echo "-";
                                        } else {
                                            echo $p->situacao_aprovacao;
                                        }
                                        ?></td>
                                    <td style="width: 180px; text-align: center;"><?php
                                        echo implode("/", array_reverse(explode("-", $p->data_registro))) . " - " . implode("/", array_reverse(explode("-", $p->data_vencimento)));
                                        ?></td>
                                <?php endif; ?>

                            </tr>
                        <?php endforeach; ?>
                    </table>
                <?php else: ?>
                    <h1 style="text-align: center;">Nenhum dado encontrado.</h1>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#info").hide();
        if ($('#esfera').val() !== 'PRIVADA') {
            $('#situation').hide();
        }

        carregaCidades($('#estado').val());
        

        $('#estado').change(function () {
            if ($(this).val()) {
                carregaCidades($(this).val());
            } else {
                $('#municipio').html('<option value="">– Escolha um estado –</option>');
            }
        });

        function carregaCidades(valorUF) {
            if (valorUF != "") {
                $('#municipio').html('<option value="">Carregando...</option>');
                $.ajax({
                    url: "<?php echo base_url("index.php/proponente_siconv/get_lista_cidades"); ?>",
                    dataType: "html",
                    data: {
                        uf: valorUF,
                        municipio: '<?php echo isset($dados_post['municipio']) ? $dados_post['municipio'] : ''; ?>'
                    },
                    type: "post",
                    beforeSend: function () {
                        $("#info").show();
                    },
                    success: function (data) {
                        $('#municipio').html(data);
                        $("#info").hide();
                    }
                });
            }
        }

        $("#gerarPdf").click(function () {
            $(".id_proponente").each(function () {
                $(this).attr('form', 'gera_pdf');
            });
        });

        $(".marcaTodos").click(function () {
            $(".id_proponente").each(function () {
                $(this).attr('checked', $(".marcaTodos").is(":checked"));
            });
        });

        $(".id_proponente").click(function () {
            if (!$(this).is(":checked"))
                $(".marcaTodos").attr("checked", false);
        });

        $('#esfera').change(function () {
            if ($(this).val() === "PRIVADA") {
                $('#situation').slideDown();
            } else {
                $('#situation').slideUp()();
            }
        });
    });
</script>