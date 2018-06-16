<?php $permissoes = $this->permissoes_usuario->get_by_usuario_id($this->session->userdata('id_usuario')); ?>
<?php
$activeElaborados = "";
$activeEnviados = "";
$activeImportadas = "";
$buscaProposta = false;
if ($this->session->userdata('projEnviado') == 'S') {
    $activeEnviados = "active";
    $buscaProposta = true;
} else
    $activeElaborados = "active";
?>

<script type="text/javascript">
    function buscaProposta(idPropostaSiconv) {
        //localhost/esicar/esicar/index.php/in/get_propostas/get_propostas_siconv?id_inicial=507964&id_final=507964
        $.ajax({
            url: '<?php echo base_url('index.php/in/get_propostas/get_propostas_siconv?id_inicial='); ?>' + idPropostaSiconv + '&id_final=' + idPropostaSiconv,
            type: 'post',
            dataType: 'html',
            success: function (data) {
                //location.href=$(location).attr('href');
            }
        });
    }

<?php if ($buscaProposta): ?>
        buscaProposta('<?php echo $this->session->userdata('id_siconv'); ?>');
    <?php sleep(15); ?>
    <?php
    if ($this->session->userdata('id_siconv') != "")
        $this->session->set_userdata('id_siconv', '');
    ?>
<?php endif; ?>
</script>

<div id="content" <?php echo(isset($apresentacao) ? 'style="margin:0px 100px"' : ''); ?>>

    <h1 class="bg-white content-heading border-bottom">MINHAS PROPOSTAS</h1>
    <div class="bg-white">
        <form method="post" class="form-horizontal">
            <div class="input-group input-lg ">
                <input type="text" class="form-control" placeholder="Pesquisar" name="filtro">
                <div class="input-group-btn">
                    <button class="btn btn-info" type="submit">
                        <i class="fa fa-search"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>
    <!-- Tabs --> 
    <div class="relativeWrap">
        <div class="box-generic">

            <div class="tabsbar">
                <ul>
                    <li class="<?php echo $activeElaborados; ?> glyphicons edit"><a href="#tab1-3"
                                                                                    data-toggle="tab"><i></i> Elaboradas (<?php echo count($propostas_cadastradas); ?>)<strong></strong></a></li>
                    <li class="<?php echo $activeEnviados; ?> glyphicons check"><a href="#tab2-3"
                                                                                   data-toggle="tab"><i></i> Cadastradas (<?php echo count($propostas_enviadas); ?>)<strong></strong></a></li>
                        <?php if ($permissoes->tornar_proj_padrao): ?>       
                        <li class="<?php echo $activeImportadas; ?> glyphicons edit"><a href="#tab3-3"
                                                                                        data-toggle="tab"><i></i> Importadas (<?php echo count($propostas_importadas); ?>)<strong></strong></a></li>
                        <?php endif; ?>

<!-- <li class="glyphicons folder_open"><a href="#tab3-3" data-toggle="tab"><i></i> <span>Padrão</span></a></li> -->
                </ul>
            </div>

            <div class="tab-content">

                <!-- cadastrados não enviados-->
                <div class="tab-pane <?php echo $activeElaborados; ?>" id="tab1-3">
                    <?php if (isset($propostas_cadastradas) AND $propostas_cadastradas != null) { ?>
                        <h3 class="bg-white" style="text-align: right;">

                            <span><?php
                                if (isset($pesquisa)) {
                                    echo "Pesquisa por: {$pesquisa}";
                                }
                                ?></span> <span class="badge badge-info"><?php
                                if (isset($num_rows)) {
                                    echo "{$num_rows} resultados";
                                }
                                ?></span>
                        </h3>
                        <div class="widget borders-none">
                            <div class="widget-body ">

                                <div class="widget-body ">
                                    <div class="panel-group accordion" id="accordion">

                                    </div>
                                </div>
                                <?php $i = 0; ?>

                                <?php foreach ($cnpjs as $cidade => $cnpj): ?>
                                    <?php
                                    $qtd = 0;
                                    $temAlerta = false;
                                    $propostas_cadastradasAux = $propostas_cadastradas;
                                    $panel = "collapse";
                                    foreach ($propostas_cadastradasAux as $proposta) {
                                        if (in_array($proposta->proponente, $cnpj)) {
                                            if (array_key_exists($proposta->idProposta, $propostas_mais_trinta_dias))
                                                $temAlerta = true;
                                            $qtd++;

                                            if ($proposta->idProposta == $this->session->userdata('linha_panel'))
                                                $panel = "in";
                                        }
                                    }

                                    if ($qtd > 0):
                                        ?>
                                        <div class="panel-heading">
                                            <h4 class="panel-title">
                                                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse-<?php echo $i; ?>">
                                                    <?php echo $cidade; ?><span style="color: red; font-size: 16px;"><?php echo " ({$qtd})"; ?></span>
                                                </a>
                                                <?php if ($temAlerta): ?>
                                                    &nbsp;<i title="Existem projetos elaborados, mas pendentes de envio para o SICONV" class="fa fa-warning" style="color: #ffff00; background-color: black; padding: 4px;"></i>
                                                <?php endif; ?>
                                            </h4>
                                        </div>
                                    <?php endif; ?>

                                    <div id="collapse-<?php echo $i; ?>" class="panel-collapse <?php echo $panel; ?>">
                                        <div class="panel-body">
                                            <div class="border-bottom tickets">
                                                <div class="row">

                                                    <?php foreach ($propostas_cadastradas as $proposta): ?>
                                                        <?php if (in_array($proposta->proponente, $cnpj)): ?>
                                                            <!-- Inicio dos dados -->
                                                            <a href="../usuario/visualiza_proposta?id=<?php echo $proposta->idProposta; ?>" class="media-heading">
                                                                <div class="innerAll border-bottom tickets">

                                                                    <div class="row">
                                                                        <div class="col-sm-8">
                                                                            <div class="pull-left ">
                                                                                <label class="label label-default"><?php echo $proposta->areanome; ?></label> 
                                                                                <?php
                                                                                if (array_key_exists($proposta->idProposta, $propostas_mais_trinta_dias))
                                                                                    echo '&nbsp;&nbsp;&nbsp;<i title="Este projeto foi elaborado a ' . $propostas_mais_trinta_dias[$proposta->idProposta] . ' dias" class="btn-sm btn-primary fa fa-warning"></i>';
                                                                                ?>
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-md-4 pull-right" >
                                                                            <div class="pull-right">
                                                                                <?php if ($permissoes->exportar_siconv && !$proposta->padrao): ?>
                                                                                    <a alt="Exportar para o SICONV" title="Exportar para o SICONV"
                                                                                       class="btn btn-sm btn-success <?php echo(isset($apresentacao) ? 'disabled' : ''); ?>" href="<?php echo base_url(); ?>index.php/in/usuario/visualiza_proposta?id=<?php echo $proposta->idProposta; ?>">
                                                                                        <i class="fa fa-arrow-up"></i>
                                                                                    </a>
                                                                                <?php endif; ?>

                                                                                <?php if ($permissoes->tornar_proj_padrao): ?>
                                                                                    <?php
                                                                                    $tipoBotao = "default";
                                                                                    if ($proposta->virou_padrao)
                                                                                        $tipoBotao = "info";
                                                                                    ?>
                                                                                    <a onclick="return confirm('Tem certeza que deseja tornar essa proposta em padrão?')" alt="Tornar padrão" title="Tornar padrão"
                                                                                       class="btn btn-sm btn-<?php echo $tipoBotao; ?> <?php echo(isset($apresentacao) ? 'disabled' : ''); ?>" href="<?php echo base_url(); ?>index.php/in/usuario/duplica_trabalho_torna_padrao?id=<?php echo $proposta->idProposta; ?>">
                                                                                        <i class="fa fa-file-o"></i>
                                                                                    </a>
                                                                                <?php endif; ?>

                                                                                <?php if ($permissoes->duplicar_projeto): ?>
                                                                                    <a onclick="return confirm('Tem certeza que deseja duplicar essa proposta?')" alt="Duplica Proposta" title="Duplica Proposta"
                                                                                       class="btn btn-sm btn-default <?php echo(isset($apresentacao) ? 'disabled' : ''); ?>" href="<?php echo base_url(); ?>index.php/in/usuario/duplica_trabalho?id=<?php echo $proposta->idProposta; ?>">
                                                                                        <i class="fa fa-files-o"></i>
                                                                                    </a> 
                                                                                <?php endif; ?>

                                                                                <?php if ($permissoes->alterar_end_projeto): ?>
                                                                                    <a alt="Altera Endereço" title="Altera Endereço" class="btn btn-sm btn-default <?php echo(isset($apresentacao) ? 'disabled' : ''); ?>"
                                                                                       href="<?php echo base_url(); ?>index.php/in/usuario/endereco?id=<?php echo $proposta->idProposta; ?>">
                                                                                        <i class="fa fa-map-marker"></i>
                                                                                    </a> 
                                                                                <?php endif; ?>

                                                                                <?php if ($permissoes->editar_projeto): ?>
                                                                                    <a alt="Editar Proposta" title="Editar Proposta" class="btn btn-sm btn-default <?php echo(isset($apresentacao) ? 'disabled' : ''); ?>"
                                                                                       href="<?php echo base_url(); ?>index.php/in/gestor/informa_valores_programa?edit=1&id=<?php echo $proposta->idProposta; ?>">
                                                                                        <i class="fa fa-edit"></i>
                                                                                    </a> 
                                                                                <?php endif; ?>

                                                                                <?php if ($permissoes->apagar_projeto): ?>
                                                                                    <a onclick="return confirm('Tem certeza que deseja excluir essa proposta?')" class="btn btn-sm btn-primary <?php echo(isset($apresentacao) ? 'disabled' : ''); ?>" title="Excluir Proposta"
                                                                                       href="<?php echo base_url(); ?>index.php/in/gestor/gerencia_proposta?delete=1&id=<?php echo $proposta->idProposta; ?>">
                                                                                        <i class="fa fa-trash-o"></i>
                                                                                    </a>
                                                                                <?php endif; ?>

                                                                                <a alt="Relatório Proposta" title="Relatório Proposta"
                                                                                   class="btn btn-sm btn-info <?php echo(isset($apresentacao) ? 'disabled' : ''); ?>" target="__blank" href="<?php echo base_url(); ?>index.php/in/usuario/imprimir_extrato?id=<?php echo $proposta->idProposta; ?>">
                                                                                    <i class="fa fa-file"></i>
                                                                                </a>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <!--ROW-->
                                                                    <div class="row">

                                                                        <div class="col-sm-12">
                                                                            <div class="media-body">
                                                                                <a href="../usuario/visualiza_proposta?id=<?php echo $proposta->idProposta; ?>" class="media-heading"><?php echo $proposta->nome; ?></a>

                                                                                <div class="clearfix"></div>

                                                                                <span style="color: blue;">Programa</span>

                                                                                <div class="clearfix"></div>
                                                                                <?php
                                                                                $programas = $programa_proposta_model->get_programas_by_proposta($proposta->idProposta);
                                                                                foreach ($programas as $p)
                                                                                    echo "- " . substr($p->nome_programa, 0, 180) . (strlen($p->nome_programa) > 180 ? "..." : "") . "<br>";
                                                                                ?>

                                                                                <div class="clearfix"></div>

                                                                                <span style="color: blue;">Data de Criação</span> <br><?php echo implode("/", array_reverse(explode("-", $proposta->data))); ?>
                                                                                <br>

                                                                                <span style="color: blue;">Autor</span><br>
                                                                                <?php echo $usuariomodel->get_nome_by_id($proposta->idGestor); ?>

                                                                                <br><br>

                                                                                <div class="clearfix pull-left">
                                                                                    Data de Inicio <label class="label label-info"><?php echo implode("/", array_reverse(explode("-", $proposta->data_inicio))); ?></label>
                                                                                    | Data Final <label class="label label-info"><?php echo implode("/", array_reverse(explode("-", $proposta->data_termino))); ?></label>
                                                                                    | Entidade <label class="label label-info"><?php echo $proposta->cidade; ?></label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <!--END ROW-->
                                                                </div>
                                                            </a>
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>
                                                    <!-- Fim dos dados -->

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php $i++; ?>
                                <?php endforeach; ?>

                            </div>
                        </div>
                        <?php
                    }      // fim if lista
                    else {
                        ?>
                        <h1 style="text-align: center;">Nenhum resultado foi encontrado.</h1>
                    <?php } ?>
                </div>


                <!-- enviados -->
                <div class="tab-pane <?php echo $activeEnviados; ?>" id="tab2-3">
                    <?php if (isset($propostas_enviadas) AND $propostas_enviadas != null) { ?>
                        <h1 class="bg-white" style="text-align: right;">

                            <span><?php
                                if (isset($pesquisa)) {
                                    echo "Pesquisa por: {$pesquisa}";
                                }
                                ?></span> <span class="badge badge-info"><?php
                                if (isset($num_rows)) {
                                    echo "{$num_rows} resultados";
                                }
                                ?></span>
                        </h1>
                        <div class="widget borders-none">
                            <div class="widget-body ">

                                <div class="widget-body ">
                                    <div class="panel-group accordion" id="accordion">

                                    </div>
                                </div>
                                <?php $i = 0; ?>

                                <?php foreach ($cnpjs as $cidade => $cnpj): ?>
                                    <?php
                                    $qtd = 0;
                                    $propostas_enviadasAux = $propostas_enviadas;
                                    $panel = "collapse";
                                    foreach ($propostas_enviadasAux as $proposta) {
                                        if (in_array($proposta->proponente, $cnpj)) {
                                            $qtd++;

                                            if ($proposta->idProposta == $this->session->userdata('linha_panel'))
                                                $panel = "in";
                                        }
                                    }

                                    if ($qtd > 0):
                                        ?>
                                        <div class="panel-heading">
                                            <h4 class="panel-title">
                                                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse_env-<?php echo $i; ?>">
                                                    <?php echo $cidade; ?><span style="color: red; font-size: 16px;"><?php echo " ({$qtd})"; ?></span>
                                                </a>
                                            </h4>
                                        </div>
                                    <?php endif; ?>

                                    <div id="collapse_env-<?php echo $i; ?>" class="panel-collapse <?php echo $panel; ?>">
                                        <div class="panel-body">
                                            <div class="border-bottom tickets">
                                                <div class="row">

                                                    <?php foreach ($propostas_enviadas as $proposta): ?>
                                                        <?php if (in_array($proposta->proponente, $cnpj)): ?>
                                                            <!-- Inicio dos dados -->
                                                            <a href="../usuario/visualiza_proposta?id=<?php echo $proposta->idProposta; ?>" class="media-heading">
                                                                <div class="innerAll border-bottom tickets">

                                                                    <div class="row">
                                                                        <div class="col-sm-8">
                                                                            <div class="pull-left ">
                                                                                <label class="label label-default"><?php echo $proposta->areanome; ?></label>
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-md-4 pull-right">
                                                                            <div class="pull-right">
                                                                                <img src="<?php echo base_url(); ?>layout/assets/images/loader.gif" style="width: 30px;" id="<?php echo $proposta->idProposta; ?>" class="loader">
                                                                                <a class="btn btn-sm btn-info atualizaParecer" title="Atualizar Situação" id="<?php echo $proposta->idProposta; ?>"><i class="fa fa-refresh"></i></a>

                                                                                <a alt="Consultar Parecer" title="Consultar Parecer" class="btn btn-sm btn-default"
                                                                                   href="<?php echo base_url(); ?>index.php/in/usuario/consultar_parecer?id=<?php echo $proposta->idProposta; ?>">
                                                                                    <i class="fa fa-envelope-o"></i>
                                                                                </a>
                                                                                <?php if ($permissoes->tornar_proj_padrao): ?>
                                                                                    <a onclick="return confirm('Tem certeza que deseja tornar essa proposta em padrão?')" alt="Tornar padrão" title="Tornar padrão"
                                                                                       class="btn btn-sm btn-default" href="<?php echo base_url(); ?>index.php/in/usuario/duplica_trabalho_torna_padrao?id=<?php echo $proposta->idProposta; ?>">
                                                                                        <i class="fa fa-file-o"></i>
                                                                                    </a>
                                                                                <?php endif; ?>

                                                                                <?php if ($permissoes->duplicar_projeto): ?>
                                                                                    <a onclick="return confirm('Tem certeza que deseja duplicar essa proposta?')" alt="Duplica Proposta" title="Duplica Proposta"
                                                                                       class="btn btn-sm btn-default" href="<?php echo base_url(); ?>index.php/in/usuario/duplica_trabalho?id=<?php echo $proposta->idProposta; ?>">
                                                                                        <i class="fa fa-files-o"></i>
                                                                                    </a> 
                                                                                <?php endif; ?>

                                                                                <a alt="Relatório Proposta" title="Relatório Proposta"
                                                                                   class="btn btn-sm btn-info" target="__blank" href="<?php echo base_url(); ?>index.php/in/usuario/imprimir_extrato?id=<?php echo $proposta->idProposta; ?>">
                                                                                    <i class="fa fa-file"></i>
                                                                                </a>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <!--ROW-->
                                                                    <div class="row">

                                                                        <div class="col-sm-12">
                                                                            <div class="media-body">
                                                                                <a href="../usuario/visualiza_proposta?id=<?php echo $proposta->idProposta; ?>" class="media-heading"><?php echo $proposta->nome; ?></a>

                                                                                <div class="clearfix"></div>

                                                                                <span style="color: blue;">Programa</span>

                                                                                <div class="clearfix"></div>
                                                                                <?php
                                                                                $programas = $programa_proposta_model->get_programas_by_proposta($proposta->idProposta);
                                                                                foreach ($programas as $p)
                                                                                    echo "- " . substr($p->nome_programa, 0, 100) . "...<br>";
                                                                                ?>

                                                                                <br>



                                                                                <div class="clearfix pull-left">
                                                                                    Situação <label style="font-size: 11px; color: #428bca;"><?php echo $proposta->situacao != "" ? $proposta->situacao : "Enviada para o SICONV"; ?></label>
                                                                                    <br>
                                                                                    N&deg; da proposta 
                                                                                    <a title="Proposta SICONV" target="_blank" href="https://www.convenios.gov.br/siconv/ConsultarProposta/ResultadoDaConsultaDePropostaDetalharProposta.do?idProposta=<?php echo $proposta->id_proposta_efetiva ?>&path=/MostraPrincipalConsultarPrograma.do&Usr=guest&Pwd=guest">
                                                                                        <span class="label label-primary" style="font-size: 12px;"><?php echo $proposta->id_siconv; ?></span>
                                                                                    </a>

                                                                                    <br>
                                                                                    <span style="color: blue;">Autor</span><br>
                                                                                    <?php echo $usuariomodel->get_nome_by_id($proposta->idGestor); ?>
                                                                                    <br><br>

                                                                                    Data de Inicio <label class="label label-info"><?php echo implode("/", array_reverse(explode("-", $proposta->data_inicio))); ?></label>
                                                                                    | Data Final <label class="label label-info"><?php echo implode("/", array_reverse(explode("-", $proposta->data_termino))); ?></label>
                                                                                    | Entidade <label class="label label-info"><?php echo $proposta->cidade; ?></label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <!--END ROW-->
                                                                </div>
                                                            </a>
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>
                                                    <!-- Fim dos dados -->

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php $i++; ?>
                                <?php endforeach; ?>

                            </div>
                        </div>

                    </div>
                    <!-- importadas-->
                    <div class="tab-pane <?php echo $activeImportadas; ?>" id="tab3-3">
                        <?php if (isset($propostas_importadas) AND $propostas_importadas != null) { ?>
                            <h3 class="bg-white" style="text-align: right;">
                            </h3>
                            <div class="widget borders-none">
                                <div class="widget-body ">

                                    <div class="widget-body ">
                                        <div class="panel-group accordion" id="accordion">

                                        </div>
                                    </div>
                                    <?php $i = 0; ?>

                                    <?php foreach ($areas as $area): ?>
                                        <?php
                                        $qtd = 0;
                                        $propostas_importadasAux = $propostas_importadas;
                                        $panel = "collapse";
                                        foreach ($propostas_importadasAux as $proposta) {
                                            if ($proposta->areanome == $area->nome) {
                                                $qtd++;
                                                if ($proposta->idProposta == $this->session->userdata('linha_panel')) {
                                                    $panel = "in";
                                                }
                                            }
                                        }

                                        if ($qtd > 0):
                                            ?>
                                            <div class="panel-heading">
                                                <h4 class="panel-title">
                                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse_imp<?php echo $i; ?>">
                                                        <?php echo $area->nome; ?><span style="color: red; font-size: 16px;"><?php echo " ({$qtd})"; ?></span>
                                                    </a>
                                                </h4>
                                            </div>

                                            <div id="collapse_imp<?php echo $i; ?>" class="panel-collapse <?php echo $panel; ?>">
                                                <div class="panel-body">
                                                    <div class="border-bottom tickets">
                                                        <div class="row">

                                                            <?php foreach ($propostas_importadas as $proposta): ?>
                                                                <?php if ($proposta->areanome == $area->nome): ?>
                                                                    <!-- Inicio dos dados -->
                                                                    <a href="../usuario/visualiza_proposta?id=<?php echo $proposta->idProposta; ?>" class="media-heading">
                                                                        <div class="innerAll border-bottom tickets">
                                                                            <div class="row">
                                                                                <div class="col-md-4 pull-right">
                                                                                    <div class="pull-right">

                                                                                        <?php if ($permissoes->tornar_proj_padrao): ?>
                                                                                            <a alt="Visualizar" title="Visualizar"
                                                                                               class="btn btn-sm btn-success" href="<?php echo base_url(); ?>index.php/in/usuario/visualiza_proposta?id=<?php echo $proposta->idProposta; ?>">
                                                                                                <i class="fa fa-search"></i>
                                                                                            </a>
                                                                                        <?php endif; ?>

                                                                                        <?php if ($this->session->userdata('nivel') == 1): ?>
                                                                                            <a onclick="return confirm('Tem certeza que deseja excluir essa proposta?')" class="btn btn-sm btn-primary" title="Excluir Proposta"
                                                                                               href="<?php echo base_url(); ?>index.php/in/gestor/delete_proposta?idProposta=<?php echo $proposta->idProposta; ?>&origem=vp">
                                                                                                <i class="fa fa-trash-o"></i>
                                                                                            </a>
                                                                                        <?php endif; ?>
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <!--ROW-->
                                                                            <div class="row">

                                                                                <div class="col-sm-12">
                                                                                    <div class="media-body">
                                                                                        <a href="../usuario/visualiza_proposta?id=<?php echo $proposta->idProposta; ?>" class="media-heading"><?php echo $proposta->nome; ?></a>

                                                                                        <div class="clearfix"></div>

                                                                                        <span style="color: blue;">Programas</span>

                                                                                        <div class="clearfix"></div>
                                                                                        <?php
                                                                                        $programas = $programa_proposta_model->get_programas_by_proposta($proposta->idProposta);
                                                                                        foreach ($programas as $p) {
                                                                                            echo "- " . substr($p->nome_programa, 0, 180) . (strlen($p->nome_programa) > 180 ? "..." : "") . "<br>" . " Obj: " . trim(substr($p->objeto, 0, 90)) . (strlen($p->objeto) > 90 ? "..." : "") . "<br><br>";
                                                                                        }
                                                                                        ?>

                                                                                        <div class="clearfix"></div>

                                                                                        <span style="color: blue;">Data de Criação</span> <br><?php echo implode("/", array_reverse(explode("-", $proposta->data))); ?>
                                                                                        <br>

                                                                                        <div class="clearfix pull-left">
                                                                                            Data de Inicio <label class="label label-info"><?php echo implode("/", array_reverse(explode("-", $proposta->data_inicio))); ?></label>
                                                                                            | Data Final <label class="label label-info"><?php echo implode("/", array_reverse(explode("-", $proposta->data_termino))); ?></label>
                                                                                        </div>

                                                                                        <br><br>
                                                                                        <div class="clearfix"></div>

                                                                                        <span style="color: blue;">Banco atende</span> <br><?php echo $proposta->banco_atende; ?>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <!--END ROW-->
                                                                        </div>
                                                                    </a>
                                                                <?php endif; ?>
                                                            <?php endforeach; ?>
                                                            <!-- Fim dos dados -->

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                        <?php if ($qtd > 0): ?>
                                            <?php $i++; ?>
                                        <?php endif; ?>
                                    <?php endforeach; ?>

                                </div>
                            </div>
                            <?php
                        }      // fim if lista
                        else {
                            ?>
                            <h1 style="text-align: center;">Nenhum resultado foi encontrado.</h1>
                        <?php } ?>
                    </div>
                </div>
                <?php
            }     // fim if lista
            else {
                ?>
                <h1 style="text-align: center;">Nenhum resultado foi encontrado.</h1>
            <?php } ?>
        </div>

        <!-- padrão 
        <div class="tab-pane" id="tab3-3">
        
        </div>
        -->
    </div>
</div>
</div>
</div>

<?php
if ($this->session->userdata('projEnviado') != "")
    $this->session->set_userdata('projEnviado', '');

if ($this->session->userdata('linha_panel') != "")
    $this->session->set_userdata('linha_panel', '');
?>

<script type="text/javascript">
    $(document).ready(function () {
        $(".loader").hide();

        $(".atualizaParecer").click(function () {
            var idProposta = $(this).attr('id');
            var urlParecer = '<?php echo base_url() . 'index.php/in/get_propostas/update_status_porposta/' ?>' + idProposta;
            $.ajax({
                url: urlParecer,
                type: 'get',
                dataType: 'html',
                beforeSend: function () {
                    $("#" + idProposta).slideDown();
                },
                success: function (data) {
                    location.href = $(location).attr('href');
                }
            });
            return false;
        });
    });
</script>