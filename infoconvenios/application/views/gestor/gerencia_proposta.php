<div id="content">
    <div class="spacing-x2">
        <!-- Widget -->
        <div class="widget widget-tabs widget-tabs-gray widget-tabs-double-2 border-bottom-none">

            <!-- Widget heading -->
            <div class="widget-head">
                <ul>
                    <li class="active"><a class="glyphicons edit" href="#overview" data-toggle="tab"><i></i>Meus Projetos</a>
                    </li>
                    <li><a class="glyphicons edit" href="#edit-account" data-toggle="tab"><i></i>Propostas Cadastradas</a>
                    </li>
                    <li><a class="glyphicons edit" href="#projects" data-toggle="tab"><i></i>Banco de Projetos</a>
                    </li>
                </ul>
            </div>
            <!-- // Widget heading END -->

            <div class="widget-body ">


                <div class="tab-content">

                    <div class="tab-pane active widget-body-regular " id="overview">

                      <table class="table-striped table">
                            <thead>
                                <tr>
                                    <th width="5%">Id Proposta</th>
                                    <th width="5%">Área</th>
                                    <th width="15%">Nome Proposta</th>
                                    <th width="10%">Cidade</th>
                                    <th width="28%">Programa</th>
                                    <th width="8%">Vigência do programa</th>
                                    <th width="27%"> </th>
                                </tr>
                            </thead>
                            <tbody>
                            
                                <?php 
                                
                                foreach ($propostas as $proposta){ //echo $proposta->nome."  <br />"; ?>
                                <tr <?php if ($proposta->padrao == true) echo "style=\"color:blue\"";?> >
                                    <td>
                                      
                                            <?php echo $proposta->idProposta;?>
                                    </td>
                                    <td>
                                        <div >
                                            <?php echo $proposta->area_nome;?></div>
                                    </td>
                                    <td>
                                      
                                            <?php echo $proposta->nome;?>
                                    </td>
                                    <td>
                                      
                                            <?php echo $proposta->cidade;?>
                                    </td>
                                    <td>
                                       
                                            <?php echo $proposta->nome_programa;?>
                                    </td>
                                    <td>
                                       
										<span class="label label-primary"> <?php echo $proposta->data_fim;?></span>
                                           
                                    </td>
                                    <td class="right actions">
                                       
                                            <?php if (isset($usuario_gestor) !== false) { ?>
												<a class="btn btn-sm btn-default" href="<?php echo  base_url();?>index.php/in/usuario/escolher_proponente_inicial?id=<?php echo  $proposta->idProposta;?>"><i class="fa fa-check-square-o"></i></a>
												
												<a onclick="return confirm ('Tem certeza que deseja duplicar esse projeto?')" alt="Duplica Trabalho" title="Duplica Trabalho" class="btn btn-sm btn-default" href="<?php echo  base_url();?>index.php/in/usuario/duplica_trabalho?id=<?php echo  $proposta->idProposta;?>"><i class="fa fa-files-o"></i></a>
												<a alt="Visualiza" title="Visualiza" class="btn btn-sm btn-default" href="<?php echo  base_url();?>index.php/in/usuario/visualiza_proposta?id=<?php echo  $proposta->idProposta;?>"><i class="fa fa-eye"></i></a> 
												<a onclick="return confirm ('Tem certeza que deseja excluir esse projeto?')" class="btn btn-sm btn-default" href="<?php echo  base_url();?>index.php/in/gestor/gerencia_proposta?delete=1&id=<?php echo  $proposta->idProposta;?>"><i class="fa fa-trash-o"></i></a>
												<a onclick="return confirm ('Tem certeza que deseja finalizar esse projeto?')" alt="Finaliza Trabalho" title="Finaliza Trabalho" class="btn btn-sm btn-default" href="<?php echo  base_url();?>index.php/in/usuario/finaliza_trabalho?id=<?php echo  $proposta->idProposta;?>"><i class="fa fa-check-circle-o"></i></a>
												<a alt="Altera Endereço" title="Altera Endereço" class="btn btn-sm btn-default" href="<?php echo  base_url();?>index.php/in/usuario/endereco?id=<?php echo  $proposta->idProposta;?>"><i class="fa fa-map-marker"></i></a>
												<a alt="Editar Proposta" title="Editar Proposta" class="btn btn-sm btn-default" href="<?php echo  base_url();?>index.php/in/gestor/incluir_proposta?edit=1&id=<?php echo  $proposta->idProposta;?>"><i class="fa fa-edit"></i></a>
												<a alt="Ver Propostas" title="Ver Propostas"class="btn btn-sm btn-default" href="<?php echo  base_url();?>index.php/in/gestor/visualiza_propostas?proposta=<?php echo  $proposta->idProposta;?>"><i class="fa fa-flag"></i></a>
												<a onclick="return confirm ('Tem certeza que deseja tornar esse projeto padrão?')" class="btn btn-sm btn-default" href="<?php echo  base_url();?>index.php/in/gestor/tornar_padrao?id=<?php echo  $proposta->idProposta;?>">Tornar padrão</a>
												<a class="btn btn-sm btn-default" href="<?php echo  base_url();?>index.php/in/usuario/escolher_proponente_master?id=<?php echo  $proposta->idProposta;?>">Alterar usuário e cidade do Projeto</a>
												
                                            <?php } else if (isset($usuario_alteracoes) !==false) { ?>
												<a alt="Visualiza" title="Visualiza" class="btn btn-sm btn-default" href="<?php echo  base_url();?>index.php/in/usuario/visualiza_proposta?id=<?php echo  $proposta->idProposta;?>"><i class="fa fa-eye"></i></a> 
												<a onclick="return confirm ('Tem certeza que deseja excluir esse projeto?')" class="btn btn-sm btn-default" href="<?php echo  base_url();?>index.php/in/gestor/gerencia_proposta?delete=1&id=<?php echo  $proposta->idProposta;?>"><i class="fa fa-trash-o"></i></a>
												<a onclick="return confirm ('Tem certeza que deseja finalizar esse projeto?')" alt="Finaliza Trabalho" title="Finaliza Trabalho" class="btn btn-sm btn-default" href="<?php echo  base_url();?>index.php/in/usuario/finaliza_trabalho?id=<?php echo  $proposta->idProposta;?>"><i class="fa fa-check-circle-o"></i></a>
                                           
                                            <!--<a class="btn btn-sm btn-default" href="<?php echo  base_url();?>index.php/in/usuario/escolher_proponente?id=<?php echo  $proposta->idProposta;?>">Alterar usuário, cidade e programa</a>-->
                                            <?php } else if (isset($usuario_aberto) !==false) { ?>
												<a onclick="return confirm ('Tem certeza que deseja duplicar esse projeto?')" alt="Duplica Trabalho" title="Duplica Trabalho" class="btn btn-sm btn-default" href="<?php echo  base_url();?>index.php/in/usuario/duplica_trabalho?id=<?php echo  $proposta->idProposta;?>"><i class="fa fa-files-o"></i></a>
												<a alt="Visualiza" title="Visualiza" class="btn btn-sm btn-default" href="<?php echo  base_url();?>index.php/in/usuario/visualiza_proposta?id=<?php echo  $proposta->idProposta;?>"><i class="fa fa-eye"></i></a> 
												<a onclick="return confirm ('Tem certeza que deseja excluir esse projeto?')" class="btn btn-sm btn-default" href="<?php echo  base_url();?>index.php/in/gestor/gerencia_proposta?delete=1&id=<?php echo  $proposta->idProposta;?>"><i class="fa fa-trash-o"></i></a>
												<a onclick="return confirm ('Tem certeza que deseja finalizar esse projeto?')" alt="Finaliza Trabalho" title="Finaliza Trabalho" class="btn btn-sm btn-default" href="<?php echo  base_url();?>index.php/in/usuario/finaliza_trabalho?id=<?php echo  $proposta->idProposta;?>"><i class="fa fa-check-circle-o"></i></a>
												<a alt="Altera Endereço" title="Altera Endereço" class="btn btn-sm btn-default" href="<?php echo  base_url();?>index.php/in/usuario/endereco?id=<?php echo  $proposta->idProposta;?>"><i class="fa fa-map-marker"></i></a>
												<a alt="Editar Proposta" title="Editar Proposta" class="btn btn-sm btn-default" href="<?php echo  base_url();?>index.php/in/gestor/incluir_proposta?edit=1&id=<?php echo  $proposta->idProposta;?>"><i class="fa fa-edit"></i></a>

												<a class="btn btn-sm btn-default" href="<?php echo  base_url();?>index.php/in/usuario/selecionar_programas?id=<?php echo  $proposta->idProposta;?>">Alterar programa</a>
												<!-- <a class="btn btn-sm btn-default" href="<?php echo  base_url();?>index.php/in/usuario/escolher_proponente?id=<?php echo  $proposta->idProposta;?>">Alterar usuário e cidade do Projeto</a>
												<a class="btn btn-sm btn-default" href="<?php echo  base_url();?>index.php/in/gestor/altera_usuario?id=<?php echo  $proposta->idProposta;?>">Alterar programa</a>
												<a class="btn btn-sm btn-default" href="<?php echo  base_url();?>index.php/in/usuario/escolher_proponente_master?id=<?php echo  $proposta->idProposta;?>">Alterar programa1</a>
												<a class="btn btn-sm btn-default" href="<?php echo  base_url();?>index.php/in/usuario/selecionar_programas?id=<?php echo  $proposta->idProposta;?>">Alterar programa2</a> -->

                                            <?php } else if (isset($usuario_master) !==false) { ?>
												<a onclick="return confirm ('Tem certeza que deseja duplicar esse projeto?')" alt="Duplica Trabalho" title="Duplica Trabalho" class="btn btn-sm btn-default" href="<?php echo  base_url();?>index.php/in/usuario/duplica_trabalho?id=<?php echo  $proposta->idProposta;?>"><i class="fa fa-files-o"></i></a>
												<a alt="Visualiza" title="Visualiza" class="btn btn-sm btn-default" href="<?php echo  base_url();?>index.php/in/usuario/visualiza_proposta?id=<?php echo  $proposta->idProposta;?>"><i class="fa fa-eye"></i></a> 
												<a onclick="return confirm ('Tem certeza que deseja excluir esse projeto?')" class="btn btn-sm btn-default" href="<?php echo  base_url();?>index.php/in/gestor/gerencia_proposta?delete=1&id=<?php echo  $proposta->idProposta;?>"><i class="fa fa-trash-o"></i></a>
												<a onclick="return confirm ('Tem certeza que deseja finalizar esse projeto?')" alt="Finaliza Trabalho" title="Finaliza Trabalho" class="btn btn-sm btn-default" href="<?php echo  base_url();?>index.php/in/usuario/finaliza_trabalho?id=<?php echo  $proposta->idProposta;?>"><i class="fa fa-check-circle-o"></i></a>
												<a alt="Altera Endereço" title="Altera Endereço" class="btn btn-sm btn-default" href="<?php echo  base_url();?>index.php/in/usuario/endereco?id=<?php echo  $proposta->idProposta;?>"><i class="fa fa-map-marker"></i></a>
												<a alt="Editar Proposta" title="Editar Proposta" class="btn btn-sm btn-default" href="<?php echo  base_url();?>index.php/in/gestor/incluir_proposta?edit=1&id=<?php echo  $proposta->idProposta;?>"><i class="fa fa-edit"></i></a>
												<a alt="Ver Propostas" title="Ver Propostas"class="btn btn-sm btn-default" href="<?php echo  base_url();?>index.php/in/gestor/visualiza_propostas?proposta=<?php echo  $proposta->idProposta;?>"><i class="fa fa-flag"></i></a>
												<a class="btn btn-sm btn-default" href="<?php echo  base_url();?>index.php/in/usuario/escolher_proponente_master?id=<?php echo  $proposta->idProposta;?>">Alterar cidade e programa</a>
                                            <?php } ?>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Tab content -->
                    <div class="tab-pane widget-body-regular" id="edit-account">

                       <table class="dynamicTable table table-condensed table-vertical-center table-thead-simple">
                            <thead>
                                <tr>
                                    <th >Id Proposta</th>
                                    <th >Área</th>
                                    <th >Nome Proposta</th>
                                    <th >Cidade</th>
                                    <th class="especificacao">Programa</th>
                                    <th class="especificacao">Vigência do programa</th>
                                    <th class="especificacao">Id no siconv</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="tbodyrow">
                                <?php foreach ($propostas_enviadas as $proposta){ //echo $proposta->nome."                               <br />"; ?>
                                <tr class="odd" <?php if ($proposta->padrao == true) echo "style=\"color:blue\"";?> >
                                    <td>
                                        <div >
                                            <?php echo $proposta->idProposta;?></div>
                                    </td>
                                    <td>
                                        <div >
                                            <?php echo $proposta->area_nome;?></div>
                                    </td>
                                    <td>
                                        <div >
                                            <?php echo $proposta->nome;?></div>
                                    </td>
                                    <td>
                                        <div >
                                            <?php echo $proposta->cidade;?></div>
                                    </td>
                                    <td>
                                        <div class="especificacao">
                                            <?php echo $proposta->nome_programa;?></div>
                                    </td>
                                    <td>
                                        <div class="especificacao">
                                            <?php echo $proposta->data_fim;?></div>
                                    </td>
                                    <td>
                                        <div class="especificacao">
                                            <?php echo $proposta->id_siconv." - ".$proposta->situacao_siconv;?></div>
                                    </td>
                                    <td>
                                       <?php if (isset($usuario_gestor) !== false) { ?>
												<a class="btn btn-sm btn-default" href="<?php echo  base_url();?>index.php/in/usuario/escolher_proponente_inicial?id=<?php echo  $proposta->idProposta;?>"><i class="fa fa-check-square-o"></i></a>
												
												<a onclick="return confirm ('Tem certeza que deseja duplicar esse projeto?')" alt="Duplica Trabalho" title="Duplica Trabalho" class="btn btn-sm btn-default" href="<?php echo  base_url();?>index.php/in/usuario/duplica_trabalho?id=<?php echo  $proposta->idProposta;?>"><i class="fa fa-files-o"></i></a>
												<a alt="Visualiza" title="Visualiza" class="btn btn-sm btn-default" href="<?php echo  base_url();?>index.php/in/usuario/visualiza_proposta?id=<?php echo  $proposta->idProposta;?>"><i class="fa fa-eye"></i></a> 
												<a onclick="return confirm ('Tem certeza que deseja excluir esse projeto?')" class="btn btn-sm btn-default" href="<?php echo  base_url();?>index.php/in/gestor/gerencia_proposta?delete=1&id=<?php echo  $proposta->idProposta;?>"><i class="fa fa-trash-o"></i></a>
												<a onclick="return confirm ('Tem certeza que deseja finalizar esse projeto?')" alt="Finaliza Trabalho" title="Finaliza Trabalho" class="btn btn-sm btn-default" href="<?php echo  base_url();?>index.php/in/usuario/finaliza_trabalho?id=<?php echo  $proposta->idProposta;?>"><i class="fa fa-check-circle-o"></i></a>
												<a alt="Altera Endereço" title="Altera Endereço" class="btn btn-sm btn-default" href="<?php echo  base_url();?>index.php/in/usuario/endereco?id=<?php echo  $proposta->idProposta;?>"><i class="fa fa-map-marker"></i></a>
												<a alt="Editar Proposta" title="Editar Proposta" class="btn btn-sm btn-default" href="<?php echo  base_url();?>index.php/in/gestor/incluir_proposta?edit=1&id=<?php echo  $proposta->idProposta;?>"><i class="fa fa-edit"></i></a>
												<a alt="Ver Propostas" title="Ver Propostas"class="btn btn-sm btn-default" href="<?php echo  base_url();?>index.php/in/gestor/visualiza_propostas?proposta=<?php echo  $proposta->idProposta;?>"><i class="fa fa-flag"></i></a>
												<a onclick="return confirm ('Tem certeza que deseja tornar esse projeto padrão?')" class="btn btn-sm btn-default" href="<?php echo  base_url();?>index.php/in/gestor/tornar_padrao?id=<?php echo  $proposta->idProposta;?>">Tornar padrão</a>
												<a class="btn btn-sm btn-default" href="<?php echo  base_url();?>index.php/in/usuario/escolher_proponente_master?id=<?php echo  $proposta->idProposta;?>">Alterar usuário e cidade do Projeto</a>
                                            
                                            <?php } else if (isset($usuario_alteracoes) !==false) { ?>
												<a alt="Visualiza" title="Visualiza" class="btn btn-sm btn-default" href="<?php echo  base_url();?>index.php/in/usuario/visualiza_proposta?id=<?php echo  $proposta->idProposta;?>"><i class="fa fa-eye"></i></a> 
												<a onclick="return confirm ('Tem certeza que deseja excluir esse projeto?')" class="btn btn-sm btn-default" href="<?php echo  base_url();?>index.php/in/gestor/gerencia_proposta?delete=1&id=<?php echo  $proposta->idProposta;?>"><i class="fa fa-trash-o"></i></a>
												<a onclick="return confirm ('Tem certeza que deseja finalizar esse projeto?')" alt="Finaliza Trabalho" title="Finaliza Trabalho" class="btn btn-sm btn-default" href="<?php echo  base_url();?>index.php/in/usuario/finaliza_trabalho?id=<?php echo  $proposta->idProposta;?>"><i class="fa fa-check-circle-o"></i></a>
                                           
                                            <!--<a class="btn btn-sm btn-default" href="<?php echo  base_url();?>index.php/in/usuario/escolher_proponente?id=<?php echo  $proposta->idProposta;?>">Alterar usuário, cidade e programa</a>-->
                                            <?php } else if (isset($usuario_aberto) !==false) { ?>
												<a onclick="return confirm ('Tem certeza que deseja duplicar esse projeto?')" alt="Duplica Trabalho" title="Duplica Trabalho" class="btn btn-sm btn-default" href="<?php echo  base_url();?>index.php/in/usuario/duplica_trabalho?id=<?php echo  $proposta->idProposta;?>"><i class="fa fa-files-o"></i></a>
												<a alt="Visualiza" title="Visualiza" class="btn btn-sm btn-default" href="<?php echo  base_url();?>index.php/in/usuario/visualiza_proposta?id=<?php echo  $proposta->idProposta;?>"><i class="fa fa-eye"></i></a> 
												<a onclick="return confirm ('Tem certeza que deseja excluir esse projeto?')" class="btn btn-sm btn-default" href="<?php echo  base_url();?>index.php/in/gestor/gerencia_proposta?delete=1&id=<?php echo  $proposta->idProposta;?>"><i class="fa fa-trash-o"></i></a>
												<a onclick="return confirm ('Tem certeza que deseja finalizar esse projeto?')" alt="Finaliza Trabalho" title="Finaliza Trabalho" class="btn btn-sm btn-default" href="<?php echo  base_url();?>index.php/in/usuario/finaliza_trabalho?id=<?php echo  $proposta->idProposta;?>"><i class="fa fa-check-circle-o"></i></a>
												<a alt="Altera Endereço" title="Altera Endereço" class="btn btn-sm btn-default" href="<?php echo  base_url();?>index.php/in/usuario/endereco?id=<?php echo  $proposta->idProposta;?>"><i class="fa fa-map-marker"></i></a>
												<a alt="Editar Proposta" title="Editar Proposta" class="btn btn-sm btn-default" href="<?php echo  base_url();?>index.php/in/gestor/incluir_proposta?edit=1&id=<?php echo  $proposta->idProposta;?>"><i class="fa fa-edit"></i></a>

												<a class="btn btn-sm btn-default" href="<?php echo  base_url();?>index.php/in/usuario/selecionar_programas?id=<?php echo  $proposta->idProposta;?>">Alterar programa</a>
												<!-- <a class="btn btn-sm btn-default" href="<?php echo  base_url();?>index.php/in/usuario/escolher_proponente?id=<?php echo  $proposta->idProposta;?>">Alterar usuário e cidade do Projeto</a>
												<a class="btn btn-sm btn-default" href="<?php echo  base_url();?>index.php/in/gestor/altera_usuario?id=<?php echo  $proposta->idProposta;?>">Alterar programa</a>
												<a class="btn btn-sm btn-default" href="<?php echo  base_url();?>index.php/in/usuario/escolher_proponente_master?id=<?php echo  $proposta->idProposta;?>">Alterar programa1</a>
												<a class="btn btn-sm btn-default" href="<?php echo  base_url();?>index.php/in/usuario/selecionar_programas?id=<?php echo  $proposta->idProposta;?>">Alterar programa2</a> -->

                                            <?php } else if (isset($usuario_master) !==false) { ?>
												<a onclick="return confirm ('Tem certeza que deseja duplicar esse projeto?')" alt="Duplica Trabalho" title="Duplica Trabalho" class="btn btn-sm btn-default" href="<?php echo  base_url();?>index.php/in/usuario/duplica_trabalho?id=<?php echo  $proposta->idProposta;?>"><i class="fa fa-files-o"></i></a>
												<a alt="Visualiza" title="Visualiza" class="btn btn-sm btn-default" href="<?php echo  base_url();?>index.php/in/usuario/visualiza_proposta?id=<?php echo  $proposta->idProposta;?>"><i class="fa fa-eye"></i></a> 
												<a onclick="return confirm ('Tem certeza que deseja excluir esse projeto?')" class="btn btn-sm btn-default" href="<?php echo  base_url();?>index.php/in/gestor/gerencia_proposta?delete=1&id=<?php echo  $proposta->idProposta;?>"><i class="fa fa-trash-o"></i></a>
												<a onclick="return confirm ('Tem certeza que deseja finalizar esse projeto?')" alt="Finaliza Trabalho" title="Finaliza Trabalho" class="btn btn-sm btn-default" href="<?php echo  base_url();?>index.php/in/usuario/finaliza_trabalho?id=<?php echo  $proposta->idProposta;?>"><i class="fa fa-check-circle-o"></i></a>
												<a alt="Altera Endereço" title="Altera Endereço" class="btn btn-sm btn-default" href="<?php echo  base_url();?>index.php/in/usuario/endereco?id=<?php echo  $proposta->idProposta;?>"><i class="fa fa-map-marker"></i></a>
												<a alt="Editar Proposta" title="Editar Proposta" class="btn btn-sm btn-default" href="<?php echo  base_url();?>index.php/in/gestor/incluir_proposta?edit=1&id=<?php echo  $proposta->idProposta;?>"><i class="fa fa-edit"></i></a>
												<a alt="Ver Propostas" title="Ver Propostas"class="btn btn-sm btn-default" href="<?php echo  base_url();?>index.php/in/gestor/visualiza_propostas?proposta=<?php echo  $proposta->idProposta;?>"><i class="fa fa-flag"></i></a>
												<a class="btn btn-sm btn-default" href="<?php echo  base_url();?>index.php/in/usuario/escolher_proponente_master?id=<?php echo  $proposta->idProposta;?>">Alterar cidade e programa</a>
                                            <?php } ?>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- // Tab content END -->

                    <!-- Tab content -->
                    <div class="tab-pane widget-body-regular" id="projects">

                       <table class="dynamicTable table table-condensed table-vertical-center table-thead-simple">
                            <thead>
                                <tr>
                                    <th >Item</th>
                                    <th >Área</th>
                                    <th >Nome Proposta</th>
                                    <th >Valor da proposta</th>
                                    <th class="especificacao">Programa</th>
                                    <th class="especificacao">Vigência do programa</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="tbodyrow">
                                <?php 
                                $count_padrao = 1;
                                foreach ($propostas_padrao as $proposta){ //echo $proposta->nome."                                 <br />"; ?>
                                <tr class="odd" <?php if ($proposta->padrao == true) echo "style=\"color:blue\"";?> >
                                    <td>
                                        <div >
                                            <?php echo $count_padrao;?></div>
                                    </td>
                                    <td>
                                        <div >
                                            <?php echo $proposta->area_nome;?></div>
                                    </td>
                                    <td>
                                        <div >
                                            <?php echo $proposta->nome;?></div>
                                    </td>
                                    <td>
                                        <div>
                                            R$ <?php echo number_format($proposta->valor_global,2,",",".");?></div>
                                    </td>
                                    
                                    <td>
                                        <div class="especificacao">
                                            <?php echo $proposta->nome_programa;?></div>
                                    </td>
                                    <td>
                                        <div class="especificacao">
                                            <?php echo $proposta->data_fim;?></div>
                                    </td>
                                    <td>
                                       
                                            <a class="btn btn-sm btn-default" href="<?php echo  base_url();?>index.php/in/usuario/escolher_proponente_inicial?id=<?php echo  $proposta->idProposta;?>"><i class="fa fa-check-square-o"></i></a>
                                            
                                            <?php if (isset($usuario_gestor) !== false) { ?>
                                            <a class="btn btn-sm btn-default" href="<?php echo  base_url();?>index.php/in/gestor/remover_padrao?id=<?php echo  $proposta->idProposta;?>">Remover padrão</a>
                                            <?php }?>
                                       
                                    </td>
                                </tr>
                                <?php 
                                $count_padrao++;
                                } ?>
                            </tbody>
                        </table>

                    </div>
                    <!-- // Tab content END -->
                </div>

            </div>
        </div>
        <!-- // Widget END -->

    </div>
</div>
