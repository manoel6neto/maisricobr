<div class="capacitare_principal">
    <div class="box_email">
        <form id="capacitare" method="post">
            <h3>Eventos cadastrados</h3><br/>
            <?php if ($eventos != NULL && count($eventos) > 0): ?>
                <table class="responstable">
                    <tr>
                        <th style="text-align: center;">Evento</th>
                        <th style="text-align: center;">Cadastro</th>
                        <th style="text-align: center;">Ativo</th>
                        <th style="text-align: center;">&nbsp;-&nbsp;</th>
                    </tr>
                    <?php foreach ($eventos as $evento): ?>
                        <tr>
                            <td style="text-align: center;"><?php echo $evento->nome; ?></td>
                            <td style="text-align: center;"><?php echo $model->format_data($evento->data_evento); ?></td>
                            <td style="text-align: center;"><?= $evento->ativo == 1 ? 'Sim' : 'Não' ?></td>
                            <td style="text-align: center;"></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
                <div class="container-fluid" style="padding: 5px; border-radius: 5px; background-color: #fafafa; margin-top: 50px !important; margin-bottom: 10px !important;">
                    <div class="form-row col" style="padding-top: 20px;">
                        <div class="form-group col-lg-10">
                            <label for="nome">Nome:</label><br />
                            <input class="boxsizingBorder" type="text" id="nome" name="nome" style="width: 1170px;" title="Nome"/><br /><br />
                        </div>
                        <div class="form-group col">
                            <label for="data">Data:</label><br />
                            <input class="boxsizingBorder" type="text" id="data" name="data" style="width: 1170px;" title="Data"/><br /><br />
                        </div>
                    </div>
                </div>
                <input class="btcap" style="width: 100px; margin-top: 10px; padding: 5px; border-radius: 5px; background: #167F92; color: #FFF; float: left;" id="login" type="submit" name="login" value="Adicionar"/>
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
