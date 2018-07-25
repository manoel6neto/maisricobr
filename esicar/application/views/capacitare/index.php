<div class="capacitare_principal">
    <form id="capacitare" method="post">
        <img src="<?php echo base_url(); ?>layout/assets/capacitare/logo_capacitare_2.jpeg" style="width: 100%; padding-bottom: 15px;"/>
        <label style="padding: 2px; font-size: 12px; color: #AAA;">Email:</label>
        <input type="text" id="email" name="email" autocomplete="false" style="width: 99%; padding: 5px 1px 5px 1px; margin-bottom: 15px; border: 1px solid #167F92;" title="Email"/><br />
        <label style="padding: 2px; font-size: 12px; color: #AAA;">Celular: <span style="font-size: 8px; color: #AAA;">DD + Número (apenas dígitos)</span></label>
        <input onkeypress="return event.charCode = 8 || event.charCode >= 48 && event.charCode <= 57" id="telefone" name="telefone" autocomplete="false" style="width: 99%; padding: 5px 1px 5px 1px; margin-bottom: 25px; border: 1px solid #167F92;" title="Celular"/><br />
        <input id="login" type="submit" name="login" value="Navegar" style="width: 100%; padding: 5px; border-radius: 5px; background: #167F92; color: #FFF; margin-bottom: 10px;"/>
    </form>
</div>
