<?php $permissoes = $this->permissoes_usuario->get_by_usuario_id($this->session->userdata('id_usuario')); ?>
<?php
$ci = &get_instance();
$ci->load->model('usuariomodel');
?>
<div id="content">
    <h1 class="bg-white content-heading border-bottom">HOME</h1>
    <div class="innerAll spacing-x2">
        <div class="row">
            <?php if ($permissoes->consultar_programa): ?>
                <div class="col-md-3 col-sm-6">
                    <div class="panel-3d">
                        <div class="front">

                            <div class="widget text-center widget-scroll" data-scroll-height="50%">
                                <div class="widget-body padding-none" style="box-shadow: 10px 10px 5px #888888;">
                                    <div>
                                        <div class="innerAll" style="background-color: #00c8d1;">
                                            <p class="lead text-white strong margin-none" style="font-size: 180%;"><i class="fa fa-search"></i><br />Programas Abertos</p>
                                        </div>
                                        <div class="innerAll">
                                            <label style="font-size: 120%;"><a href="<?php echo base_url(); ?>index.php/in/dados_siconv/busca_programas" style="text-decoration: none; color: #00c8d1"><?php echo $total_rows; ?> Programas Abertos</a></label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($permissoes->visualiza_emendas): ?>
                <div class="col-md-3 col-sm-6">
                    <div class="panel-3d">
                        <div class="front">

                            <div class="widget text-center widget-scroll" data-scroll-height="50%">
                                <div class="widget-body padding-none" style="box-shadow: 10px 10px 5px #888888;">
                                    <div>
                                        <div class="innerAll" style="background-color: #62cd99">
                                            <p class="lead text-white strong margin-none" style="font-size: 180%;"><i class="fa fa-file-text"></i><br />Emendas Disponíveis</p>
                                        </div>
                                        <div class="innerAll">
                                            <label style="font-size: 120%;"><a href="<?php echo base_url(); ?>index.php/in/dados_siconv/busca_emendas_municipio" style="text-decoration: none; color: #62cd99"><?php echo $total_emendas; ?> Emendas Disponíveis</a></label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            <?php endif; ?>
            <?php if ($ci->usuariomodel->ativa_modo_normal_ou_vendedor()): ?>
                <?php if (($this->session->userdata('sistema') == 'M' || $this->session->userdata('sistema') == 'E') || ($this->session->userdata('nivel_gestor') != null && $this->session->userdata('nivel_gestor') == 'C') || $this->session->userdata('nivel') == 1): ?>
                    <?php if ((($this->session->userdata('sistema') == 'M' || $this->session->userdata('sistema') == 'E') || $this->session->userdata('nivel') == 1) && $permissoes->utilizar_padrao): ?>
                        <div class="col-md-3 col-sm-16">
                            <div class="panel-3d">
                                <div class="front">
                                    <div class="widget text-center widget-scroll" data-scroll-height="50%">
                                        <div class="widget-body padding-none" style="box-shadow: 10px 10px 5px #888888;">
                                            <div>
                                                <div class="innerAll bg-inverse">
                                                    <p class="lead strong margin-none text-white" style="font-size: 180%;"><i class="fa fa-clipboard"></i><br />Banco de Propostas</p>
                                                </div>
                                                <div class="innerAll">
                                                    <label class="label label-inverse" style="font-size: 100%;"><a href="<?php echo base_url(); ?>index.php/in/gestor/visualiza_banco_propostas" style="text-decoration: none; color: #fff"><?php echo count($propostas); ?> Propostas Disponíveis</a></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if ($permissoes->visualiza_minhas_propostas): ?>
                        <div class="col-md-3 col-sm-6">
                            <div class="panel-3d">
                                <div class="front">
                                    <div class="widget text-center widget-scroll" data-scroll-height="50%">
                                        <div class="widget-body padding-none" style="box-shadow: 10px 10px 5px #888888;">
                                            <div>
                                                <div class="innerAll bg-success">
                                                    <p class="lead strong margin-none text-white" style="font-size: 180%;"><i class="fa fa-folder"></i><br />Minhas Propostas</p>
                                                </div>
                                                <div class="innerAll">
                                                    <label class="label label-success" style="font-size: 100%;"><a href="<?php echo base_url(); ?>index.php/in/gestor/visualiza_propostas" style="text-decoration: none; color: #fff"><?php echo count($propostas_cadastradas); ?> Elaborados / <?php echo count($propostas_enviadas); ?> Cadastradas</a></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            <?php endif; ?>
            <?php if ($this->session->userdata('sistema') == 'P' && $this->session->userdata('nivel') != 1): ?>
                <div class="col-md-3 col-sm-6">
                    <div class="panel-3d">
                        <div class="front">
                            <div class="widget text-center widget-scroll" data-scroll-height="50%">
                                <div class="widget-body padding-none" style="box-shadow: 10px 10px 5px #888888;">
                                    <div>
                                        <div class="innerAll bg-inverse">
                                            <p class="lead strong margin-none text-white" style="font-size: 180%;"><i class="fa fa-folder-open"></i><br />Minhas Emendas</p>
                                        </div>
                                        <div class="innerAll">
                                            <label class="label label-inverse" style="font-size: 100%;"><a href="<?php echo base_url(); ?>index.php/in/dados_siconv/busca_emendas" style="text-decoration: none; color: #fff">Visualizar</a></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="panel-3d">
                        <div class="front">
                            <div class="widget text-center widget-scroll" data-scroll-height="50%">
                                <div class="widget-body padding-none" style="box-shadow: 10px 10px 5px #888888;">
                                    <div>
                                        <div class="bg-primary innerAll">
                                            <p class="lead strong margin-none text-white" style="font-size: 180%;"><i class="fa fa-folder"></i><br />Emendas por Proponente</p>
                                        </div>
                                        <div class="innerAll">
                                            <label style="font-size: 100%;"><a href="<?php echo base_url(); ?>index.php/in/dados_siconv/busca_emendas_geral" style="text-decoration: none; color: #3a7ec0">Visualizar</a></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <?php if ($permissoes->visualiza_prop_parecer): ?>
                    <div class="col-md-3 col-sm-6">
                        <div class="panel-3d">
                            <div class="front">
                                <div class="widget text-center widget-scroll" data-scroll-height="50%">
                                    <div class="widget-body padding-none" style="box-shadow: 10px 10px 5px #888888;">
                                        <div>
                                            <div class="innerAll" style="background-color: #626b77;">
                                                <p class="lead strong margin-none text-white" style="font-size: 180%;"><i class="fa fa-retweet"></i><br />Propostas e Pareceres</p>
                                            </div>
                                            <div class="innerAll">
                                                <label style="font-size: 120%;"><a href="<?php echo base_url(); ?>index.php/in/dados_siconv/visualiza_propostas_pareceres" style="text-decoration: none; color: #626b77"><?php echo $num_propostas; ?> Propostas no Siconv em 2015</a></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>

        <br/>

        <?php
        if ($vencimento_dia != null || $vencimento_cinco_dias != null || $vencimento_dez_dias != null) {
            echo '<div style="background-color: #fff; width: 38%;" class="innerAll box_vencimento">';
            echo '<h1 class="content-heading"><img src="' . base_url('layout/assets/images/warning.jpg') . '" height="20"> Atenção <img src="' . base_url('layout/assets/images/warning.jpg') . '" height="20"></h1>';

            if ($vencimento_dia != null)
                echo "<h4>Programas com vencimento de vigência hoje. <a href='#' id='do_dia'>Clique aqui</a></h4>";

            if ($vencimento_cinco_dias != null)
                echo "<h4>Programas com vencimento de vigência faltando 5 dias. <a href='#' id='cinco_dias'>Clique aqui</a></h4>";

            if ($vencimento_dez_dias != null)
                echo "<h4>Programas com vencimento de vigência faltando 10 dias <a href='#' id='dez_dias'>Clique aqui</a></h4>";
            echo '</div>';
        }
        ?>

        <?php if ($this->session->userdata('tipo_gestor') == 10 && $ci->usuariomodel->test_desconto($this->session->userdata('id_usuario')) && $ci->usuariomodel->get_tempo_restante($this->session->userdata('id_usuario'))->days <= 3): ?>
            <br/>
            <div class="innerAll">
                <a href="http://infoconvenios.physisbrasil.com.br/infoconvenios/index.php/compra?token=UGh5NWk1X0MwbVByYXMy&PA=1&SP=1&TP=A&desconto=<?php echo $this->session->userdata('id_usuario'); ?>" target="_blank"><img src="<?php echo base_url() ?>layout/assets/images/promo_home.gif"</a>
            </div>
        <?php endif; ?>
    </div>
    <div class="clearfix"></div>
</div>

<!-- // Content END -->
<div class="clearfix"></div>

<div style="visibility: hidden;" id="oculta_alerta">
    <div id="info_dia" title="Programas com vencimento hoje">
        <?php foreach ($vencimento_dia as $programa): ?>
            <div class="innerAll border-bottom tickets">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="pull-right ">
                            <div></div><label class="label label-primary codigo"><?php echo $programa->codigo; ?></label>
                        </div>

                        <ul class="media-list">
                            <li class="media">
                                <div class="media-body">
                                    <?php
                                    if (isset($programa->emenda_nome)) {

                                        echo "<br><label class=\"label label-info\">Emenda:</label> " . $programa->emenda_cnpj . "|" . $programa->emenda_nome . "|" . $programa->emenda_valor . "<br>";
                                    }

                                    if (isset($programa->emenda) && $programa->emenda != "")
                                        echo "<label class=\"label label-info\">Parlementar:</label> " . $programa_model->get_parlamentar_by_emenda($programa->emenda) . "|" . $programa->emenda . "<br>";
                                    ?>

                                    <a class="link_programa" style="active: red;" href="<?php echo $programa->link . "&path=/MostraPrincipalConsultarPrograma.do&Usr=guest&Pwd=guest"; ?>" target="_blank" class="media-heading"> <?php echo $programa->nome; ?></a>
                                    <div class="clearfix"></div>
                                    <?php echo $programa->descricao; ?>
                                    <br><br>
                                    <b>Qualificação do Proponente:</b> <?php echo $programa->qualificacao; ?>
                                    <br>
                                    <b>Qualificação da Proposta:</b> <?php echo $programa->atende; ?>
                                    <br>
                                    <b>Estados Atendidos:</b> <?php echo $programa->estados; ?>
                                    <br><br>



                                    <?php if (isset($programa->data_inicio) && strtotime($programa->data_inicio) > 0): ?>
                                        <span style="color: red;">Proposta Voluntária</span><br>
                                        Inicio da Vigência <label class="label label-info"><?php echo implode("/", array_reverse(explode("-", $programa->data_inicio))); ?></label>
                                        | Final da Vigência <label class="label label-info"><?php echo implode("/", array_reverse(explode("-", $programa->data_fim))); ?></label>
                                        <br>
                                    <?php endif; ?>

                                    <?php if (isset($programa->data_inicio_benef) && strtotime($programa->data_inicio_benef) > 0): ?>
                                        <span style="color: red;">Proposta de Proponente Específico do Concedente</span><br>
                                        Inicio da Vigência <label class="label label-info"><?php echo implode("/", array_reverse(explode("-", $programa->data_inicio_benef))); ?></label>
                                        | Final da Vigência <label class="label label-info"><?php echo implode("/", array_reverse(explode("-", $programa->data_fim_benef))); ?></label>
                                        <br>
                                    <?php endif; ?>

                                    <?php if (isset($programa->data_inicio_parlam) && strtotime($programa->data_inicio_parlam) > 0): ?>
                                        <span style="color: red;">Proposta de Proponente de Emenda Parlamentar</span><br>
                                        Inicio da Vigência <label class="label label-info"><?php echo implode("/", array_reverse(explode("-", $programa->data_inicio_parlam))); ?></label>
                                        | Final da Vigência <label class="label label-info"><?php echo implode("/", array_reverse(explode("-", $programa->data_fim_parlam))); ?></label>
                                    <?php endif; ?>



                                </div>

                            </li>

                        </ul>


                    </div>

                </div>

            </div>
        <?php endforeach; ?>
    </div>


    <div id="info_cinco_dias" title="Programas com 5 dias restantes">
        <?php foreach ($vencimento_cinco_dias as $programa): ?>
            <div class="innerAll border-bottom tickets">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="pull-right ">
                            <div></div><label class="label label-primary codigo"><?php echo $programa->codigo; ?></label>
                        </div>

                        <ul class="media-list">
                            <li class="media">
                                <div class="media-body">
                                    <?php
                                    if (isset($programa->emenda_nome)) {

                                        echo "<br><label class=\"label label-info\">Emenda:</label> " . $programa->emenda_cnpj . "|" . $programa->emenda_nome . "|" . $programa->emenda_valor . "<br>";
                                    }

                                    if (isset($programa->emenda) && $programa->emenda != "")
                                        echo "<label class=\"label label-info\">Parlementar:</label> " . $programa_model->get_parlamentar_by_emenda($programa->emenda) . "|" . $programa->emenda . "<br>";
                                    ?>

                                    <a class="link_programa" style="active: red;" href="<?php echo $programa->link . "&path=/MostraPrincipalConsultarPrograma.do&Usr=guest&Pwd=guest"; ?>" target="_blank" class="media-heading"> <?php echo $programa->nome; ?></a>
                                    <div class="clearfix"></div>
                                    <?php echo $programa->descricao; ?>
                                    <br><br>
                                    <b>Qualificação do Proponente:</b> <?php echo $programa->qualificacao; ?>
                                    <br>
                                    <b>Qualificação da Proposta:</b> <?php echo $programa->atende; ?>
                                    <br>
                                    <b>Estados Atendidos:</b> <?php echo $programa->estados; ?>
                                    <br><br>



                                    <?php if (isset($programa->data_inicio) && strtotime($programa->data_inicio) > 0): ?>
                                        <span style="color: red;">Proposta Voluntária</span><br>
                                        Inicio da Vigência <label class="label label-info"><?php echo implode("/", array_reverse(explode("-", $programa->data_inicio))); ?></label>
                                        | Final da Vigência <label class="label label-info"><?php echo implode("/", array_reverse(explode("-", $programa->data_fim))); ?></label>
                                        <br>
                                    <?php endif; ?>

                                    <?php if (isset($programa->data_inicio_benef) && strtotime($programa->data_inicio_benef) > 0): ?>
                                        <span style="color: red;">Proposta de Proponente Específico do Concedente</span><br>
                                        Inicio da Vigência <label class="label label-info"><?php echo implode("/", array_reverse(explode("-", $programa->data_inicio_benef))); ?></label>
                                        | Final da Vigência <label class="label label-info"><?php echo implode("/", array_reverse(explode("-", $programa->data_fim_benef))); ?></label>
                                        <br>
                                    <?php endif; ?>

                                    <?php if (isset($programa->data_inicio_parlam) && strtotime($programa->data_inicio_parlam) > 0): ?>
                                        <span style="color: red;">Proposta de Proponente de Emenda Parlamentar</span><br>
                                        Inicio da Vigência <label class="label label-info"><?php echo implode("/", array_reverse(explode("-", $programa->data_inicio_parlam))); ?></label>
                                        | Final da Vigência <label class="label label-info"><?php echo implode("/", array_reverse(explode("-", $programa->data_fim_parlam))); ?></label>
                                    <?php endif; ?>



                                </div>

                            </li>

                        </ul>


                    </div>

                </div>

            </div>
        <?php endforeach; ?>
    </div>

    <div id="info_dez_dias" title="Programas com 10 dias restantes">
        <?php foreach ($vencimento_dez_dias as $programa): ?>
            <div class="innerAll border-bottom tickets">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="pull-right ">
                            <label class="label label-primary codigo"><?php echo $programa->codigo; ?></label>
                        </div>
                        <ul class="media-list">
                            <li class="media">
                                <div class="media-body">
                                    <?php
                                    if (isset($programa->emenda_nome)) {

                                        echo "<br><label class=\"label label-info\">Emenda:</label> " . $programa->emenda_cnpj . "|" . $programa->emenda_nome . "|" . $programa->emenda_valor . "<br>";
                                    }

                                    if (isset($programa->emenda) && $programa->emenda != "")
                                        echo "<label class=\"label label-info\">Parlementar:</label> " . $programa_model->get_parlamentar_by_emenda($programa->emenda) . "|" . $programa->emenda . "<br>";
                                    ?>

                                    <a class="link_programa" href="<?php echo $programa->link . "&path=/MostraPrincipalConsultarPrograma.do&Usr=guest&Pwd=guest"; ?>" target="_blank" class="media-heading"> <?php echo $programa->nome; ?></a>
                                    <div class="clearfix"></div>
                                    <?php echo $programa->descricao; ?>
                                    <br><br>
                                    <b>Qualificação do Proponente:</b> <?php echo $programa->qualificacao; ?>
                                    <br>
                                    <b>Qualificação da Proposta:</b> <?php echo $programa->atende; ?>
                                    <br>
                                    <b>Estados Atendidos:</b> <?php echo $programa->estados; ?>
                                    <br><br>



                                    <?php if (isset($programa->data_inicio) && strtotime($programa->data_inicio) > 0): ?>
                                        <span style="color: red;">Proposta Voluntária</span><br>
                                        Inicio da Vigência <label class="label label-info"><?php echo implode("/", array_reverse(explode("-", $programa->data_inicio))); ?></label>
                                        | Final da Vigência <label class="label label-info"><?php echo implode("/", array_reverse(explode("-", $programa->data_fim))); ?></label>
                                        <br>
                                    <?php endif; ?>

                                    <?php if (isset($programa->data_inicio_benef) && strtotime($programa->data_inicio_benef) > 0): ?>
                                        <span style="color: red;">Proposta de Proponente Específico do Concedente</span><br>
                                        Inicio da Vigência <label class="label label-info"><?php echo implode("/", array_reverse(explode("-", $programa->data_inicio_benef))); ?></label>
                                        | Final da Vigência <label class="label label-info"><?php echo implode("/", array_reverse(explode("-", $programa->data_fim_benef))); ?></label>
                                        <br>
                                    <?php endif; ?>

                                    <?php if (isset($programa->data_inicio_parlam) && strtotime($programa->data_inicio_parlam) > 0): ?>
                                        <span style="color: red;">Proposta de Proponente de Emenda Parlamentar</span><br>
                                        Inicio da Vigência <label class="label label-info"><?php echo implode("/", array_reverse(explode("-", $programa->data_inicio_parlam))); ?></label>
                                        | Final da Vigência <label class="label label-info"><?php echo implode("/", array_reverse(explode("-", $programa->data_fim_parlam))); ?></label>
                                    <?php endif; ?>



                                </div>

                            </li>

                        </ul>


                    </div>

                </div>

            </div>
        <?php endforeach; ?>
    </div>

    <!-- <div id="info_quantitativo_municipio" title="Informações da Entidade"> -->
    <!-- <div align="center"><h1>Processando...</h1></div> -->
    <!-- </div> -->

</div>

<style type="text/css">
    .ui-front { z-index: 1000 !important; }
    a.link_programa:link {
        color: red;
    }

    .box_vencimento{
        box-shadow: 10px 10px 5px #888888;
    }
</style>

<script type="text/javascript">
    $(document).ready(function () {
        var dialog_cinco;
        var dialog_dez;
        var dialog_dia;
// 	var dialog_quantitativo;

        dialog_dia = $("#info_dia").dialog({
            height: parseInt(screen.height / 2.5),
            width: parseInt((screen.width / 4) * 3),
            modal: true,
            buttons: {
                "Fechar": function () {
                    $(this).dialog("close");
                }
            }
        }).position({
            my: "center",
            at: "center",
            of: window
        });

        dialog_dia.dialog("close");

        dialog_cinco = $("#info_cinco_dias").dialog({
            height: parseInt(screen.height / 2.5),
            width: parseInt((screen.width / 4) * 3),
            modal: true,
            buttons: {
                "Fechar": function () {
                    $(this).dialog("close");
                }
            }
        }).position({
            my: "center",
            at: "center",
            of: window
        });

        dialog_cinco.dialog("close");

        dialog_dez = $("#info_dez_dias").dialog({
            height: parseInt(screen.height / 2.5),
            width: parseInt((screen.width / 4) * 3),
            modal: true,
            buttons: {
                "Fechar": function () {
                    $(this).dialog("close");
                }
            }
        }).position({
            my: "center",
            at: "center",
            of: window
        });

        dialog_dez.dialog("close");

        $("#do_dia").click(function () {
            dialog_dia.dialog("open");

            return false;
        });

        $("#cinco_dias").click(function () {
            dialog_cinco.dialog("open");

            return false;
        });

        $("#dez_dias").click(function () {
            dialog_dez.dialog("open");

            return false;
        });

// 	dialog_quantitativo = $( "#info_quantitativo_municipio" ).dialog({
// 		height: parseInt(screen.height/2.5),
// 		width: parseInt((screen.width/2)),
// 		modal: true,
// 		buttons: {
// 			"Fechar": function() {
// 				$( this ).dialog( "close" );
// 			}
// 		}
// 	}).position({
// 	    my: "center",
// 	    at: "center",
// 	    of: window
// 	});

// 	dialog_quantitativo.dialog("close");

// 	function busca_dados(){
// 		dialog_quantitativo.dialog("open");
// 		$.ajax({
        //url:'<?php //echo base_url('index.php/relatorio/gera_rel_quantitativo_ajax');  ?>',
// 			dataType:'html',
// 			success:function(data){
// 				$("#info_quantitativo_municipio").html(data);
// 				dialog_quantitativo.dialog("open");
// 			}
// 		});
// 	}

<?php //if($this->session->userdata('nivel') == 4 && $this->session->userdata('rel_visualizado') != 'S'):  ?>
        //busca_dados();
<?php //$this->session->set_userdata('rel_visualizado', 'S');  ?>
<?php //endif;  ?>
    });
</script>