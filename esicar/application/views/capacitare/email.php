<div class="capacitare_principal">
    <div class="box_email">
        <form id="capacitare" method="post">
            <h2>Usuários cadastrados</h2>
            <?php if ($capacitare_email != NULL && count($capacitare_email) > 0): ?>
                <table class="responstable">
                    <tr>
                        <td colspan="4" style="text-align: left !important;">
                            <input type="checkbox" class="selecionarTodosList" style="margin-left: 10px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span> Selecionar todos</span>
                        </td>
                    </tr>
                    <tr>
                        <th style="width: 30px; border: 0px !important;"></th>
                        <th style="border: 0px !important;">Email</th>
                        <th style="border: 0px !important;">Cadastro</th>
                    </tr>
                    <?php foreach ($capacitare_email as $email): ?>
                        <tr>
                            <td style="width: 30px;"><input type="checkbox" class="checkboxInput" name="emails[]" value="<?php echo $email->email; ?>"/></td>
                            <td><?php echo $email->email; ?></td>
                            <td><?php echo $model->format_data($email->cadastro); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
                <label style="font-size: 14px; color: #444;">Assunto:</label><br />
                <input type="text" id="assunto" name="assunto" style="width: 1170px;" title="Assunto"/><br /><br />
                <label style="font-size: 14px; color: #444;">Mensagem:</label><br />
                <textarea rows="5" cols="144" id="mensagem" name="mensagem" draggable="false" maxlength="1000" title="Mensagem"></textarea><br />
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
