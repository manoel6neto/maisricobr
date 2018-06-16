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
$activeEscPropo = "";
$activeVisProp = "";
$activeBancoProp = "";
$activeRelProg = "";
$activeProgEmen = "";
$activeProgs = "";
$activeBuscaProg = "";
$activePropParec = "";
$activePropObj = "";
$activeAreaVendedor = "";
$activeAjuda = "";
$activeEmailContato = "";
$activeModelosDocumentos = "";
$activeLinks = "";
$activeRelatorio = "";
$activeTutoriais = "";
$activeRelacaoEntidades = "";
$activeDeclaracao = "";
$activeSugestoes = "";
$activeBuscaEmendas = "";
$activeBuscaEmenda = "";
$activeBuscaEmendasGeral = "";
$activeCertificado = "";
$activeImportSiconv = "";

switch ($this->session->userdata('pagAtual')) {
    case "escolher_proponente" :
        $activeEscPropo = "active";
        $this->session->unset_userdata('filtros');
        break;
    case "visualiza_propostas" :
        $activeVisProp = "active";
        $this->session->unset_userdata('filtros');
        break;
    case "visualiza_banco_propostas" :
        $activeBancoProp = "active";
        $this->session->unset_userdata('filtros');
        break;
    case "busca_programas" :
        $activeBuscaProg = "active";
        $this->session->unset_userdata('filtros');
        break;
    case "visualiza_propostas_pareceres":
        $activePropParec = "active";
        break;
    case "visualiza_propostas_obj":
        $activePropObj = "active";
        break;
    case "busca_emendas":
        $activeBuscaEmenda = "active";
        break;
    case "busca_emendas_geral" :
        $activeBuscaEmendasGeral = "active";
        break;
    case "suporte":
        $activeEmailContato = "active";
        $activeAjuda = "open";
        $this->session->unset_userdata('filtros');
        break;
    case "modelos_documentos":
        $activeModelosDocumentos = "active";
        $activeAjuda = "open";
        $this->session->unset_userdata('filtros');
        break;
    case "links_uteis":
        $activeLinks = "active";
        $activeAjuda = "open";
        $this->session->unset_userdata('filtros');
        break;
    case "area_vendedor":
        $activeAreaVendedor = "active";
        $this->session->unset_userdata('filtros');
        break;
    case "relatorio":
        $activeRelatorio = "active";
        break;
    case "tutoriais":
        $activeTutoriais = "active";
        $activeAjuda = "open";
        $this->session->unset_userdata('filtros');
        break;
    case "relacao_entidades":
        $activeRelacaoEntidades = "active";
        break;
    case "declaracao":
        $activeAjuda = "open";
        $activeDeclaracao = "active";
        break;
    case "sugestoes":
        $activeSugestoes = "active";
        $activeAjuda = "open";
        break;
    case "busca_emendas":
        $activeBuscaEmendas = "active";
        break;
    case "certificado":
        $activeCertificado = "active";
        break;
    case "importsiconv":
        $activeImportSiconv = "active";
        break;
}

$permissoes = $this->permissoes_usuario->get_by_usuario_id($this->session->userdata('id_usuario'));

$this->load->model('proposta_model');
$this->load->model('contato_municipio_model');

$ci = &get_instance();
$ci->load->model('usuariomodel');
?>

<div id="menu" class="hidden-print hidden-xs">
    <div class="sidebar bg-white" style="border-right: solid 2px lightgray;">
        <div class="sidebarMenuWrapper" style="top: 0px;">
            <?php if (($this->session->userdata('sistema') == 'M' || $this->session->userdata('sistema') == 'E') || ($this->session->userdata('nivel_gestor') != null && $this->session->userdata('nivel_gestor') == 'C') || $this->session->userdata('nivel') == 1): ?>
                <ul class="list-unstyled">
                    <?php if ($permissoes->utilizar_padrao): ?>
                        <li class="<?php echo $activeRelatorio; ?>"><a href="<?= base_url(); ?>index.php/relatorio"><span><i class="fa fa-bar-chart-o"></i> Relatórios</span></a></li>
                    <?php endif; ?>
                    <?php if ($permissoes->consultar_programa): ?>
                        <li class="<?php echo $activeBuscaProg; ?>"><a href="<?= base_url(); ?>index.php/in/dados_siconv/busca_programas"><span><i id="item-busca-menu" class="fa fa-search"></i> Programas Abertos</span></a></li>
                    <?php endif; ?>
                    <?php if ($this->session->userdata('nivel') == 1): ?>
                        <li class="<?php echo $activeBuscaEmendasGeral; ?>"><a href="<?= base_url(); ?>index.php/in/dados_siconv/busca_emendas_geral"><span><i class="fa fa-folder"></i> Oportunidades de Emendas</span></a></li>
                        <li class="<?php echo $activePropParec; ?>"><a href="<?= base_url(); ?>index.php/in/dados_siconv/visualiza_propostas_pareceres"><span><i class="fa fa-retweet"></i> Propostas e Pareceres</span></a></li>
                        <li class="<?php echo $activeImportSiconv; ?>"><a href="<?= base_url(); ?>index.php/in/get_propostas/import_from_siconv_to_esicar"><span><i class="fa fa-download"></i> Importar do Siconv</span></a></li>
                    <?php else: ?>
                        <?php if ($this->session->userdata('sistema') == 'P'): ?>
                            <li class="<?php echo $activeBuscaEmenda; ?>"><a href="<?= base_url(); ?>index.php/in/dados_siconv/busca_emendas"><span><i class="fa fa-folder-open"></i> Oportunidades de Emendas</span></a></li>
                            <li class="<?php echo $activeBuscaEmendasGeral; ?>"><a href="<?= base_url(); ?>index.php/in/dados_siconv/busca_emendas_geral"><span><i class="fa fa-folder"></i> Emendas por Proponente</span></a></li>
                        <?php else: ?>
                            <?php if ($permissoes->visualiza_emendas): ?>
                                <li class="<?php echo $activeBuscaEmendasGeral; ?>"><a href="<?= base_url(); ?>index.php/in/dados_siconv/busca_emendas_municipio"><span><i class="fa fa-file-text"></i> Oportunidades de Emendas</span></a></li>
                            <?php endif; ?>
                            <?php if ($permissoes->visualiza_prop_parecer): ?>
                                <li class="<?php echo $activePropParec; ?>"><a href="<?= base_url(); ?>index.php/in/dados_siconv/visualiza_propostas_pareceres"><span><i class="fa fa-retweet"></i> Propostas e Pareceres</span></a></li>
                                <li class="<?php echo $activePropObj; ?>"><a href="<?= base_url(); ?>index.php/in/dados_siconv/visualiza_propostas_por_objeto"><span><i class="fa fa-retweet"></i> Propostas por Objeto</span></a></li>
                            <?php endif; ?>
                        <?php endif; ?>
                    <?php endif; ?>    

                    <?php if ($ci->usuariomodel->ativa_modo_normal_ou_vendedor()): ?>
                        <?php if ($permissoes->utilizar_padrao): ?>
                            <li class="<?php echo $activeBancoProp; ?>"><a href="<?= base_url(); ?>index.php/in/gestor/visualiza_banco_propostas"><span><i class="fa fa-clipboard"></i> Banco de Propostas</span></a></li>
                        <?php endif; ?>
                        <?php if ($permissoes->criar_projeto): ?>
                            <li class="<?php echo $activeEscPropo; ?>"><a href="<?= base_url(); ?>index.php/in/gestor/escolher_proponente"><span><i class="fa fa-plus"></i> Nova Proposta</span></a></li>
                        <?php endif; ?>

                        <?php if ($permissoes->visualiza_minhas_propostas): ?>
                            <li class="<?php echo $activeVisProp; ?>"><a href="<?= base_url(); ?>index.php/in/gestor/visualiza_propostas"> <span><i class="fa <?php
                                        if ($activeVisProp == 'active') {
                                            echo " fa-folder-open";
                                        } else {
                                            echo " fa-folder";
                                        }
                                        ?>"></i> Minhas Propostas
                                                                                                                                                    <?php if (count($this->proposta_model->checa_propostas_trinta_dias()) > 0): ?>
                                            &nbsp;&nbsp;&nbsp;
                                            <i title="Existem propostas elaborados, mas pendentes de envio para o SICONV" class="fa fa-warning" style="color: #ffff00; background-color: black; padding: 4px;"></i>
                                        <?php endif; ?>
                                    </span></a></li>
                        <?php endif; ?>

                    <?php endif; ?>	

                    <?php if ($ci->usuariomodel->ativa_modo_normal_ou_vendedor()): ?>
                        <li class="dropdown keep-open <?php echo $activeAjuda; ?>" id="myDropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span><i class="fa fa-question"></i> Ajuda <b class="caret"></b></span></a>
                            <ul class="dropdown-menu" style="margin-left: 20px;">
                                <?php if ($this->session->userdata('nivel') == 2 && $this->session->userdata('tipo_gestor') == 10): ?>
                                    <!--<li class="<?php echo $activeEmailContato; ?>"><a href="<?= base_url(); ?>index.php/in/usuario/suporte"><span><i class="fa fa-comment"></i> Suporte</span></a></li>-->
                                    <li class="<?php echo $activeTutoriais; ?>"><a href="<?= base_url(); ?>index.php/in/usuario/tutoriais"><span><i class="fa fa-book"></i> Tutoriais</span></a></li>
                                    <li class="<?php echo $activeLinks; ?>"><a href="<?= base_url(); ?>index.php/in/usuario/links_uteis"><span><i class="fa fa-external-link"></i> Links Úteis</span></a></li>
                                <?php else: ?>
                                    <!--<li class="<?php echo $activeEmailContato; ?>"><a href="<?= base_url(); ?>index.php/in/usuario/suporte"><span><i class="fa fa-comment"></i> Suporte</span></a></li>-->
                                    <li class="<?php echo $activeModelosDocumentos; ?>"><a href="<?= base_url(); ?>index.php/in/usuario/modelos_documentos"><span><i class="fa fa-download"></i> Documentos</span></a></li>
                                    <li class="<?php echo $activeDeclaracao; ?>"><a href="<?= base_url(); ?>index.php/declaracao"><span><i class="fa fa-file-text-o"></i> Criar Declarações</span></a></li>
                                    <li class="<?php echo $activeLinks; ?>"><a href="<?= base_url(); ?>index.php/in/usuario/links_uteis"><span><i class="fa fa-external-link"></i> Links Úteis</span></a></li>
                                    <li class="<?php echo $activeTutoriais; ?>"><a href="<?= base_url(); ?>index.php/in/usuario/tutoriais"><span><i class="fa fa-book"></i> Tutoriais</span></a></li>

                                    <li class="<?php echo $activeSugestoes; ?>"><a href="<?= base_url(); ?>index.php/in/usuario/lista_sugestoes"><span><i class="fa fa-comments"></i> Sugestões</span></a></li>
                                <?php endif; ?>
                            </ul>
                        </li>
                    <?php endif; ?>	
                        
                    <?php if ($this->session->userdata('nivel') == 1 || $this->session->userdata('nivel') == 4): ?>
                        <hr style="border: 1px solid lightgray;">
                        <?php if ($this->session->userdata('nivel') == 4): ?>
                            <li><a id="ocultaMenu" style="cursor: pointer;" title="Menu do Vendedor"><i id="icone-menu-vendedor" class="fa fa-arrow-circle-<?php
                                    if ($this->session->userdata("escondeMenu") == "S") {
                                        echo "down";
                                    } else {
                                        echo "up";
                                    }
                                    ?>"></i></a></li>
                            <?php endif; ?>
                        <?php endif; ?>

                    <?php if ($this->session->userdata('nivel') == 4 && $this->session->userdata('id_usuario') != 362): ?>
                            <li style="margin-top: -10px;" id="ocultaAreaVendedor" class="<?php echo $activeAreaVendedor; ?>"><a href="<?= base_url(); ?>index.php/controle_usuarios/area_vendedor"><span><i class="fa fa-user"></i> Área Representante
                                    <?php if (!$this->contato_municipio_model->check_contato_status()):?>
                                    <?php // if ($this->contato_municipio_model->verifica_alerta_retorno() || $this->contato_municipio_model->verifica_alerta_marca_retorno()): ?>
                                        &nbsp;&nbsp;&nbsp;
                                        <i title="Existem informações pendentes no seu cadastro de visitas" class="fa fa-warning" style="color: #ffff00; background-color: black; padding: 4px;"></i>
                                    <?php endif; ?>
                                </span></a></li>

                    <?php endif; ?>

                    <?php if ($this->session->userdata('nivel') == 1 || $this->session->userdata('nivel') == 4): ?>
                        <li id="ocultaRelacaoEntidades" class="<?php echo $activeRelacaoEntidades; ?>"><a href="<?= base_url(); ?>index.php/proponente_siconv/relacao_entidades"><span><i class="fa fa-file-text"></i> Buscar Entidades</span></a></li>
                    <?php endif; ?>
                </ul>
            <?php elseif ($this->session->userdata('sistema') == 'P'): ?>
                <ul class="list-unstyled">
                    <?php if ($permissoes->consultar_programa && ($this->session->userdata('nivel_gestor') != null && $this->session->userdata('nivel_gestor') == 'C')): ?>
                        <li class="<?php echo $activeBuscaProg; ?>"><a href="<?= base_url(); ?>index.php/in/dados_siconv/busca_programas"><span><i class="fa fa-search"></i> Programas Abertos</span></a></li>
                    <?php endif; ?>
                    <li class="<?php echo $activeBuscaEmenda; ?>"><a href="<?= base_url(); ?>index.php/in/dados_siconv/busca_emendas"><span><i class="fa fa-folder-open"></i> Minhas Emendas</span></a></li>
                    <li class="<?php echo $activeBuscaEmendasGeral; ?>"><a href="<?= base_url(); ?>index.php/in/dados_siconv/busca_emendas_geral"><span><i class="fa fa-folder"></i> Emendas por Proponente</span></a></li>

                    <li class="dropdown keep-open <?php echo $activeAjuda; ?>" id="myDropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span><i class="fa fa-question"></i> Ajuda <b class="caret"></b></span></a>
                        <ul class="dropdown-menu" style="margin-left: 20px;">
                            <!--<li class="<?php echo $activeEmailContato; ?>"><a href="<?= base_url(); ?>index.php/in/usuario/suporte"><span><i class="fa fa-comment"></i> Suporte</span></a></li>-->
                            <li class="<?php echo $activeLinks; ?>"><a href="<?= base_url(); ?>index.php/in/usuario/links_uteis"><span><i class="fa fa-external-link"></i> Links Úteis</span></a></li>
                            <?php if ($this->session->userdata('nivel') == 1): ?>
                                <li class="<?php echo $activeSugestoes; ?>"><a href="<?= base_url(); ?>index.php/in/usuario/lista_sugestoes"><span><i class="fa fa-comments"></i> Sugestões</span></a></li>
                            <?php else: ?>
                                <li class="<?php echo $activeSugestoes; ?>"><a href="<?= base_url(); ?>index.php/in/usuario/cria_sugestao"><span><i class="fa fa-comments"></i> Sugestões</span></a></li>
                            <?php endif; ?>
                        </ul>
                    </li>
                </ul>
            <?php endif; ?>

            <?php if ($this->session->userdata('nivel') == 1): ?>
                <ul class="list-unstyled">
                    <li class="<?php echo $activeCertificado; ?>"><a href="<?php echo base_url('index.php/certificado_usuario'); ?>"><span><i class="fa fa-certificate"></i> Gerar Certificado</span></a></li>
                </ul>
            <?php endif; ?>
            
            <ul class="list-unstyled">
                <li><a href="<?php echo base_url(); ?>index.php/in/login/sair" class="menu-icon"><i class="fa fa-power-off"></i> Sair</a></li>
            </ul>
              </div>
</div>
</div>


<script type="text/javascript">
    $(document).ready(function () {
        $('.dropdown.keep-open').on({
            "shown.bs.dropdown": function () {
                this.closeable = false;
            },
            "click": function () {
                this.closeable = true;
            },
            "hide.bs.dropdown": function () {
                return this.closeable;
            }
        });

<?php if ($activeAjuda === "open"): ?>
            $('.dropdown.keep-open').load('hide.bs.dropdown', function () {
                this.closeable = false;
            });
<?php endif; ?>

<?php if ($this->session->userdata('nivel') == 4): ?>
    <?php if ($this->session->userdata("escondeMenu") == "S"): ?>
                $("#ocultaAreaVendedor").hide();
                $("#ocultaRelacaoEntidades").hide();
    <?php endif; ?>
<?php endif; ?>

        $("#ocultaMenu").click(function () {
            alteraMenu();
        });

        function alteraMenu() {
            $.ajax({
                url: '<?php echo base_url('index.php/in/gestor/ocultaMenu'); ?>',
                type: 'post',
                dataType: 'html',
                data: {
                    ocultar: $("#ocultaAreaVendedor").is(":visible")
                },
                success: function (data) {
                }
            });

            if ($("#ocultaAreaVendedor").is(":visible")) {
                $("#ocultaAreaVendedor").slideUp();
                $("#ocultaRelacaoEntidades").slideUp();
                $("#icone-menu-vendedor").attr("class", "fa fa-arrow-circle-down");
            } else if (!$("#ocultaAreaVendedor").is(":visible")) {
                $("#ocultaAreaVendedor").slideDown();
                $("#ocultaRelacaoEntidades").slideDown();
                $("#icone-menu-vendedor").attr("class", "fa fa-arrow-circle-up");
            }
        }
    });
</script>