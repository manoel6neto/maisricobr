<style>
    .list-unstyled li.last-child:after {
        position: absolute;
        right: -20px;
        top: 0;
        height: 0;
        width: 0;
        border-bottom: 20px inset transparent;
        border-left: 0px solid #fafafa;
        border-top: 20px inset transparent;
        content: "";
        z-index: 2
    }

    .list-unstyled li a {
        display: block;
        height: 40px;
        line-height: 40px;
        padding: 0 10px 0 30px !important;
        font-weight: 600;
    }

    .list-unstyled li.active a {
        color: #fff;
        background-color: #7c7c7c;
    }
</style>

<?php
$activeNovoUser = "";
$activeVincularCNPJ = "";
$activeLogs = "";
$acitiveUsuarios = "";
$acitiveUsuariosAvulso = "";

switch ($this->session->userdata('pagAtual')) {
    case "novo_usuario" :
        $activeNovoUser = "active";
        break;
    case "vincular_cnpj" :
        $activeVincularCNPJ = "active";
        break;
    case "logs" :
        $activeLogs = "active";
        break;
    case "lista_usuario_avulso":
        $acitiveUsuariosAvulso = "active";
        break;
    default:
        $acitiveUsuarios = "active";
        break;
}

$permissoes = $this->permissoes_usuario->get_by_usuario_id($this->session->userdata('id_usuario'));

$ci = &get_instance();
$ci->load->model('usuariomodel');
$ci->load->model('certificado_usuario_model');
?>

<div id="menu" class="hidden-print hidden-xs">
    <div class="sidebar bg-white" style="border-right: solid 2px lightgray;">
        <div class="sidebarMenuWrapper" style="top: 0px;">
            <ul class="list-unstyled">
                <li><a href="<?= base_url(); ?>index.php/in/gestor"><span><i class="fa fa-home"></i> Home</span></a></li>

                <?php if ($this->session->userdata('nivel') != 3 && $this->session->userdata('nivel') != 5): ?> 	
                    <li class="<?php echo $acitiveUsuarios; ?>"><a href="<?= base_url(); ?>index.php/controle_usuarios?avulso=1"><span><i class="fa fa-user"></i> Gerenciar Usuários</span></a></li>

                    <?php if ($this->session->userdata('tipo_gestor') != 10): ?>    
                        <?php if ($permissoes->vincular_cnpj_usuario && $this->session->userdata('nivel') != 4): ?>
                            <?php $link_get = ($this->session->userdata('nivel') === "2" || $this->session->userdata('nivel') === "6") ? "?id=" . $this->session->userdata('id_usuario') : ""; ?>
                            <li class="<?php echo $activeVincularCNPJ; ?>"><a href="<?= base_url(); ?>index.php/controle_usuarios/vincular_cnpj<?php echo $link_get; ?>"><span><i class="fa fa-link"></i> CNPJ Vinculado</span></a></li>
                        <?php endif; ?>

                        <?php if ($this->session->userdata('nivel') == 1 || $this->session->userdata('nivel') == 2 || $this->session->userdata('nivel') == 6): ?>
                            <li class="<?php echo $activeNovoUser; ?>"><a href="<?= base_url(); ?>index.php/controle_usuarios/novo_usuario"><span><i class="fa fa-plus"></i> Novo Usuário</span></a></li>
                        <?php endif; ?>

                        <?php if ($this->session->userdata('nivel') == 1 || $this->session->userdata('nivel') == 2): ?>
                            <li class="<?php echo $activeLogs; ?>"><a href="<?= base_url(); ?>index.php/in/system_logs_controller/visualizar_logs"><span><i class="fa fa-cogs"></i> Logs do sistema</span></a></li>
                        <?php endif; ?>

                        <?php if ($this->session->userdata('nivel') == 1): ?>
                            <li class="<?php echo $acitiveUsuariosAvulso; ?>"><a href="<?= base_url(); ?>index.php/controle_usuarios/lista_usuario_avulso"><span><i class="fa fa-user-md"></i> Usuários Avulso</span></a></li>
                        <?php endif; ?>
                    <?php endif; ?>
                <?php endif; ?>

                <?php if ($ci->certificado_usuario_model->check_tem_certificado()): ?>
                    <ul class="list-unstyled">
                        <li><a target="_blank" href="<?php echo base_url('index.php/certificado_usuario/imprime_certificado'); ?>"><span><i class="fa fa-certificate"></i> Imprimir Certificado</span></a></li>
                    </ul>
                <?php endif; ?>

                <?php if ($this->session->userdata('nivel') != 4 && $this->session->userdata('tipo_gestor') != 10): ?>
                    <li><a target="_blank" href="<?= base_url('arquivos_download/licenca_uso.pdf'); ?>"><span><i class="fa fa-file-text"></i> Licença de Uso</span></a></li>
                                <?php endif; ?>
            </ul>

        </div>
    </div>
</div>
