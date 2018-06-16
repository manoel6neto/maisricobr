<div id="content" class="innerAll bg-white">

    <h1 class="bg-white content-heading border-bottom">Propostas Comerciais &nbsp;&nbsp;<img src="<?php echo base_url(); ?>layout/assets/images/loader.gif" style="width: 30px;" id="loader"></h1>

    <br>

    <a class="btn btn-success" target="_blank" href="<?php echo base_url('arquivos_download/tabela_precos.pdf'); ?>"><i class="fa fa-download"></i> Tabela de Preços</a>

    <input class="btn btn-primary" type="button" value="Nova Proposta" onclick="location.href = '<?php echo base_url(); ?>index.php/proposta_comercial/gera_proposta'">
    <br><br>

    <div class="bg-white">
        <div style="padding-top: 2%;">
            <div class="col-md-10 col-sm-10 col-sm-offset-1 bg-white">
                <?php if ($proposta_cadastradas != null): ?>
                    <table class="table">
                        <tr style="color: #428bca; font-size: 14px;">
                            <th>Descrição</th>
                            <th>Valor</th>
                            <th>% Desc</th>
                            <th>Tipo</th>
                            <th>Data</th>
                            <th></th>
                        </tr>

                        <?php foreach ($proposta_cadastradas as $p): ?>
                            <tr>
                                <td><?php echo $p->descricao_proposta_comercial; ?></td>
                                <td><?php echo $p->tipo_proposta == 'Governos Municipais' ? number_format($p->valor_proposta_comercial, 2, ",", ".") : number_format(($p->valor_proposta_comercial * ((100 - $p->percentual_desconto) / 100)), 2, ",", ".") ?></td>
                                <td><?php echo $p->percentual_desconto > 0 ? number_format($p->percentual_desconto, 2, ",", ".") . " %" : ""; ?></td>
                                <td><?php echo $p->tipo_proposta; ?></td>
                                <td><?php echo implode("/", array_reverse(explode("-", $p->data_cadastro))); ?></td>
                                <td style="text-align: right;">
                                    <a title="Enviar Proposta Por Email" class="btn btn-sm btn-success mandaEmail" id="<?php echo $p->id_proposta_comercial ?>"><i class="fa fa-envelope-o"></i></a>
                                    <a title="Gerar PDF" class="btn btn-sm btn-info" onclick="window.open('<?php echo base_url('index.php/proposta_comercial/gera_pdf?id=' . $p->id_proposta_comercial); ?>', '_blank');">Download</a>
                                    <a title="Excluir" class="btn btn-sm btn-primary" onclick="location.href = '<?php echo base_url(); ?>index.php/proposta_comercial/deleta_proposta?id=<?php echo $p->id_proposta_comercial; ?>';"><i class="fa fa-trash-o"></i></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                <?php else: ?>
                    <h1 style="text-align: center;">Nenhuma proposta encontrada.</h1>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#loader").hide();

        $(".mandaEmail").click(function () {
            var idProposta = $(this).attr('id');

            $.ajax({
                url: "<?php echo base_url("index.php/proposta_comercial/get_dados_visita"); ?>",
                dataType: "json",
                data: {
                    id: idProposta
                },
                type: "get",
                beforeSend: function () {
                    $("#loader").show();
                },
                success: function (data) {
                    $("#loader").hide();

                    $("#idProposta").val(idProposta);
                    $("#contatoPrincipal").html(data.nome_contato + " - " + data.email_contato);

                    $("#dialog-form").css('visibility', 'visible');
                    dialog.dialog("open");
                }
            });
        });

        var dialog;

        dialog = $("#dialog-form").dialog({
            height: 520,
            width: 550,
            modal: true,
            buttons: {
                "Enviar": function () {
                    $("#pop").css('height', $(document).height());
                    $('#pop').css('display', 'block');

                    var emailRegex = /^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/;

                    if ($("#email").val() === "" || ($("#email").val() !== "" && checkRegexp($("#email").val(), emailRegex))) {
                        if (!confirm("Confirma o envio do PDF da proposta?")) {
                            return false;
                        }

                        $(this).dialog("close");

                        $.ajax({
                            url: "<?php echo base_url("index.php/proposta_comercial/send_proposta_usuario"); ?>",
                            dataType: "html",
                            data: {
                                id: $("#idProposta").val(),
                                assunto: $("#assunto").val(),
                                mensagem: $("#mensagem").val(),
                                email: $("#email").val()
                            },
                            type: "post",
                            beforeSend: function () {
                                $("#loader").show();
                            },
                            success: function (data) {
                                $("#loader").hide();
                                if (data)
                                    alert("Proposta Enviada com Sucesso.");
                                else
                                    alert("Erro ao Enviar Proposta.");
                            }
                        });
                    }
                },
                "Cancelar": function () {
                    $(this).dialog("close");
                }
            }
        }).position({
            my: "center",
            at: "center",
            of: window
        });

        function checkRegexp(o, regexp) {
            emails = o.split(",");
            for (i = 0; i < emails.length; i++) {
                if ($.trim(emails[i]) !== "") {
                    if (!(regexp.test($.trim(emails[i])))) {
                        alert("Informe um email válido.");
                        return false;
                    }
                }
            }

            return true;
        }

        dialog.dialog("close");
    });
</script>


<div id="dialog-form" title="Enviar Proposta Comercial" style="visibility: collapse;">
    <form>
        <fieldset>
            <input type="hidden" name="idProposta" id="idProposta">
            <label for="assunto">Assunto</label><br>
            <input type="text" name="assunto" size="40" id="assunto" value="Proposta Comercial Sistema e-SICAR" class="text ui-widget-content ui-corner-all" style="color: black;"><br>
            <label for="mensagem">Mensagem</label><br>
            <textarea name="mensagem" id="mensagem" rows="8" cols="50" class="text ui-widget-content ui-corner-all">Segue a proposta comercial de utilização do sistema e-SICAR.</textarea><br>
            <label>Contato Principal</label><br>
            <i><label id="contatoPrincipal"></label></i><br><br>
            <label for="email">Copia de Email - Separe os emails por vírgula (,)</label><br>
            <input type="text" name="email" id="email" size="40" value="" class="text ui-widget-content ui-corner-all" style="color:black;"><br>

            <!-- Allow form submission with keyboard without duplicating the dialog button -->
            <input type="submit" tabindex="-1" style="position:absolute; top:-1000px">
        </fieldset>
    </form>
</div>
