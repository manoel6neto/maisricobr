<div id="content">

    <h1 class="bg-white content-heading border-bottom">GERENCIAMENTO DE
        PROJETOS</h1>
    <div class="bg-white">
        <form method="post" class="form-horizontal">
            <div class="input-group input-lg ">
                <input type="text" class="form-control" placeholder="Pesquisar">
                <div class="input-group-btn">
                    <button class="btn btn-default" type="button">
                        <i class="fa fa-search"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>
<!-- Tabs -->
<div class="relativeWrap" >
	<div class="box-generic">
	
		<div class="tabsbar">
			<ul>
				<li class="glyphicons folder_open"><a href="#tab1-3" data-toggle="tab"><i></i> Enviados <strong></strong></a></li>
				<li class="glyphicons folder_open"><a href="#tab2-3" data-toggle="tab"><i></i> Cadastrados <strong></strong></a></li>
				<!-- <li class="glyphicons folder_open"><a href="#tab3-3" data-toggle="tab"><i></i> <span>Padrão</span></a></li> -->
			</ul>
		</div>
		
		
		<div class="tab-content">
				
			<!-- cadastrados -->
			<div class="tab-pane active" id="tab1-3">
				 <?php if (isset($propostas_enviadas) AND $propostas_enviadas != null) { ?>
        <h1 class="bg-white" style="text-align: right;">
            <span><?php if (isset($pesquisa)) {
        echo "Pesquisa por: {$pesquisa}";
    } ?></span>
            <span class="badge badge-info"><?php if (isset($num_rows)) {
        echo "{$num_rows} resultados";
    } ?></span>
        </h1>
        <div class="widget borders-none">
            <div class="widget-body ">
    <?php $i = 1;
    foreach ($propostas_enviadas as $proposta) { ?>
                    <a
                        href="../usuario/visualiza_proposta?id=<?php echo $proposta->idProposta; ?>"
                        target="_blank" class="media-heading">
                        <div class="innerAll border-bottom tickets">

                            <div class="row">

                                <div class="col-sm-8">
                                    <div class="pull-left ">
                                        <label class="label label-default"><?php echo $proposta->idProposta; ?></label>
                                    </div>
                                </div>

                                <div class="col-md-4 pull-right">
                                    <div class="pull-right">
                                        <a
                                            onclick="return confirm('Tem certeza que deseja duplicar esse projeto?')"
                                            alt="Duplica Trabalho" title="Duplica Trabalho"
                                            class="btn btn-sm btn-default"
                                            href="<?php echo base_url(); ?>index.php/in/usuario/duplica_trabalho?id=<?php echo $proposta->idProposta; ?>"><i
                                                class="fa fa-files-o"></i></a> 
                                        <a
                                            onclick="return confirm('Tem certeza que deseja tornar esse projeto em padrão?')"
                                            alt="Tornar padrão" title="Tornar padrão"
                                            class="btn btn-sm btn-default"
                                            href="<?php echo base_url(); ?>index.php/in/usuario/duplica_trabalho_torna_padrao?id=<?php echo $proposta->idProposta; ?>"><i
                                                class="fa fa-files-o"></i></a>
                                        <a alt="Altera Endereço"
                                                                          title="Altera Endereço" class="btn btn-sm btn-default"
                                                                          href="<?php echo base_url(); ?>index.php/in/usuario/endereco?id=<?php echo $proposta->idProposta; ?>"><i
                                                class="fa fa-map-marker"></i></a> <a alt="Editar Proposta"
                                                                             title="Editar Proposta" class="btn btn-sm btn-default"
                                                                             href="<?php echo base_url(); ?>index.php/in/gestor/incluir_proposta?edit=1&id=<?php echo $proposta->idProposta; ?>"><i
                                                class="fa fa-edit"></i></a> <a
                                            onclick="return confirm('Tem certeza que deseja excluir esse projeto?')"
                                            class="btn btn-sm btn-primary"
                                            href="<?php echo base_url(); ?>index.php/in/gestor/gerencia_proposta?delete=1&id=<?php echo $proposta->idProposta; ?>"><i
                                                class="fa fa-trash-o"></i></a>
                                    </div>
                                </div>
                            </div>
                            <div class="row">

                                <div class="col-sm-12">

                                    <ul class="media-list">
                                        <li class="media">
                                            <!--div class="pull-left">
                                                    <div class="center">
                                                            <div class="checkbox">
                                                                    <label class="checkbox-custom"> <i
                                                                            class="fa fa-fw fa-square-o"></i> <input type="checkbox">
                                                                    </label>
                                                            </div>
                                                    </div>
                                            </div-->

                                            <div class="media-body">


                                                <a
                                                    href="../usuario/visualiza_proposta?id=<?php echo $proposta->idProposta; ?>"
                                                    target="_blank" class="media-heading"><?php echo $proposta->nome; ?></a>



                                                <div class="clearfix"></div>
        <?php echo substr($proposta->nome_programa, 0, 100) . "..."; ?>

                                                <br> <br>

                                                <div class="clearfix pull-left">
                                                    Data de Inicio <label class="label label-info"><?php echo $proposta->data_inicio; ?></label>
                                                    | Data Final <label class="label label-info"><?php echo $proposta->data_termino; ?></label>
                                                    | Orgão <label class="label label-info"><?php echo $proposta->cidade; ?></label>
                                                </div>


                                            </div>

                                        </li>

                                    </ul>


                                </div>



                                <!--a
                        onclick="return confirm ('Tem certeza que deseja finalizar esse projeto?')"
                        class="btn btn-success pull-right"
                        href="<?php echo base_url(); ?>index.php/in/usuario/finaliza_trabalho?id=<?php echo $proposta->idProposta; ?>"><i
                                class="fa fa-check-circle-o"></i> Finaliza Trabalho</a-->

                            </div>


                        </div>
                    </a>
        <?php
    }
    ?>


            </div>
        </div>
        <?php
    }  // fim if lista
    else {
        ?>
        <h1 style="text-align: center;">Nenhum resultado foi encontrado.</h1>
<?php } ?>
			</div>
			
			
			<!-- enviados -->
			<div class="tab-pane" id="tab2-3">
			 <?php if (isset($propostas_cadastradas) AND $propostas_cadastradas != null) { ?>
        <h1 class="bg-white" style="text-align: right;">
            <span><?php if (isset($pesquisa)) {
        echo "Pesquisa por: {$pesquisa}";
    } ?></span>
            <span class="badge badge-info"><?php if (isset($num_rows)) {
        echo "{$num_rows} resultados";
    } ?></span>
        </h1>
        <div class="widget borders-none">
            <div class="widget-body ">
    <?php $i = 1;
    foreach ($propostas_cadastradas as $proposta) { ?>
                    <a
                        href="../usuario/visualiza_proposta?id=<?php echo $proposta->idProposta; ?>"
                        target="_blank" class="media-heading">
                        <div class="innerAll border-bottom tickets">

                            <div class="row">

                                <div class="col-sm-8">
                                    <div class="pull-left ">
                                        <label class="label label-default"><?php echo $proposta->idProposta; ?></label>
                                    </div>
                                </div>

                                <div class="col-md-4 pull-right">
                                    <div class="pull-right">
                                        <a
                                            onclick="return confirm('Tem certeza que deseja duplicar esse projeto?')"
                                            alt="Duplica Trabalho" title="Duplica Trabalho"
                                            class="btn btn-sm btn-default"
                                            href="<?php echo base_url(); ?>index.php/in/usuario/duplica_trabalho?id=<?php echo $proposta->idProposta; ?>"><i
                                                class="fa fa-files-o"></i></a> 
                                        <a
                                            onclick="return confirm('Tem certeza que deseja tornar esse projeto em padrão?')"
                                            alt="Tornar padrão" title="Tornar padrão"
                                            class="btn btn-sm btn-default"
                                            href="<?php echo base_url(); ?>index.php/in/usuario/duplica_trabalho_torna_padrao?id=<?php echo $proposta->idProposta; ?>"><i
                                                class="fa fa-files-o"></i></a>
                                        <a alt="Altera Endereço"
                                                                          title="Altera Endereço" class="btn btn-sm btn-default"
                                                                          href="<?php echo base_url(); ?>index.php/in/usuario/endereco?id=<?php echo $proposta->idProposta; ?>"><i
                                                class="fa fa-map-marker"></i></a> <a alt="Editar Proposta"
                                                                             title="Editar Proposta" class="btn btn-sm btn-default"
                                                                             href="<?php echo base_url(); ?>index.php/in/gestor/incluir_proposta?edit=1&id=<?php echo $proposta->idProposta; ?>"><i
                                                class="fa fa-edit"></i></a> <a
                                            onclick="return confirm('Tem certeza que deseja excluir esse projeto?')"
                                            class="btn btn-sm btn-primary"
                                            href="<?php echo base_url(); ?>index.php/in/gestor/gerencia_proposta?delete=1&id=<?php echo $proposta->idProposta; ?>"><i
                                                class="fa fa-trash-o"></i></a>
                                    </div>
                                </div>
                            </div>
                            <div class="row">

                                <div class="col-sm-12">

                                    <ul class="media-list">
                                        <li class="media">
                                            <!--div class="pull-left">
                                                    <div class="center">
                                                            <div class="checkbox">
                                                                    <label class="checkbox-custom"> <i
                                                                            class="fa fa-fw fa-square-o"></i> <input type="checkbox">
                                                                    </label>
                                                            </div>
                                                    </div>
                                            </div-->

                                            <div class="media-body">


                                                <a
                                                    href="../usuario/visualiza_proposta?id=<?php echo $proposta->idProposta; ?>"
                                                    target="_blank" class="media-heading"><?php echo $proposta->nome; ?></a>



                                                <div class="clearfix"></div>
        <?php echo substr($proposta->nome_programa, 0, 100) . "..."; ?>

                                                <br> <br>

                                                <div class="clearfix pull-left">
                                                    Data de Inicio <label class="label label-info"><?php echo $proposta->data_inicio; ?></label>
                                                    | Data Final <label class="label label-info"><?php echo $proposta->data_termino; ?></label>
                                                    | Orgão <label class="label label-info"><?php echo $proposta->cidade; ?></label>
                                                </div>


                                            </div>

                                        </li>

                                    </ul>


                                </div>



                                <!--a
                        onclick="return confirm ('Tem certeza que deseja finalizar esse projeto?')"
                        class="btn btn-success pull-right"
                        href="<?php echo base_url(); ?>index.php/in/usuario/finaliza_trabalho?id=<?php echo $proposta->idProposta; ?>"><i
                                class="fa fa-check-circle-o"></i> Finaliza Trabalho</a-->

                            </div>


                        </div>
                    </a>
        <?php
    }
    ?>


            </div>
        </div>
        <?php
    }  // fim if lista
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