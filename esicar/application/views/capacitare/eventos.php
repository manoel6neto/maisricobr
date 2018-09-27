<div class="capacitare_principal">
    <div class="box_email">
        <form id="capacitare" method="post">
            <h3>Eventos cadastrados</h3><br/>
            <?php if ($eventos != NULL && count($eventos) > 0): ?>
                <div class="table-responsive">
                    <table class="table table-bordered responstable">
                        <tr>
                            <th style="text-align: center; width: 60%;">Evento</th>
                            <th style="text-align: center; width: 20%;">Cadastro</th>
                            <th style="text-align: center; width: 10%;">Ativo</th>
                            <th style="text-align: center; width: 10%;">&nbsp;-&nbsp;</th>
                        </tr>
                        <?php foreach ($eventos as $evento): ?>
                            <tr>
                                <td style="text-align: center;"><?php echo $evento->nome; ?></td>
                                <td style="text-align: center;"><?php echo $model->format_data_only($evento->data_evento); ?></td>
                                <td style="text-align: center;"><?= $evento->ativo == 1 ? 'Sim' : 'Não' ?></td>
                                <td style="text-align: center;">
                                    <div class="btn-group">
                                        <button class="btn btn-danger btn-sm" type="button">
                                            Ações
                                        </button>
                                        <button type="button" class="btn btn-sm btn-danger dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <span class="sr-only">Ações</span>
                                        </button>
                                        <div class="dropdown-menu" role="menu">
                                            <a class="dropdown-item" href="<?php echo base_url("index.php/capacitare/set_evento_ativo?id={$evento->id}"); ?>">Tornar evento ativo</a>
                                            <a class="dropdown-item" href="<?php echo base_url("index.php/capacitare/remove_evento?id={$evento->id}"); ?>">Remover evento</a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                <div class="container-fluid" style="padding: 5px; border-radius: 5px; background-color: #fafafa; margin-top: 50px !important; margin-bottom: 10px !important;">
                    <div class="form-row col" style="padding-top: 20px;">
                        <div class="col-sm-10">
                            <label for="nome">Nome:</label><br />
                            <input class="form-control" type="text" id="nome" name="nome" style="width: 1170px;" title="Nome"/><br /><br />
                        </div>
                        <div class="col-sm-2">
                            <label for="data">Data:</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroupPrepend">dd/mm/yyyy</span>
                                </div>
                                <input type="text" class="form-control" id="data" name="data" aria-describedby="inputGroupPrepend" required>
                            </div>
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
