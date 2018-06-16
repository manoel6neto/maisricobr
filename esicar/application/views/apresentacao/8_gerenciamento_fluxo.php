<?php include 'layout/assets/css/chart/PHPtoOrgChart.php'; ?>
<link type="text/css" rel="stylesheet" href="<?php echo base_url('layout/assets/css/chart/phpToOrgChartStyle.css') ?>">

<?php
$data = array(
    '<div title="Selecionar o PROPONENTE e o ORGÃO da proposta">Proponente & Orgão <div style="color:#000; font-style:italic">Destinatário e Destinador do recruso</div></div>'
    => array(
        '<div title="Selecionar o programa aberto desejado">Programas<div style="color:#000; font-style:italic">Oportunidade</div></div>'
        => array(
            '<div title="Sem os valores a proposta não pode ser continuada">Valores/Objeto/Contrapartida<div style="color:#000; font-style:italic">Selecionar e calcular</div></div>'
            => array(
                '<div title="Informações como Área, Nome da Proposta, Datas (Início e Fim) e os dados bancários">Dados da Proposta<div style="color:#000; font-style:italic">Informações gerais</div></div>'
                => array(
                    '1' => '<div title="Datas de início e fim para a execução da proposta">Datas<div style="color:#000; font-style:italic">Datas de início e fim do projeto</div></div>',
                    '2' => '<div title="Instituição Bancária e Dados da conta para Convêniamento da proposta">Dados Bancários<div style="color:#000; font-style:italic">Dados bancários:Banco, AG e CC</div></div>'
                ),
                '1' => '<div title="Caracterização do Proponente (Município, Estado ou outro) e descrição do objetivo de forma clara e objetiva">Justificativa/Objeto da proposta<div style="color:#000; font-style:italic">Caracterização do Município</div></div>',
                '<div title="Detalhamento financeiro para as metas e etapas do projeto">Crono Físico<div style="color:#000; font-style:italic">Informações financeiras do projeto</div></div>'
                => array(
                    '<div title="Detalhamento das metas da proposta">Metas<div style="color:#000; font-style:italic">Definição das metas</div></div>'
                    => array(
                        '1' => '<div title="Detalha o que será feito na meta em questão">Especificação<div style="color:#000; font-style:italic">Descrição da meta</div></div>',
                        '2' => '<div title="Unidade de fornecimento e quantidades da meta">Fornecimento/Quant.<div style="color:#000; font-style:italic">Unidade de fornecimento e quantidades</div></div>',
                        '3' => '<div title="Valor total e individual dos itens da meta">Valor<div style="color:#000; font-style:italic">Valor total e individual</div></div>',
                        '4' => '<div title="Endereço da execução da meta">Endereço<div style="color:#000; font-style:italic">Endereço de execução</div></div>'
                    ),
                    '<div title="Detalhamento das etapas da proposta">Etapas<div style="color:#000; font-style:italic">Definição das etapas</div></div>'
                    => array(
                        '1' => '<div title="Detalha o que será feito na etapa em questão">Especificação Etapa<div style="color:#000; font-style:italic">Descrição da etapa</div></div>',
                        '2' => '<div title="Unidade de fornecimento e quantidades da etapa">Fornecimento/Quant. Etapa<div style="color:#000; font-style:italic">Unidade de fornecimento e quantidades</div></div>',
                        '3' => '<div title="Valor total e individual dos itens da etapa">Valor Etapa<div style="color:#000; font-style:italic">Valor total e individual</div></div>',
                        '4' => '<div title="Datas de início e fim para a etapa">Datas Etapa <div style="color:#000; font-style:italic">Datas limites para a etapa</div></div>',
                        '5' => '<div title=Endereço da execução da etapa"">Endereço Etapa <div style="color:#000; font-style:italic">Endereço de execução</div></div>'
                    )
                ),
                '<div title="Detalhamento das datas e valores dos pagamentos tanto por parte do CONVENENTE, quanto do CONCEDENTE">Crono Desembolso<div style="color:#000; font-style:italic">Informações do "pagamento" do projeto</div></div>'
                => array(
                    '1' => '<div title="Responsável pelo desembolso CONCEDENTE ou CONVENENTE">Responsável<div style="color:#000; font-style:italic">Concedente ou Convenente</div></div>',
                    '2' => '<div title="Mês e o Ano do desembolso">Data<div style="color:#000; font-style:italic">Mês e Ano</div></div>',
                    '<div title="Valor do desembolso">Valor<div style="color:#000; font-style:italic">Valor do desembolso</div></div>'
                    => array(
                        '1' => '<div title="Associação das Metas e Etapas que serão pagas com o valor do desembolso">Meta/Etapa<div style="color:#000; font-style:italic">Metas e Etapas cobertas pelo desembolso</div></div>'
                    )
                ),
                '<div title="Detalhamento de todas as Obras, Serviços e compras. Com valores, quantidades, unidades e endereço para cada um deles">Plano Detalhado<div style="color:#000; font-style:italic">Detalhamento das Obras/Serviços/Compras</div></div>' 
                => array (
                    '1' => '<div title="Selecionar o tipo de despesa">Tipo Despesa<div style="color:#000; font-style:italic">Obra/Serviço/Bem/Outros</div></div>',
                    '2' => '<div title="Selecionar a natureza da despesa utilizando o código aprópriado do siconv">Natureza<div style="color:#000; font-style:italic">Classificação da despesa</div></div>',
                    '3' => '<div title="Selecionar todos os outros detalhes para a despesa em questão">Detalhamento<div style="color:#000; font-style:italic">Unidade/Quant./Valor/Endereço</div></div>'
                )
            )
        )
    )
);
echo '
<div class="orgchart">';
PHPtoOrgChart($data);
echo '</div>';
?>