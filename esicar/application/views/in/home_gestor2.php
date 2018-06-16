<?php $permissoes = $this->permissoes_usuario->get_by_usuario_id($this->session->userdata('id_usuario')); ?>
<?php
$ci = &get_instance();
$ci->load->model('usuariomodel');
?>
<div id="content">
    <h1 class="bg-white content-heading border-bottom">PAINEL PRINCIPAL</h1>
    <div class="innerAll spacing-x2">
        <div style="margin-top: -20px; margin-left: -22px !important; margin-right: -22px !important; float: top; width: auto !important;" class="row">
            <?php if ($this->session->userdata('sistema') != 'P' && $permissoes->consultar_programa || ($this->session->userdata('nivel_gestor') != null && $this->session->userdata('nivel_gestor') == 'C')): ?>
            <a href="<?php echo base_url(); ?>index.php/in/dados_siconv/busca_programas">
                    <div class="col-md-3 col-sm-6">
                        <div class="panel-3d">
                            <div class="a magictime swashIn" id="programas">
                                <div class="img">
                                    <img class="imagem" src="<?php echo base_url('tiles/envelope.png'); ?>"/>
                                </div>
                                <div class="divtexto">Programas Abertos</div>
                            </div>
                        </div>
                    </div>
                </a>
                <!--                <div class="col-md-3 col-sm-6" style="width: 25% !important; height: 15% !important; margin: 0px !important; padding: 5px !important;">
                                    <div class="panel-3d">
                                        <div class="front">
                                            <div class="widget text-center widget-scroll" data-scroll-height="50%" style="border-radius: 2%;">
                                                <div class="widget-body padding-none" style="box-shadow: 10px 10px 5px #888888; border-radius: 2%;">
                                                    <div style="border-radius: 2%;">
                                                        <div class="innerAll bg-info-light">
                                                            <p class="lead text-white strong margin-none" style="font-size: 180%;"><i class="fa fa-search"></i><br />Programas Abertos</p>
                                                        </div>
                                                        <div class="innerAll">
                                                            <label class="label label-info-light" style="font-size: 100%;"><a href="<?php echo base_url(); ?>index.php/in/dados_siconv/busca_programas" style="text-decoration: none; color: #fff"><?php echo $total_rows; ?> Programas Abertos</a></label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>-->
            <?php endif; ?>
            <?php if ($ci->usuariomodel->ativa_modo_normal_ou_vendedor()): ?>
                <?php if (($this->session->userdata('sistema') == 'M' || $this->session->userdata('sistema') == 'E') || ($this->session->userdata('nivel_gestor') != null && $this->session->userdata('nivel_gestor') == 'C') || $this->session->userdata('nivel') == 1): ?>
                    <?php if ((($this->session->userdata('sistema') == 'M' || $this->session->userdata('sistema') == 'E') || $this->session->userdata('nivel') == 1) && $permissoes->utilizar_padrao): ?>
                        <a href="<?php echo base_url(); ?>index.php/in/gestor/visualiza_banco_propostas">
                            <div class="col-md-3 col-sm-6">
                                <div class="panel-3d">
                                    <div class="a magictime swashIn" id="propostas">
                                        <div class="img">
                                            <img class="imagem" src="<?php echo base_url('tiles/banco.png'); ?>"/>
                                        </div>
                                        <div class="divtexto">Banco de Propostas</div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    <?php endif; ?>

                    <?php if ($permissoes->visualiza_minhas_propostas && $this->session->userdata('nivel') != 4): ?>
                        <a href="<?php echo base_url(); ?>index.php/in/gestor/visualiza_propostas">
                            <div class="col-md-3 col-sm-6">
                                <div class="panel-3d">
                                    <div class="a magictime swashIn" id="bancodeproposta">
                                        <div class="img">
                                            <img class="imagem" src="<?php echo base_url('tiles/minhaspropostas.png'); ?>"/>
                                        </div>
                                        <div class="divtexto">Minhas Propostas</div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    <?php endif; ?>
                <?php endif; ?>
            <?php endif; ?>
            <?php if ($this->session->userdata('sistema') == 'P' && $this->session->userdata('nivel') != 1): ?>
                <div class="col-md-3 col-sm-6">
                    <div class="panel-3d">
                        <div class="front">
                            <div class="widget text-center widget-scroll" data-scroll-height="50%"style="border-radius: 2%;">
                                <div class="widget-body padding-none" style="box-shadow: 10px 10px 5px #888888; border-radius: 2%;">
                                    <div style="border-radius: 2%;">
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
                            <div class="widget text-center widget-scroll" data-scroll-height="50%" style="border-radius: 2%;">
                                <div class="widget-body padding-none" style="box-shadow: 10px 10px 5px #888888; border-radius: 2%;">
                                    <div style="border-radius: 2%;">
                                        <div class="bg-primary innerAll">
                                            <p class="lead strong margin-none text-white" style="font-size: 180%;"><i class="fa fa-folder"></i><br />Emendas por Proponente</p>
                                        </div>
                                        <div class="innerAll">
                                            <label class="label label-primary" style="font-size: 100%;"><a href="<?php echo base_url(); ?>index.php/in/dados_siconv/busca_emendas_geral" style="text-decoration: none; color: #fff">Visualizar</a></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <a href="<?php echo base_url(); ?>index.php/in/dados_siconv/visualiza_propostas_pareceres">
                    <div class="col-md-3 col-sm-6">
                        <div class="panel-3d">
                            <div class="a magictime swashIn" id="emendas">
                                <div class="img">
                                    <img class="imagem" src="<?php echo base_url('tiles/emendas.png'); ?>"/>
                                </div>
                                <div class="divtexto" id="espaco">
                                    Propostas&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&<br>Pareceres
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
                <!--                <div class="col-md-3 col-sm-6" style="width: 25% !important; height: 15% !important; margin: 0px !important; padding: 5px !important;">
                                    <div class="panel-3d">
                                        <div class="front">
                                            <div class="widget text-center widget-scroll" data-scroll-height="50%" style="border-radius: 2%;">
                                                <div class="widget-body padding-none" style="box-shadow: 10px 10px 5px #888888; border-radius: 2%;">
                                                    <div style="border-radius: 2%;">
                                                        <div class="bg-primary innerAll">
                                                            <p class="lead strong margin-none text-white" style="font-size: 180%;"><i class="fa fa-retweet"></i><br />Propostas e Pareceres</p>
                                                        </div>
                                                        <div class="innerAll">
                                                            <label class="label label-primary" style="font-size: 100%;"><a href="<?php echo base_url(); ?>index.php/in/dados_siconv/visualiza_propostas_pareceres" style="text-decoration: none; color: #fff">Visualizar</a></label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>-->
            <?php endif; ?>
        </div>
        <?php if ($this->session->userdata('sistema') != 'P' && $this->session->userdata('nivel') != 1): ?>
            <canvas id="myChart" width="200" height="70" style="padding: 10px; margin-bottom: 0px !important;"></canvas>
        <?php endif; ?>

        <!--	<div style="background-color: #9dd3af; width: 100%;" class="innerAll box_vencimento">
                        <h3>NOVIDADE</h3>
                        <h4><b>Senhores usuários</b></h4>
                        <h5>
                                <p>Uma funcionalidade foi adicionada ao menu do e-SICAR.</p>
                                <p><b>Emendas Disponíveis</b> - Área destinada as Emendas Parlamentares (recursos destinados a sua entidade por deputados e senadores) e Proponente Específico do Concedente (recursos destinados a sua entidade pelos Ministérios)</p>
                                <br/>
                                <p>Para ver como utilizar mais esta facilidade pensada em você, <a target="_blank" href="http://www.physisbrasil.com/#!ed-1/cnjd">acesse aqui o tutorial</a>.</p>
                        </h5>
                </div>-->

        <!-- Colocar novo alerta-->
        <?php
        if ($vencimento_dia != null || $vencimento_cinco_dias != null || $vencimento_dez_dias != null) {
            echo '<div class="row" style="margin-top: 10px !important;">';
            echo '<div style="background-color: #fff; width: 38%; border-radius: 2%;" class="innerAll box_vencimento">';
            echo '<h1 class="content-heading"><img src="' . base_url('layout/assets/images/warning.jpg') . '" height="20"> Atenção <img src="' . base_url('layout/assets/images/warning.jpg') . '" height="20"></h1>';

            if ($vencimento_dia != null)
                echo "<h4>Programas com vencimento de vigência hoje. <a href='#' id='do_dia'>Clique aqui</a></h4>";

            if ($vencimento_cinco_dias != null)
                echo "<h4>Programas com vencimento de vigência faltando 5 dias. <a href='#' id='cinco_dias'>Clique aqui</a></h4>";

            if ($vencimento_dez_dias != null)
                echo "<h4>Programas com vencimento de vigência faltando 10 dias <a href='#' id='dez_dias'>Clique aqui</a></h4>";
            echo '</div>';
            echo '</div>';
        }
        ?>

    </div>
    <div class="clearfix"></div>
</div>

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
<?php if ($this->session->userdata('nivel') != 1): ?>
            var ctx = document.getElementById("myChart").getContext('2d');
            Chart.defaults.global.defaultFontColor = 'grey';
            Chart.defaults.global.defaultFontStyle = 'bold';
            Chart.defaults.global.animation.duration = 8000;
            Chart.defaults.global.title.display = true;
            Chart.defaults.global.title.text = 'Desempenho Anual (Propostas)';
            Chart.defaults.global.title.fontColor = 'grey';
            Chart.defaults.global.title.fontStyle = 'bold';
            Chart.defaults.global.title.fontSize = 14;
            var myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ["2014", "2015", "2016", "2017", "2018"],
                    datasets: [{
                            label: 'Cadastradas',
                            data: [ <?php echo count($propostas_por_ano['2014']); ?>, <?php echo count($propostas_por_ano['2015']); ?>, <?php echo count($propostas_por_ano['2016']); ?>, <?php echo count($propostas_por_ano['2017']); ?>, <?php echo count($propostas_por_ano['2018']); ?>],
                            backgroundColor: [
                                'rgba(51, 51, 51, 0.3)',
                                'rgba(114, 175 , 70, 0.3)',
                                'rgba(255, 140, 0, 0.3)',
                                'rgba(203, 64, 64, 0.3)',
                                'rgba(66, 139, 202, 0.3)'
                            ],
                            borderColor: [
                                'rgba(51, 51, 51, 1)',
                                'rgba(114, 175, 70, 1)',
                                'rgba(255, 140, 0, 1)',
                                'rgba(203, 64, 64, 1)',
                                'rgba(66, 139, 202, 1)'
                            ],
                            borderWidth: 1
                        },
                        {
                            label: 'Enviadas',
                            data: [<?php echo count($propostas_por_ano['2014envi']); ?>, <?php echo count($propostas_por_ano['2015envi']); ?>, <?php echo count($propostas_por_ano['2016envi']); ?>, <?php echo count($propostas_por_ano['2017envi']); ?>, <?php echo count($propostas_por_ano['2018envi']); ?>],
                            backgroundColor: [
                                'rgba(51, 51, 51, 0.6)',
                                'rgba(114, 175 , 70, 0.6)',
                                'rgba(255, 140, 0, 0.6)',
                                'rgba(203, 64, 64, 0.6)',
                                'rgba(66, 139, 202, 0.6)'
                            ],
                            borderColor: [
                                'rgba(51, 51, 51, 1)',
                                'rgba(114, 175, 70, 1)',
                                'rgba(255, 140, 0, 1)',
                                'rgba(203, 64, 64, 1)',
                                'rgba(66, 139, 202, 1)'
                            ],
                            borderWidth: 1
                        },
                        {
                            label: 'Aprovadas',
                            data: [<?php echo count($propostas_por_ano['2014apro']); ?>, <?php echo count($propostas_por_ano['2015apro']); ?>, <?php echo count($propostas_por_ano['2016apro']); ?>, <?php echo count($propostas_por_ano['2017apro']); ?>, <?php echo count($propostas_por_ano['2018apro']); ?>],
                            backgroundColor: [
                                'rgba(51, 51, 51, 0.9)',
                                'rgba(114, 175 , 70, 0.9)',
                                'rgba(255, 140, 0, 0.9)',
                                'rgba(203, 64, 64, 0.9)',
                                'rgba(66, 139, 202, 0.9)'
                            ],
                            borderColor: [
                                'rgba(51, 51, 51, 1)',
                                'rgba(114, 175, 70, 1)',
                                'rgba(255, 140, 0, 1)',
                                'rgba(203, 64, 64, 1)',
                                'rgba(66, 139, 202, 1)'
                            ],
                            borderWidth: 1
                        }]
                },
                options: {
                    legend: {
                        display: false,
                        labels: {
                            fontColor: 'black',
                            fontStyle: 'bold'
                        }
                    },
                    scales: {
                        yAxes: [{
                                ticks: {
                                    beginAtZero: true,
                                    stacked: true
                                }
                            }],
                        xAxes: [{
                                ticks: {
                                    beginAtZero: true,
                                    stacked: true
                                }
                            }]
                    },
                    layout: {
                        padding: {
                            left: 2,
                            right: 2,
                            top: 2,
                            bottom: 2
                        }
                    },
                    responsive: true
                }
            });
            myChart.generateLegend();
<?php endif; ?>

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
        //url:'<?php //echo base_url('index.php/relatorio/gera_rel_quantitativo_ajax');                                                                                                ?>',
// 			dataType:'html',
// 			success:function(data){
// 				$("#info_quantitativo_municipio").html(data);
// 				dialog_quantitativo.dialog("open");
// 			}
// 		});
// 	}

<?php //if($this->session->userdata('nivel') == 4 && $this->session->userdata('rel_visualizado') != 'S'):                                                                                                ?>
        //busca_dados();
<?php //$this->session->set_userdata('rel_visualizado', 'S');                                                                                                ?>
<?php //endif;                                                                                                ?>
    });
</script>