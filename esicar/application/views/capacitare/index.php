<div class="capacitare_principal">
    <div class="box">
        <form id="capacitare" method="post">
            <img src="<?php echo base_url(); ?>layout/assets/capacitare/logo_capacitare.png" width="300" height="200" style="float: start; padding-bottom: 10px;"/>
            <label style="margin-left: 8px; font-size: 12px; color: #AAA;">Email:</label>
            <input type="text" id="email" name="email" autocomplete="false" style="width: 280px; margin-left: 8px; margin-bottom: 10px;" title="Email"/><br />
            <label style="margin-left: 8px; font-size: 12px; color: #AAA;">Celular: <span style="font-size: 8px; color: #AAA;">DD + Número (apenas dígitos)</span></label>
            <input onkeypress="return event.charCode = 8 || event.charCode >= 48 && event.charCode <= 57" id="telefone" name="telefone" autocomplete="false" style="width: 280px; margin-left: 8px; margin-bottom: 25px;" title="Celular"/><br />
            <input id="login" type="submit" name="login" value="Navegar" style="width: 100px; margin-left: 98px; padding: 5px; border-radius: 5px; background: #167F92; color: #FFF;"/>
        </form>
    </div>
</div>
