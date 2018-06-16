<div class=WordSection1 style="height: 180mm; z-index: 1;">
    <div style="padding-top: 20mm;">
        <h3><p align="center">DESEMPENHO DA GESTÃO SICONV (2009–2018)</p></h3>
        <p align="justify">O SICONV é o sistema de Convênios do Governo Federal – Através dele os recursos financeiros devem ser transferidos aos municípios e sem burocracia.</p>  
        <p align="justify">Por ano em media de <?= $nivel_e_fator_de_cadastro['programas_ofertados'] ?> Programas Voluntários são disponibilizados aos Municípios, destinados às esferas administrativas dos Governos Municipais,
            Organizações Sociais sem Fins Lucrativos, Empresas Mistas e Consórcios Públicos, possibilitando uma média de <?= $nivel_e_fator_de_cadastro['envio_proposta'] ?> oportunidades de cadastramentos de propostas.
            Esse número independe das oportunidades previstas através de emendas parlamentares e especifico do concedente. Essa oferta visa à captação de recursos federais
            por meio da celebração de convênios e contrato de repasses, para fortalecimento da gestão pública nas diversas áreas como: saúde, educação, assistência social,
            saneamento, segurança pública, desenvolvimento urbano, geração de renda, tecnologias, etc. Em quatro anos são pelo menos <?= $nivel_e_fator_de_cadastro['programas_ofertados'] * 8 ?> oportunidades de envio de propostas.</p> 
        <p align="justify">Nos últimos 10 anos, diante das oportunidades oferecidas pelo SICONV através dos programas federais, em referidas esferas administrativas, foram
            registrados índices de aprovação de propostas que apontam média <?= number_format($percentual_aprovadas_2009_a_2018, 0, ',', '.'); ?> % refletindo resultados com
            indicadores muito baixos para o Governo Municipal, para as Organizações Sociais e para Consórcios Públicos.   </p>
        <p align="justify">Considerando os esforços dos Prefeitos e Técnicos, os resultados no cenário SICONV, mostram que os municípios perdem recursos por falta de propostas e 
            projetos, devido a erros no cadastramento, por limitações técnicas e falta de ferramentas tecnológicas de apoio ao RH. Essa realidade é caracterizada pela ausência de
            aproveitamento das oportunidades de cadastramento de média anual de <?= number_format(($perdas_propostas_voluntarias_2009_2012 + $perdas_propostas_voluntarias_2013_2016 + $perdas_propostas_voluntarias_2017_2018) / 10, 0, ',', '.') ?>
            propostas voluntárias e pelo menos <?= $nivel_e_fator_de_cadastro['emendas_parl_espec'] ?> emendas, cabíveis às demais esferas.</p>
        <p align="justify">As Emendas dos Deputados Federais (513) e Senadores (81) geram anualmente R$ 16.500.000,00 por cada parlamentar e um montante de R$ 9.801.000.000,00 
            (nove bilhões e oitocentos e um milhões de reis), para obrigatoriamente ser distribuído aos municípios. Desse montante 50 % (media de 5 bilhões) deve obrigatoriamente
            ser aplicado na saúde por meio das emendas impositivas. Quanto os municípios estão perdendo diante dessa possibilidade?</p>
        <p align="justify">Considerando fatores populacionais e econômicos propomos estabelecer uma média de 6 propostas anuais desses recursos carimbados para todas as esferas
            administrativas. Contudo, nos últimos 09 anos 
            <?php if ($nome_empresa != NULL) : ?>
                a empresa <?= $nome_empresa ?> foi contemplada
            <?php elseif ($cidade != NULL) : ?>
                o município <?= $cidade ?> foi contemplado
            <?php elseif ($estado != NULL) : ?>
                <?= $estado ?>
            <?php elseif ($regiao != NULL) : ?>
                <?php if ($regiao == 'NE'): ?>
                    a região NORDESTE foi contemplada
                <?php elseif ($regiao == 'N'): ?>
                    a região NORTE foi contemplada
                <?php elseif ($regiao == 'CO'): ?>
                    a região CENTRO OESTE foi contemplada
                <?php elseif ($regiao == 'SE'): ?>
                    a região SUDESTE foi contemplada
                <?php elseif ($regiao == 'S'): ?>
                    a região SUL foi contemplada
                <?php else: ?>
                    o BRASIL foi contemplado
                <?php endif; ?>
            <?php else: ?>
                o BRASIL foi contemplado
            <?php endif; ?>
            com <?= $quantidade_emenda_parlamentar_2009_a_2018 ?> emendas parlamentares e com
            <?= $quantidade_emenda_especifico_2009_a_2018 ?> especifico do concedente, gerando um déficit <?= $quantidade_perda_emenda_2009_a_2018 ?> perdas de oportunidades. A soma do
            valor concedido é de R$ <?= number_format($valor_emendas_aprovadas_2009_a_2018 + $valor_perda_emenda_2009_a_2018, 2, ',', '.'); ?> com respectiva aprovação de 
            R$ <?= number_format($valor_emendas_aprovadas_2009_a_2018, 2, ',', '.'); ?> e perda de R$ <?= number_format($valor_perda_emenda_2009_a_2018, 2, ',', '.'); ?>. Essas perdas poderão
            ser reduzidas e a captação desses recursos poderá ser ampliada diversas vezes, em quantidade de emendas e em valor.</p> 
        <p align="justify">O município, diante das oportunidades oferecidas, tem por direito, solicitar dos Deputados Federais, Senadores e Ministros as emendas parlamentares e especifico
            do concedente, para garantir a redução dos agravos no SUS, caracterizado principalmente pela alta taxa de mortalidade por falta de atendimento humanizado em todos os municípios
            do Brasil, assim como redução da taxa de vulnerabilidade social por falta de recursos nas diversas áreas de gestão pública.</p>
        <p align="justify">A gestão da captação de recursos é compartilhada portanto a analise dos processos e resultados  devem ser consideradas a partir da inclusão dos Governos Municipais,
            Organizações Sociais sem Fins Lucrativos, Empresas Mistas e Consórcios Públicos e inclusive os Órgãos do Estado com sede em nosso município.</p>
    </div>
</div>
