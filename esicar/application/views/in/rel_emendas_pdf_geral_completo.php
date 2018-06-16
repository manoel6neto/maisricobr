<?php
$i = 0;
$j = 0;
$numeroEmenda = "";
$codigoSiconv = "";
$achouProposta = false;
$dataEmenda = "";

echo "<div style='text-align:center;'><h2>Relatório de Emendas</h2></div>";

foreach ($emendas as $em) {
    if ($numeroEmenda == "" || $numeroEmenda != $em->emenda) {
        $anoEmenda = explode("-", $em->data_inicio_parlam);
        echo '<div class="panel-heading">
            <h4 class="panel-title"><a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse-' . $j . '"><span style="color: red;">' . $em->emenda . '/' . $anoEmenda[0] . '</a></h4>
        </div>
        <div id="collapse-' . $j . '" class="panel-collapse collapse">
            <div class="panel-body">
                <div class="innerAll border-bottom tickets">
                    <div class="row">
        ';

        echo '<table class="table">';
        $numeroEmenda = $em->emenda;
        $dataEmenda = substr($em->data_inicio_parlam, 0, 4);
        $j++;
    } else {
        if ($dataEmenda == "" || substr($dataEmenda, 0, 4) != substr($em->data_inicio_parlam, 0, 4)) {
            $anoEmenda = explode("-", $em->data_inicio_parlam);
            echo '<div class="panel-heading">
                <h4 class="panel-title"><a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse-' . ($j) . '"><span style="color: red;">' . $em->emenda . '/' . $anoEmenda[0] . '</a></h4>
            </div>
            <div id="collapse-' . ($j) . '" class="panel-collapse collapse">
                <div class="panel-body">
                    <div class="innerAll border-bottom tickets">
                        <div class="row">
        ';

            echo '<table class="table">';
            $j++;
        }
        $numeroEmenda = $em->emenda;
        $dataEmenda = substr($em->data_inicio_parlam, 0, 4);
    }

    echo "<tr style='background-color:#DCDCDC;'><td style='color:red;'>Proponente</td><td style='color:#428bca;' colspan='8'>" . $em->nome . "</td></tr>";
    $achouProposta = false;
    foreach ($emendas_propostas as $e) {
        $qtd = 0;
        if ($em->emenda == $e->codigo_emenda && $em->codigo_programa == $e->codigo_programa && str_replace("-", "", str_replace("/", "", str_replace(".", "", $em->cnpj))) == $e->proponente) {
            $achouProposta = true;
            //if($codigoSiconv == "" || $codigoSiconv != $e->codigo_siconv){
            //$codigoSiconv = $e->codigo_siconv;
            //$programas = $programa_banco_proposta_model->get_programas_by_proposta($e->id_proposta);
            //foreach ($programas as $programa){
            echo "<tr>";
            echo "<td style='color:red;'>Programa</td><td colspan='7'> <b>" . $e->codigo_programa . "</b> - " . (substr($e->nome_programa, 0, 180) . (strlen($e->nome_programa) > 180 ? "..." : "")) . "</td>";
            //$dadosPrograma = $programa_model->get_dados_programa($programa->codigo_programa);
            echo "</tr>";
            //}

            echo "<tr><td style='color:red;'>Valor da Emenda</td><td colspan='8'>{$em->valor}</td></tr>";
            echo "<tr style='color:#428bca;'><td colspan='9'>Proposta Cadastrada</td></tr>";
            echo "<tr>";
            echo "<td style='color:red;'>Valor Repasse</td><td>{$e->valor_repasse}</td>";
            echo "<td style='color:red;'>Número</td><td><a style='font-size: 12px;' class='label label-info' target='_blank' href='https://www.convenios.gov.br/siconv/ConsultarProposta/ResultadoDaConsultaDePropostaDetalharProposta.do?idProposta={$e->id_siconv}&path=/MostraPrincipalConsultarPrograma.do&Usr=guest&Pwd=guest'>{$e->codigo_siconv}</a></td>";
            echo "<td style='color:red;'>Situação</td><td>" . $e->situacao . "</td>";
            echo "<td style='color:blue; font-size: 14px;'><a href='" . base_url("index.php/in/dados_siconv/detalha_propostas_pareceres?id={$e->id_proposta}") . "' target='_blank'>Detalhar Proposta</a></td>";
            echo "</tr>";

            //Get valor a utilizar
            $valor_emenda = str_replace(",", ".", str_replace(".", "", str_replace("R$ ", "", $em->valor)));
            $valor_repasse = str_replace(",", ".", str_replace(".", "", str_replace("R$ ", "", $e->valor_repasse)));

            //Valor a utilizar é pegar todas os programas que usam a proposta e pegar o repasse
            echo "<tr><td style='color:red;'>Valor a Utilizar</td><td colspan='8'>R$ " . number_format(($valor_emenda - $valor_repasse), 2, ",", ".") . "</td></tr>";
        }
    }
    if (!$achouProposta) {
        if ($emendas_propostas != null) {
            $programas = $programa_model->get_programa_by_codigo($em->codigo_programa);
            foreach ($programas as $programa) {
                echo "<tr>";
                echo "<td style='color:red;'>Programa</td><td colspan='7'><b>" . $programa->codigo . "</b> - " . (substr($programa->descricao, 0, 180) . (strlen($programa->descricao) > 180 ? "..." : "")) . " </td>";
                //$dadosPrograma = $programa_model->get_dados_programa($programa->codigo_programa);
                echo "</tr>";
            }
        }
        echo "<tr><td style='color:red;'>Valor da Emenda</td><td colspan='8'>{$em->valor}</td></tr>";
    }
    echo "<tr><td colspan='9'></td></tr>";

    if ((isset($emendas[$i + 1]->emenda) && ($numeroEmenda == "" || $numeroEmenda == $emendas[$i + 1]->emenda)) && (isset($emendas[$i + 1]->data_inicio_parlam) && ($dataEmenda == "" || substr($dataEmenda, 0, 4) == substr($emendas[$i + 1]->data_inicio_parlam, 0, 4)))) {
        
    } else {
        echo "</table>
        </div>
            </div>
                </div>
                    </div>";
    }

    $i++;
}