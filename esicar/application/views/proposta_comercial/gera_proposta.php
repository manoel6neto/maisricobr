<script src="<?php echo base_url('configuracoes/js/jquery.mask.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>configuracoes/js/maskedinput.min.js"></script>
<script src="<?php echo base_url('layout/assets/components/library/multiselect/js/bootstrap-multiselect.js'); ?>"></script>

<div id="content" class="innerAll bg-white">
    <h1 class="bg-white content-heading border-bottom">Nova Proposta</h1>

    <div class="col-md-4">

        <?php echo form_open(); ?>

        <?php echo validation_errors(); ?>

        <div class="form-group">
            <h4 style="color: darkred; font-style: italic;">Partes da proposta</h4>
            <?php echo form_checkbox('capa', '1', TRUE, 'id="capa"'); ?>
            <?php echo form_label('Capa', 'capa'); ?>
            <br/>
            <?php echo form_checkbox('sobre', '1', TRUE, 'id="sobre"'); ?>
            <?php echo form_label('Sobre a concepção e metodologia', 'sobre'); ?>
            <br/>
            <?php echo form_checkbox('desempenho', '1', TRUE, 'id="desempenho"'); ?>
            <?php echo form_label('Desempenho geral do siconv', 'desempenho'); ?>
            <br/>
            <?php echo form_checkbox('apresentacao', '1', TRUE, 'id="apresentacao"'); ?>
            <?php echo form_label('Apresentação geral da proposta', 'apresentacao'); ?>
            <br/>
            <?php echo form_checkbox('oportunidade', '1', TRUE, 'id="oportunidade"'); ?>
            <?php echo form_label('Oportunidades da proposta', 'oportunidade'); ?>
            <br/>
            <?php echo form_checkbox('proposta', '1', TRUE, 'id="proposta"'); ?>
            <?php echo form_label('Proposta técnica', 'proposta'); ?>
            <br/>
            <?php echo form_checkbox('funcionalidade', '1', TRUE, 'id="funcionalidade"'); ?>
            <?php echo form_label('Uso das funcionalidades por esfera de gestão', 'funcionalidade'); ?>
            <br/>
            <?php echo form_checkbox('proposta_comercial', '1', TRUE, 'id="proposta_comercial"'); ?>
            <?php echo form_label('Proposta comercial', 'proposta_comercial'); ?>
            <br/>
            <?php echo form_checkbox('resultado', '1', TRUE, 'id="resultado"'); ?>
            <?php echo form_label('Resultados pretendidos', 'resultado'); ?>
            <br/>
            <?php echo form_checkbox('anexo1', '1', TRUE, 'id="anexo1"'); ?>
            <?php echo form_label('Anexo I - Relatório de gestão siconv - Integrado', 'anexo1'); ?>
            <br/>
            <?php echo form_checkbox('anexo2', '1', TRUE, 'id="anexo2"'); ?>
            <?php echo form_label('Anexo II - Relatório governo municipal', 'anexo2'); ?>
            <br/>
            <?php echo form_checkbox('anexo3', '1', TRUE, 'id="anexo3"'); ?>
            <?php echo form_label('Anexo III - Relatório governo estadual', 'anexo3'); ?>
            <br/>
            <?php echo form_checkbox('anexo4', '1', TRUE, 'id="anexo4"'); ?>
            <?php echo form_label('Anexo IV - Relatório O.S.C', 'anexo4'); ?>
            <br/>
            <?php echo form_checkbox('anexo5', '1', TRUE, 'id="anexo5"'); ?>
            <?php echo form_label('Anexo V - Relatório consórcios', 'anexo5'); ?>
            <br/>
            <?php echo form_checkbox('anexo6', '1', TRUE, 'id="anexo6"'); ?>
            <?php echo form_label('Anexo VI - Relatório empresa mista', 'anexo6'); ?>
            <br/>
            <?php echo form_checkbox('anexo7', '1', TRUE, 'id="anexo7"'); ?>
            <?php echo form_label('Anexo VII - Proposta incubadora', 'anexo7'); ?>
            <br/>
            <?php echo form_checkbox('anexo8', '1', TRUE, 'id="anexo8"'); ?>
            <?php echo form_label('Anexo VIII - Tabela promocional para incubadoras', 'anexo8'); ?>
            <br/>
        </div>    

        <div class="form-group">
            <?php echo form_label('Descrição Proposta *', 'descricao_proposta_comercial'); ?>
            <?php echo form_input('descricao_proposta_comercial', set_value('descricao_proposta_comercial', isset($proposta) ? $proposta->descricao_proposta_comercial : ''), 'class="form-control" maxlength="150"'); ?>
        </div>

        <div class="form-group">
            <?php echo form_label('Tipo Proposta *', 'tipo_proposta'); ?>
            <?php echo form_dropdown('tipo_proposta', $tipo_proposta, set_value('tipo_proposta', isset($proposta) ? $proposta->tipo_proposta : ''), 'class="form-control" id="tipo_proposta"'); ?>
        </div>

        <div class="form-group">
            <?php echo form_label('Parcelas Proposta *', 'parcelas_proposta_comercial'); ?>
            <?php echo form_input('parcelas_proposta_comercial', set_value('parcelas_proposta_comercial', isset($proposta) ? $proposta->parcelas_proposta_comercial : '1'), 'class="form-control" maxlength="2"'); ?>
        </div>

        <div id="dados_estadual">
            <div class="form-group">
                <?php echo form_label('Valor da Proposta *', 'valor_proposta_comercial'); ?>
                <?php echo form_input('valor_proposta_comercial', set_value('valor_proposta_comercial', isset($proposta) ? $proposta->valor_proposta_comercial : ''), 'class="form-control" id="valor_proposta_comercial"'); ?>
            </div>
        </div>

        <div id="dados_interesse">
            <div class="form-group">
                <?php echo form_label('Entidade *', 'entidade'); ?>
                <?php echo form_dropdown('entidade', $entidade_interesse, set_value('entidade', isset($proposta) ? $proposta->tipo_proposta : ''), 'class="form-control"'); ?>
            </div>
        </div>

        <div id="dados_consorcio">
            <div class="form-group">
                <?php echo form_label('Nº Entes Consorciados *', 'num_associado'); ?>
                <?php echo form_input('num_associado', set_value('num_associado', isset($proposta) ? $proposta->num_cnpj : ''), 'class="form-control"'); ?>
            </div>
        </div>

        <div id="dados_parlamentar">
            <div class="form-group">
                <?php echo form_label('Nº CNPJs *', 'num_parlamentar'); ?>
                <?php echo form_input('num_parlamentar', set_value('num_parlamentar', isset($proposta) ? $proposta->num_cnpj : ''), 'class="form-control"'); ?>
            </div>
        </div>

        <div id="dados_casadinha">
            <div class="form-group" id="eh_capital">
                <?php echo form_checkbox('capital', '1', FALSE, 'id="capital"'); ?>
                <?php echo form_label('Capital?', 'capital'); ?>
            </div>

            <div class="form-group" id="eh_capital">
                <?php echo form_checkbox('cnpj_extra', '1', FALSE, 'id="cnpj_extra"'); ?>
                <?php echo form_label('CNPJs Extras', 'cnpj_extra'); ?>
            </div>

            <div id="nums_cnpj_extra">
                <div class="form-group">
                    <?php echo form_label('Nº CNPJ Extra Eco Mista', 'num_cnpj'); ?>
                    <?php echo form_input('num_cnpj', set_value('num_cnpj', isset($proposta) ? $proposta->num_cnpj : ''), 'class="form-control"'); ?>
                </div>
                <!-- 
                <div class="form-group">
                <?php echo form_label('Nº CNPJ Extra Autarquias', 'num_cnpj_autarquias'); ?>
                <?php echo form_input('num_cnpj_autarquias', set_value('num_cnpj_autarquias', isset($proposta) ? $proposta->num_cnpj : ''), 'class="form-control"'); ?>
                </div>
                -->
                <div class="form-group">
                    <?php echo form_label('Nº CNPJ Extra O.S.C', 'num_cnpj_sem_fim'); ?>
                    <?php echo form_input('num_cnpj_sem_fim', set_value('num_cnpj_sem_fim', isset($proposta) ? $proposta->num_cnpj : ''), 'class="form-control"'); ?>
                </div>
            </div>
        </div>

        <div id="dados_ossfl">
            <div class="form-group">
                <div class="form-group">
                    <?php echo form_label('Desconto (%)', 'percentual_desconto'); ?>
                    <?php echo form_input('percentual_desconto', set_value('percentual_desconto', isset($proposta) ? $proposta->percentual_desconto : '0'), 'class="form-control" maxlength="2"'); ?>
                </div>

                <div class="form-group">
                    <?php echo form_label('Empresas', 'empresas'); ?>
                    <br>
                    <select name="empresas[]" class='form-control' multiple='multiple' style='display: none;' id="empresas">
                        <?php foreach ($empresas_privadas as $value): ?>
                            <option value=<?= $value->cnpj ?>><?= $value->cnpj ?> - <?= $value->nome ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <?php echo form_label('Nome do Contato *', 'nome_contato', array('class' => (form_error('nome_contato') != "" ? "error" : ""))); ?>
                    <?php echo form_input('nome_contato', set_value('nome_contato', isset($dados_usuario->nome_contato) ? $dados_usuario->nome_contato : ""), 'class="form-control"'); ?>
                </div>
                <div class="form-group">
                    <?php echo form_label('Nome da Instituição *', 'nome_instituicao', array('class' => (form_error('nome_instituicao') != "" ? "error" : ""))); ?>
                    <?php echo form_input('nome_instituicao', set_value('nome_instituicao', isset($dados_usuario->nome_instituicao) ? $dados_usuario->nome_instituicao : ""), 'class="form-control"'); ?>
                </div>
                <div class="form-group">
                    <?php echo form_label('Email do Contato *', 'email_contato', array('class' => (form_error('email_contato') != "" ? "error" : ""))); ?>
                    <?php echo form_input('email_contato', set_value('email_contato', isset($dados_usuario->email_contato) ? $dados_usuario->email_contato : ""), 'class="form-control"'); ?>
                </div>

                <div class="form-group">
                    <?php echo form_label('Telefone <span style="font-size: x-small;">(somente numeros)</span>', 'telefone_contato', array('class' => (form_error('telefone_contato') != "" ? "error" : ""))); ?>
                    <?php echo form_input('telefone_contato', set_value('telefone_contato', isset($dados_usuario->telefone_contato) ? $dados_usuario->telefone_contato : ""), 'class="form-control" id="telefone_contato" maxlength="11"'); ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <input type="submit" class="btn btn-primary" value="Salvar" id="cadastrar">
            <input class="btn btn-primary" type="button" value="Voltar" onclick="location.href = '<?php echo base_url(); ?>index.php/proposta_comercial';">
        </div>

        <?php echo form_close(); ?>

    </div>

</div>

<script type="text/javascript">
    $(document).ready(function () {
        oculta_campos();

        $("#empresas").multiselect({
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

        function mostra_info_adicional(valor) {
            oculta_campos();

            if (valor !== "") {
                if (valor === "Governos Municipais") {
                    $("#dados_casadinha").show();
                    $("#eh_capital").show();
                    $("#dados_consorcio").hide();
                    document.getElementById("empresas").required = false;
                } else if (valor === "Empresas Interesse Público") {
                    $("#dados_interesse").show();
                    $("#eh_capital").hide();
                    $("#dados_consorcio").hide();
                    document.getElementById("empresas").required = false;
                } else if (valor === "Consórcios Públicos") {
                    $("#dados_consorcio").show();
                    $("#dados_casadinha").hide();
                    $("#eh_capital").hide();
                    document.getElementById("empresas").required = false;
                } else if (valor === "Parlamentar") {
                    $("#dados_parlamentar").show();
                } else if (valor === "Governos Estaduais") {
                    $("#dados_estadual").show();
                    document.getElementById("empresas").required = false;
                } else if (valor === "Organizações Sociais Sem Fins Lucrativos" || valor === "Consórcios Públicos") {
                    $("#dados_ossfl").show();
                    document.getElementById("empresas").required = true;
                }
            }
        }

        $("#valor_proposta_comercial").mask("#.##0,00", {reverse: true});

        $("#cnpj_extra").click(function () {
            $("#nums_cnpj_extra").hide();
            if ($(this).is(":checked"))
                $("#nums_cnpj_extra").show();
        });

        function oculta_campos() {
            $("#dados_consorcio").hide();
            $("#dados_casadinha").hide();
            $("#dados_interesse").hide();
            $("#dados_parlamentar").hide();
            $("#dados_ossfl").hide();
            $("#nums_cnpj_extra").hide();
            $("#eh_capital").hide();
            $("#capital").attr('checked', false);
            $("#dados_estadual").hide();
        }

        $("#tipo_proposta").change(function () {
            mostra_info_adicional($(this).val());
        });

        mostra_info_adicional($("#tipo_proposta").val());
    });
</script>