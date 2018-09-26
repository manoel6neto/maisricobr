<div class="capacitare_principal">
    <div class="box_email">
        <form id="capacitare" method="post">
            <h3>Usuários cadastrados</h3><br/>
            <?php if ($capacitare_email != NULL && count($capacitare_email) > 0): ?>
                <table class="responstable">
                    <tr>
                        <td colspan="4" style="text-align: left !important;">
                            <input type="checkbox" class="selecionarTodosList" style="margin-left: 10px;">&nbsp;<span> Selecionar todos</span>
                        </td>
                    </tr>
                    <tr>
                        <th style="width: 50px; text-align: center;"></th>
                        <th style="text-align: center;">Email</th>
                        <th style="text-align: center;">Cadastro</th>
                    </tr>
                    <?php foreach ($capacitare_email as $email): ?>
                        <tr>
                            <td style="width: 30px;"><input type="checkbox" class="checkboxInput" name="emails[]" value="<?php echo $email->email; ?>"/></td>
                            <td><?php echo $email->email; ?></td>
                            <td><?php echo $model->format_data($email->cadastro); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
                <label for="assunto">Assunto:</label><br />
                <input class="boxsizingBorder" type="text" id="assunto" name="assunto" style="width: 1170px;" title="Assunto"/><br /><br />
                <label for="mensagem">Mensagem:</label><br />
                <textarea class="boxsizingBorder noresize" rows="7" cols="140" id="mensagem" name="mensagem" draggable="false" maxlength="1000" title="Mensagem"></textarea><br />
                <input class="btcap" style="width: 100px; margin-top: 10px; padding: 5px; border-radius: 5px; background: #167F92; color: #FFF; float: left;" id="login" type="submit" name="login" value="Enviar"/>
            </form>
            <form action="<?php echo base_url("index.php/capacitare/principal"); ?>">
                <input class="btcap" style="width: 100px; margin-left: 10px; margin-top: 10px; padding: 5px; border-radius: 5px; background: #167F92; color: #FFF;" id="voltar" type="submit" name="voltar" value="Voltar"/>
            </form>
        <?php else: ?>
            <h1>Nenhum usuário cadastrado!!</h1>
        <?php endif; ?>

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
