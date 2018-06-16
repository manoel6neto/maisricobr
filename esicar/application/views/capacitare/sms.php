<div class="capacitare_principal">
    <div class="box_celulares">
        <form id="capacitare" method="post">
            <h2>Usuários cadastrados</h2>
            <?php if ($capacitare_sms != NULL && count($capacitare_sms) > 0): ?>
                <table class="responstable">
                    <tr>
                        <td colspan="4" style="text-align: left !important;">
                            <input type="checkbox" class="selecionarTodosList" style="margin-left: 10px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span> Selecionar todos</span>
                        </td>
                    </tr>
                    <tr>
                        <th style="width: 30px; border: 0px !important;"></th>
                        <th style="border: 0px !important;">Celular</th>
                        <th style="border: 0px !important;">Cadastro</th>
                    </tr>
                    <?php foreach ($capacitare_sms as $sms): ?>
                        <tr>
                            <td><input type="checkbox" class="checkboxInput" name="celulares[]" value="<?php echo $sms->telefone; ?>"/></td>
                            <td><?php echo $sms->telefone; ?></td>
                            <td><?php echo $model->format_data($sms->cadastro); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
                <label style="font-size: 14px; color: #444;">Mensagem:</label><br />
                <textarea rows="5" cols="144" id="mensagem" name="mensagem" draggable="false" maxlength="100" title="Mensagem"></textarea><br />
                <input style="width: 100px; margin-top: 10px; padding: 5px; border-radius: 5px; background: #167F92; color: #FFF;" id="login" type="submit" name="login" value="Enviar"/>
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
