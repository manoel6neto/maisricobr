<div id="content" class="innerAll bg-white">

    <h1 class="bg-white content-heading border-bottom">Relatórios</h1>

    <br>

    <div class="widget borders-none">
        <div class="widget-body ">
            <div class="panel-group accordion" id="accordion">

            </div>
        </div>

        <div class="panel">

            <?php if ($this->session->userdata('nivel') == 1): ?>
                <div class="panel-heading">
                    <h4 class="panel-title"><a href="<?php echo base_url('index.php/relatorio_ganho_perca_controller/lista_percas_emendas_maiores_cidades'); ?>"><span style="color: #428bca;">Relatório Nacional de Perdas de Emendas</span></a></h4>
                </div>

                <div class="panel-heading">
                    <h4 class="panel-title"><a href="<?php echo base_url('index.php/relatorio_ganho_perca_controller/lista_ganhos_maiores_cidades'); ?>"><span style="color: #428bca;">Ganhos das Cidades - PR</span></a></h4>
                </div>    

            <?php endif; ?>

            <div class="panel-heading">
                <h4 class="panel-title"><a href="<?php echo base_url('index.php/controle_relatorios'); ?>"><span style="color: #428bca;">Status Nacional</span></a></h4>
            </div>


            <div class="panel-heading">
                <h4 class="panel-title"><a href="<?php echo base_url('index.php/relatorio_siconv_controller/planilha'); ?>"><span style="color: #428bca;">Desempenho de Programas e Propostas</span></a></h4>
            </div>

            <div class="panel-heading">
                <h4 class="panel-title"><a href="<?php echo base_url('index.php/relatorio_siconv_controller/relatorio'); ?>"><span style="color: #428bca;">Avaliação de Desempenho de Programas e Propostas</span></a></h4>
            </div>

            <?php if ($this->session->userdata('nivel') == 1): ?>
                <div class="panel-heading">
                    <h4 class="panel-title"><a href="<?php echo base_url('index.php/in/usuario/utilizacao_sistema_cliente'); ?>"><span style="color: #428bca;">Utilização do Sistema Pelos Clientes</span></a></h4>
                </div>
            <?php endif; ?>

        </div>

        <div class="panel">
            <div class="panel-heading">
                <h4 class="panel-title"><a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse-0"><span style="color: #428bca;">Atividades Desenvolvidas no Sistema</span></a></h4>
            </div>
            <div id="collapse-0" class="panel-collapse collapse">
                <div class="panel-body">
                    <div class="innerAll border-bottom tickets">
                        <div class="row">
                            <div class="col-md-4 pull-left">
                                <div class="pull-left">
                                    <form action="relatorio/rel_proj_desenv" method="post">
                                        <div class="form-group">
                                            <label>Tipo de Proposta: </label>
                                            <select name="tipo_projeto" class="form-control">
                                                <option value="">Todos</option>
                                                <option value="1">Elaborados</option>
                                                <option value="2">Banco de Propostas</option>
                                                <option value="3">Enviados</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label>Ano: </label>
                                            <div class="row">
                                                <div class="col-xs-6">
                                                    <label>Inicial</label>
                                                    <select name="ano_inicio" id="ano_inicio" class="form-control">
                                                        <option value="">Todos</option>
                                                        <?php foreach ($anos as $ano): ?>
                                                            <option value="<?php echo $ano; ?>"><?php echo $ano; ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>

                                                <div class="col-xs-6">
                                                    <label>Final</label>
                                                    <select name="ano_fim" id="ano_fim" class="form-control">
                                                        <option value="">Todos</option>
                                                        <?php foreach ($anos as $ano): ?>
                                                            <option value="<?php echo $ano; ?>"><?php echo $ano; ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <input type="submit" class="btn btn-primary" value="Pesquisar" id="pesquisa_projeto">
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php if ($this->session->userdata('nivel') != 3): ?>
                <div class="panel-heading">
                    <h4 class="panel-title"><a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse-1"><span style="color: #428bca;">Controle de Atividades por Usuário</span></a></h4>
                </div>
                <div id="collapse-1" class="panel-collapse collapse">
                    <div class="panel-body">
                        <div class="innerAll border-bottom tickets">
                            <div class="row">
                                <div class="col-md-4 pull-left">
                                    <div class="pull-left">
                                        <form action="relatorio/rel_atividade_usuario" method="post">
                                            <div class="form-group">
                                                <label>Responsável: </label>
                                                <?php echo form_dropdown('usuario', $usuarios, '', 'class="form-control"'); ?>
                                            </div>

                                            <input type="submit" class="btn btn-primary" value="Pesquisar" id="pesquisa_usuario">
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="panel-heading">
                    <h4 class="panel-title"><a class="accordion-toggle" href="<?php echo base_url('index.php/relatorio/rel_desempenho_sistema'); ?>"><span style="color: #428bca;">Resumo das Atividades do Sistema</span></a></h4>
                </div>

            <?php endif; ?>

            <?php if ($this->session->userdata('nivel') == 1 || $this->session->userdata('nivel') == 15): ?>
                <div class="panel-heading">
                    <h4 class="panel-title"><a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse-2"><span style="color: #428bca;">Resumo de Visitas do Representante</span></a></h4>
                </div>
                <div id="collapse-2" class="panel-collapse collapse">
                    <div class="panel-body">
                        <div class="innerAll border-bottom tickets">
                            <div class="row">
                                <div class="col-md-4 pull-left">
                                    <div class="pull-left">
                                        <form action="relatorio/rel_visita_representante" method="post">
                                            <div class="form-group">
                                                <label>Representante: </label>
                                                <select name="usuario" class="form-control">
                                                    <option value="">Todos</option>
                                                    <?php foreach ($representates as $usuario): ?>
                                                        <option value="<?php echo $usuario->id_usuario; ?>"><?php echo $usuario->nome; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>

                                            <input type="submit" class="btn btn-primary" value="Pesquisar" id="pesquisa_usuario">
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            <?php endif; ?>
        </div>
    </div>

</div>

<script>
    $(document).ready(function () {
        $("#pesquisa_projeto").click(function () {
            if ($("#ano_inicio").val() != "" && $("#ano_fim").val() != "") {
                if ($("#ano_inicio").val() > $("#ano_fim").val()) {
                    alert("O ano inicial deve ser menor que o final.");
                    return false;
                }
            }
        });
    });
</script>