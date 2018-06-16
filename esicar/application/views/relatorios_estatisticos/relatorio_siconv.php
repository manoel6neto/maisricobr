<script type="text/javascript" src="<?php echo base_url(); ?>configuracoes/js/maskedinput.min.js"></script>
<script src="<?php echo base_url('layout/assets/components/library/multiselect/js/bootstrap-multiselect.js'); ?>"></script>

<div id="content" class="bg-white">
    <h1 class="bg-white content-heading border-bottom"><?= $title_header ?></h1>

    <div class="bg-white innerAll col-md-3">
        <form action="<?php echo base_url($action); ?>" target="_blank" method="post">
            <input id="cidades" name="cidades" type="hidden">
            <div class="form-group">
                <label for="regiao">Regiões</label>
                <select name="regiao" id="regiao" class="form-control">
                    <?php foreach ($regioes as $value): ?>
                        <option value="<?= $value['sigla'] ?>" <?= isset($value['disabled']) ? $value['disabled'] : '' ?>><?= $value['nome'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="estado">Estados</label>
                <select name="estado" id="estado" class="form-control">
                    <option value="">TODOS</option>
                </select>
            </div>

            <div class="form-group">
                <?php echo form_label('Múnicipio', 'municipio'); ?>
                <?php echo form_dropdown('municipio', array("" => "TODOS"), '', "id='municipio' class='form-control'"); ?>
            </div>

            <div class="form-group">
                <?php echo form_label('Esfera Administrativa', 'esfera'); ?>
                <br>
                <select name="esfera[]" id="esfera" class="form-control" required="true" multiple="multiple" style="display: none;">
                    <?= $esferas ?>
                </select>
            </div>
            <div class="form-group">
                <?php echo form_label('Proponente', 'proponente'); ?>
                <br>
                <?php echo form_dropdown('proponente[]', array("" => "- Escolha uma esfera -"), '', "id='proponente' class='form-control'  required='true' multiple='multiple' style='display: none;'"); ?>
            </div>

            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Gerar Relatório" id="cadastrar"><input class="loader" >
            </div>
        </form>
    </div>
</div>

<script>
    $(document).ready(function () {
        $(".loader").hide();
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
            $("#proponente").html("");
            $("#proponente").multiselect("destroy");
            $("#proponente").attr('style', 'display: none;');
            $.ajax({
                url: '<?php echo base_url('index.php/relatorio_siconv_controller/busca_estados') ?>',
                dataType: 'html',
                type: 'post',
                data: {
                    regiao: $(this).val()
                },
                beforeSend: function () {
                    $("#estado").html("<option value=''>TODOS</option>");
                    $("#municipio").html("<option value=''>TODOS</option>");
                },
                success: function (data) {
                    $("#estado").html(data);
                }
            });

            $.ajax({
                url: '<?php echo base_url('index.php/relatorio_siconv_controller/get_esferas') ?>',
                dataType: 'html',
                type: 'post',
                data: {
                    regiao: $(this).val()
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


        $('#estado').change(function () {
            $("#proponente").html("");
            $("#proponente").multiselect("destroy");
            $("#proponente").attr('style', 'display: none;');
            if ($(this).val()) {
                carregaCidades($(this).val());
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

        function carregaCidades(valorUF) {
            if (valorUF != "") {
                $('#municipio').html('<option value="">Carregando...</option>');
                $.ajax({
                    url: "<?php echo base_url("index.php/relatorio_siconv_controller/get_lista_cidades"); ?>",
                    dataType: "html",
                    data: {
                        uf: valorUF
                    },
                    type: "post",
                    beforeSend: function () {
                        //$("#dados_contato_municipio").slideUp();
                    },
                    success: function (data) {
                        $('#municipio').html(data);
                        $("#esfera").val("");
                        $("#proponente").val("");
                    }
                });
            }
        }

        $("#municipio").change(function () {
            $("#proponente").html("");
            $("#proponente").multiselect("destroy");
            $("#proponente").attr('style', 'display: none;');
            
            $.ajax({
                url: '<?php echo base_url('index.php/relatorio_siconv_controller/get_esferas') ?>',
                dataType: 'html',
                type: 'post',
                data: {
                    municipio: $(this).val()
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

        $("#esfera").change(function () {
            $('#proponente').html('<option value="">Carregando...</option>');
            $.ajax({
                url: "<?php echo base_url("index.php/relatorio_siconv_controller/get_lista_proponentes"); ?>",
                dataType: "json",
                data: {
                    esfera: $(this).val(),
                    regiao: $("#regiao").val(),
                    uf: $("#estado").val(),
                    municipio: $("#municipio").val()
                },
                type: "post",
                beforeSend: function () {
                    $(".loader").show();
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
                    document.getElementById("cidades").value = data.cidades;
                    $("#proponente").multiselect("rebuild");

                    $(".loader").hide();
                },
                error: function () {
                    $(".loader").hide();
                    console.log("Erro");
                }
            });
        });

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