<div class="navbar navbar-fixed-top bg-white main" role="navigation">

    <div class="navbar-header">
        <div class="navbar-brand">
            <div class="pull-left">
                <a href="" class="toggle-button toggle-sidebar btn-navbar"><i
                        class="fa fa-bars"></i></a>
            </div>
            <a href="<?php echo base_url(); ?>index.php/in/gestor" class="appbrand innerL texto"><img src="<?php echo base_url() ?>layout/assets/images/logo_interno.png" style="width: 65%;"/></a>
        </div>
    </div>


    <!-- div class="navbar-header navbar-right hidden-xs">
    <ul class="nav navbar-nav ">
                    <li class="dropdown notification">
                            <a href="#" class="dropdown-toggle menu-icon" data-toggle="dropdown">
                            <i class="fa fa-fw fa-envelope-o"></i><span class="badge badge-primary">2</span></a>
                    </li>
                    <li class="dropdown notification  ">
                            <a href="#" class="dropdown-toggle menu-icon" data-toggle="dropdown">
                            <i class="fa fa-fw fa-exclamation-circle"></i><span class="badge badge-info">2</span></a>
                    </li>
                     <li class="dropdown notification  ">
                            <a href="#" class="dropdown-toggle menu-icon" data-toggle="dropdown">
                            <i class="fa fa-fw fa-dropbox"></i><span class="badge badge-success badge-icon"><i class="fa fa-check"></i></span></a>
                    </li>
            </ul>
    -->

    <?php
    $ci = &get_instance();
    $ci->load->model('usuariomodel');
    ?>

    <ul class="nav navbar-nav  navbar-right hidden-xs">
        <!-- <li class="dropdown">
                <a href="" class="dropdown-toggle user" data-toggle="dropdown">
                        <img src="" alt="" class="img-circle"/><span class="hidden-xs hidden-sm"> Usuário</span>
                        <span class="caret"></span>
                </a>
                <ul class="dropdown-menu list">
                        <li><a href="#">Perfil<i class="fa fa-user pull-right"></i></a></li>
                        <li><a href="#">Conta<i class="fa fa-pencil pull-right"></i></a></li>
                        <li><a href="#">Ajuda<i class="fa fa-question-circle pull-right"></i></a></li>
                        <li><a href="#">Sair<i class="fa fa-sign-out pull-right"></i></a></li>
                </ul>
        </li> -->
        <?php if ($this->session->userdata('nivel') == 4 && $this->session->userdata('sistema') == 'P'): ?>
            <li style="margin-right: 10px;">
                <span style="color: #428bca; font-size: 14px;">Parlamentar<br></span><span style="color: red; font-size: 14px;"><?php echo $ci->usuariomodel->get_parlamentar_vinculado_vendedor() . " - " . $this->session->userdata('estado_parlamentar'); ?></span>
            </li>
        <?php endif; ?>

        <?php if ($this->session->userdata('nivel') == 4 && $this->session->userdata('sistema') == 'M'): ?>
            <?php $dadosMunicipio = $ci->usuariomodel->get_municipio_by_vendedor($this->session->userdata('id_usuario'), true); ?>
            <li style="margin-right: 10px; margin-top: 6px;">
                <br><span style="color: red; font-size: 14px;"><?php echo $dadosMunicipio[0] . " - " . $dadosMunicipio[1]; ?></span>
            </li>
        <?php endif; ?>

        <?php if ($this->session->userdata('tipo_gestor') == 10 && $ci->usuariomodel->get_tempo_restante($this->session->userdata('id_usuario')) != null): ?>
            <?php if ($ci->usuariomodel->check_is_free($this->session->userdata('id_usuario'))): ?>
                <li style="margin-right: 10px; margin-top: 5px;">
                    <span style="color: gray; font-size: 12px; font-weight: bold;">CONTA GRATUITA: </span><br><span style="color: #3a7ec0; font-size: 12px; font-weight: bold;"><?php echo 'Restantes ' . ($ci->usuariomodel->get_tempo_restante($this->session->userdata('id_usuario'))->days + 1) . ' dias' ?></span>
                </li>
            <?php else: ?>
                <li style="margin-right: 10px; margin-top: 5px;">
                    <span style="color: gray; font-size: 12px; font-weight: bold;">CONTA CONTRATO: </span><br><span style="color: #3a7ec0; font-size: 12px; font-weight: bold;"><?php echo 'Restantes ' . ($ci->usuariomodel->get_tempo_restante($this->session->userdata('id_usuario'))->days + 1) . ' dias' ?></span>
                </li>
            <?php endif; ?>
        <?php endif; ?>    

        <?php if ($this->session->userdata('nivel') == 1): ?>
            <li style="margin-right: 10px;">
                <span style="color: red; font-size: 14px;">Endereço IP <br><?php echo $_SERVER['SERVER_ADDR']; ?></span>
            </li>
        <?php endif; ?>

        <li style="margin-right: 2px;">
            <!--<img src="<?php echo base_url(); ?>layout/assets/images/bandeira.png" height="30px;">-->
            <span style="color: #3a7ec0; font-size: 14px; font-weight: bold;"> | </span><br><span style="color: #3a7ec0; font-size: 14px; font-weight: bold;"> | </span>
        </li>
        <li style="padding: 5px; margin-right: 10px;">
            <span style="color: #3a7ec0; font-size: 12px; font-weight: bold;"><?php echo $this->session->userdata('nome_usuario') . '</span><br/><span style="color: #3a7ec0; font-size: 12px; font-weight: bold;">' . $this->session->userdata('entidade'); ?></span></li>
        <?php if ($this->session->userdata('nivel') == 1 || ($this->session->userdata('nivel') == 2 && $this->session->userdata('tipo_gestor') != 10) || $this->session->userdata('nivel') == 4 || $this->session->userdata('nivel') == 6) { ?>
            <li><a href = "<?php echo base_url(); ?>index.php/controle_usuarios" title="Área do Cliente" class = "menu-icon"><i class = "fa fa-user"></i></a></li>
        <?php } else { ?>
            <?php if ($this->session->userdata('tipo_gestor') == 10): ?>
                <li id="menu_user"><a class="menu-icon" id="user_v" href='#'><i class="fa fa-user"></i></a></li>
            <?php else: ?>
                <li><a href = "<?php echo base_url(); ?>index.php/controle_usuarios/atualiza_usuario?id=<?php echo $this->session->userdata('id_usuario'); ?>" class = "menu-icon"><i class = "fa fa-user"></i></a></li>
            <?php endif; ?> 
        <?php } ?>
        <li><a href="<?php echo base_url(); ?>index.php/in/login/sair" class="menu-icon"><i class="fa fa-power-off"></i></a></li>

    </ul>
</div>

<?php if ($this->session->userdata('tipo_gestor') == 10): ?>
    <div id="painel-form" hidden="true">
        <form action="#" id="form_modal">
            <table>
                <tr style="border-bottom: solid silver; border-width: thin; margin-top: 5px; width: 100%;">
                    <td style="width: 150px;">
                        <br><p></p>
                        <label style="color: black; font-size: 10px; font-weight: bold; margin-left: 5px;">Usuario: </label>
                    </td>
                    <td style="width: 250px; text-align: right; text-spacing: trim-end; padding-right: 5px;">
                        <br><p></p>
                        <label style="color: #3a7ec0; font-size: 10px; margin-right: 10px;"><?php echo $ci->usuariomodel->get_nome_by_id($this->session->userdata('id_usuario')); ?></label>
                        <a href="<?php echo base_url(); ?>index.php/controle_usuarios/atualiza_usuario?id=<?php echo $this->session->userdata('id_usuario'); ?>" style="text-decoration: none;"><span style="background-color: #3a7ec0; color: #fefefe; font-size: 8px; font-weight: bold; margin: 1px; padding: 1px; border: solid #3a7ec0; border-width: 2px; border-radius: 3px;"> Editar </span></a>
                    </td>
                </tr>

                <tr style="border-bottom: solid silver; border-width: thin; margin-top: 15px; margin-bottom: 2px; width: 100%;">
                    <td style="width: 150px;">
                        <br><p></p>
                        <label style="color: black; font-size: 10px; font-weight: bold; margin-left: 5px;">Dados do contrato: </label>
                    </td>
                    <td style="width: 250px; text-align: right; text-spacing: trim-end; padding-right: 5px;">
                        <br><p></p>
                        <label style="color: #3a7ec0; font-size: 10px;"><?php echo date("d/m/Y", strtotime($ci->usuariomodel->get_gestor_by_usuario_view($this->session->userdata('id_usuario'))->inicio_vigencia)) . '  -  ' . date("d/m/Y", strtotime($ci->usuariomodel->get_gestor_by_usuario_view($this->session->userdata('id_usuario'))->validade)); ?></label><br>
                    </td>
                </tr>

                <tr style="border-bottom: solid silver; border-width: thin; margin-top: 15px; margin-bottom: 2px; width: 100%;">
                    <td style="width: 150px;">
                        <br><p></p>
                        <label style="color: black; font-size: 10px; font-weight: bold; margin-left: 5px;">Dias restantes: </label>
                    </td>
                    <td style="width: 250px; text-align: right; text-spacing: trim-end; padding-right: 5px;">
                        <br><p></p>
                        <label style="color: #3a7ec0; font-size: 10px; margin-right: 10px;"><?php echo ($ci->usuariomodel->get_tempo_restante($this->session->userdata('id_usuario'))->days + 1) . '(dias)'; ?></label>
                        <?php if ($ci->usuariomodel->test_desconto($this->session->userdata('id_usuario'))) { ?>
                            <a href="<?php echo base_url(); ?>index.php/compra?token=UGh5NWk1X0MwbVByYXMy&PA=1&SP=1&TP=A&desconto=<?php echo $this->session->userdata('id_usuario'); ?>" style="text-decoration: none;"><span style="background-color: #3a7ec0; color: #fefefe; font-size: 8px; font-weight: bold; margin: 1px; padding: 1px; border: solid #3a7ec0; border-width: 2px; border-radius: 3px;"> Renovar </span></a>
                        <?php  } else { ?>
                            <a href="<?php echo base_url(); ?>index.php/compra?token=UGh5NWk1X0MwbVByYXMy&PA=1&SP=1&TP=A" style="text-decoration: none;"><span style="background-color: #3a7ec0; color: #fefefe; font-size: 8px; font-weight: bold; margin: 1px; padding: 1px; border: solid #3a7ec0; border-width: 2px; border-radius: 3px;"> Renovar </span></a>
                        <?php } ?>
                    </td>
                </tr>

                <tr style="border-width: thin; margin-top: 15px; width: 100%;">
                    <td style="width: 150px;">
                        <br><p></p>
                        <label style="color: black; font-size: 10px; font-weight: bold; margin-left: 5px;">Proponente: </label>
                    </td>
                    <td style="width: 250px; text-align: right; text-spacing: trim-end; padding-right: 5px;">
                        <br><p></p>
                        <label style="color: #3a7ec0; font-size: 10px;"><?php echo $ci->usuariomodel->get_cnpjs_by_usuario_view($this->session->userdata('id_usuario'))->cnpj_instituicao . '<p> (' . $ci->usuariomodel->get_cnpjs_by_usuario_view($this->session->userdata('id_usuario'))->cnpj . ')'; ?></label><p>
                    </td>
                </tr>
            </table>
        </form>
    </div>
<?php endif; ?>

<script type="text/javascript">
    $(document).ready(function () {
        var dialog_user;

        dialog_user = $("#painel-form").dialog({
            modal: false,
            width: 430,
            height: 290,
            title: "Área do cliente"
        });

        dialog_user.dialog("close");

        $("#menu_user").click(function () {
            if (dialog_user.is(":visible")) {
                dialog_user.dialog("close");
            } else {
                dialog_user.dialog("open");
                dialog_user.dialog({position: [($(window).width() / 1.33), 50]});
            }
        });
    });
</script>

<style type="text/css">
    a {
        text-decoration:none;
    } 
</style>