<div class="capacitare_principal">
    <div class="box_celulares">
        <form id="capacitare" method="post">
            <h3>Usuários cadastrados</h3><br/>
            <?php if ($capacitare_sms != NULL && count($capacitare_sms) > 0): ?>
                <div class="table-responsive">
                    <table class="table table-bordered responstable">
                        <tr>
                            <td colspan="4" style="text-align: left !important;">
                                <input type="checkbox" class="selecionarTodosList" style="margin-left: 10px;">&nbsp;<span> Selecionar todos</span>
                            </td>
                        </tr>
                        <tr>
                            <th style="width: 50px; text-align: center;"></th>
                            <th style="text-align: center;">Nº Celular</th>
                            <th style="text-align: center;">(Data & Hora) Cadastro</th>
                        </tr>
                        <?php foreach ($capacitare_sms as $sms): ?>
                            <tr>
                                <td><input type="checkbox" class="checkboxInput" name="celulares[]" value="<?php echo $sms->telefone; ?>"/></td>
                                <td><?php echo $model->mask($sms->telefone, '(##) #####-####'); ?></td>
                                <td><?php echo $model->format_data($sms->cadastro); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                <label for="mensagem">Mensagem:</label><br />
                <textarea class="form-control noresize" rows="7" cols="140" contenteditable="true" contextmenu="true" id="mensagem" name="mensagem" draggable="false" maxlength="100" title="Mensagem"></textarea><br />
                <input class="btcap" style="width: 100px; margin-top: 10px; padding: 5px; border-radius: 5px; background: #167F92; color: #FFF; float: left;" id="login" type="submit" name="login" value="Enviar"/>
            </form>
            <form action="<?php echo base_url("index.php/capacitare/principal"); ?>">
                <input class="btcap" style="width: 100px; margin-left: 10px; margin-top: 10px; padding: 5px; border-radius: 5px; background: #167F92; color: #FFF;" id="voltar" type="submit" name="voltar" value="Voltar"/>
            </form>
        <?php else: ?>
            <h1>Nenhum usuário cadastrado!!</h1>
        <?php endif; ?>
        </form>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $(".selecionarTodosList").click(function () {
            $(".checkboxInput").each(function () {
                if ($(".selecionarTodosList").is(":checked")) {
                    $(this).attr("checked", $(".selecionarTodosList").is(":checked"));
                } else {
                    $(this).removeAttr("checked");
                }
            });
        });
    });
</script>
