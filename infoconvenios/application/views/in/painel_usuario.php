<link rel="stylesheet" type="text/css" media="screen" href="<?= base_url(); ?>configuracoes/css/style.css">

<script type="text/javascript" language="Javascript1.1" src="<?= base_url(); ?>configuracoes/js/dimmingdiv.js"></script>
<script type="text/javascript" language="Javascript1.1" src="<?= base_url(); ?>configuracoes/js/layout-common.js"></script>
<script type="text/javascript" language="Javascript1.1" src="<?= base_url(); ?>configuracoes/js/key-events.js"></script>
<script type="text/javascript" language="Javascript1.1" src="<?= base_url(); ?>configuracoes/js/scripts.js"></script>
<script type="text/javascript" language="Javascript1.1" src="<?= base_url(); ?>configuracoes/js/cpf.js"></script>
<script type="text/javascript" language="Javascript1.1" src="<?= base_url(); ?>configuracoes/js/moeda.js"></script>
<script type="text/javascript" language="Javascript1.1" src="<?= base_url(); ?>configuracoes/js/textCounter.js"></script>
<script type="text/javascript" language="Javascript1.1" src="<?= base_url(); ?>configuracoes/js/calculaValor.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>configuracoes/js/thumbnailviewer.js"></script>

<script type="text/javascript" src="<?= base_url(); ?>configuracoes/js/jquery-1.8.2.min.js" charset="utf-8"></script>
<script type="text/javascript" src="<?= base_url(); ?>configuracoes/js/jquery-ui-1.9.0.custom.min.js" charset="utf-8"></script>
<link rel="stylesheet" type="text/css" media="screen" href="<?= base_url(); ?>configuracoes/css/jquery-ui-1.9.0.custom.min.css">

<script type="text/javascript" src="<?= base_url(); ?>configuracoes/js/jquery-1.8.2.min.js" charset="utf-8"></script>
<script type="text/javascript" src="<?= base_url(); ?>configuracoes/js/jquery-ui-1.9.0.custom.min.js" charset="utf-8"></script>
<link rel="stylesheet" type="text/css" media="screen" href="<?= base_url(); ?>configuracoes/css/jquery-ui-1.9.0.custom.min.css">

<div id="painel-form" style="width: 340px; height: 220px; background-color: #fefefe; border: solid #3a7ec0; border-width: thin;">
    <table>
        <tr style="border-bottom: solid silver; border-width: thin; margin-top: 5px; width: 100%;">
            <td style="width: 120px;">
                <br><p></p>
                <label style="color: black; font-size: 10px; font-weight: bold; margin-left: 5px;">Usuario: </label>
            </td>
            <td style="width: 220px; text-align: right; text-spacing: trim-end; padding-right: 10px;">
                <br><p></p>
                <label style="color: #3a7ec0; font-size: 10px; margin-right: 5px;"><?php echo $usuariomodel->get_nome_by_id($this->session->userdata('id_usuario')); ?></label>
                <a href="<?php echo base_url(); ?>index.php/controle_usuarios/atualiza_usuario?id=<?php echo $this->session->userdata('id_usuario'); ?>" ><span style="background-color: #3a7ec0; color: #fefefe; font-size: 8px; font-weight: bold; margin: 1px; padding: 1px; border: solid #3a7ec0; border-width: 2px; border-radius: 3px;"> Editar </span></a>
            </td>
        </tr>
        
        <tr style="border-bottom: solid silver; border-width: thin; margin-top: 15px; margin-bottom: 2px; width: 100%;">
            <td style="width: 120px;">
                <br><p></p>
                <label style="color: black; font-size: 10px; font-weight: bold; margin-left: 5px;">Data Cadastro: </label>
            </td>
            <td style="width: 220px; text-align: right; text-spacing: trim-end; padding-right: 10px;">
                <br><p></p>
                <label style="color: #3a7ec0; font-size: 10px;"><?php echo date("d/m/Y", strtotime($usuariomodel->get_gestor_by_usuario($this->session->userdata('id_usuario'))[0]->inicio_vigencia)); ?></label><br>
            </td>
        </tr>
        
        <tr style="border-bottom: solid silver; border-width: thin; margin-top: 15px; margin-bottom: 2px; width: 100%;">
            <td style="width: 120px;">
                <br><p></p>
                <label style="color: black; font-size: 10px; font-weight: bold; margin-left: 5px;">Data Vencimento: </label>
            </td>
            <td style="width: 220px; text-align: right; text-spacing: trim-end; padding-right: 10px;">
                <br><p></p>
                <a href="<?php echo base_url(); ?>index.php/compra?token=UGh5NWk1X0MwbVByYXMy"><span style="background-color: #3a7ec0; color: #fefefe; font-size: 8px; font-weight: bold; margin: 1px; padding: 1px; border: solid #3a7ec0; border-width: 2px; border-radius: 3px;"> Renovar </span></a>
                <label style="color: #3a7ec0; font-size: 10px; margin-left: 5px;"><?php echo date("d/m/Y", strtotime($usuariomodel->get_gestor_by_usuario($this->session->userdata('id_usuario'))[0]->validade)); ?></label><br>
            </td>
        </tr>
        
        <tr style="border-width: thin; margin-top: 15px; margin-bottom: 2px; width: 100%;">
            <td style="width: 120px;">
                <br><p></p>
                <label style="color: black; font-size: 10px; font-weight: bold; margin-left: 5px;">Proponente: </label>
            </td>
            <td style="width: 220px; text-align: right; text-spacing: trim-end; padding-right: 10px;">
                <br><p></p>
                <label style="color: #3a7ec0; font-size: 10px;"><?php echo $usuariomodel->get_cnpjs_by_usuario($this->session->userdata('id_usuario'))[0]->cnpj_instituicao . '<p> (' . $usuariomodel->get_cnpjs_by_usuario($this->session->userdata('id_usuario'))[0]->cnpj . ')'; ?></label><p>
            </td>
        </tr>
    </table>
</div>


