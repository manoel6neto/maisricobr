<script src="<?php echo base_url('layout/assets/components/library/multiselect/js/bootstrap-multiselect.js'); ?>"></script>

<style type="text/css">
    .error{
        color: red;
    }
</style>

<div id="content" class="innerAll bg-white">
    <h1 class="bg-white content-heading border-bottom"><?php echo isset($_GET['id']) ? "Editar" : "Novo"; ?> Usuário</h1>
    <?php $action = isset($_GET['id']) ? "controle_usuarios/atualiza_usuario?id=" . $_GET['id'] : ""; ?>
    <?php $readonly = (isset($_GET['id']) && $this->session->userdata('nivel') != 1) ? "readonly='readonly'" : ""; ?>
    <?php echo form_open($action); ?>

    <?php echo validation_errors(); ?>

    <div class="form-group">
        <?php echo form_label('Nome *', 'nome', array('class' => (form_error('nome') != "" ? "error" : ""))); ?>
        <?php echo form_input(array('name' => 'nome', 'class' => 'form-control '), set_value('nome', isset($usuario) ? $usuario->nome : '')); ?>
    </div>

    <div class="form-group">
        <?php echo form_label('Email *', 'email', array('class' => (form_error('email') != "" ? "error" : ""))); ?>
        <?php echo form_input(array('name' => 'email', 'class' => 'form-control', 'id' => 'email', 'oncontextmenu' => "return false;", 'autocomplete' => 'off'), set_value('email', isset($usuario) ? $usuario->email : ''), $readonly); ?>
    </div>

    <?php if (!isset($_GET['id'])): ?>
        <div class="form-group">
            <?php echo form_label('Confirmar Email *', 'confirmar_email', array('class' => (form_error('confirmar_email') != "" ? "error" : ""))); ?>
            <?php echo form_input(array('name' => 'confirmar_email', 'class' => 'form-control', 'id' => 'confirmar_email', 'oncontextmenu' => "return false;", 'autocomplete' => 'off'), set_value('confirma_email'), $readonly); ?>
        </div>
    <?php endif; ?>

    <div class="form-group">
        <?php echo form_label('Telefone<span style="font-size: x-small;">(somente numeros)</span>', 'telefone', array('class' => (form_error('telefone') != "" ? "error" : ""))); ?>
        <?php echo form_input(array('name' => 'telefone', 'class' => 'form-control campoNumerico', 'maxlength' => 11), set_value('telefone', isset($usuario) ? $usuario->telefone : '')); ?>
    </div>

    <div class="form-group">
        <?php echo form_label('Celular<span style="font-size: x-small;">(somente numeros)</span>', 'celular', array('class' => (form_error('celular') != "" ? "error" : ""))); ?>
        <?php echo form_input(array('name' => 'celular', 'class' => 'form-control campoNumerico', 'maxlength' => 11), set_value('celular', isset($usuario) ? $usuario->celular : '')); ?>
    </div>

    <?php
    $readonlyEntidade = "";
    if ($this->session->userdata('nivel') == 3 || $this->session->userdata('nivel') == 5) {
        $readonlyEntidade = "readonly='readonly'";
    }
    ?>
    <div class="form-group">
        <?php echo form_label('Nome da Entidade', 'entidade'); ?>
        <?php echo form_input(array('name' => 'entidade', 'class' => 'form-control', 'maxlength' => 150), set_value('entidade', isset($usuario) ? $usuario->entidade : $entidadeDefault), $readonlyEntidade); ?>
    </div>

    <div class="form-group">
        <?php echo form_label('Login - CPF *<span style="font-size: x-small;">(somente numeros)</span>', 'login', array('class' => (form_error('login') != "" ? "error" : ""))); ?>
        <?php echo form_input(array('name' => 'login', 'class' => 'form-control campoNumerico', 'maxlength' => 11, 'autocomplete' => 'off'), set_value('login', isset($usuario) ? $usuario->login : ''), $readonly); ?>
    </div>

    <?php if (isset($_GET['id'])): ?>
        <label style="color: red;">Alteração de Senhas</label>
        <div class="form-group">
            <?php echo form_label('Senha Login e-SICAR', 'senha'); ?>
            <?php echo form_password(array('name' => 'senha', 'class' => 'form-control', 'placeholder' => '*******'), set_value('senha', '')); ?>
        </div>
    <?php endif; ?>

    <?php if ($this->session->userdata('nivel') == 1): ?>
        <div class="form-group" id="acessoSistema">
            <fieldset>
                <legend><?php echo form_label('Acesso ao Sistema *', '', array('class' => (form_error('usuario_sistema') != "" ? "error" : ""))); ?></legend>
                <?php echo form_radio('usuario_sistema', 'T', set_radio('usuario_sistema', 'T', (isset($usuario) && $usuario->usuario_sistema == 'T') ? true : false), 'class="usuario_sistema" id="tipo_acesso_todos"'); ?>
                <?php echo form_label('Todos', 'tipo_gestor'); ?>
                <?php echo form_radio('usuario_sistema', 'M', set_radio('usuario_sistema', 'M', (isset($usuario) && $usuario->usuario_sistema == 'M') ? true : false), 'class="usuario_sistema" id="tipo_acesso_municipal"'); ?>
                <?php echo form_label('e-SICAR Municipio', 'usuario_sistema'); ?>
                <?php echo form_radio('usuario_sistema', 'P', set_radio('usuario_sistema', 'P', (isset($usuario) && $usuario->usuario_sistema == 'P') ? true : false), 'class="usuario_sistema" id="tipo_acesso_parlamentar"'); ?>
                <?php echo form_label('e-SICAR Parlamentar', 'usuario_sistema'); ?>
                <?php echo form_radio('usuario_sistema', 'E', set_radio('usuario_sistema', 'E', (isset($usuario) && $usuario->usuario_sistema == 'E') ? true : false), 'class="usuario_sistema" id="tipo_acesso_estadual"'); ?>
                <?php echo form_label('e-SICAR Estadual', 'usuario_sistema'); ?>
            </fieldset>
        </div>
    <?php endif; ?>

    <?php
    $niveis = array("" => "Escolha");
    foreach ($nivelUsuarios as $nivelUsuario) {
        $niveis[$nivelUsuario->id_nivel_usuario] = $nivelUsuario->nome . " - " . $nivelUsuario->descricao;
    }

    if ($this->session->userdata('nivel') != 1)
        unset($niveis[9]);

    if ($this->session->userdata('nivel') == 1) {
        unset($niveis[13]);
        unset($niveis[14]);
    } elseif ($this->session->userdata('nivel') == 2 || $this->session->userdata('nivel') == 3 || $this->session->userdata('nivel') == 5) {
        unset($niveis[1]);
        unset($niveis[2]);
        unset($niveis[4]);
        unset($niveis[7]);
        unset($niveis[8]);
        if (isset($_GET['id'])) {
            unset($niveis[3]);
            unset($niveis[5]);
        }

        if ($this->session->userdata('nivel') != 2)
            unset($niveis[6]);

        if (isset($niveis[5]) && $this->session->userdata('nivel') == 2 && $this->session->userdata('usuario_sistema') == "P")
            unset($niveis[5]);
    }else if (isset($_GET['id']) && $this->session->userdata('nivel') == 4 && $this->session->userdata('id_usuario') == $_GET['id']) {
        unset($niveis[""]);
        unset($niveis[1]);
        unset($niveis[3]);
        unset($niveis[2]);
        unset($niveis[6]);
        unset($niveis[7]);
        unset($niveis[8]);
        unset($niveis[5]);
    } else if ($this->session->userdata('nivel') == 4) {
        unset($niveis[1]);
        unset($niveis[3]);
        unset($niveis[4]);
        unset($niveis[6]);
        unset($niveis[7]);
        unset($niveis[8]);
        unset($niveis[5]);
    } else if ($this->session->userdata('nivel') == 6) {
        unset($niveis[1]);
        unset($niveis[2]);
        unset($niveis[4]);
        unset($niveis[3]);
        unset($niveis[5]);
        unset($niveis[6]);
        if (isset($_GET['id'])) {
            unset($niveis[8]);
            unset($niveis[7]);
        }

        if (isset($niveis[8]) && $this->session->userdata('nivel') == 6 && $this->session->userdata('usuario_sistema') == "P")
            unset($niveis[8]);
    }
    ?>
    <div class="form-group" id="mostraNivel">
        <?php echo form_label('Nível *', 'id_nivel', array('class' => (form_error('id_nivel') != "" ? "error" : ""))); ?>
        <?php echo form_dropdown('id_nivel', $niveis, set_value('id_nivel', isset($usuario) ? $usuario->id_nivel : ''), 'class="form-control" id="nivel_usuario"'); ?>
    </div>
    <?php if ($this->session->userdata('nivel') != 12 && $this->session->userdata('nivel') != 13 && $this->session->userdata('nivel') != 14): ?>  
        <div id="dadosSiconv">
            <label style="color: #428bca;">DADOS SICONV</label>
            <div class="form-group">
                <?php echo form_label('Login SICONV', 'login_siconv'); ?>
                <?php echo form_input(array('name' => 'login_siconv', 'class' => 'form-control campoNumerico', 'id' => 'login_siconv'), set_value('login_siconv', isset($usuario) ? $usuario->login_siconv : '')); ?>
            </div>

            <div class="form-group">
                <?php echo form_label('Senha SICONV', 'senha_siconv'); ?>
                <?php echo form_password(array('name' => 'senha_siconv', 'class' => 'form-control', 'id' => 'senha_siconv', 'placeholder' => '*******'), set_value('senha_siconv', isset($usuario) ? '' : '')); ?>
            </div>

            <?php if ($this->session->userdata('nivel') == 2 && isset($gestor_usuario->inicio_vigencia) && (isset($usuario) && $usuario->id_nivel == 2)): ?>
                <div class="form-group">
                    <label>Datas do Contrato</label><br>
                    <?php
                    if ($usuario->data_cadastro != "")
                        echo "Data de Cadastro: <label class='label label-info'>" . implode("/", array_reverse(explode("-", $usuario->data_cadastro))) . "</label><br>";
                    ?>
                    <?php echo "Inicio Vigência: <label class='label label-info'>" . implode("/", array_reverse(explode("-", $gestor_usuario->inicio_vigencia))) . "</label> | Final da Vigência: <label class='label label-info'>" . implode("/", array_reverse(explode("-", $gestor_usuario->validade))) . "</label>"; ?>
                </div>
            <?php endif; ?>
        </div>
        <hr>
    <?php endif; ?>

    <?php if ($this->session->userdata('nivel') == 2): ?>
        <div id="dadosSubgestor">
            <div class="form-group">
                <fieldset>
                    <legend><?php echo form_label('Tipo de Subgestor *', '', array('class' => (form_error('tipo_subgestor') != "" ? "error" : ""))); ?></legend>
                    <?php echo form_radio('tipo_subgestor', "M", set_radio('tipo_subgestor', 'M', (isset($gestor_usuario) && $gestor_usuario->tipo_subgestor == "M") ? true : false), 'class="tipo_subgestor"'); ?>
                    <?php echo form_label('Municipal', 'tipo_subgestor'); ?>
                    <?php echo form_radio('tipo_subgestor', "S", set_radio('tipo_subgestor', 'S', (isset($gestor_usuario) && $gestor_usuario->tipo_subgestor == "S") ? true : false), 'class="tipo_subgestor"'); ?>
                    <?php echo form_label('Secretarias', 'tipo_subgestor'); ?>
                </fieldset>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($this->session->userdata('nivel') == 1 || $this->session->userdata('nivel') == 4): ?>
        <div id="dadosGestor">
            <div class="form-group">
                <fieldset>
                    <legend><?php echo form_label('Tipo de Gestor *', '', array('class' => (form_error('tipo_gestor') != "" ? "error" : ""))); ?></legend>
                    <?php echo form_radio('tipo_gestor', 0, set_radio('tipo_gestor', '0', (isset($gestor_usuario) && $gestor_usuario->tipo_gestor == 0) ? true : false), 'class="tipo_gestor"'); ?>
                    <?php echo form_label('Municipal', 'tipo_gestor'); ?>
                    <?php echo form_radio('tipo_gestor', 1, set_radio('tipo_gestor', '1', (isset($gestor_usuario) && $gestor_usuario->tipo_gestor == 1) ? true : false), 'class="tipo_gestor"'); ?>
                    <?php echo form_label('Parlamentar', 'tipo_gestor'); ?>
                    <?php echo form_radio('tipo_gestor', 2, set_radio('tipo_gestor', '2', (isset($gestor_usuario) && $gestor_usuario->tipo_gestor == 2) ? true : false), 'class="tipo_gestor"'); ?>
                    <?php echo form_label('Estadual', 'tipo_gestor'); ?>
                </fieldset>
            </div>

            <div class="form-group">
                <?php echo form_label('Período do contrato *', 'validade', array('class' => (form_error('validade') != "" ? "error" : ""))); ?>
                <?php echo form_input(array('name' => 'validade', 'class' => 'form-control'), set_value('validade', isset($gestor_usuario) ? $data_model->get_meses_to_time($data_model->retornaDiffDatas($gestor_usuario->inicio_vigencia, $gestor_usuario->validade, false)) : '')); ?>
            </div>

            <?php if (isset($gestor_usuario)): ?>
                <div class="form-group">
                    <label>Datas do Contrato</label><br>
                    <?php
                    if ($usuario->data_cadastro != "")
                        echo "Data de Cadastro: <label class='label label-info'>" . implode("/", array_reverse(explode("-", $usuario->data_cadastro))) . "</label><br>";
                    ?>
                    <?php echo "Inicio Vigência: <label class='label label-info'>" . implode("/", array_reverse(explode("-", $gestor_usuario->inicio_vigencia))) . "</label> | Final da Vigência: <label class='label label-info'>" . implode("/", array_reverse(explode("-", $gestor_usuario->validade))) . "</label>"; ?>
                </div>
            <?php endif; ?>

            <div class="form-group" id="qnt_cnpj">
                <?php echo form_label('Quantidade de CNPJs *', 'quantidade_cnpj', array('class' => (form_error('quantidade_cnpj') != "" ? "error" : ""))); ?>
                <?php echo form_input(array('name' => 'quantidade_cnpj', 'class' => 'form-control'), set_value('quantidade_cnpj', isset($gestor_usuario) ? $gestor_usuario->quantidade_cnpj : '')); ?>
            </div>

            <?php if (!isset($_GET['id'])): ?>
                <div id="dadosCNPJ">
                    <div class="form-group">
                        <?php echo form_label('Estado', 'estado'); ?>
                        <?php echo form_dropdown('estado', $proponente_siconv_model->getListaEstados(), set_value('estado', isset($cnpj_siconv) ? $cnpj_siconv->estado : ''), "id='estado' class='form-control'"); ?>
                    </div>

                    <div class="form-group" id="dadosCNPJM">
                        <?php echo form_label('Múnicipio', 'municipio'); ?>
                        <?php echo form_dropdown('municipio', array("" => "– Escolha um estado –"), '', "id='municipio' class='form-control'"); ?>
                    </div>

                    <div class="form-group" id="dadosCNPJP">
                        <?php echo form_label('Esfera Administrativa', 'esfera'); ?>
                        <?php echo form_dropdown('esfera', $proponente_siconv_model->getListaEsferas(), '', "id='esfera' class='form-control' multiple='multiple' style='display: none;'"); ?>
                        &nbsp;
                        <?php echo form_label('Proponente', 'proponente'); ?>
                        <?php echo form_dropdown('proponente[]', array("" => "- Escolha uma esfera -"), '', "id='proponente' class='form-control'  multiple='multiple' style='display: none;'"); ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <?php if ($this->session->userdata('nivel') == 1): ?>
        <div id="dadosComum">
            <?php
            $usuarios = array("" => "Escolha");
            foreach ($usuariosGestor as $gestor) {
                if ($gestor->usuario_sistema == "M") {
                    $usuarios[$gestor->id_usuario] = $gestor->nome . " - Municipal";
                } elseif ($gestor->usuario_sistema == "P") {
                    $usuarios[$gestor->id_usuario] = $gestor->nome . " - Parlamentar";
                } else {
                    $usuarios[$gestor->id_usuario] = $gestor->nome . " - Estadual";
                }
            }
            ?>
            <div class="form-group">
                <?php echo form_label('Gestor do Sistema *', 'id_gestor', array('class' => (form_error('id_gestor') != "" ? "error" : ""))) ?>
                <?php echo form_dropdown('id_gestor', $usuarios, set_value('id_gestor', isset($gestor_usuario) ? $gestor_usuario->id_usuario : ''), 'class="form-control" id="id_gestor"') ?>
            </div>
        </div>
    <?php endif; ?>
    <?php if ($this->session->userdata('nivel') == 1 || $this->session->userdata('nivel') == 12 || $this->session->userdata('nivel') == 13 || $this->session->userdata('nivel') == 14 || $this->session->userdata('nivel') == 15): ?>
        <div id="dadosVendedor">
            <label>Restringir Acesso</label>
            <div class="form-group">
                <?php echo form_label('Estado', 'estado'); ?>
                <?php echo form_multiselect('estado_restrito[]', $proponente_siconv_model->getListaEstados(), $estados_block, "id='estado_restrito' multiple='multiple' class='form-control' style='display: none;'"); ?>
                &nbsp;
                <?php if ($this->session->userdata('nivel') == 1 || $this->session->userdata('nivel') == 15) : ?>
                    <?php echo form_label('Municipio', 'municipio'); ?>
                    <?php echo form_multiselect('municipio_restrito[]', $municipios, $municipios_block, "id='municipio_restrito' multiple='multiple' class='form-control' style='display: none;'"); ?>
                    &nbsp;
                    <?php echo form_label('Esfera Administrativa', 'esfera'); ?>
                    <?php echo form_multiselect('esfera_restrita[]', $proponente_siconv_model->getListaEsferas(), $esferas_block, "id='esfera_restrita' multiple='multiple' class='form-control' style='display: none;'"); ?>
                <?php elseif (!isset($usuario) && $this->session->userdata('nivel') == 13): ?>
                    <?php echo form_label('Municipio', 'municipio'); ?>
                    <?php echo form_multiselect('municipio_restrito[]', $municipios_block, $municipios_block, "id='municipio_restrito' multiple='multiple' class='form-control' style='display: none;'"); ?>
                <?php elseif (isset($usuario) && $usuario->id_nivel == 14): ?>
                    <?php echo form_label('Municipio', 'municipio'); ?>
                    <?php echo form_multiselect('municipio_restrito[]', $municipios_block, $municipios_block, "id='municipio_restrito' multiple='multiple' class='form-control' style='display: none;'"); ?>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>

    <div class="form-group">
        <input type="submit" class="btn btn-primary" name="cadastra" value="Salvar" id="cadastrar">
        <?php if ($this->session->userdata('nivel') != 3 && $this->session->userdata('nivel') != 5 && $this->session->userdata('nivel') != 7 && $this->session->userdata('nivel') != 8): ?>
            <input class="btn btn-primary" type="button" value="Voltar" onclick="location.href = '<?php echo base_url(); ?>index.php/controle_usuarios';">
        <?php else: ?>
            <input class="btn btn-primary" type="button" value="Voltar" onclick="location.href = '<?php echo base_url(); ?>index.php/in/gestor';">
        <?php endif; ?>
    </div>
    <label id="info" style="color: #428bca;"><b>Processando...</b></label>

    <?php echo form_close(); ?>

</div>

<script>
    $(document).ready(function () {
        $("#dadosGestor").hide();
        $("#dadosSubgestor").hide();
        $("#dadosComum").hide();
        $("#mostraNivel").show();
        $("#dadosCNPJ").hide();
        $("#info").hide();

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

        $("#estado_restrito").multiselect({
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

        $("#municipio_restrito").multiselect({
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

        $("#esfera_restrita").multiselect({
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

<?php if (!isset($_GET['id'])): ?>
            //            $("#estado_restrito").multiselect('selectAll', false);
            //            $("#esfera_restrita").multiselect('selectAll', false);

            $("#estado_restrito").multiselect("updateButtonText");
            $("#esfera_restrita").multiselect("updateButtonText");
<?php endif; ?>

<?php if (isset($_GET['id'])): ?>
            //            $("#municipio_restrito").multiselect('selectAll', false);

            $("#municipio_restrita").multiselect("updateButtonText");
<?php endif; ?>


        $("#nivel_usuario").change(function () {
            verificaNivelUsuario($(this).val());
        });

        $(".tipo_gestor").click(function () {
            exibeDadosCNPJ($(this).val());
        });

        $('#estado').change(function () {
            if ($(this).val()) {
                carregaCidades($(this).val());
            } else {
                $('#municipio').html('<option value="">– Escolha um estado –</option>');
            }
        });

        function exibeDadosCNPJ(valor) {
            if (valor == "0") {
                $("#dadosCNPJ").slideDown();
                $("#qnt_cnpj").show();
                $("#dadosCNPJM").show();
                $("#dadosCNPJP").show();
            } else {
                if (valor == "2") {
                    $("#dadosCNPJ").slideDown();
                    $("#qnt_cnpj").hide();
                    $("#dadosCNPJM").hide();
                    $("#dadosCNPJP").hide();
                } else {
                    $("#dadosCNPJ").slideUp();
                }
            }
        }

        exibeDadosCNPJ(retornaRadioChecked());

        function retornaRadioChecked() {
            var valorRetorno;
            $(".tipo_gestor").each(function () {
                if ($(this).is(":checked"))
                    valorRetorno = $(this).val();
            });

            return valorRetorno;
        }

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
                        $("#esfera").val("");
                        $("#proponente").val("");
<?php if (isset($dados_post['esfera'])): ?>
                            $("#esfera").val('<?php echo $dados_post['esfera']; ?>');
                            $("#esfera").trigger('change');
<?php endif; ?>

                        $("#info").hide();
                    }
                });
            }
        }

        $("#estado_restrito").change(function () {
            $municipios = $('#municipio_restrito').val();

            $('#municipio_restrito').html('<option value="">Carregando...</option>');
            $.ajax({
                url: "<?php echo base_url("index.php/proponente_siconv/get_lista_cidades_restrito"); ?>",
                dataType: "json",
                data: {
                    uf: $(this).val(),
                    municipio_restrito: $municipios
                },
                type: "post",
                beforeSend: function () {
                    $("#info").show();
                },
                success: function (data) {
                    $("#municipio_restrito").multiselect({
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

                    $("#municipio_restrito").multiselect("dataprovider", data);
                    $("#municipio_restrito").multiselect("rebuild");

                    $("#info").hide();
                },

                error: function (jq, status, message) {
                    alert('Erro encontrado. Status: ' + status + ' - Mensagem: ' + message);
                }
            });
        });

        $("#municipio").change(function () {
            $("#esfera").val("");
            $("#esfera").multiselect("rebuild");
            $("#proponente").html("");
            $("#proponente").multiselect("destroy");
            $("#proponente").attr('style', 'display: none;');
        });

        $("#esfera").change(function () {
            $('#proponente').html('<option value="">Carregando...</option>');
            $.ajax({
                url: "<?php echo base_url("index.php/proponente_siconv/get_lista_proponentes"); ?>",
                dataType: "json",
                data: {
                    esfera: $(this).val(),
                    uf: $("#estado").val(),
                    municipio: $("#municipio").val(),
                    tipo: "GESTOR",
                    id: '<?php echo $this->input->get('id'); ?>'
                },
                type: "post",
                beforeSend: function () {
                    $("#info").show();
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

                    $("#proponente").multiselect("dataprovider", data);
                    $("#proponente").multiselect("rebuild");

                    $("#info").hide();
                }
            });
        });

        carregaCidades($('#estado').val());

        function verificaNivelUsuario(valor) {
            $("#dadosGestor").slideUp();
            $("#dadosSubgestor").slideUp();
            $("#dadosComum").slideUp();
            $("#dadosSiconv").slideUp();
            $("#dadosVendedor").slideUp();
            $("#acessoSistema").slideUp();

//            $("#tipo_acesso_todos").attr('disabled',false);
            $("#tipo_acesso_municipal").attr('disabled', false);
            $("#tipo_acesso_parlamentar").attr('disabled', false);
            $("#tipo_acesso_estadual").attr('disabled', false);

            if (valor != "12" && valor != "13" && valor != "14")
                $("#acessoSistema").slideDown();
            if (valor == "2")
                $("#dadosGestor").slideDown();
            else if (valor == "6")
                $("#dadosSubgestor").slideDown();
            else if (valor == "3" || valor == "5" || valor == "8" || valor == "7")
                $("#dadosComum").slideDown();
            else if (valor == "4" || valor == "13" || valor == "14" || valor == "15") {
                $("#dadosVendedor").slideDown();
                $("#tipo_acesso_todos").attr('checked', 'checked');
//                $("#tipo_acesso_todos").attr('disabled',true);
                $("#tipo_acesso_municipal").attr('disabled', true);
                $("#tipo_acesso_parlamentar").attr('disabled', true);
                $("#tipo_acesso_estadual").attr('disabled', true);
            }

            if (valor != "1" && valor != "5" && valor != "8" && valor != "12" && valor != "13" && valor != "14")
                $("#dadosSiconv").slideDown();

<?php if ($this->session->userdata('nivel') == "5" || $this->session->userdata('nivel') == "8"): ?>
                $("#dadosSiconv").slideUp();
<?php endif; ?>
        }

        $("#email").keypress(function (e) {
            if ((e.ctrlKey && e.which == 99) || (e.ctrlKey && e.which == 118))
                return false
        });

        $("#confirmar_email").keypress(function (e) {
            if ((e.ctrlKey && e.which == 99) || (e.ctrlKey && e.which == 118))
                return false
        });

        $('.campoNumerico').bind('keydown', soNums);

        $('.campoNumerico').blur(function () {
            $(this).val(mnum($(this).val()));
        });

        function mnum(v) {
            v = v.replace(/\D/g, "");
            return v;
        }

        function soNums(e) {

            //teclas adicionais permitidas (tab,delete,backspace,setas direita e esquerda)
            keyCodesPermitidos = new Array(8, 9, 37, 39, 46);

            //numeros e 0 a 9 do teclado alfanumerico
            for (x = 48; x <= 57; x++) {
                keyCodesPermitidos.push(x);
            }

            //numeros e 0 a 9 do teclado numerico
            for (x = 96; x <= 105; x++) {
                keyCodesPermitidos.push(x);
            }

            //Pega a tecla digitada
            keyCode = e.which;

            //Verifica se a tecla digitada é permitida
            if ($.inArray(keyCode, keyCodesPermitidos) != -1) {
                return true;
            }
            return false;
        }

        verificaNivelUsuario($("#nivel_usuario").val());

        $("#abre_siconv").click(function () {
            dialog.dialog("close");
        });

<?php if (isset($_GET['id']) && ($this->session->userdata('nivel') == 2 || $this->session->userdata('nivel') == 3 || $this->session->userdata('nivel') == 5 || $this->session->userdata('nivel') == 6 || $this->session->userdata('nivel') == 7 || $this->session->userdata('nivel') == 8)): ?>
            $("#mostraNivel").hide();
<?php endif; ?>

        var dialog;

        dialog = $("#dialog-message").dialog({
            open: function (event, ui) {
                $(this).parents(".ui-dialog:first").find(".ui-dialog-titlebar-close").remove();
            },
            height: 200,
            width: 550,
            modal: true,
        }).position({
            my: "center",
            at: "center",
            of: window
        });

<?php if ($this->session->userdata('altera_senha_siconv') != 'S'): ?>
            dialog.dialog("close");
<?php endif; ?>

<?php if ($this->session->userdata('falha_login') == 'S'): ?>
            $("#login_siconv").css({'color': '#fff', 'background-color': '#ff7777'});
            $("#senha_siconv").css({'color': '#fff', 'background-color': '#ff7777'});
    <?php $this->session->set_userdata('falha_login', ''); ?>
    <?php $this->session->set_userdata('altera_senha_siconv', ''); ?>
<?php endif; ?>
    });
</script>

<div id="dialog-message" title="ALERTA">
    <br/><br/>
    <p style="font-size: 16px; text-align: justify;">
        <span class="ui-icon ui-icon-circle-check" style="float:left; "></span>
        <b><a href='https://www.convenios.gov.br/siconv/secure/entrar-login.jsp' onclick="window.open('https://www.convenios.gov.br/siconv/secure/entrar-login.jsp', 'mywindow', 'menubar=1,resizable=1,width=800,height=600');
                return false;" target='_blank' id="abre_siconv">Clique aqui para alterar sua senha no SICONV</a></b>
    </p>
</div>
