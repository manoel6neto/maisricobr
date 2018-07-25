<div class="capacitare_principal">
    <div class="box_principal">
        <h1 style="text-align: center; margin-bottom: 50px;">CAPACITARE - GESTÃO DE HOTSPOT</h1>
        <form id="principal" method="post">
            <select class="select_capacitare" id="eventos" name="eventos">
                <option value="0">Todos</option>
                <?php foreach ($eventos as $evento): ?>
                    <option value="<?php echo $evento->id; ?>"><?php echo $evento->nome . ' - ' . $model->format_data_date($evento->data_evento); ?></option>
                <?php endforeach; ?>
            </select>
            <div class="buttons_div" style="float: left; margin-left: 5%;">
                <input style="width: 200px; padding: 5px; border-radius: 5px; background: #167F92; color: #FFF; height: 100px; font-size: 25px; font-weight: 900;" id="acao_email" type="submit" name="acao" value="EMAIL"/>
            </div>
            <div class="buttons_div" style="float: right; margin-right: 5%;">
                <input style="width: 200px; padding: 5px; border-radius: 5px; background: #167F92; color: #FFF; height: 100px; font-size: 25px; font-weight: 900;" id="acao_sms" type="submit" name="acao" value="SMS"/>
            </div>
        </form>
    </div>
</div>