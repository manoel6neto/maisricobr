<div class="navbar navbar-fixed-top bg-white main" role="navigation">

    <div class="navbar-header">
        <div class="navbar-brand">
            <div class="pull-left">
                <a href="" class="toggle-button toggle-sidebar btn-navbar"><i
                        class="fa fa-bars"></i></a>
            </div>
            <?php if ($this->session->userdata('sistema') == 'P'): ?>
                <a href="<?php echo base_url(); ?>index.php/in/gestor" class="appbrand innerL texto"><img src="<?php echo base_url() ?>layout/assets/images/logo.png" style="width: 90px;"/></a>
            <?php elseif ($this->session->userdata('sistema') == 'M' && $this->session->userdata('nivel') != 9): ?>
                <a href="<?php echo base_url(); ?>index.php/in/gestor" class="appbrand innerL texto"><img src="<?php echo base_url() ?>layout/assets/images/logo.png" style="width: 90px;"/></a>
            <?php elseif ($this->session->userdata('sistema') == 'E'): ?>
                <a href="<?php echo base_url(); ?>index.php/in/gestor" class="appbrand innerL texto"><img src="<?php echo base_url() ?>layout/assets/images/logo.png" style="width: 90px;"/></a>
            <?php elseif($this->session->userdata('nivel') == 9): ?>
                <a href="<?php echo base_url(); ?>index.php/in/gestor" class="appbrand innerL texto"><img src="<?php echo base_url() ?>layout/assets/images/logo.png" style="width: 90px;"/></a>
            <?php endif; ?>
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
        <?php 
        $ci =&get_instance();
        $ci->load->model('usuariomodel');
        ?>
        
        <?php if ($this->session->userdata('nivel') == 4 && $this->session->userdata('sistema') == 'P'): ?>
            <li style="margin-right: 10px;">
                <span style="color: #428bca; font-size: 14px;">Parlamentar<br></span><span style="color: red; font-size: 14px;"><?php echo $ci->usuariomodel->get_parlamentar_vinculado_vendedor() . " - " . $this->session->userdata('estado_parlamentar'); ?></span>
            </li>
        <?php endif; ?>
        
        <?php if ($this->session->userdata('nivel') == 4 && $this->session->userdata('sistema') == 'M'): ?>
        <?php $dadosMunicipio = $ci->usuariomodel->get_municipio_by_vendedor($this->session->userdata('id_usuario'), true);?>
            <li style="margin-right: 10px; margin-top: 6px;">
                <!--<br><span style="color: red; font-size: 14px;"><?php echo $dadosMunicipio[0]." - ".$dadosMunicipio[1]; ?></span>-->
            </li>
        <?php endif; ?>
        
        <?php if ($this->session->userdata('nivel') == 1): ?>
            <li style="margin-right: 10px;">
                <span style="color: red; font-size: 14px;">Endereço IP <br><?php echo $_SERVER['SERVER_ADDR']; ?></span>
            </li>
        <?php endif; ?>
        
        <li style="margin-right: 2px;">
            <img src="<?php echo base_url(); ?>layout/assets/images/bandeira.png" height="30px;">
        </li>
        <li style="padding: 5px; margin-right: 10px;">
            <span style="color: red; font-size: 14px;"><?php echo $this->session->userdata('nome_usuario') . '</span><br/><span style="color: #428bca; font-size: 12px; font-weight: bold;">' . $this->session->userdata('entidade'); ?></span></li>
        <?php if ($this->session->userdata('nivel') == 1 || ($this->session->userdata('nivel') == 2 && $this->session->userdata('tipo_gestor') != 10)  || $this->session->userdata('nivel') == 4 || $this->session->userdata('nivel') == 6) { ?>
            <li><a href = "<?php echo base_url(); ?>index.php/controle_usuarios" title="Área do Cliente" class = "menu-icon"><i class = "fa fa-user"></i></a></li>
        <?php } else { ?>
            <li><a href = "<?php echo base_url(); ?>index.php/controle_usuarios/atualiza_usuario?id=<?php echo $this->session->userdata('id_usuario'); ?>" class = "menu-icon"><i class = "fa fa-user"></i></a></li>
        <?php } ?>
        <li><a href="<?php echo base_url(); ?>index.php/in/login/sair" class="menu-icon"><i class="fa fa-power-off"></i></a></li>

    </ul>
</div>