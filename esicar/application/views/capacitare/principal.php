<div class="capacitare_principal">
    <div class="box_principal">
        <h2 style="text-align: center; padding-bottom: 3%;">GESTÃO DE HOTSPOT</h2>
        <form id="principal" method="post">
            <div class="form-row" style="padding: 3%;">
                <select class="select_capacitare" id="eventos" name="eventos">
                    <option value="0">Todos os eventos</option>
                    <?php foreach ($eventos as $evento): ?>
                        <option value="<?php echo $evento->id; ?>"><?php echo $evento->nome . ' - ' . $model->format_data_date($evento->data_evento); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-row centralizar" style="padding: 5% 0 5% 0;">
                <div class="btn-group btn-group-lg" role="group" aria-label="Módulos">
                    <button id="acao_evento" type="submit" name="acao_evento" class="btn btn-secondary btn-capacitare">EVENTOS</button>
                    <button id="acao_sms" type="submit" name="acao_sms" class="btn btn-secondary btn-capacitare">SMS</button>
                    <button id="acao_email" type="submit" name="acao_email" class="btn btn-secondary btn-capacitare">E-MAIL</button>
                </div>
            </div>
        </form>
    </div>
</div>