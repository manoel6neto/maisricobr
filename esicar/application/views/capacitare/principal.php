<div class="capacitare_principal">
    <div class="box_principal">
        <h2 style="text-align: center; padding-bottom: 4%;"><u>GESTÃO DE HOTSPOT</u></h2>
        <form id="principal" method="post">
            <div class="form-row centralizar" style="padding: 4% 0 4% 0;">
                <select class="select_capacitare" id="eventos" name="eventos">
                    <option class="select_capacitare" value="0">Todos os eventos</option>
                    <?php foreach ($eventos as $evento): ?>
                        <option class="select_capacitare" value="<?php echo $evento->id; ?>"><?php echo $evento->nome . ' '; ?><?= $evento->ativo == 1 ? "(Ativo)" : ""; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-row centralizar" style="padding: 4% 0 4% 0;">
                <div class="btn-group btn-group-lg" role="group" aria-label="Módulos">
                    <button id="acao_evento" type="submit" name="acao" class="btn btn-secondary btn-capacitare" value="EVENTOS">EVENTOS</button>
                    <button id="acao_sms" type="submit" name="acao" class="btn btn-secondary btn-capacitare" value="SMS">SMS</button>
                    <button id="acao_email" type="submit" name="acao" class="btn btn-secondary btn-capacitare" value="EMAIL">E-MAIL</button>
                </div>
            </div>
        </form>
    </div>
</div>
