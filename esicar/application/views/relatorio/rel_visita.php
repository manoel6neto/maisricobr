<div id="content" class="innerAll bg-white">

    <?php if ($this->session->userdata('nivel') == 1): ?>
        <h1 class="bg-white content-heading border-bottom">Relatório de visitas Por representante</h1>
    <?php else: ?>
        <h1 class="bg-white content-heading border-bottom">Relatório de visitas Realizadas</h1>
    <?php endif; ?>

    <br>

    <?php if ($dados_rel != null): ?>
        <form action="rel_visita_representante_pdf" target="_blank" method="post">
            <input type="submit" value="Gerar PDF" class="btn btn-primary">
        </form>

        <br>

        <!--        <div class="widget borders-none">
                    <div class="widget-body ">
                        <div class="panel-group accordion" id="accordion">
        
                        </div>
                    </div>-->

        <div class="panel">
            <?php
            $titulo = "";
            $i = 0;
            $j = 0;
            foreach ($dados_rel as $dados) {
                $qtd = 0;

                foreach ($dados_rel as $d) {
                    if ($d->municipio == $dados->municipio)
                        $qtd++;
                }

                if ($titulo == "" || $titulo != $dados->municipio) {
                    //Accordion do município
                    echo '<div class="panel-heading">
				<h4 class="panel-title"><a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse-' . $j . '"><span style="color: red;">' . $dados->municipio . ' / ' . $dados->municipio_uf_sigla . ' <span style="color: #428bca;">(' . $qtd . ')</span></span></a></h4>
			</div>
		<div id="collapse-' . $j . '" class="panel-collapse collapse">
			<div class="panel-body">
				<div class="innerAll border-bottom tickets">
					<div class="row">
		';
                    $titulo = $dados->municipio;
                    $j++;
                } else
                    $titulo = $dados->municipio;

//        Accordion do representante
                echo '<div class="panel-heading">
				<h4 class="panel-title"><a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse-' . str_replace(' ', '', $dados->nome) . $j . '"><span style="color: red;">' . $dados->nome . '</span></a></h4>
			</div>
		<div id="collapse-' . str_replace(' ', '', $dados->nome) . $j . '" class="panel-collapse collapse">
			<div class="panel-body">
				<div class="innerAll border-bottom tickets">
					<div class="row">
		';
                echo '<table class="table">';
                echo "<tr style='background-color:#DCDCDC;'>
				<td><b>Contato:</b> {$dados->nome_contato}</td>
				<td><b>Email:</b> {$dados->email_contato}</td>
				<td colspan=4><b>Telefone:</b> " . $contato_municipio_model->formataCelular($dados->telefone_contato) . "</td>
		</tr>";

//                $historico_contato = $historico_contato_municipio_model->get_all_historico($dados->id_contato_municipio);

                $atividades = $avaliacao_visita_model->get_all_atividades();

                echo "<tr>"
                . "<th><b>Meta</b></th>"
                . "<th><b>Etapa</b></th>"
                . "<th><b>Atividade</b></th>"
                . "<th><b>Data</b></th>"
                . "<th><b>Status</b></th>"
                . "</tr>";

                $metas = $avaliacao_visita_model->get_all_metas();

                foreach ($metas as $m) {

                    $etapas = $avaliacao_visita_model->get_epatas_from_meta($m->id_meta, $dados->id_contato_municipio);
                    /* Contagem de atividades (span) */
                    $span = 0;
                    foreach ($etapas as $e) {
                        foreach ($e->atividades as $a) {
                            $span++;
                        }
                    }

                    $first_meta = true;
                    foreach ($etapas as $e) {
                        $first_etapa = true;
                        foreach ($e->atividades as $a) {
                            echo "<tr>";
                            if ($first_meta) {
                                echo "<td rowspan=" . $span . ">{$m->nome}</td>";
                                $first_meta = false;
                            }

                            if ($first_etapa) {
                                echo "<td rowspan=" . count($e->atividades) . ">{$e->nome}</td>";
                                $first_etapa = false;
                            }
                            
                            echo"<td>{$a->nome}</td>";
                            if ($a->data != null) {
                                echo "<td>{$a->data}</td>";
                                if ($a->status != null) {
                                    if ($a->status == '1') {
                                        echo "<td>Positivo</td>";
                                    } else {
                                        echo "<td>Negativo</td>";
                                    }
                                } else {
                                    echo "<td>--------</td>";
                                }
                            } else {
                                echo "<td>--------</td><td>--------</td>";
                            }

                            echo"</tr>";
                        }
                    }
//                    echo "</tr>";
                }

//                    foreach ($historico_contato as $historico) {
//                        echo "<tr>
//				<td><b>Status:</b> {$contato_municipio_model->getStatusContato($historico->status_contato)}</td>
//				<td><b>Data da Visita:</b> " . implode("/", array_reverse(explode("-", $historico->data_visita))) . "</td>
//				<td><b>Data do Retorno:</b> " . implode("/", array_reverse(explode("-", $historico->data_retorno))) . "</td>
//				<td><b>Classificação:</b> {$historico_contato_municipio_model->getClassVisita($historico->class_contato)}</td>
//				<td style="width:50%;'><b>Obs Gerais:</b> {$historico->obs_gerais}</td>
//			</tr>";
//                    }

                echo "<tr><td colspan='5'></td></tr>";

                echo "</table> </div>
                                    </div>
				</div>
			</div>";

                if (isset($dados_rel[$i + 1]->municipio) && ($titulo == "" || $titulo == $dados_rel[$i + 1]->municipio)) {
                    
                } else {
                    echo "      </div>
				</div>
			</div>
		</div>";
                }

                $i++;
            }
            ?>

        </div>
        <!--</div>-->

    <?php else: ?>
        <h1 style="text-align: center;">Nenhum dado encontrado.</h1>
    <?php endif; ?>

    <?php if ($this->session->userdata('nivel') == 4): ?>
        <a class="btn btn-primary" href="<?php echo base_url('index.php/controle_usuarios/area_vendedor'); ?>">Voltar</a>
    <?php else: ?>
        <a class="btn btn-primary" href="<?php echo base_url('index.php/relatorio'); ?>">Voltar</a>
    <?php endif; ?>
</div>