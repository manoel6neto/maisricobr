<?php
define("LATIN1_UC_CHARS", "ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝ");
define("LATIN1_LC_CHARS", "àáâãäåæçèéêëìíîïðñòóôõöøùúûüý");

function uc_latin1($str) {
    $str = strtoupper(strtr($str, LATIN1_LC_CHARS, LATIN1_UC_CHARS));
    return strtr($str, array(
        "ß" => "SS"
    ));
}

include 'application/controllers/BaseController.php';

class Usuario extends BaseController {

    function __construct() {
        parent::__construct();

        $this->load->model('usuariomodel');
        $this->usuario_logado = $this->usuariomodel->get_by_id($this->session->userdata('id_usuario'));
        $this->login_usuario = $this->usuario_logado->login_siconv;
        $this->senha = base64_decode($this->usuario_logado->senha_siconv);
        $this->cookie_file_path = tempnam("/tmp", "CURLCOOKIE" . rand());
        $this->login = $this->usuario_logado->nome;
        $this->login_siconv = null;
    }

    function verifica_permissoes($id) {
        if ($id == 2)
            return true;

        $paginas_permitidas = array('0' => array('cidades_ajax', 'gerencia_proposta_usuario', 'visualiza_propostas', 'incluir_bens_da_proposta',
                'listar_obras', 'incluir_etapa_do_cronograma_de_desembolso', 'incluir_meta_do_cronograma_de_desembolso',
                'excluir_parcela_do_cronograma_de_desembolso', 'incluir_parcela_do_cronograma_de_desembolso',
                'listar_cronograma', 'incluir_etapa_da_meta', 'listar_etapas', 'meta', 'recusar_trabalho',
                'aceitar_trabalho', 'listar_metas', 'apaga_meta', 'incluir_justificativa', 'visualiza_proposta',
                'duplica_trabalho', 'ver_trabalhos', 'lista_relatorios', 'relatorio_programas', 'escolher_proponente_inicial',
                'sala_conferencia', 'compra_passe', 'finaliza_trabalho', 'retorno_pagseguro', 'ler_pdf', 'endereco',
                'index', 'gerapdf_programas', 'relatorio_programas_emenda', 'gerapdf_programas_emenda', 'selecionar_programas',
                'escolhe_programa', 'selecionar_objetos'),
            '1' => array(''),
            '3' => array('programas', 'relatorio', 'solicitar_mudanca', 'gerapdf'),
            '4' => array('proposta_padrao'),
            '5' => array('proposta_alteracoes', 'escolher_proponente', 'escolher_endereco', 'escolher_dados_basicos',
                'incluir_proposta', 'escolhe_endereco', 'escolher_proponente_padrao', 'selecionar_programas_padrao'),
            '6' => array('proposta_aberta', 'escolher_proponente_cnpj', 'escolher_proponente', 'incluir_proposta', 'escolher_proponente_padrao',
                'selecionar_programas_padrao', 'alterar_senha_master'),
            '7' => array('proposta_aberta', 'escolher_proponente_cnpj', 'escolher_proponente', 'incluir_proposta', 'adiciona_usuario_trabalho',
                'escolher_proponente_padrao', 'selecionar_programas_padrao', 'escolher_proponente_master', 'escolhe_programa_master',
                'selecionar_programas_master', 'selecionar_objetos_master'),
            '8' => array(),
            '9' => array('compra_relatorio'),
            '99' => array()
        );

        //páginas por nível de usuário
        if (!in_array($this->router->method, $paginas_permitidas[$id]) && !in_array($this->router->method, $paginas_permitidas[0]))
            return false;

        $this->load->model('usuario_model');
        $paginas_restritas = array('incluir_bens_da_proposta', 'listar_obras', 'incluir_etapa_do_cronograma_de_desembolso',
            'incluir_meta_do_cronograma_de_desembolso', 'excluir_parcela_do_cronograma_de_desembolso',
            'incluir_parcela_do_cronograma_de_desembolso', 'listar_cronograma', 'incluir_etapa_da_meta',
            'listar_etapas', 'meta', 'listar_metas', 'apaga_meta',
            'incluir_justificativa', 'visualiza_proposta', 'ver_trabalhos', 'finaliza_trabalho',
            'endereco', 'escolhe_endereco');

        //acesso a determinados projetos (só o padrão e os que ele adicionou)
        if (in_array($this->router->method, $paginas_restritas)) {
            //o id do projeto deve existir, senão não pode prosseguir
            if ($this->input->get_post('id', TRUE) !== false) {
                //verificaçao se é o gestor da proposta ou se é padrão
                if ($this->usuario_model->verifica_dono_proposta($this->usuario_logado->idPessoa, $this->input->get_post('id', TRUE)) != true)
                    return false;
            } else
                return false;
        }

        return true;
    }

    function proposta_aberta() {
        $usuario = $this->usuario_logado->idPessoa;
        $this->load->model('proposta_model');
        if ($this->proposta_model->verifica_proponente($usuario) == false && $this->usuario_logado->tipoPessoa == 6) {
            $this->encaminha('escolher_proponente?id=' . $this->usuario_logado->idPessoa);
        }
        $usuario = $this->usuario_logado->idPessoa;
        $this->load->model('proposta_model');

        if ($this->input->get_post('delete', TRUE) !== false) {
            $id = $this->input->get_post('id', TRUE);
            $this->proposta_model->delete_record($id);
        }
        $data['propostas'] = $this->proposta_model->get_all_abertas_user($usuario);
        $data['propostas_enviadas'] = $this->proposta_model->get_all_enviadas_user($usuario);
        $data['propostas_padrao'] = $this->proposta_model->get_all_padrao();

        if ($this->usuario_logado->tipoPessoa == 6)
            $data['usuario_aberto'] = true;
        else if ($this->usuario_logado->tipoPessoa == 7)
            $data['usuario_master'] = true;

        $data['main'] = 'gestor/gerencia_proposta';
        $data['title'] = 'Physis - gerenciamento de propostas';
        $data['login'] = $this->login;
        $this->load->vars($data);
        $this->load->view('in/template');
    }

    function proposta_alteracoes() {
        $usuario = $this->usuario_logado->idPessoa;
        $this->load->model('proposta_model');

        if ($this->input->get_post('delete', TRUE) !== false) {
            $id = $this->input->get_post('id', TRUE);
            $this->proposta_model->delete_record($id);
        }
        $data['propostas'] = $this->proposta_model->get_all_abertas_user($usuario);
        $data['propostas_enviadas'] = $this->proposta_model->get_all_enviadas_user($usuario);
        $data['propostas_padrao'] = $this->proposta_model->get_all_padrao();

        $this->alert("Propostas restantes: " . $this->usuario_logado->quantidade);
        $data['usuario_alteracoes'] = true;

        $data['main'] = 'gestor/gerencia_proposta';
        $data['title'] = 'Physis - gerenciamento de propostas';
        $data['login'] = $this->login;
        $this->load->vars($data);
        $this->load->view('in/template');
    }

    function proposta_padrao() {
        $usuario = $this->usuario_logado->idPessoa;
        $this->load->model('proposta_model');

        if ($this->input->get_post('delete', TRUE) !== false) {
            $id = $this->input->get_post('id', TRUE);
            $this->proposta_model->delete_record($id);
        }
        $data['propostas'] = $this->proposta_model->get_all_abertas_user($usuario);
        $data['propostas_enviadas'] = $this->proposta_model->get_all_enviadas_user($usuario);
        $data['propostas_padrao'] = $this->proposta_model->get_all_padrao();

        $data['usuario_padrao'] = true;
        $data['main'] = 'gestor/gerencia_proposta';
        $data['title'] = 'Physis - gerenciamento de propostas';
        $data['login'] = $this->login;
        $this->load->vars($data);
        $this->load->view('in/template');
    }

    function endereco() {
        $this->load->model('trabalho_model');

        if ($this->input->get_post('UF', TRUE) !== false) {

            $options = array(
                'Proposta_idProposta' => $this->input->get_post('id', TRUE),
                'UF' => $this->input->get_post('UF', TRUE),
                'municipio_sigla' => $this->input->get_post('municipio_sigla', TRUE),
                'municipio_nome' => $this->input->get_post('municipio_nome', TRUE),
                'endereco' => $this->input->get_post('endereco', TRUE),
                'cep' => $this->input->get_post('cep', TRUE)
            );
            $this->trabalho_model->add_endereco($options);

            $this->load->model('system_logs');
            $this->load->model('cidades_siconv');
            $dadosEndereco = $this->trabalho_model->obter_endereco_por_id($this->input->get_post('id', TRUE));
            $this->system_logs->add_log(ALTERA_ENDERECO . " - UF: " . $this->obterEstadoNome($this->input->get_post('UF', TRUE)) . ", Municipio: " . $this->cidades_siconv->get_by_id($this->input->get_post('municipio_nome', TRUE))->Nome . ", Endereço: " . $this->input->get_post('endereco', TRUE));

            $this->alert("Feito.");
            $this->encaminha(base_url('index.php/in/gestor/visualiza_propostas'));
            exit();
        } else {
            $this->load->model('proposta_model');
            $id = $this->input->get_post('id', TRUE);
            $proposta = $this->proposta_model->get_by_id($id);
            $data['proposta'] = $proposta;
            $data['meta'] = $this->trabalho_model->obter_endereco_por_id($this->input->get_post('id', TRUE));
            $data['cidades'] = $this->trabalho_model->obter_cidades_siconv();
            $data['ufs'] = $this->trabalho_model->obter_uf_siconv();
            $data['enderecos'] = $this->trabalho_model->obter_enderecos($this->usuario_logado->id_usuario);
            $data['title'] = 'Physis - gerenciamento de usuários';
            $data['id'] = $this->input->get_post('id', TRUE);
            $data['main'] = 'gestor/endereco';
            $data['login'] = $this->login;
            $this->load->vars($data);
            $this->load->view('in/template');
        }
    }

    function escolher_proponente_inicial() {
        if ($this->usuario_logado->tipoPessoa == 5) {
            $this->encaminha('escolher_proponente_padrao?id=' . $this->input->get_post('id', TRUE));
        } else if ($this->usuario_logado->tipoPessoa == 6 || $this->usuario_logado->tipoPessoa == 7) {
            $this->encaminha('duplica_trabalho?id=' . $this->input->get_post('id', TRUE));
        }
    }

    function imprimir_extrato() {
        ini_set('max_execution_time', 0);

        $this->load->model('proposta_model');
        $this->load->model('trabalho_model');
        $this->load->model('cnpj_siconv');
        $this->load->model('usuariomodel');
        $this->load->model('programa_proposta_model');
        $this->load->model('proponente_siconv_model');
        $this->load->model('orgaos_model');
        $this->load->model('cronograma_model');

        $id = $this->input->get_post('id', TRUE);
        $proposta = $this->proposta_model->get_by_id($id);
        $justificativa = $this->trabalho_model->obter_justificativa_por_proposta($id);

        $codigo = $proposta->id_siconv;
        $programas_proposta = $this->programa_proposta_model->get_programas_by_proposta($id);

        $this->db->where('idbanco', $proposta->banco);
        $banco = $this->db->get('banco')->row(0)->nome;

        $agencia = $proposta->agencia;
        $objeto = $justificativa->objeto;
        $concedente = $proposta->orgao;
        $nome_concedente = $this->orgaos_model->get_nome_orgao($concedente);
        $proponente = $proposta->proponente;
        $proponente_siconv = $this->proponente_siconv_model->get_instituicao($proponente, true);
        $razao_social_proponente = $proponente_siconv->nome;
        $endereco = $proponente_siconv->endereco;
        $cidade = $proponente_siconv->municipio;
        $estado = $proponente_siconv->municipio_uf_sigla;
        $cep = $proponente_siconv->cep;
        $codigo_municipio = $proponente_siconv->codigo_municipio;
        $esfera_administrativa = $proponente_siconv->esfera_administrativa;
        $telefone = $proponente_siconv->telefone;
        $nome_responsavel = $proponente_siconv->nome_responsavel;
        $valor_global = $proposta->valor_global;
        $contrapartida = $proposta->total_contrapartida;
        $contrapartida_financeira = $proposta->contrapartida_financeira;
        $bens = $proposta->contrapartida_bens;
        $inicio = $proposta->data_inicio;
        $fim = $proposta->data_termino;

        $metas = $this->trabalho_model->get_metas_proposta($id);
        $desembolsos = $this->cronograma_model->get_by_proposta($id);

        $this->db->where('Proposta_idProposta', $id);
        $despesas = $this->db->get('despesa')->result();

        //gerando o pdf
        $this->load->library('mPDF');

        ob_start(); // inicia o buffer

        $mpdf = new mPDF('', 'A4');
        $mpdf->allow_charset_conversion = true;
        $mpdf->charset_in = 'UTF-8';

        $header = array(
            'L' => array(
                'content' => 'SIHS',
                'font-size' => 6
            ),
            'C' => array(
                'content' => strtoupper($this->session->userdata('entidade')),
                'font-size' => 10
            ),
            'R' => array(
                'content' => 'SIHS',
                'font-size' => 6
            ),
            'line' => 1
        );

        $mpdf->SetHeader($header, 'O');
        $mpdf->SetFooter('{DATE d/m/Y}||{PAGENO}/{nb}');

        $html .= "<body style='margin:0; padding:0; font-family: serif; font-size: 8pt;'>";
        $html .= "<h4 style='text-align: center;'>Relatório Proposta</h4><p>";
        $html .= "<table border='1' width='100%'>";
        $html .= "<tr><td colspan='6'>N° / ANO DA PROPOSTA:<p>{$codigo}</td></tr>";
        $html .= "<tr><td colspan='6' style='text-align: center;'>DADOS DO CONCEDENTE</td></tr>";
        $html .= "<tr><td colspan='6'>OBJETO:<p>{$objeto}</td></tr>";
        $html .= "<tr><td colspan='6'>JUSTIFICATIVA:<p>{$justificativa->Justificativa}</td></tr>";
        $html .= "</table>";
        $html .= "<br><br>";
        $html .= "<pagebreak>";
        $html .= "<h4 style='text-align: center;'>2 - DADOS DO CONCEDENTE/PROPONENTE/VALORES</h4><p>";
        $html .= "<table border='1' width='100%'>";
        $html .= "<tr><td colspan='3'>CONCEDENTE:<p>{$concedente}</td><td colspan='3'>NOME DO ORGÃO:<p>{$nome_concedente}</td></tr>";
        $html .= "<tr><td colspan='6'>PROPONENTE:<p>{$proponente}</td></tr>";
        $html .= "<tr><td colspan='6'>RAZÃO SOCIAL DO PROPONENTE:<p>{$razao_social_proponente}</td></tr>";
        $html .= "<tr><td colspan='6'>ENDEREÇO DO PROPONENTE:<p>{$endereco}</td></tr>";
        $html .= "<tr><td>CIDADE:<p>{$cidade}</td><td>UF:<p>{$estado}</td><td>CÓDIGO CIDADE:<p>{$codigo_municipio}</td><td>CEP:<p>{$cep}</td><td>E.A.:<p>{$esfera_administrativa}</td><td>TELEFONE:<p>{$telefone}</td></tr>";
        $html .= "<tr><td  colspan='3'>BANCO:<p>{$banco}</td><td colspan='3'>AGÊNCIA:<p>{$agencia}</td><tr>";
        $html .= "<tr><td colspan='6'>NOME DO RESPONSÁVEL:<p>{$nome_responsavel}</td></tr>";
        $html .= "<tr><td colspan='3'>VALOR GLOBAL:</td><td colspan='3'>R$ " . number_format($valor_global, 2, ',', '.') . "</td></tr>";
        $html .= "<tr><td colspan='3'>VALOR CONTRAPARTIDA:</td><td colspan='3'>R$ " . number_format($contrapartida, 2, ',', '.') . "</td></tr>";
        $html .= "<tr><td colspan='3'>VALOR CONTRAPARTIDA FINANCEIRA:</td><td colspan='3'>R$ " . number_format($contrapartida_financeira, 2, ',', '.') . "</td></tr>";
        $html .= "<tr><td colspan='3'>VALOR CONTRAPARTIDA BENS:</td><td colspan='3'>R$ 0,00</td></tr>";
        $html .= "<tr><td colspan='3'>INÍCIO VIGÊNCIA:</td><td colspan='3'>" . date("d/m/Y", strtotime($inicio)) . "</td></tr>";
        $html .= "<tr><td colspan='3'>FIM VIGÊNCIA:</td><td colspan='3'>" . date("d/m/Y", strtotime($fim)) . "</td></tr>";
        $html .= "</table>";
        $html .= "<br><br>";
        $html .= "<pagebreak>";
        $html .= "<h4 style='text-align: center;'>3 - PLANO DE TRABALHO</h4><p>";
        foreach ($metas as $key => $meta) {
            $html .= "<h5 style='text-align: left;'>META N°" . ($key + 1) . "</h5>";
            $html .= "<table border='1' width='100%'>";
            $html .= "<tr><td colspan='6'>ESPECIFICAÇÃO: {$meta->especificacao}</td></tr>";
            $html .= "<tr><td colspan='3'>UNIDADE MEDIDA: {$meta->fornecimento}</td><td colspan='3'>QUANTIDADE: {$meta->quantidade}</td></tr>";
            $html .= "<tr><td colspan='3'>VALOR UNITÁRIO: R$" . number_format($meta->valorUnitario, 2, ',', '.') . "</td><td colspan='3'>VALOR TOTAL: R$" . number_format($meta->total, 2, ',', '.') . "</td></tr>";
            $html .= "<tr><td colspan='3'>INÍCIO: " . date("d/m/Y", strtotime($meta->data_inicio)) . "</td><td colspan='3'>FIM: " . date("d/m/Y", strtotime($meta->data_termino)) . "</td></tr>";
            $html .= "<tr><td colspan='2'>MUNICÍPIO:" . $this->get_cidade($meta->municipio_nome) . "</td><td colspan='2'>ESTADO: " . $this->get_estado($meta->UF) . "</td><td colspan='2'>CÓDIGO MUNC.: {$meta->municipio_nome}</td></tr>";
            $html .= "<tr><td colspan='6'>ENDEREÇO: {$meta->endereco}</td></tr>";
            $html .= "</table>";
            $html .= "<br>";

            $this->db->where('Meta_idMeta', $meta->idMeta);
            $etapas = $this->db->get('etapa');
            if ($etapas->num_rows > 0) {
                foreach ($etapas->result() as $key => $etapa) {
                    $html .= "<h5 style='text-align: left;'>ETAPA N°" . ($key + 1) . "</h5>";
                    $html .= "<table border='1' width='100%'>";
                    $html .= "<tr><td colspan='6'>ESPECIFICAÇÃO: {$etapa->especificacao}</td></tr>";
                    $html .= "<tr><td colspan='3'>UNIDADE MEDIDA: {$etapa->fornecimento}</td><td colspan='3'>QUANTIDADE: {$etapa->quantidade}</td></tr>";
                    $html .= "<tr><td colspan='3'>VALOR UNITÁTIO: R$" . number_format($etapa->valorUnitario, 2, ',', '.') . "</td><td colspan='3'>VALOR TOTAL: R$" . number_format($etapa->total, 2, ',', '.') . "</td></tr>";
                    $html .= "<tr><td colspan='3'>INÍCIO: " . date("d/m/Y", strtotime($etapa->data_inicio)) . "</td><td colspan='3'>FIM: " . date("d/m/Y", strtotime($etapa->data_termino)) . "</td></tr>";
                    $html .= "<tr><td colspan='2'>MUNICÍPIO:" . $this->get_cidade($meta->municipio_nome) . "</td><td colspan='2'>ESTADO: " . $this->get_estado($meta->UF) . "</td><td colspan='2'>CÓDIGO MUNC.: {$meta->municipio_nome}</td></tr>";
                    $html .= "<tr><td colspan='6'>ENDEREÇO: {$etapa->endereco}</td></tr>";
                    $html .= "</table>";
                }
            }

            $html .= "<br><br>";
            $html .= "<pagebreak>";
        }

        $html .= "<h4 style='text-align: center;'>4 - DESEMBOLSOS</h4><p>";

        foreach ($desembolsos as $key => $desebolso) {
            $meta = NULL;
            $this->db->where('Cronograma_idCronograma', $desebolso->idCronograma);
            $query = $this->db->get('cronograma_meta');
            if ($query->num_rows > 0) {
                $this->db->where('idMeta', $query->row(0)->Meta_idMeta);
                $query2 = $this->db->get('meta');
                if ($query2->num_rows > 0) {
                    $meta = $query2->row(0);
                }
            }

            $html .= "<table border='1' width='100%'>";
            $html .= "<tr><td colspan='6'>RESPONSÁVEL: {$desebolso->responsavel}</td></tr>";
            $html .= "<tr><td colspan='3'>MÊS: " . $this->retorna_nome_mes($desebolso->mes) . "</td><td colspan='3'>ANO: {$desebolso->ano}</td></tr>";
            $html .= "<tr><td colspan='3'>VALOR PARCELA: R$" . number_format($desebolso->parcela, 2, ',', '.') . "</td><td colspan='3'>TOTAL META: R$" . number_format($meta->total, 2, ',', '.') . "</td></tr>";
            $html .= "</table>";
        }
        $html .= "<br><br>";
        $html .= "<pagebreak>";

        $html .= "<h4 style='text-align: center;'>5 - PLANO DE APLICAÇÃO DETALHADO</h4><p>";
        $html .= "<table border='1' width='100%'>";
        foreach ($despesas as $despesa) {
            $html .= "<tr><td colspan='8'>DESCRIÇÃO: {$despesa->descricao}</td></tr>";
            if ($despesa->natureza_aquisicao == 1) {
                $html .= "<tr><td colspan='4'>NATUREZA DA AQUISIÇÃO: Recursos do convênio</td><td colspan='4'>NATUREZA DA DESPESA: {$despesa->natureza_despesa}</tr>";
            } else {
                $html .= "<tr><td colspan='4'>NATUREZA DA AQUISIÇÃO: Contrapartida bens e serviços</td><td colspan='4'>NATUREZA DA DESPESA: {$despesa->natureza_despesa}</tr>";
            }
            $html .= "<tr><td colspan='8'>ENDEREÇO DE LOCALIZAÇÃO: {$despesa->endereco}</td></tr>";
            $html .= "<tr><td colspan='1'>CEP: {$despesa->cep}</td><td colspan='1'>UF: " . $this->get_estado($despesa->UF) . "</td><td colspan='4'>CÓDIGO DO MUNICÍPIO: {$despesa->municipio}</td><td colspan='2'>MUNICÍPIO: " . $this->get_cidade($despesa->municipio) . "</td></tr>";
            $html .= "<tr><td colspan='2'>UNIDADE: {$despesa->fornecimento}</td><td colspan='2'>QUANTIDADE: {$despesa->quantidade}</td><td colspan='2'>V. UNITÁRIO: R$" . number_format($despesa->valor_unitario, 2, ',', '.') . "</td><td colspan='2'>V. TOTAL: R$" . number_format($despesa->total, 2, ',', '.') . "</td></tr>";
            $html .= "<tr><td colspan='8'>OBSERVAÇÃO: {$despesa->observacao}</td></tr>";
            $html .= "<tr><td colspan='8'></td></tr>";
        }
        $html .= "</table>";
        $html .= "<br><br>";
        $html .= "</body>";

        $mpdf->SetDefaultFontSize(8);
        $mpdf->SetFontSize(8);
        $mpdf->WriteHTML($html);

        $mpdf->Output();

        exit();
    }

    function get_estado($codigo) {
        $this->db->where('cod_estados', $codigo);
        $query = $this->db->get('estados');

        if ($query->num_rows > 0) {
            return $query->row(0)->sigla;
        }

        return "-";
    }

    public function retorna_nome_mes($m) {
        $mes = NULL;
        switch ($m) {
            case 1: $mes = "Janeiro";
                break;
            case 2: $mes = "Fevereiro";
                break;
            case 3: $mes = "Março";
                break;
            case 4: $mes = "Abril";
                break;
            case 5: $mes = "Maio";
                break;
            case 6: $mes = "Junho";
                break;
            case 7: $mes = "Julho";
                break;
            case 8: $mes = "Agosto";
                break;
            case 9: $mes = "Setembro";
                break;
            case 10: $mes = "Outubro";
                break;
            case 11: $mes = "Novembro";
                break;
            case 12: $mes = "Dezembro";
                break;
        }

        return $mes;
    }

    function get_cidade($codigo) {
        $this->db->where('Codigo', $codigo);
        $query = $this->db->get('cidades_siconv');

        if ($query->num_rows > 0) {
            return $query->row(0)->Nome;
        }

        return "-";
    }

    function escolher_proponente() {
        $this->load->model('programa_model');

        if ($this->input->get_post('login', TRUE) !== false && $this->input->get_post('senha', TRUE) !== false) {

            $this->obter_paginaLogin_escolha($this->input->get_post('login', TRUE), $this->input->get_post('senha', TRUE), 'escolher_proponente');

            $pagina = "https://www.convenios.gov.br/siconv/proposta/IncluirDadosProposta/IncluirDadosProposta.do";
            $documento = $this->obter_pagina($pagina);

            $documento = $this->removeSpaceSurplus($documento);

            $txt1 = $this->getTextBetweenTags($documento, "<table> <tr>", "<\/tr> <\/table>");
            if (count($txt1) > 0)
                $data['tabela'] = $txt1[0];
            else { //quando o cpf esta vinculado a uma so prefeitura
                $pagina = "https://www.convenios.gov.br/siconv/participe/VisualizarCadastramento/VisualizarCadastramento.do";
                $documento = $this->obter_pagina($pagina);

                $documento = $this->removeSpaceSurplus($documento);
                $txt2 = $this->getTextBetweenTags($documento, "<div class=\"nome\">", "<\/div>");
                $txt3 = $this->getTextBetweenTags($documento, "<div class=\"identificacao\">", "<\/div>");

                if (count($txt2) > 0) {
                    $data['tabela'] = '';
                    foreach ($txt2 as $chave => $nome) {
                        $aux1 = explode(" ", trim($txt3[$chave]));
                        $cnpj_cidade = $aux1[1];
                        $data['tabela'] .= '<tr><td style="font-family: Arial; font-size: 14px; font-weight: bold; color: #003399;">
					<input type="radio" name="cnpjProponente" value="' . $cnpj_cidade . '"  id="escolherProponenteCnpjProponente">
					' . $txt3[$chave] . ' - ' . $nome . '</td> </tr>';
                    }
                } else {
                    $txt1 = $this->getTextBetweenTags($documento, "align=right>Identificação<\/td> <td class=\"field\" colspan=2>", "<\/td>");
                    $nome = $this->getTextBetweenTags($documento, "<td class=\"label\">Nome<\/td> <td class=\"field\" colspan=4>", "<\/td>");
                    $carac = array(".", "-", "/", "&nbsp;");
                    $cnpj = str_replace($carac, "", trim($txt1[0]));
                    $nome = str_replace("&nbsp;", "", trim($nome[0]));

                    $data2 = array(
                        'usuario_siconv' => $this->input->get_post('login', TRUE),
                        'senha_siconv' => $this->encripta($this->input->get_post('senha', TRUE)),
                        'cidade' => $nome,
                        'proponente' => $cnpj
                    );
                    if ($nome == '') {
                        $this->alert("Verifique se o site do siconv se encontra no ar. Caso esteja, entre em contato com a Physis Brasil.");
                        $this->voltaPagina();
                    }
                    $inserido = $this->proposta_model->update_record($this->input->get_post('id', TRUE), $data2);
                    $this->alert("Alterado para a cidade de: " . $nome);
                    $this->encaminha('proposta_aberta');
                }
            }

            $data['usuario_siconv'] = $this->input->get_post('login', TRUE);
            $data['senha_siconv'] = $this->encripta($this->input->get_post('senha', TRUE));
            $data['id'] = $this->input->get_post('id', TRUE);
            $data['login'] = $this->login;
            $data['title'] = "Physis - Finaliza Trabalho";
            $data['main'] = 'in/escolher_proponente';

            $this->load->view('in/template', $data);
        } else {
            $data['title'] = 'Physis - gerenciamento de usuários';

            $data['main'] = 'in/escolher_login_usuario';
            $data['login'] = $this->login;
            $data['id'] = $this->input->get_post('id', TRUE);
            $this->load->vars($data);
            $this->load->view('in/template');
        }
    }

    function escolher_proponente_padrao() {
        $this->load->model('programa_model');
        $this->load->model('proposta_model');

        if ($this->input->get_post('login', TRUE) !== false && $this->input->get_post('senha', TRUE) !== false) {
            $flag_incluir = false;
            $this->obter_paginaLogin_escolha($this->input->get_post('login', TRUE), $this->input->get_post('senha', TRUE), 'escolher_proponente_padrao?id=' . $this->input->get_post('id', TRUE));

            $pagina = "https://www.convenios.gov.br/siconv/proposta/IncluirDadosProposta/IncluirDadosProposta.do";
            $documento = $this->obter_pagina($pagina);

            $documento = $this->removeSpaceSurplus($documento);

            $txt1 = $this->getTextBetweenTags($documento, "<table> <tr>", "<\/tr> <\/table>");
            if (count($txt1) > 0)
                $data['tabela'] = $txt1[0];
            else { //quando o cpf esta vinculado a uma so prefeitura
                $pagina = "https://www.convenios.gov.br/siconv/participe/VisualizarCadastramento/VisualizarCadastramento.do";
                $documento = $this->obter_pagina($pagina);

                $documento = $this->removeSpaceSurplus($documento);
                $txt2 = $this->getTextBetweenTags($documento, "<div class=\"nome\">", "<\/div>");
                $txt3 = $this->getTextBetweenTags($documento, "<div class=\"identificacao\">", "<\/div>");

                if (count($txt2) > 0) {
                    $data['tabela'] = '';
                    foreach ($txt2 as $chave => $nome) {
                        $aux1 = explode(" ", trim($txt3[$chave]));
                        $cnpj_cidade = $aux1[1];
                        $data['tabela'] .= '<tr><td style="font-family: Arial; font-size: 14px; font-weight: bold; color: #003399;">
					<input type="radio" name="cnpjProponente" value="' . $cnpj_cidade . '"  id="escolherProponenteCnpjProponente">
					' . $txt3[$chave] . ' - ' . $nome . '</td> </tr>';
                    }
                } else {
                    $flag_incluir = true;
                    $txt1 = $this->getTextBetweenTags($documento, "align=right>Identificação<\/td> <td class=\"field\" colspan=2>", "<\/td>");
                    $carac = array(".", "-", "/", "&nbsp;");
                    $cnpj = str_replace($carac, "", trim($txt1[0]));
                    $data['tabela'] = "<input type=\"hidden\" name=\"cnpjProponente\" value=\"$cnpj\">";

                    $proposta = $this->proposta_model->get_by_id($this->input->get_post('id', TRUE));
                    $data['title'] = "Physis - Gerencia Usuários";
                    $data['bancos'] = $this->proposta_model->obter_bancos();
                    $data['proposta'] = $proposta;
                    $data['usuario_siconv'] = $this->input->get_post('login', TRUE);
                    $data['senha_siconv'] = $this->encripta($this->input->get_post('senha', TRUE));
                    $data['cnpjProponente'] = $cnpj;
                    $data['id'] = $this->input->get_post('id', TRUE);
                    $data['login'] = $this->login;
                    $data['title'] = "Physis - Finaliza Trabalho";
                    $data['main'] = 'in/incluir_proposta';
                    $this->load->view('in/template', $data);
                }
            }
            if ($flag_incluir == false) {
                $data['orgaos'] = $this->programa_model->get_all_orgaos();
                $data['usuario_siconv'] = $this->input->get_post('login', TRUE);
                $data['senha_siconv'] = $this->encripta($this->input->get_post('senha', TRUE));
                $data['id'] = $this->input->get_post('id', TRUE);
                $data['login'] = $this->login;
                $data['title'] = "Physis - Finaliza Trabalho";
                $data['main'] = 'in/escolher_proponente_padrao';

                $this->load->view('in/template', $data);
            }
        } else {
            $data['title'] = 'Physis - gerenciamento de usuários';

            $data['main'] = 'in/escolher_login_usuario_padrao';
            $data['login'] = $this->login;
            $data['id'] = $this->input->get_post('id', TRUE);
            $this->load->vars($data);
            $this->load->view('in/template');
        }
    }

    function escolher_proponente_master() {
        $this->load->model('programa_model');

        if ($this->input->get_post('login', TRUE) !== false && $this->input->get_post('senha', TRUE) !== false) {

            $this->obter_paginaLogin_escolha($this->input->get_post('login', TRUE), $this->input->get_post('senha', TRUE), 'escolher_proponente_master?id=' . $this->input->get_post('id', TRUE));
            $pagina = "https://www.convenios.gov.br/siconv/proposta/IncluirDadosProposta/IncluirDadosProposta.do";
            $documento = $this->obter_pagina($pagina);
            $documento = $this->removeSpaceSurplus($documento);

            $txt1 = $this->getTextBetweenTags($documento, "<table> <tr>", "<\/tr> <\/table>");
            if (count($txt1) > 0)
                $data['tabela'] = $txt1[0];
            else { //quando o cpf esta vinculado a uma so prefeitura
                $pagina = "https://www.convenios.gov.br/siconv/participe/VisualizarCadastramento/VisualizarCadastramento.do";
                $documento = $this->obter_pagina($pagina);
                $documento = $this->removeSpaceSurplus($documento);

                $txt1 = $this->getTextBetweenTags($documento, "align=right>Identificação<\/td> <td class=\"field\" colspan=2>", "<\/td>");
                $carac = array(".", "-", "/", "&nbsp;");
                $cnpj = str_replace($carac, "", trim($txt1[0]));
                $data['tabela'] = "<input type=\"hidden\" name=\"cnpjProponente\" value=\"$cnpj\">";
            }
            $data['orgaos'] = $this->programa_model->get_all_orgaos();
            $data['usuario_siconv'] = $this->input->get_post('login', TRUE);
            $data['senha_siconv'] = $this->encripta($this->input->get_post('senha', TRUE));
            $data['id'] = $this->input->get_post('id', TRUE);
            $data['login'] = $this->login;
            $data['title'] = "Physis - Finaliza Trabalho";
            $data['main'] = 'in/escolher_proponente_master';

            $this->load->view('in/template', $data);
        } else {
            $data['title'] = 'Physis - gerenciamento de usuários';

            $data['main'] = 'in/escolher_login_usuario_master';
            $data['login'] = $this->login;
            $data['id'] = $this->input->get_post('id', TRUE);
            $this->load->vars($data);
            $this->load->view('in/template');
        }
    }

    function selecionar_programas() {
        $this->load->model('trabalho_model');
        $this->load->model('proposta_model');
        $this->load->model('programa_model');
        if ($this->input->get_post('cnpjProponente', TRUE) !== false && $this->input->get_post('orgao', TRUE) !== false) {
            if ($this->input->get_post('orgao', TRUE) === '') {
                $this->alert("Escolha o órgão");
                $this->voltaPagina();
            }
            //echo $this->desencripta($this->input->get_post('senha_siconv', TRUE)); die();
            $pagina = "https://www.convenios.gov.br/siconv/IncluirProgramasProposta/EscolherProponenteEscolherProponente.do";
            $this->obter_paginaLogin_escolha($this->input->get_post('usuario_siconv', TRUE), $this->desencripta($this->input->get_post('senha_siconv', TRUE)), '../gestor/alterar_senha?id_programa=' . $this->input->get_post('id', TRUE));
            $fields = array(
                'invalidatePageControlCounter' => '1',
                'cnpjProponente' => $this->input->get_post('cnpjProponente', TRUE)
            );
            $fields_string = null;
            foreach ($fields as $key => $value) {
                $fields_string .= $key . '=' . $value . '&';
            }
            rtrim($fields_string, '&');

            $this->obter_pagina_post($pagina, $fields, $fields_string);
            $pagina = "https://www.convenios.gov.br/siconv/IncluirProgramasProposta/ConsultarProgramasConsultar.do";
            $fields = array(
                'orgao' => $this->input->get_post('orgao', TRUE),
                'qualificacaoProponenteColAsArray' => '',
                'numeroEmendaParlamentar' => '',
                'anoPrograma' => '',
                'codigoPrograma' => '',
                'nomePrograma' => '',
                'descricaoPrograma' => '',
                'objetoPrograma' => '',
                'modalidade' => ''
            );

            $fields_string = null;
            foreach ($fields as $key => $value) {
                $fields_string .= $key . '=' . $value . '&';
            }
            rtrim($fields_string, '&');
            $documento = $this->obter_pagina_post($pagina, $fields, $fields_string);
            $documento = $this->removeSpaceSurplus($documento);

            $txt1 = $this->getTextBetweenTags($documento, "<table id=\"row\">", "<\/table>");
            $data['trabalho_model'] = $this->trabalho_model;
            $data['cnpjProponente'] = $this->input->get_post('cnpjProponente', TRUE);
            $data['usuario_siconv'] = $this->input->get_post('usuario_siconv', TRUE);
            $data['senha_siconv'] = $this->input->get_post('senha_siconv', TRUE);
            $data['orgao'] = $this->input->get_post('orgao', TRUE);
            $data['id'] = $this->input->get_post('id', TRUE);
            $data['tabela'] = $txt1[0];
            $data['login'] = $this->login;
            $data['title'] = "Physis - Finaliza Trabalho";
            $data['main'] = 'in/selecionar_programas';

            $this->load->view('in/template', $data);
        } else {

            if ($this->usuario_logado->tipoPessoa == 6) {
                $proposta = $this->proposta_model->obter_cnpj_aberto($this->usuario_logado->idPessoa);
                $this->obter_paginaLogin_escolha($proposta->usuario_siconv, $this->desencripta($proposta->senha_siconv), 'alterar_senha_master');
                $data['usuario_siconv'] = $proposta->usuario_siconv;
                $data['senha_siconv'] = $proposta->senha_siconv;
                $data['cnpjProponente'] = $proposta->cnpj;
            } else {
                $this->obter_paginaLogin_escolha($this->input->get_post('login', TRUE), $this->desencripta($this->input->get_post('senha', TRUE)), 'alterar_senha_master');
                $data['usuario_siconv'] = $this->input->get_post('usuario_siconv', TRUE);
                $data['senha_siconv'] = $this->input->get_post('senha_siconv', TRUE);
                $data['cnpjProponente'] = $this->input->get_post('cnpjProponente', TRUE);
            }
            $data['orgaos'] = $this->programa_model->get_all_orgaos();

            $data['id'] = $this->input->get_post('id', TRUE);
            $data['login'] = $this->login;
            $data['title'] = "Physis - Finaliza Trabalho";
            $data['main'] = 'in/escolher_proponente_inicial';

            $this->load->view('in/template', $data);
        }
    }

    function alterar_senha_master() {
        $this->load->model('proposta_model');
        $cnpj = $this->proposta_model->obter_cnpj_aberto($this->usuario_logado->idPessoa);
        if ($this->input->get_post('senha', TRUE) !== false) {

            $this->obter_paginaLogin_escolha($cnpj->usuario_siconv, $this->input->get_post('senha', TRUE), 'alterar_senha_master');

            $data1 = array(
                'idPessoa' => $this->usuario_logado->idPessoa,
                'senha_siconv' => $this->encripta($this->input->get_post('senha', TRUE))
            );

            $inserido = $this->proposta_model->altera_cnpj_aberto($data1);
            $this->alert("Senha alterada");
            $this->encaminha('proposta_aberta');
        } else {
            $data['title'] = 'Physis - alterar senha';
            $data['main'] = 'in/alterar_senha_master';
            $data['usuario'] = $cnpj->usuario_siconv;
            $data['login'] = $this->login;
            $this->load->vars($data);
            $this->load->view('in/template');
        }
    }

    function escolhe_programa() {
        $this->load->model('proposta_model');
        $this->load->model('cidades_model');
        $this->load->model('trabalho_model');

        if ($this->input->get_post('objetos', TRUE) === false) {
            $this->alert("Escolha pelo menos um objeto");
            $this->voltaPagina();
        }

        $remotePageUrl = 'https://www.convenios.gov.br/siconv/IncluirProgramasProposta/ConsultarProgramasConsultar.do';
        $remotePageUrl1 = "https://www.convenios.gov.br/siconv/ForwardAction.do?modulo=Principal&path=/MostraPrincipalConsultarPrograma.do?Usr=guest&Pwd=guest";
        //$this->obter_paginaLogin($this->input->get_post('usuario_siconv', TRUE), $this->desencripta($this->input->get_post('senha_siconv', TRUE)));
        $this->obter_paginaLogin_escolha($this->input->get_post('usuario_siconv', TRUE), $this->desencripta($this->input->get_post('senha_siconv', TRUE)), 'escolhe_programa');
        $this->obter_pagina($remotePageUrl1);
        $this->obter_pagina($remotePageUrl);
        $documento = $this->obter_pagina("https://www.convenios.gov.br/siconv/programa/DetalharPrograma/DetalharPrograma.do?id=" . $this->input->get_post('idRowSelectionAsArray', TRUE));

        $documento = $this->removeSpaceSurplus($documento);

        $txt1 = $this->getTextBetweenTags($documento, "Nome do Programa<\/td> <td class=\"field\">", "<\/td>");
        $txt2 = $this->getTextBetweenTags($documento, "Código do Programa<\/td> <td class=\"field\">", "<\/td>");

        $objetos_string = '';
        foreach ($this->input->get_post('objetos', TRUE) as $key => $value) {
            if ($key == 0)
                $objetos_string .= $value;
            else
                $objetos_string .= ',' . $value;
        }

        $data2 = array(
            'nome_programa' => trim($txt1[0]),
            'codigo_programa' => trim($txt2[0]),
            'programa' => "https://www.convenios.gov.br/siconv/programa/DetalharPrograma/DetalharPrograma.do?id=" . $this->input->get_post('idRowSelectionAsArray', TRUE),
            'usuario_siconv' => $this->input->get_post('usuario_siconv', TRUE),
            'senha_siconv' => $this->input->get_post('senha_siconv', TRUE),
            'orgao' => $this->input->get_post('orgao', TRUE),
            'id_programa' => $this->input->get_post('idRowSelectionAsArray', TRUE),
            'objeto' => $objetos_string
        );

        $inserido = $this->proposta_model->atualiza_proposta($this->input->get_post('id', TRUE), $data2);

        $this->alert("Proposta anexada");
        $this->encaminha('proposta_aberta');
    }

    function escolhe_programa_master() {
        $this->load->model('proposta_model');
        $this->load->model('cidades_model');
        $this->load->model('trabalho_model');

        if ($this->input->get_post('objetos', TRUE) === false) {
            $this->alert("Escolha pelo menos um objeto");
            $this->voltaPagina();
        }

        $remotePageUrl = 'https://www.convenios.gov.br/siconv/IncluirProgramasProposta/ConsultarProgramasConsultar.do';
        $remotePageUrl1 = "https://www.convenios.gov.br/siconv/ForwardAction.do?modulo=Principal&path=/MostraPrincipalConsultarPrograma.do?Usr=guest&Pwd=guest";
        //$this->obter_paginaLogin($this->input->get_post('usuario_siconv', TRUE), $this->desencripta($this->input->get_post('senha_siconv', TRUE)));
        $this->obter_paginaLogin_escolha($this->input->get_post('usuario_siconv', TRUE), $this->desencripta($this->input->get_post('senha_siconv', TRUE)), 'escolhe_programa_master');
        $this->obter_pagina($remotePageUrl1);
        $this->obter_pagina($remotePageUrl);
        $documento = $this->obter_pagina("https://www.convenios.gov.br/siconv/programa/DetalharPrograma/DetalharPrograma.do?id=" . $this->input->get_post('idRowSelectionAsArray', TRUE));

        $documento = $this->removeSpaceSurplus($documento);

        $txt1 = $this->getTextBetweenTags($documento, "Nome do Programa<\/td> <td class=\"field\">", "<\/td>");
        $txt2 = $this->getTextBetweenTags($documento, "Código do Programa<\/td> <td class=\"field\">", "<\/td>");
        $cidade_cnpj = $this->cidades_model->obter_cidade_por_cnpj($this->input->get_post('cnpjProponente', TRUE));

        $objetos_string = '';
        foreach ($this->input->get_post('objetos', TRUE) as $key => $value) {
            if ($key == 0)
                $objetos_string .= $value;
            else
                $objetos_string .= ',' . $value;
        }

        $data2 = array(
            'nome_programa' => trim($txt1[0]),
            'codigo_programa' => trim($txt2[0]),
            'programa' => "https://www.convenios.gov.br/siconv/programa/DetalharPrograma/DetalharPrograma.do?id=" . $this->input->get_post('idRowSelectionAsArray', TRUE),
            'usuario_siconv' => $this->input->get_post('usuario_siconv', TRUE),
            'senha_siconv' => $this->input->get_post('senha_siconv', TRUE),
            'orgao' => $this->input->get_post('orgao', TRUE),
            'proponente' => $this->input->get_post('cnpjProponente', TRUE),
            'cidade' => $cidade_cnpj,
            'id_programa' => $this->input->get_post('idRowSelectionAsArray', TRUE),
            'objeto' => $objetos_string
        );

        $inserido = $this->proposta_model->atualiza_proposta($this->input->get_post('id', TRUE), $data2);

        $this->alert("Programa anexado");
        if ($this->usuario_logado->tipoPessoa == 2)
            $this->encaminha('../gestor/gerencia_proposta');
        else
            $this->encaminha('proposta_aberta');
    }

    function selecionar_objetos() {
        $this->load->model('trabalho_model');
        if ($this->input->get_post('cnpjProponente', TRUE) !== false && $this->input->get_post('orgao', TRUE) !== false) {
            if ($this->input->get_post('idRowSelectionAsArray', TRUE) === false) {
                $this->alert("Escolha o programa");
                $this->voltaPagina();
            }
            $this->load->model('proposta_model');

            $cnpjProponente = $this->input->get_post('cnpjProponente', TRUE);
            $usuario_siconv = $this->input->get_post('usuario_siconv', TRUE);
            $senha_siconv = $this->input->get_post('senha_siconv', TRUE);
            $orgao = $this->input->get_post('orgao', TRUE);
            $id_programa = $this->input->get_post('idRowSelectionAsArray', TRUE);

            $this->obter_paginaLogin_escolha($usuario_siconv, $this->desencripta($senha_siconv), "");

            $pagina = "https://www.convenios.gov.br/siconv/IncluirProgramasProposta/EscolherProponenteEscolherProponente.do";
            $fields = array(
                'invalidatePageControlCounter' => '1',
                'cnpjProponente' => $cnpjProponente
            );
            $fields_string = null;
            foreach ($fields as $key => $value) {
                $fields_string .= $key . '=' . $value . '&';
            }
            rtrim($fields_string, '&');
            utf8_decode($this->obter_pagina("https://www.convenios.gov.br/siconv/proposta/IncluirDadosProposta/IncluirDadosProposta.do"));
            //echo $pagina."?".$fields_string;
            utf8_decode($this->obter_pagina_post($pagina, $fields, $fields_string));

            //consultar programas****************************************************
            $pagina = "https://www.convenios.gov.br/siconv/IncluirProgramasProposta/ConsultarProgramasConsultar.do";
            $fields = array(
                'orgao' => $orgao,
                'qualificacaoProponenteColAsArray' => '',
                'numeroEmendaParlamentar' => '',
                'anoPrograma' => '',
                'codigoPrograma' => '',
                'nomePrograma' => '',
                'descricaoPrograma' => '',
                'objetoPrograma' => '',
                'modalidade' => ''
            );
            $fields_string = null;
            foreach ($fields as $key => $value) {
                $fields_string .= $key . '=' . $value . '&';
            }
            rtrim($fields_string, '&');
            //echo $pagina."?".$fields_string;
            utf8_decode($this->obter_pagina_post($pagina, $fields, $fields_string));
//$this->obter_pagina_post($pagina, $fields, $fields_string);
            //escolhendo programas

            $pagina = "https://www.convenios.gov.br/siconv/IncluirProgramasProposta/SelecionarProgramasSelecionar.do";
            $fields = array(
                'idRowSelectionAsArray' => $id_programa
            );
            $fields_string = null;
            foreach ($fields as $key => $value) {
                $fields_string .= $key . '=' . $value . '&';
            }
            rtrim($fields_string, '&');
            $documento = utf8_decode($this->obter_pagina_post($pagina, $fields, $fields_string));
            //echo $pagina."?".$fields_string;
            //echo $documento;
            $documento = $this->removeSpaceSurplus($documento);
            $txt1 = $this->getTextBetweenTags($documento, "<a href=\"javascript:document.location='", "';\" class=\"buttonLink\">Selecionar Objetos");

            //página inicial para a inserção dos primeiros dados******************************************************
            $documento = utf8_decode($this->obter_pagina("https://www.convenios.gov.br" . $txt1[0]));
            //echo $documento;
            $documento = $this->removeSpaceSurplus($documento);

            $objetos_tabela = $this->getTextBetweenTags($documento, "<tr class=\"objetos\" id=\"tr-salvarObjetos\" >", "<\/tr>");

            $data['tabela'] = utf8_encode($objetos_tabela[0]);
            $data['trabalho_model'] = $this->trabalho_model;
            $data['cnpjProponente'] = $this->input->get_post('cnpjProponente', TRUE);
            $data['usuario_siconv'] = $this->input->get_post('usuario_siconv', TRUE);
            $data['senha_siconv'] = $this->input->get_post('senha_siconv', TRUE);
            $data['orgao'] = $this->input->get_post('orgao', TRUE);
            $data['idRowSelectionAsArray'] = $this->input->get_post('idRowSelectionAsArray', TRUE);
            $data['id'] = $this->input->get_post('id', TRUE);

            $data['login'] = $this->login;
            $data['title'] = "Physis - Finaliza Trabalho";
            $data['main'] = 'in/selecionar_objetos';

            $this->load->view('in/template', $data);
        } else {
            $this->voltaPagina();
        }
    }

    function selecionar_objetos_master() {
        $this->load->model('trabalho_model');
        if ($this->input->get_post('cnpjProponente', TRUE) !== false && $this->input->get_post('orgao', TRUE) !== false) {
            if ($this->input->get_post('idRowSelectionAsArray', TRUE) === false) {
                $this->alert("Escolha o programa");
                $this->voltaPagina();
            }
            $this->load->model('proposta_model');

            $cnpjProponente = $this->input->get_post('cnpjProponente', TRUE);
            $usuario_siconv = $this->input->get_post('usuario_siconv', TRUE);
            $senha_siconv = $this->input->get_post('senha_siconv', TRUE);
            $orgao = $this->input->get_post('orgao', TRUE);
            $id_programa = $this->input->get_post('idRowSelectionAsArray', TRUE);

            $this->obter_paginaLogin_exporta($usuario_siconv, $this->desencripta($senha_siconv), "");
            $pagina = "https://www.convenios.gov.br/siconv/IncluirProgramasProposta/EscolherProponenteEscolherProponente.do";
            $fields = array(
                'invalidatePageControlCounter' => '1',
                'cnpjProponente' => $cnpjProponente
            );
            $fields_string = null;
            foreach ($fields as $key => $value) {
                $fields_string .= $key . '=' . $value . '&';
            }
            rtrim($fields_string, '&');
            utf8_decode($this->obter_pagina("https://www.convenios.gov.br/siconv/proposta/IncluirDadosProposta/IncluirDadosProposta.do"));
            //echo $pagina."?".$fields_string;
            utf8_decode($this->obter_pagina_post($pagina, $fields, $fields_string));

            //consultar programas****************************************************
            $pagina = "https://www.convenios.gov.br/siconv/IncluirProgramasProposta/ConsultarProgramasConsultar.do";
            $fields = array(
                'orgao' => $orgao,
                'qualificacaoProponenteColAsArray' => '',
                'numeroEmendaParlamentar' => '',
                'anoPrograma' => '',
                'codigoPrograma' => '',
                'nomePrograma' => '',
                'descricaoPrograma' => '',
                'objetoPrograma' => '',
                'modalidade' => ''
            );
            $fields_string = null;
            foreach ($fields as $key => $value) {
                $fields_string .= $key . '=' . $value . '&';
            }
            rtrim($fields_string, '&');
            //echo $pagina."?".$fields_string;
            utf8_decode($this->obter_pagina_post($pagina, $fields, $fields_string));
//$this->obter_pagina_post($pagina, $fields, $fields_string);
            //escolhendo programas

            $pagina = "https://www.convenios.gov.br/siconv/IncluirProgramasProposta/SelecionarProgramasSelecionar.do";
            $fields = array(
                'idRowSelectionAsArray' => $id_programa
            );
            $fields_string = null;
            foreach ($fields as $key => $value) {
                $fields_string .= $key . '=' . $value . '&';
            }
            rtrim($fields_string, '&');
            $documento = utf8_decode($this->obter_pagina_post($pagina, $fields, $fields_string));
            //echo $pagina."?".$fields_string;
            //echo $documento;
            $documento = $this->removeSpaceSurplus($documento);
            $txt1 = $this->getTextBetweenTags($documento, "<a href=\"javascript:document.location='", "';\" class=\"buttonLink\">Selecionar Objetos");

            //página inicial para a inserção dos primeiros dados******************************************************
            $documento = utf8_decode($this->obter_pagina("https://www.convenios.gov.br" . $txt1[0]));
            //echo $documento;
            $documento = $this->removeSpaceSurplus($documento);

            $objetos_tabela = $this->getTextBetweenTags($documento, "<tr class=\"objetos\" id=\"tr-salvarObjetos\" >", "<\/tr>");

            $data['tabela'] = utf8_encode($objetos_tabela[0]);
            $data['trabalho_model'] = $this->trabalho_model;
            $data['cnpjProponente'] = $this->input->get_post('cnpjProponente', TRUE);
            $data['usuario_siconv'] = $this->input->get_post('usuario_siconv', TRUE);
            $data['senha_siconv'] = $this->input->get_post('senha_siconv', TRUE);
            $data['orgao'] = $this->input->get_post('orgao', TRUE);
            $data['idRowSelectionAsArray'] = $this->input->get_post('idRowSelectionAsArray', TRUE);
            $data['id'] = $this->input->get_post('id', TRUE);

            $data['login'] = $this->login;
            $data['title'] = "Physis - Finaliza Trabalho";
            $data['main'] = 'in/selecionar_objetos_master';

            $this->load->view('in/template', $data);
        } else {
            $this->voltaPagina();
        }
    }

    function selecionar_programas_master() {
        $this->load->model('trabalho_model');
        if ($this->input->get_post('cnpjProponente', TRUE) !== false && $this->input->get_post('orgao', TRUE) !== false) {
            if ($this->input->get_post('orgao', TRUE) === '') {
                $this->alert("Escolha o órgão");
                $this->voltaPagina();
            }
            //echo $this->desencripta($this->input->get_post('senha_siconv', TRUE)); die();
            $pagina = "https://www.convenios.gov.br/siconv/IncluirProgramasProposta/EscolherProponenteEscolherProponente.do";
            $this->obter_paginaLogin_escolha($this->input->get_post('usuario_siconv', TRUE), $this->desencripta($this->input->get_post('senha_siconv', TRUE)), 'selecionar_programas_master?id=' . $this->input->get_post('id', TRUE));
            $fields = array(
                'invalidatePageControlCounter' => '1',
                'cnpjProponente' => $this->input->get_post('cnpjProponente', TRUE)
            );
            $fields_string = null;
            foreach ($fields as $key => $value) {
                $fields_string .= $key . '=' . $value . '&';
            }
            rtrim($fields_string, '&');

            $this->obter_pagina_post($pagina, $fields, $fields_string);
            $pagina = "https://www.convenios.gov.br/siconv/IncluirProgramasProposta/ConsultarProgramasConsultar.do";
            $fields = array(
                'orgao' => $this->input->get_post('orgao', TRUE),
                'qualificacaoProponenteColAsArray' => '',
                'numeroEmendaParlamentar' => '',
                'anoPrograma' => '',
                'codigoPrograma' => '',
                'nomePrograma' => '',
                'descricaoPrograma' => '',
                'objetoPrograma' => '',
                'modalidade' => ''
            );

            $fields_string = null;
            foreach ($fields as $key => $value) {
                $fields_string .= $key . '=' . $value . '&';
            }
            rtrim($fields_string, '&');
            $documento = $this->obter_pagina_post($pagina, $fields, $fields_string);
            $documento = $this->removeSpaceSurplus($documento);

            $txt1 = $this->getTextBetweenTags($documento, "<table id=\"row\">", "<\/table>");
            $data['trabalho_model'] = $this->trabalho_model;
            $data['cnpjProponente'] = $this->input->get_post('cnpjProponente', TRUE);
            $data['usuario_siconv'] = $this->input->get_post('usuario_siconv', TRUE);
            $data['senha_siconv'] = $this->input->get_post('senha_siconv', TRUE);
            $data['orgao'] = $this->input->get_post('orgao', TRUE);
            $data['id'] = $this->input->get_post('id', TRUE);
            $data['tabela'] = $txt1[0];
            $data['login'] = $this->login;
            $data['title'] = "Physis - Finaliza Trabalho";
            $data['main'] = 'in/selecionar_programas_master';

            $this->load->view('in/template', $data);
        } else {
            $this->voltaPagina();
        }
    }

    function adiciona_usuario_trabalho() {

        $this->load->model('usuario_model');
        $data['title'] = "Physis - Adicionar Usuário";
        $data['main'] = 'in/adiciona_usuario';
        $data['login'] = $this->login;
        $data['tipo_usuario'] = $this->input->get_post('tipo_usuario', TRUE);
        $data['tipos'] = $this->usuario_model->get_all_tipos_usuarios();
        $this->load->view('in/template', $data);
    }

    function incluir_proposta() {
        $usuario = $this->usuario_logado->id_usuario;
        $this->load->model('proposta_model');
        $this->load->model('cidades_model');
        $this->load->model('trabalho_model');

        if ($this->input->get_post('edit', TRUE) !== false) {

            if ($this->input->get_post('data', TRUE) !== false) {

                $data_programa = implode("-", array_reverse(explode("/", trim($this->input->get_post('data', TRUE)))));
                $data_inicio = implode("-", array_reverse(explode("/", $this->input->get_post('inicioVigencia', TRUE))));
                $data_termino = implode("-", array_reverse(explode("/", $this->input->get_post('terminoVigencia', TRUE))));

                $percentual = str_replace(".", "", $this->input->get_post('percentual', TRUE));
                $percentual = str_replace(",", ".", $percentual);

                $total_contrapartida = str_replace(".", "", $this->input->get_post('valorContrapartida', TRUE));
                $total_contrapartida = str_replace(",", ".", $total_contrapartida);
                $contrapartida_financeira = str_replace(".", "", $this->input->get_post('valorContrapartidaFinanceira', TRUE));
                $contrapartida_financeira = str_replace(",", ".", $contrapartida_financeira);
                $contrapartida_bens = str_replace(".", "", $this->input->get_post('valorContrapartidaBensServicos', TRUE));
                $contrapartida_bens = str_replace(",", ".", $contrapartida_bens);
                $repasse = str_replace(".", "", $this->input->get_post('valorRepasse', TRUE));
                $repasse = str_replace(",", ".", $repasse);
                $repasse_voluntario = str_replace(".", "", $this->input->get_post('valorRepassevoluntario', TRUE));
                $repasse_voluntario = str_replace(",", ".", $repasse_voluntario);

                $cidade_cnpj = $this->cidades_model->obter_cidade_por_cnpj($this->input->get_post('cnpjProponente', TRUE));
                $objetos_string = '';
                foreach ($this->input->get_post('objetos', TRUE) as $key => $value) {
                    if ($key == 0)
                        $objetos_string .= $value;
                    else
                        $objetos_string .= ',' . $value;
                }
                $data1 = array(
                    'nome' => $this->input->get_post('proposta', TRUE),
                    'percentual' => $percentual,
                    'valor_global' => $this->input->get_post('valorGlobal', TRUE),
                    'total_contrapartida' => $total_contrapartida,
                    'contrapartida_financeira' => $contrapartida_financeira,
                    'contrapartida_bens' => $contrapartida_bens,
                    'repasse' => $repasse,
                    'repasse_voluntario' => $repasse_voluntario,
                    'agencia ' => $this->input->get_post('agencia', TRUE) . "-" . $this->input->get_post('digito', TRUE),
                    'data' => $data_programa,
                    'data_inicio' => $data_inicio,
                    'data_termino' => $data_termino,
                    'idGestor' => $usuario,
                    'area' => $this->input->get_post('area', TRUE),
                    'banco' => $this->input->get_post('banco', TRUE),
                    'objeto' => $objetos_string,
                    'qualificacao' => $this->input->get_post('qualificacaoProponente', TRUE)
                );

                $id = $this->input->get_post('id', TRUE);
                $inserido = $this->proposta_model->update_record($id, $data1);
                if ($this->get_post_action('avancar') == 'avancar') {
                    $this->encaminha(base_url() . 'index.php/in/usuario/incluir_justificativa?id=' . $id . '&edita_gestor=1');
                }
            }

            $this->load->model('trabalho_model');
            $this->load->model('proposta_model');
            $id = $this->input->get_post('id', TRUE);
            if ($this->input->get_post('id', TRUE) !== false) {

                $tela1 = $this->trabalho_model->obter_saida_tela1_online($this->input->get_post('id', TRUE));
                $this->obter_paginaLogin_exporta($this->login_usuario, $this->senha, $this->input->get_post('id', TRUE));
                $pagina = "https://www.convenios.gov.br/siconv/IncluirProgramasProposta/EscolherProponenteEscolherProponente.do";
                $fields = array(
                    'invalidatePageControlCounter' => '1',
                    'cnpjProponente' => $tela1->proponente
                );
                $fields_string = null;
                foreach ($fields as $key => $value) {
                    $fields_string .= $key . '=' . $value . '&';
                }
                rtrim($fields_string, '&');
                utf8_decode($this->obter_pagina("https://www.convenios.gov.br/siconv/proposta/IncluirDadosProposta/IncluirDadosProposta.do"));
                //echo $pagina."?".$fields_string;
                utf8_decode($this->obter_pagina_post($pagina, $fields, $fields_string));

                //consultar programas****************************************************
                $pagina = "https://www.convenios.gov.br/siconv/IncluirProgramasProposta/ConsultarProgramasConsultar.do";
                $fields = array(
                    'orgao' => $tela1->orgao,
                    'qualificacaoProponenteColAsArray' => '',
                    'numeroEmendaParlamentar' => '',
                    'anoPrograma' => '',
                    'codigoPrograma' => '',
                    'nomePrograma' => '',
                    'descricaoPrograma' => '',
                    'objetoPrograma' => '',
                    'modalidade' => ''
                );
                $fields_string = null;
                foreach ($fields as $key => $value) {
                    $fields_string .= $key . '=' . $value . '&';
                }
                rtrim($fields_string, '&');
                //echo $pagina."?".$fields_string;
                utf8_decode($this->obter_pagina_post($pagina, $fields, $fields_string));

                $pagina = "https://www.convenios.gov.br/siconv/IncluirProgramasProposta/SelecionarProgramasSelecionar.do";
                $fields = array(
                    'idRowSelectionAsArray' => $tela1->id_programa
                );
                $fields_string = null;
                foreach ($fields as $key => $value) {
                    $fields_string .= $key . '=' . $value . '&';
                }
                rtrim($fields_string, '&');
                $documento = utf8_decode($this->obter_pagina_post($pagina, $fields, $fields_string));
                //echo $pagina."?".$fields_string;
                //echo $documento;
                $documento = $this->removeSpaceSurplus($documento);
                $txt1 = $this->getTextBetweenTags($documento, "<a href=\"javascript:document.location='", "';\" class=\"buttonLink\">Selecionar Objetos");

                //página inicial para a inserção dos primeiros dados******************************************************
                $documento = utf8_decode($this->obter_pagina("https://www.convenios.gov.br" . $txt1[0]));
                //echo $documento;
                $documento = $this->removeSpaceSurplus($documento);
                $objetos = $this->getTextBetweenTags($documento, "<input type=\"checkbox\" name=\"objetos\" value=\"", "\" onmouseover");
                $qualificacao = $this->getTextBetweenTags($documento, "name=\"qualificacaoProponente\" value=\"", "<br \/>");

                $objetos_tabela = $this->getTextBetweenTags($documento, "<tr class=\"objetos\" id=\"tr-salvarObjetos\" >", "<\/tr>");
                $qualificacao_tabela = $this->getTextBetweenTags($documento, "<tr class=\"qualificacaoProponente\" id=\"tr-salvarQualificacaoProponente\" >", "<\/tr>");

                $quali_proponente = "";
                foreach ($qualificacao as $quali) {
                    $aux1 = explode(" | ", $quali);
                    /* echo intval(substr($aux1[0], -5, 4))." - ";
                      echo intval($tela1->percentual)."<br>"; */
                    if (intval(substr($aux1[0], -5, 4)) == intval($tela1->percentual)) {
                        //echo $quali_proponente." quali ";
                        $quali_proponente = strtok($quali, "\"");
                        break;
                    }
                }

                $pagina = "https://www.convenios.gov.br/siconv/ManterProgramaProposta/ValoresDoProgramaSalvar.do";
            }

            $proposta = $this->proposta_model->get_by_id($id);
            $data['objetos_tabela'] = utf8_encode($objetos_tabela[0]);
            $data['qualificacao_tabela'] = utf8_encode($qualificacao_tabela[0]);
            $data['senha_siconv'] = $this->senha;
            $data['usuario_siconv'] = $this->login_usuario;
            $data['areas'] = $this->trabalho_model->obter_areas();
            $data['login'] = $this->login;
            $data['proposta'] = $proposta;
            $data['main'] = 'gestor/incluir_proposta';
        } else if ($this->input->get_post('data', TRUE) !== false) {
            $data_programa = implode("-", array_reverse(explode("/", trim($this->input->get_post('data', TRUE)))));
            $data_inicio = implode("-", array_reverse(explode("/", $this->input->get_post('inicioVigencia', TRUE))));
            $data_termino = implode("-", array_reverse(explode("/", $this->input->get_post('terminoVigencia', TRUE))));

            $percentual = str_replace(".", "", $this->input->get_post('percentual', TRUE));
            $percentual = str_replace(",", ".", $percentual);
            $valor_global = str_replace(".", "", $this->input->get_post('valorGlobal', TRUE));
            $valor_global = str_replace(",", ".", $valor_global);
            $total_contrapartida = str_replace(".", "", $this->input->get_post('valorContrapartida', TRUE));
            $total_contrapartida = str_replace(",", ".", $total_contrapartida);
            $contrapartida_financeira = str_replace(".", "", $this->input->get_post('valorContrapartidaFinanceira', TRUE));
            $contrapartida_financeira = str_replace(",", ".", $contrapartida_financeira);
            $contrapartida_bens = str_replace(".", "", $this->input->get_post('valorContrapartidaBensServicos', TRUE));
            $contrapartida_bens = str_replace(",", ".", $contrapartida_bens);
            $repasse = str_replace(".", "", $this->input->get_post('valorRepasse', TRUE));
            $repasse = str_replace(",", ".", $repasse);
            $repasse_voluntario = str_replace(".", "", $this->input->get_post('valorRepassevoluntario', TRUE));
            $repasse_voluntario = str_replace(",", ".", $repasse_voluntario);

            $remotePageUrl = 'https://www.convenios.gov.br/siconv/IncluirProgramasProposta/ConsultarProgramasConsultar.do';
            $remotePageUrl1 = "https://www.convenios.gov.br/siconv/ForwardAction.do?modulo=Principal&path=/MostraPrincipalConsultarPrograma.do?Usr=guest&Pwd=guest";
            $this->obter_paginaLogin($this->login_usuario, $this->senha);
            $this->obter_pagina($remotePageUrl1);
            $this->obter_pagina($remotePageUrl);
            $documento = $this->obter_pagina("https://www.convenios.gov.br/siconv/programa/DetalharPrograma/DetalharPrograma.do?id=" . $this->input->get_post('id_programa', TRUE));

            $documento = $this->removeSpaceSurplus($documento);

            $txt1 = $this->getTextBetweenTags($documento, "Nome do Programa<\/td> <td class=\"field\">", "<\/td>");
            $txt2 = $this->getTextBetweenTags($documento, "Código do Programa<\/td> <td class=\"field\">", "<\/td>");

            $cidade_cnpj = $this->cidades_model->obter_cidade_por_cnpj($this->input->get_post('cnpjProponente', TRUE));
            $objetos_string = '';
            foreach ($this->input->get_post('objetos', TRUE) as $key => $value) {
                if ($key == 0)
                    $objetos_string .= $value;
                else
                    $objetos_string .= ',' . $value;
            }
            $data1 = array(
                'nome_programa' => trim($txt1[0]),
                'codigo_programa' => trim($txt2[0]),
                'programa' => "https://www.convenios.gov.br/siconv/programa/DetalharPrograma/DetalharPrograma.do?id=" . $this->input->get_post('id_programa', TRUE),
                'cidade' => $cidade_cnpj,
                'nome' => $this->input->get_post('proposta', TRUE),
                'percentual' => $percentual,
                'valor_global' => $valor_global,
                'total_contrapartida' => $total_contrapartida,
                'contrapartida_financeira' => $contrapartida_financeira,
                'contrapartida_bens' => $contrapartida_bens,
                'repasse' => $repasse,
                'repasse_voluntario' => $repasse_voluntario,
                'agencia ' => $this->input->get_post('agencia', TRUE) . "-" . $this->input->get_post('digito', TRUE),
                'data' => $data_programa,
                'data_inicio' => $data_inicio,
                'data_termino' => $data_termino,
                'idGestor' => $usuario,
                'area' => $this->input->get_post('area', TRUE),
                'banco' => $this->input->get_post('banco', TRUE),
                'proponente' => $this->input->get_post('cnpjProponente', TRUE),
                'orgao' => $this->input->get_post('orgao', TRUE),
                'id_programa' => $this->input->get_post('id_programa', TRUE),
                'usuario_siconv' => $this->login_usuario,
                'senha_siconv' => $this->senha,
                'objeto' => $objetos_string,
                'qualificacao' => $this->input->get_post('qualificacaoProponente', TRUE)
            );

            $data['login'] = $this->login;
            if ($this->input->get_post('id', TRUE) !== false) {
                $id = $this->input->get_post('id', TRUE);
                $inserido = $this->proposta_model->update_record($id, $data1);
            } else {
                $inserido = $this->proposta_model->add_records($data1);
            }
            ?>
            <script>
                window.location = 'atribui_permissoes_todos?pessoa=<?php echo $this->usuario_logado->id_usuario ?>&proposta=<?php echo $inserido ?>';
            </script>                
            <?php
            if ($this->get_post_action('avancar') == 'avancar') {
                $this->encaminha('../usuario/incluir_justificativa?id=' . $inserido . '&edita_gestor=1');
            } else {
                $this->encaminha(base_url() . 'index.php/in/gestor/visualiza_propostas');
            }
        } else {
            if ($this->input->get_post('idRowSelectionAsArray', TRUE) === false) {
                $this->alert("Escolha o programa");
                $this->voltaPagina();
            }

            $this->load->model('trabalho_model');
            $this->load->model('proposta_model');

            $cnpjProponente = $this->input->get_post('cnpjProponente', TRUE);
            $usuario_siconv = $this->login_usuario;
            $senha_siconv = $this->senha;
            $orgao = $this->input->get_post('orgao', TRUE);
            $id_programa = $this->input->get_post('idRowSelectionAsArray', TRUE);

            $this->obter_paginaLogin_exporta($usuario_siconv, $senha_siconv, "");
            $pagina = "https://www.convenios.gov.br/siconv/IncluirProgramasProposta/EscolherProponenteEscolherProponente.do";
            $fields = array(
                'invalidatePageControlCounter' => '1',
                'cnpjProponente' => $cnpjProponente
            );
            $fields_string = null;
            foreach ($fields as $key => $value) {
                $fields_string .= $key . '=' . $value . '&';
            }
            rtrim($fields_string, '&');
            utf8_decode($this->obter_pagina("https://www.convenios.gov.br/siconv/proposta/IncluirDadosProposta/IncluirDadosProposta.do"));
            utf8_decode($this->obter_pagina_post($pagina, $fields, $fields_string));

            //consultar programas****************************************************
            $pagina = "https://www.convenios.gov.br/siconv/IncluirProgramasProposta/ConsultarProgramasConsultar.do";
            $fields = array(
                'orgao' => $orgao,
                'qualificacaoProponenteColAsArray' => '',
                'numeroEmendaParlamentar' => '',
                'anoPrograma' => '',
                'codigoPrograma' => '',
                'nomePrograma' => '',
                'descricaoPrograma' => '',
                'objetoPrograma' => '',
                'modalidade' => ''
            );
            $fields_string = null;
            foreach ($fields as $key => $value) {
                $fields_string .= $key . '=' . $value . '&';
            }
            rtrim($fields_string, '&');
            utf8_decode($this->obter_pagina_post($pagina, $fields, $fields_string));
            //escolhendo programas

            $pagina = "https://www.convenios.gov.br/siconv/IncluirProgramasProposta/SelecionarProgramasSelecionar.do";
            $fields = array(
                'idRowSelectionAsArray' => $id_programa
            );
            $fields_string = null;
            foreach ($fields as $key => $value) {
                $fields_string .= $key . '=' . $value . '&';
            }
            rtrim($fields_string, '&');
            $documento = utf8_decode($this->obter_pagina_post($pagina, $fields, $fields_string));
            $documento = $this->removeSpaceSurplus($documento);
            $txt1 = $this->getTextBetweenTags($documento, "<a href=\"javascript:document.location='", "';\" class=\"buttonLink\">Selecionar Objetos");

            //página inicial para a inserção dos primeiros dados******************************************************
            $documento = utf8_decode($this->obter_pagina("https://www.convenios.gov.br" . $txt1[0]));
            $documento = $this->removeSpaceSurplus($documento);

            $objetos_tabela = $this->getTextBetweenTags($documento, "<tr class=\"objetos\" id=\"tr-salvarObjetos\" >", "<\/tr>");
            $qualificacao_tabela = $this->getTextBetweenTags($documento, "<tr class=\"qualificacaoProponente\" id=\"tr-salvarQualificacaoProponente\" >", "<\/tr>");

            $data['objetos_tabela'] = utf8_encode($objetos_tabela[0]);
            $data['qualificacao_tabela'] = utf8_encode($qualificacao_tabela[0]);

            $data['cnpjProponente'] = $this->input->get_post('cnpjProponente', TRUE);
            $data['usuario_siconv'] = $this->login_usuario;
            $data['senha_siconv'] = $this->senha;
            $data['orgao'] = $this->input->get_post('orgao', TRUE);
            $data['id_programa'] = $this->input->get_post('idRowSelectionAsArray', TRUE);
            $data['areas'] = $this->trabalho_model->obter_areas();
            $data['login'] = $this->login;
            $data['main'] = 'gestor/incluir_proposta';
        }
        $data['cidades'] = $this->proposta_model->obter_estados();
        $data['bancos'] = $this->proposta_model->obter_bancos();
        $data['title'] = 'Physis - Incluir Proposta';
        $data['login'] = $this->login;
        $data['areas'] = $this->trabalho_model->obter_areas();
        $data['main'] = 'gestor/incluir_proposta';
        $this->load->vars($data);
        $this->load->view('in/template_projeto');
    }

    function escolhe_endereco() {
        $this->load->model('trabalho_model');
        $this->load->model('proposta_model');
        $this->load->model('cidades_model');

        if ($this->input->get_post('UF', TRUE) !== false) {
            $proposta_atual = $this->proposta_model->get_by_id($this->input->get_post('id', TRUE));
            $id_proposta = $this->trabalho_model->copia_proposta($this->input->get_post('id', TRUE));
            $metas = $this->trabalho_model->copia_justificativa($this->input->get_post('id', TRUE), $id_proposta);
            $metas = $this->trabalho_model->copia_metas($this->input->get_post('id', TRUE), $id_proposta);
            $etapas = $this->trabalho_model->copia_etapas($metas);
            $cronogramas = $this->trabalho_model->copia_cronogramas($this->input->get_post('id', TRUE), $id_proposta);
            $cronogramas_meta = $this->trabalho_model->copia_cronogramas_meta($cronogramas, $metas);
            $cronogramas_etapa = $this->trabalho_model->copia_cronogramas_etapa($cronogramas_meta, $etapas);
            $despesas = $this->trabalho_model->copia_despesas($this->input->get_post('id', TRUE), $id_proposta);
            $despesas = $this->trabalho_model->copia_trabalhos($this->input->get_post('id', TRUE), $id_proposta);
            $cidade_cnpj = $this->cidades_model->obter_cidade_por_cnpj($this->input->get_post('cnpjProponente', TRUE));

            $hoje = date("Y-m-01", strtotime("+1 month"));
            $validade = $proposta_atual->validade + 1;
            $final = date("Y-m-01", strtotime("+$validade months"));
            $data1 = array(
                'senha_siconv' => $this->input->get_post('senha_siconv', TRUE),
                'usuario_siconv' => $this->input->get_post('usuario_siconv', TRUE),
                'cidade' => $cidade_cnpj,
                'proponente' => $this->input->get_post('cnpjProponente', TRUE),
                'valor_global' => $this->input->get_post('valorGlobal', TRUE),
                'idGestor' => $this->usuario_logado->idPessoa,
                'padrao' => 0,
                'data' => $hoje,
                'data_inicio' => $hoje,
                'data_termino' => $final,
                'enviado' => 0,
                'id_siconv' => null,
                'agencia ' => $this->input->get_post('agencia', TRUE) . "-" . $this->input->get_post('digito', TRUE),
                'area' => $this->input->get_post('area', TRUE),
                'banco' => $this->input->get_post('banco', TRUE)
            );

            $inserido = $this->proposta_model->update_record($id_proposta, $data1);

            $options = array(
                'Proposta_idProposta' => $id_proposta,
                'UF' => $this->input->get_post('UF', TRUE),
                'municipio_sigla' => $this->input->get_post('municipio_sigla', TRUE),
                'municipio_nome' => $this->input->get_post('municipio_nome', TRUE),
                'endereco' => $this->input->get_post('endereco', TRUE),
                'cep' => $this->input->get_post('cep', TRUE)
            );
            $this->trabalho_model->add_endereco($options);

            $justificativa = $this->trabalho_model->atualiza_justificativa($id_proposta, $this->input->get_post('cnpjProponente', TRUE));

            $this->alert("Feito.");
            $this->encaminha('visualiza_proposta?id=' . $id_proposta);
        } else {
            $data['meta'] = $this->trabalho_model->obter_endereco_por_id($this->input->get_post('id', TRUE));
            $data['cidades'] = $this->trabalho_model->obter_cidades_siconv();
            $data['ufs'] = $this->trabalho_model->obter_uf_siconv();
            $data['enderecos'] = $this->trabalho_model->obter_enderecos($this->usuario_logado->idPessoa);
            $data['title'] = 'Physis - gerenciamento de usuários';
            $data['id'] = $this->input->get_post('id', TRUE);
            $data['main'] = 'in/escolhe_endereco';
            $data['login'] = $this->login;
            $data['senha_siconv'] = $this->input->get_post('senha_siconv', TRUE);
            $data['usuario_siconv'] = $this->input->get_post('usuario_siconv', TRUE);
            $data['cnpjProponente'] = $this->input->get_post('cnpjProponente', TRUE);
            $data['id'] = $this->input->get_post('id', TRUE);
            $data['valorGlobal'] = $this->input->get_post('valorGlobal', TRUE);
            $data['agencia'] = $this->input->get_post('agencia', TRUE);
            $data['digito'] = $this->input->get_post('digito', TRUE);
            $data['banco'] = $this->input->get_post('banco', TRUE);

            $this->load->vars($data);
            $this->load->view('in/template');
        }
    }

    function sala_conferencia() {

        $data['title'] = "Physis - Gerencia Usuários";
        $this->load->model('usuario_model');
        $data['usuarios'] = $this->usuario_model->get_all_gestores();
        $data['usuario_model'] = $this->usuario_model;
        $data['nome_usuario'] = $this->usuario_logado->nome;
        $data['main'] = 'in/sala_conferencia';
        $data['login'] = $this->login;

        $this->load->view('in/template', $data);
    }

    function compra_passe() {

        if ($this->input->get_post('cadastra', TRUE) !== false) {
            $this->load->library('pagseguro');
            $id = $this->input->get_post('id', TRUE);
            $tipo = $this->input->get_post('tipo', TRUE);
            $opcao = $this->input->get_post('opcao', TRUE);

            $usuario = array(
                'id' => $this->usuario_logado->idPessoa,
                'nome' => $this->usuario_logado->nome,
                //'ddd'        => '21', // só números
                'telefone' => $this->usuario_logado->telefone, // só números
                'email' => $this->usuario_logado->email,
                //'shippingType' => 3, //1=Encomenda normal (PAC), 2=SEDEX, 3=Tipo de frete não especificado.
                'cep' => "00000000", // só números
                'logradouro' => $this->usuario_logado->endereco
            );
            $this->pagseguro->set_user($usuario);

            if ($tipo == 4) {
                if ($opcao == 0) {
                    $products[] = array(
                        'id' => '999',
                        'descricao' => '10 propostas',
                        'valor' => '0.10',
                        'quantidade' => 1,
                        'peso' => 0
                    );
                } else if ($opcao == 1) {
                    $products[] = array(
                        'id' => '999',
                        'descricao' => '20 propostas',
                        'valor' => '0.18',
                        'quantidade' => 1,
                        'peso' => 0
                    );
                } else if ($opcao == 2) {
                    $products[] = array(
                        'id' => '999',
                        'descricao' => '30 propostas',
                        'valor' => '0.25',
                        'quantidade' => 1,
                        'peso' => 0
                    );
                }
            } else if ($tipo == 6) {
                if ($opcao == 0) {
                    $products[] = array(
                        'id' => '999',
                        'descricao' => '1 mês',
                        'valor' => '0.10',
                        'quantidade' => 1,
                        'peso' => 0
                    );
                } else if ($opcao == 1) {
                    $products[] = array(
                        'id' => '999',
                        'descricao' => '3 meses',
                        'valor' => '0.18',
                        'quantidade' => 1,
                        'peso' => 0
                    );
                } else if ($opcao == 2) {
                    $products[] = array(
                        'id' => '999',
                        'descricao' => '3 meses',
                        'valor' => '0.25',
                        'quantidade' => 1,
                        'peso' => 0
                    );
                }
            }

            $this->pagseguro->set_products($products);
            $config['reference'] = rand(999, 9999);

            $data['title'] = "Physis - Gerencia Usuários";
            $data['botao'] = $this->pagseguro->get_button($config);
            $data['main'] = 'in/compra_pagseguro';
            $data['login'] = $this->login;
            $this->load->view('in/template', $data);
        } else {
            $data['title'] = "Physis - Gerencia Usuários";
            $this->load->model('usuario_model');
            $data['usuarios'] = $this->usuario_model->get_all_gestores();
            $data['usuario_model'] = $this->usuario_model;
            $data['nome_usuario'] = $this->usuario_logado->nome;
            $data['main'] = 'in/compra_passe';
            $data['login'] = $this->login;
            $data['id'] = $this->input->get_post('id', TRUE);
            $data['tipo'] = $this->input->get_post('tipo', TRUE);
            $this->load->view('in/template', $data);
        }
    }

    function compra_relatorio() {

        $this->load->library('pagseguro');
        $id = $this->input->get_post('id', TRUE);
        $nome = $this->input->get_post('nome', TRUE);

        $usuario = array(
            'id' => $this->usuario_logado->idPessoa,
            'nome' => $this->usuario_logado->nome,
            'ddd' => '73', // só números
            'telefone' => $this->usuario_logado->telefone, // só números
            'email' => $this->usuario_logado->email,
            'shippingType' => 3, //1=Encomenda normal (PAC), 2=SEDEX, 3=Tipo de frete não especificado.
            'cep' => "45656512", // só números
            'numero' => '1'
        );
        $this->pagseguro->set_user($usuario);

        $products[] = array(
            'id' => $id,
            'descricao' => $nome,
            'valor' => '0.10',
            'quantidade' => 1,
            'peso' => 0
        );

        $this->pagseguro->set_products($products);
        $config['reference'] = $id;

        $data['title'] = "Physis - Gerencia Usuários";
        $data['botao'] = $this->pagseguro->get_button($config);
        $data['main'] = 'in/compra_pagseguro';
        $data['login'] = $this->login;
        $this->load->view('in/template', $data);
    }

    public function retorno_pagseguro() {

        if (count($_POST) > 0) {

            // SALVA O POST PARA DEGUG
            $this->debug($_P0ST);
            $informacao = array();
            $this->load->library('pagseguro'); //Carrega a library
            // faz conexão com PS para validar o retorno
            $retorno = $this->pagseguro->notificationPost();
            // quando recebe uma notificação que necessita uma requisição GET 
            // para recuperar status da transação
            $notificationType = (isset($_POST['notificationType']) && $_POST['notificationType'] != '') ? $_POST['notificationType'] : FALSE;
            $notificationCode = (isset($_POST['notificationCode']) && $_POST['notificationCode'] != '') ? $_POST['notificationCode'] : FALSE;

            // É uma notificação de status. Passa a ação para o método que vai 
            // atualizar o status do pedido.
            if ($notificationType && $notificationCode) {
                $not = $this->pagseguro->get_notification($notificationCode);
                /*
                 * FAZ AS ATUALIZAÇÕES COM A NOTIFICAÇÃO DE STATUS
                 */
            }
            // informação quando é enviado um POST completo
            $transacaoID = (isset($_POST['TransacaoID'])) ? $_POST['TransacaoID'] : FALSE;

            // Se existe $transacaoID é uma notificação via POST logo após a
            // solicitação de pagamento, neste momento
            if ($transacaoID) {

                /*
                 * FAZ AS ATUALIZAÇÕES COM A NOTIFICAÇÃO DE STATUS
                 */
            }

            //O post foi validado pelo PagSeguro.
            if ($retorno == "VERIFICADO") {

                if ($_POST['StatusTransacao'] == 'Aprovado') {
                    $informacao['status'] = '1';
                } elseif ($_POST['StatusTransacao'] == 'Em Análise') {
                    $informacao['status'] = '2';
                } elseif ($_POST['StatusTransacao'] == 'Aguardando Pagto') {
                    $informacao['status'] = '3';
                } elseif ($_POST['StatusTransacao'] == 'Completo') {
                    $informacao['status'] = '4';
                } elseif ($_POST['StatusTransacao'] == 'Cancelado') {
                    $informacao['status'] = '5';
                }
            } else if ($retorno == "FALSO") {
                //O post não foi validado pelo PagSeguro.
                $informacao['status'] = '1000';
            } else {
                //Erro na integração com o PagSeguro.
                $informacao['status'] = '6';
            }
        } else {

            // POST não recebido, indica que a requisição é o retorno do Checkout PagSeguro.
            // No término do checkout o usuário é redirecionado para este bloco.
            // redirecionar para página de OBRIGADO e aguarde...
            // redirect('loja');
        }
    }

    public function debug($array) {

        $data = array();
        foreach ($array as $c => $v) {
            $data[] = $c . ': ' . $v;
        }

        $output = implode("\n", $data);

        $this->load->helper('file');
        write_file(APPPATH . "cache/pagseguro_" . date("Y-m-d_h-i") . ".php", $output);
    }

    function finaliza_trabalho() {
        $this->load->model('trabalho_model');
        $this->load->model('proposta_model');
        if ($this->input->get_post('id', TRUE) !== false) {
            if ($this->trabalho_model->verifica_trabalhos($this->input->get_post('id', TRUE)) != true) {
                $this->voltaPagina();
            }
            $this->alert('Lembre-se de anexar os arquivos: Capacidade Tec., Dec. Contrapartida e QDD');

            // THOMAS: Estava realizando apenas para usuarios tipo 6 verificar a necessidade de realizar agora no novo padrão de usuarios.
            /* $prop = $this->proposta_model->obter_cnpj_aberto($this->usuario_logado->id_usuario);
              $data1 = array(
              'senha_siconv' => $prop->senha_siconv,
              'usuario_siconv' => $prop->usuario_siconv,
              'proponente' => $prop->cnpj
              ); */

            $inserido = $this->proposta_model->atualiza_proposta($this->input->get_post('id', TRUE), $data1);
            $data['trabalho_model'] = $this->trabalho_model;
            $data['id'] = $this->input->get_post('id', TRUE);

            $data['login'] = "";
            $data['title'] = "Physis - Finaliza Trabalho";
            $data['main'] = 'in/finaliza_trabalho';

            $this->load->view('in/template', $data);
        } else {
            $this->voltaPagina();
        }
    }

    function lista_relatorios() {

        $data['title'] = "Physis - Gestão de Usuários e Propostas";
        $data['main'] = 'in/lista_relatorios';
        $data['login'] = $this->login;
        $this->load->view('in/template', $data);
    }

    function duplica_trabalho() {
        $this->load->model('trabalho_model');
        $this->load->model('proposta_model');
        if ($this->input->get_post('id', TRUE) !== false) {

            $id_proposta = $this->trabalho_model->copia_proposta_usuario($this->input->get_post('id', TRUE), $this->usuario_logado->id_usuario);
            $programas = $this->trabalho_model->copiar_programa_proposta($this->input->get_post('id', TRUE), $id_proposta);
            $metas = $this->trabalho_model->copia_justificativa($this->input->get_post('id', TRUE), $id_proposta);
            $metas = $this->trabalho_model->copia_metas($this->input->get_post('id', TRUE), $id_proposta, $programas);
            $etapas = $this->trabalho_model->copia_etapas($metas);
            $cronogramas = $this->trabalho_model->copia_cronogramas($this->input->get_post('id', TRUE), $id_proposta);
            $cronogramas_meta = $this->trabalho_model->copia_cronogramas_meta($cronogramas, $metas);
            $cronogramas_etapa = $this->trabalho_model->copia_cronogramas_etapa($cronogramas_meta, $etapas);
            $despesas = $this->trabalho_model->copia_despesas($this->input->get_post('id', TRUE), $id_proposta, $programas);
            $despesas = $this->trabalho_model->copia_trabalhos($this->input->get_post('id', TRUE), $id_proposta);

            $this->load->model('system_logs');
            $this->system_logs->add_log(DUPLICA_PROJETO . " - ID: " . $this->input->get_post('id', TRUE));

            $this->alert("Proposta duplicada com sucesso!");
            $this->encaminha(base_url() . 'index.php/in/gestor/visualiza_propostas');
        } else {
            $this->voltaPagina();
        }
    }

    //THOMAS: Duplica a proposta e torna padrão limpando os dados que devem ser sobrescritos pelo modal
    function duplica_trabalho_torna_padrao() {
        $this->load->model('trabalho_model');
        $this->load->model('proposta_model');
        if ($this->input->get_post('id', TRUE) !== false) {

            $id_proposta = $this->trabalho_model->copia_proposta_usuario($this->input->get_post('id', TRUE), $this->usuario_logado->id_usuario, true);
            $programas = $this->trabalho_model->copiar_programa_proposta($this->input->get_post('id', TRUE), $id_proposta);
            $metas = $this->trabalho_model->copia_justificativa($this->input->get_post('id', TRUE), $id_proposta);
            $metas = $this->trabalho_model->copia_metas($this->input->get_post('id', TRUE), $id_proposta, $programas);
            $etapas = $this->trabalho_model->copia_etapas($metas);
            $cronogramas = $this->trabalho_model->copia_cronogramas($this->input->get_post('id', TRUE), $id_proposta);
            $cronogramas_meta = $this->trabalho_model->copia_cronogramas_meta($cronogramas, $metas);
            $cronogramas_etapa = $this->trabalho_model->copia_cronogramas_etapa($cronogramas_meta, $etapas);
            $despesas = $this->trabalho_model->copia_despesas($this->input->get_post('id', TRUE), $id_proposta, $programas);
            $despesas = $this->trabalho_model->copia_trabalhos($this->input->get_post('id', TRUE), $id_proposta);
            $this->proposta_model->torna_projeto_padrao($this->input->get_post('id', TRUE));

            $this->load->model('system_logs');
            $this->system_logs->add_log(TORNA_PROJETO_PADRAO . " - ID: " . $this->input->get_post('id', TRUE));

            $this->alert("Proposta transformada em padrão com sucesso!");
            $this->encaminha(base_url() . 'index.php/in/gestor/visualiza_propostas');
        } else {
            $this->voltaPagina();
        }
    }

    public function duplica_trabalho_ativa_padrao() {
        $this->load->model('proposta_model');
        if ($this->input->get_post('id', TRUE) !== false) {

            $this->proposta_model->ativa_padrao($this->input->get_post('id', TRUE));

            $this->load->model('system_logs');
            $this->system_logs->add_log("ATIVOU PROJETO PADRAO" . " - ID: " . $this->input->get_post('id', TRUE));

            $this->alert("Proposta padrão ativada com sucesso!");
            $this->encaminha(base_url() . 'index.php/in/gestor/visualiza_propostas');
        } else {
            $this->voltaPagina();
        }
    }

    function ver_trabalhos() {

        $usuario = $this->usuario_logado->idPessoa;
        $this->load->model('trabalho_model');
        $this->load->model('proposta_model');
        $this->load->model('usuario_model');
        //$data['trabalhos_foreach'] = $this->trabalho_model->obter_por_usuario($usuario);
        //$trabalhos->obter_por_proposta_gestor($proposta->idProposta);
        $data['trabalhos'] = $this->trabalho_model;
        $data['usuarios'] = $this->usuario_model;
        $data['propostas'] = $this->proposta_model->get_all($usuario);
        $data['programas'] = $this->proposta_model->obter_programas($usuario);
        $data['gestor'] = $usuario;
        $data['trabalho_id'] = null;
        $data['proposta_id'] = $this->input->get_post('id', TRUE);
        $data['cod_cidades'] = null;
        $data['cod_programa'] = null;
        $data['usuario_padrao'] = true;

        $data['leitura_aberta'] = true;

        $data['cidades'] = $this->proposta_model->obter_estados();
        $data['title'] = 'Physis - gerenciamento de propostas';
        $data['main'] = 'gestor/visualiza_propostas';
        $data['login'] = $this->login;
        $this->load->vars($data);
        $this->load->view('in/template');
    }

    function relatorio_programas_emenda() {
        $this->load->model('programa_model');
        $this->load->model('usuariomodel');
        $logado = true;
        $usuario_logado = $this->usuario_logado;

        $this->session->set_userdata('pagAtual', 'relatorio_programas_emenda');

        $this->load->model('cidades_model');
        $data['title'] = "Physis - Gestão de Usuários e Propostas";
        $data['main'] = 'in/relatorio_programas_emenda';

        $data['login'] = "Olá, " . $usuario_logado->nome . " - <a href=\"" . base_url() . "index.php/in/login/sair\">Sair</a>";

        $this->load->view('in/template', $data);
    }

    function relatorio_programas() {
        $this->load->model('programa_model');
        $this->load->model('usuariomodel');
        $logado = true;
        $usuario_logado = $this->usuario_logado;

        $this->session->set_userdata('pagAtual', 'relatorio_programas');

        $this->load->model('cidades_model');
        $data['title'] = "Physis - Gestão de Usuários e Propostas";
        $data['main'] = 'in/relatorio_programas';

        $data['cidades'] = $this->cidades_model->obter_estados();
        $data['orgaos'] = $this->programa_model->obter_lista_distinct('orgao');
        $data['qualificacao'] = $this->programa_model->obter_lista_distinct('qualificacao');
        $data['atende'] = $this->programa_model->obter_lista_distinct('atende');

        $data['login'] = "Olá, " . $usuario_logado->nome . " - <a href=\"" . base_url() . "index.php/in/login/sair\">Sair</a>";

        $this->load->view('in/template', $data);
    }

    //----------------------------------- AJAX --------------------------------------------
    function ajax_programas() {

        $this->load->model('programa_model');

        $dataInicio = implode("-", array_reverse(explode("/", $this->input->get_post('Dt_Inicio', TRUE))));
        $dataFim = implode("-", array_reverse(explode("/", $this->input->get_post('Dt_Fim', TRUE))));
        //if (strtotime(str_replace("/","-",$dataFim_Programa))>=strtotime($dataInicio) && strtotime(str_replace("/","-",$dataFim_Programa))<=strtotime($dataFim)){
        $this->load->model('cidades_model');
        $estado = $this->input->get_post('cod_estados', TRUE);
        $estado = $this->obterEstadoNome($estado);
        $cidade = $this->input->get_post('cod_cidades', TRUE);
        $cnpj = $this->cidades_model->obter_cnpj($cidade);
        $orgao = $this->input->get_post('orgao', TRUE);
        $vigencia = $this->input->get_post('vigencia', TRUE);

        if ($cidade == '')
            $listaCidade = array();
        else
            $listaCidade = $this->programa_model->obter_por_cidade($dataInicio, $dataFim, $cidade, $orgao, '', '', $vigencia);
        $listaEstado = $this->programa_model->obter_por_estado($dataInicio, $dataFim, $estado, $orgao, '', '', $vigencia);

        usort($listaCidade, array($this, 'ordena_por_orgao'));
        usort($listaEstado, array($this, 'ordena_por_orgao'));

        $this->load->model('usuariomodel');
        $usuario_logado = $this->usuario_logado;

        $data['listaCidade'] = $listaCidade;
        $data['listaEstado'] = $listaEstado;

        $this->load->view('ajax/teste_programas', $data);
        #return $listaEstado;
    }

    function teste_programas() {
        $this->load->model('programa_model');
        $this->load->model('usuario_model');
        $logado = $this->usuario_model->logged(1);
        $usuario_logado = $this->usuario_model->get_by_login($this->session->userdata('login'));

        $this->load->model('cidades_model');
        $data['title'] = "Physis - Gestão de Usuários e Propostas";


        $data['cidades'] = $this->cidades_model->obter_estados();
        $data['orgaos'] = $this->programa_model->obter_lista_distinct('orgao');
        $data['qualificacao'] = $this->programa_model->obter_lista_distinct('qualificacao');
        $data['atende'] = $this->programa_model->obter_lista_distinct('atende');

        $data['login'] = "Olá, " . $usuario_logado->nome . " - <a href=\"" . base_url() . "index.php/in/login/sair\">Sair</a>";

        $this->load->view('ajax/teste_programas', $data);
    }

    //----------------------------------- #AJAX --------------------------------------------
    function gerapdf_programas1() {

        $this->load->model('programa_model');

        $dataInicio = implode("-", array_reverse(explode("/", $this->input->get_post('Dt_Inicio', TRUE))));
        $dataFim = implode("-", array_reverse(explode("/", $this->input->get_post('Dt_Fim', TRUE))));

        $this->load->model('cidades_model');
        $estado = $this->input->get_post('cod_estados', TRUE);
        $estado = $this->obterEstadoNome($estado);
        $cidade = $this->input->get_post('cod_cidades', TRUE);
        $cnpj = $this->cidades_model->obter_cnpj($cidade);
        $orgao = $this->input->get_post('orgao', TRUE);
        $vigencia = $this->input->get_post('vigencia', TRUE);

        $listaEstado = $this->programa_model->obter_por_estado($dataInicio, $dataFim, $estado, $orgao, '', '', $vigencia);
        usort($listaEstado, array($this, 'ordena_por_orgao'));

        foreach ($listaEstado as $programa) {
            if (in_array($programa->qualificacao, $this->input->post('qualificacao', TRUE)) && in_array($programa->atende, $this->input->post('atende', TRUE))) {
                $lista[] = $programa;
            }
        }
    }

    function gerapdf_programas_emenda() {
        ini_set('memory_limit', '512M');
        $this->load->model('programa_model');

        $dataInicio = implode("-", array_reverse(explode("/", $this->input->get_post('Dt_Inicio', TRUE))));
        $dataFim = implode("-", array_reverse(explode("/", $this->input->get_post('Dt_Fim', TRUE))));
        $vigencia = $this->input->get_post('vigencia', TRUE);
        $emenda = $this->input->get_post('emenda', TRUE);
        $cnpj = $this->input->get_post('cnpj', TRUE);

        if ($this->validar_cnpj($cnpj) == false && trim($cnpj) !== '') {
            $this->alert("CNPJ com formato inválido");
            $this->voltaPagina();
        }

        $cnpj = preg_replace('/[^0-9]/', '', (string) $cnpj);

        $listaEmendas = $this->programa_model->obter_programas_emenda($dataInicio, $dataFim, $vigencia, $emenda, $cnpj);


        usort($listaEmendas, array($this, 'ordena_por_programa'));
        $tabela = "PHYSIS BRASIL CONSULTORIA E ASSESSORIA SOLIDÁRIA <br>";
        $programa_atual = '';
        foreach ($listaEmendas as $programa) {

            if ($programa_atual == $programa->codigo_programa)
                $tabela .= "<br />";
            else {
                $tabela .= "<br /><span style='color:blue'>" . $programa->codigo_programa . ":</span><br />";
                $tabela .= "<b>PROGRAMA: </b>" . $programa->nome . "<br />";
                $tabela .= $programa->descricao . "<br>";
                $tabela .= "<b>Beneficiários:</b> <br />";
                $programa_atual = $programa->codigo_programa;
            }
            $tabela .= " - CNPJ: " . $programa->cnpj_beneficiario . "<br />";
            $tabela .= " - Nome: " . $programa->nome_beneficiario . "<br />";
            $tabela .= " - Estado: " . $programa->estado . "<br />";
            $tabela .= " - Valor: " . $programa->valor_beneficiario . "<br />";
            $tabela .= " - Emenda: " . $programa->emenda_beneficiario . "<br />";
            $tabela .= " - Parlamentar: " . $programa->parlamentar_beneficiario . "<br />";
        }

        $this->load->model('usuariomodel');
        $usuario_logado = $this->usuario_logado;

        $this->load->library('mPDF');

        ob_start();  //inicia o buffer

        $mpdf = new mPDF();
        $mpdf->allow_charset_conversion = true;
        $mpdf->charset_in = 'UTF-8';

        $mpdf->WriteHTML($tabela);

        $mpdf->Output();

        die();
    }

    function gerapdf_programas() {
        $this->load->library('mPDF');
        $this->load->model('programa_model');

        $dataInicio = implode("-", array_reverse(explode("/", $this->input->get_post('Dt_Inicio', TRUE))));
        $dataFim = implode("-", array_reverse(explode("/", $this->input->get_post('Dt_Fim', TRUE))));
        //if (strtotime(str_replace("/","-",$dataFim_Programa))>=strtotime($dataInicio) && strtotime(str_replace("/","-",$dataFim_Programa))<=strtotime($dataFim)){
        $this->load->model('cidades_model');
        $estado = $this->input->get_post('cod_estados', TRUE);
        $estado = $this->obterEstadoNome($estado);
        $cidade = $this->input->get_post('cod_cidades', TRUE);
        $cnpj = $this->cidades_model->obter_cnpj($cidade);
        $orgao = $this->input->get_post('orgao', TRUE);
        $vigencia = $this->input->get_post('vigencia', TRUE);
        ob_start();  //inicia o buffer
        $tabela = utf8_encode($tabela);
        $listaEstado = $this->programa_model->obter_por_estado($dataInicio, $dataFim, $estado, $orgao, '', '', $vigencia);
        usort($listaEstado, array($this, 'ordena_por_orgao'));

        $mpdf = new mPDF('', // mode - default ''
                '', // format - A4, for example, default ''
                0, // font size - default 0
                '', // default font family
                15, // margin_left
                15, // margin right
                30, // margin top
                16, // margin bottom
                5, // margin header
                9, // margin footer
                'L');  // L - landscape, P - portrait
        $mpdf->allow_charset_conversion = true;
        $mpdf->charset_in = 'UTF-8';
        $head = "<div style=\"position:relative;float:right;top:200px;right:0;color:white;background-color:red;width:80px;height:80px;margin:0\">
  		<p style=\"font-weight:bold;text-align:center;margin:0;padding-top:10;\">Página</p>
  		<p style=\"margin-top:-40px;font-weight:bold;padding:0;margin:0;color:white;text-align:center;font-size:200%;\">{PAGENO}</p>
  		</div>
  		<img style=\"position:absolute;top:0;left:0;width:80px;height:80px;\" src=\"./layout/assets/images/logophysis.png\"/>
  		<div style=\"position:relative;margin:-60px;\">
  		<h3 style=\"top:10;color:red;text-align:center;margin:0;padding-top:10;\">PHYSIS BRASIL CONSULTORIA E ASSESSORIA SOLIDÁRIA</h3>
  		</div>";
        $mpdf->SetHTMLHeader($head);
        /* $style = '<style>@page {
          margin: 0px;
          }</style>';
          $mpdf->WriteHTML($style); */
        $tabela = utf8_encode($tabela);
        $estado = $this->input->get_post('cod_estados', TRUE);
        $orgao_atual = '';
        $subtotal = 0;
        $total = 0;
        $i = 0;

        $tabelasub = "<table style=\"font-size: 13px;\"><thead>
  <tr>
     <th align=\"center\" style=\"font-size: 16px;\"><b>Orgao Superior</b></th>
     <th align=\"center\" style=\"font-size: 16px;\"><b>Quantidade de Programas</b></th>
  </tr>
 </thead><tbody>";
        foreach ($listaEstado as $programa) {
            if (in_array($programa->qualificacao, $this->input->post(NULL, TRUE)) && in_array($programa->atende, $this->input->post(NULL, TRUE))) {

                if ($orgao_atual == $programa->orgao) {
                    $subtotal++;
                    $total++;
                    $tabela .= "<br /><br /><table style=\"font-size: 13px;\">";
                } else {
                    if ($i == 0) {
                        $tabelasub .= "<tr><td>" . uc_latin1($programa->orgao) . "</td>";
                        $i++;
                    }
                    if ($orgao_atual != '') {
                        $subtotal++;
                        $total++;
                        $mpdf->WriteHTML($tabela);
                        $mpdf->WriteHTML('<p style=\"font-size: 13px;\">Subtotal: ' . $subtotal . ' programa(s)</p>');
                        $tabelasub .= "<td align=\"center\">" . $subtotal . "</td></tr><tr><td>" . uc_latin1($programa->orgao) . "</td>";
                        $subtotal = 0;
                        $mpdf->AddPage();
                        $tabela = '';
                    }
                    $tabela .= "<br /><h2 style=\"margin:-13px 0;\">" . $programa->orgao . "</h2><br /><table style=\"font-size: 13px;\">";
                    $orgao_atual = $programa->orgao;
                }
                $tabela .= "<tr><td><b>CÓDIGO DO PROGRAMA:</b></td><td colspan=3>" . $programa->codigo . "</td></tr>";
                $tabela .= "<tr><td><b>ÓRGÃO SUPERIOR: </b></td><td colspan=3 bgcolor=\"#EEE\">" . $programa->orgao . "</td></tr>";
                $tabela .= "<tr><td><b>ÓRGÃO: </b></td><td colspan=3>" . $programa->orgao_vinculado . "</td></tr>";
                $tabela .= "<tr><td><b>INÍCIO VIGÊNCIA: </b></td><td>" . implode("/", array_reverse(explode("-", $programa->data_inicio))) . "</td>";
                $tabela .= "<td><b>FIM VIGÊNCIA: </b> " . implode("/", array_reverse(explode("-", $programa->data_fim))) . "</td>";
                $tabela .= "<td><b>QUALIFICAÇÃO: </b> " . $programa->qualificacao . "</td></tr>";
                $tabela .= "<tr><td><b>PROGRAMA: </b></td><td colspan=3>" . $programa->nome . "</td></tr>";
                $tabela .= "</table> <font style=\"font-size: 13px;\">";
                $tabela .= "<p align=\"justify\" style=\"font-size: 13px;\">" . $programa->descricao . "</p>";
                $tabela .= "<p align=\"justify\" style=\"font-size: 13px;\"><b>Obs.: </b> " . $programa->observacao . "</p>";
                $tabela .= "<p align=\"justify\" style=\"font-size: 13px;\"><b>INDICADO PARA: </b> " . $programa->atende . "</p></font>";
            }
        }
        $mpdf->WriteHTML($tabela);
        $mpdf->WriteHTML('<p style=\"font-size: 13px;\">Subtotal: ' . $subtotal . ' programa(s)</p>');
        $mpdf->AddPage();
        $tabelasub .= "<td align=\"center\">" . $subtotal . "</td></tr>";
        $mpdf->WriteHtml('<br /><h2 style=\"margin:-13px 0;\">Quantitativo dos Programas</h2><br />');
        $tabelasub .= "<tr><td>Total</td>
  					<td align=\"center\">" . $total . "</td></tr></tbody></table>";
        $mpdf->WriteHtml($tabelasub);


        $mpdf->Output();
    }

    function gerapdf_programas2() {//##madruga: dexei essa função para testar, e futuramente fazer outra por cima dela.
        $this->load->model('programa_model');

        $dataInicio = implode("-", array_reverse(explode("/", $this->input->get_post('Dt_Inicio', TRUE))));
        $dataFim = implode("-", array_reverse(explode("/", $this->input->get_post('Dt_Fim', TRUE))));
        //if (strtotime(str_replace("/","-",$dataFim_Programa))>=strtotime($dataInicio) && strtotime(str_replace("/","-",$dataFim_Programa))<=strtotime($dataFim)){
        $this->load->model('cidades_model');
        $estado = $this->input->get_post('cod_estados', TRUE);
        $estado = $this->obterEstadoNome($estado);
        $cidade = $this->input->get_post('cod_cidades', TRUE);
        $cnpj = $this->cidades_model->obter_cnpj($cidade);
        $orgao = $this->input->get_post('orgao', TRUE);
        $vigencia = $this->input->get_post('vigencia', TRUE);

        if ($cidade == '')
            $listaCidade = array();
        else
            $listaCidade = $this->programa_model->obter_por_cidade($dataInicio, $dataFim, $cidade, $orgao, '', '', $vigencia);

        $listaEstado = $this->programa_model->obter_por_estado($dataInicio, $dataFim, $estado, $orgao, '', '', $vigencia);

        usort($listaCidade, array($this, 'ordena_por_orgao'));
        usort($listaEstado, array($this, 'ordena_por_orgao'));

        //$this->load->model('usuario_model');
        $usuario_logado = $this->usuario_logado;

        $this->load->library('mPDF');

        ob_start();  //inicia o buffer
        $tabela = utf8_encode($tabela);


        $mpdf = new mPDF();
        $mpdf->allow_charset_conversion = true;
        $mpdf->charset_in = 'UTF-8';
        $tabela = "PHYSIS BRASIL CONSULTORIA E ASSESSORIA SOLIDÁRIA <br> Lista Por cidade:";
        $orgao_atual = '';
        foreach ($listaCidade as $programa) {
            if (in_array($programa->qualificacao, $this->input->post(NULL, TRUE)) && in_array($programa->atende, $this->input->post(NULL, TRUE))) {
                if ($orgao_atual == $programa->orgao)
                    $tabela .= "<br /><br /><table style=\"font-size: 13px;\">";
                else {
                    $tabela .= "<br /><span style='color:blue'>" . $programa->orgao . ":</span><br /><table style=\"font-size: 13px;\">";
                    $orgao_atual = $programa->orgao;
                }
                $tabela .= "<tr><td><b>CÓDIGO DO PROGRAMA:</b></td><td colspan=3>" . $programa->codigo . "</td></tr>";
                $tabela .= "<tr><td><b>ÓRGÃO SUPERIOR: </b></td><td colspan=3 bgcolor=\"grey\">" . $programa->orgao . "</td></tr>";
                $tabela .= "<tr><td><b>ÓRGÃO PROVENENTE: </b></td><td colspan=3>" . $programa->orgao_vinculado . "</td></tr>";
                $tabela .= "<tr><td><b>INÍCIO VIGÊNCIA: </b></td><td>" . implode("/", array_reverse(explode("-", $programa->data_inicio))) . "</td>";
                $tabela .= "<td><b>FIM VIGÊNCIA: </b> " . implode("/", array_reverse(explode("-", $programa->data_fim))) . "</td>";
                $tabela .= "<td><b>QUALIFICAÇÃO: </b> " . $programa->qualificacao . "</td></tr>";
                $tabela .= "<tr><td><b>PROGRAMA: </b></td><td colspan=3>" . $programa->nome . "</td></tr>";
                $tabela .= "</table> <font style=\"font-size: 13px;\">";
                $tabela .= $programa->descricao . "<br>";
                $tabela .= "<b>Obs.: </b> " . $programa->observacao . "<br>";
                $tabela .= "<b>INDICADO PARA: </b> " . $programa->atende . "<br></font>";
            }
        }

        $tabela .= "<br> Lista Por estado:";
        foreach ($listaEstado as $programa) {
            if (in_array($programa->qualificacao, $this->input->post(NULL, TRUE)) && in_array($programa->atende, $this->input->post(NULL, TRUE))) {
                if ($orgao_atual == $programa->orgao)
                    $tabela .= "<br /><br /><table style=\"font-size: 13px;\">";
                else {
                    $tabela .= "<br /><span style='color:blue'>" . $programa->orgao . ":</span><br /><table style=\"font-size: 13px;\">";
                    $orgao_atual = $programa->orgao;
                }
                $tabela .= "<tr><td><b>CÓDIGO DO PROGRAMA:</b></td><td colspan=3>" . $programa->codigo . "</td></tr>";
                $tabela .= "<tr><td><b>ÓRGÃO SUPERIOR: </b></td><td colspan=3 bgcolor=\"grey\">" . $programa->orgao . "</td></tr>";
                $tabela .= "<tr><td><b>ÓRGÃO PROVENENTE: </b></td><td colspan=3>" . $programa->orgao_vinculado . "</td></tr>";
                $tabela .= "<tr><td><b>INÍCIO VIGÊNCIA: </b></td><td>" . implode("/", array_reverse(explode("-", $programa->data_inicio))) . "</td>";
                $tabela .= "<td><b>FIM VIGÊNCIA: </b> " . implode("/", array_reverse(explode("-", $programa->data_fim))) . "</td>";
                $tabela .= "<td><b>QUALIFICAÇÃO: </b> " . $programa->qualificacao . "</td></tr>";
                $tabela .= "<tr><td><b>PROGRAMA: </b></td><td colspan=3>" . $programa->nome . "</td></tr>";
                $tabela .= "</table> <font style=\"font-size: 13px;\">";
                $tabela .= $programa->descricao . "<br>";
                $tabela .= "<b>Obs.: </b> " . $programa->observacao . "<br>";
                $tabela .= "<b>INDICADO PARA: </b> " . $programa->atende . "<br></font>";
            }
        }
        $mpdf->WriteHTML($tabela);

        $mpdf->Output();
        die();
    }

    function ordena_por_programa($a, $b) {
        if ($a->codigo_programa == $b->codigo_programa) {
            return 0;
        } else if ($a->codigo_programa < $b->codigo_programa) {
            return -1;
        } else {
            return 1;
        }
    }

    function ordena_por_orgao($a, $b) {
        if ($a->orgao == $b->orgao) {
            return 0;
        } else if ($a->orgao < $b->orgao) {
            return -1;
        } else {
            return 1;
        }
    }

    function ler_pdf() {
        $id = $this->input->get_post('id', TRUE);
        $nome = $this->input->get_post('nome', TRUE);
        $this->load->model('usuario_model');
        $logado = $this->usuario_model->logged(1);
        $usuario_logado = $this->usuario_model->get_by_login($this->session->userdata('login'));
        if ($this->usuario_model->verifica_relatorio($id, $usuario_logado->idPessoa) == true) {
            $pdf = 'configuracoes/' . $nome . '.pdf';
            header('Content-type: application/pdf');
            readfile($pdf);
            die();
        }
        $this->alert("Sem permissões para acessar esse arquivo");
        $this->voltaPagina();
    }

    function cidades_ajax() {
        $cod_estados = mysql_real_escape_string($_REQUEST['cod_estados']);

        $this->load->model('cidades_model');
        $cidades_aux = $this->cidades_model->obter_cidades_inverso($this->obterEstadoNome($this->obterEstadoInverso($cod_estados)));

        foreach ($cidades_aux as $cidade) {
            $cidades[] = array(
                'cod_cidades' => $cidade['Codigo'],
                'Codigo' => $cidade['Codigo'],
                'nome' => $cidade['Nome'],
            );
        }

        echo( json_encode($cidades) );
    }

    function index() {
        if ($this->usuario_logado->tipoPessoa == 5) {
            redirect('in/usuario/proposta_alteracoes');
        } else if ($this->usuario_logado->tipoPessoa == 6) {

            if ($this->usuario_logado->validade < date('Y-m-d')) {
                $this->alert("Contrato expirado. Efetue uma nova compra para continuar utilizando nosso serviço");
                $this->encaminha('../inicio/compra_passe?id=' . $this->usuario_logado->idPessoa);
                //redirect('inicio/compra_passe?id='.$query->idPessoa);
            } else
                redirect('in/usuario/proposta_aberta');
        }
        else if ($this->usuario_logado->tipoPessoa == 7) {
            //echo $this->usuario_logado->validade." - ".date('Y-m-d');
            if ($this->usuario_logado->validade < date('Y-m-d')) {
                $this->alert("Contrato expirado. Efetue uma nova compra para continuar utilizando nosso serviço");
                $this->encaminha('../inicio/compra_passe?id=' . $this->usuario_logado->idPessoa);
            } else
                redirect('in/usuario/proposta_aberta');
        }
        else if ($this->usuario_logado->tipoPessoa == 8) {
            //echo $this->usuario_logado->validade." - ".date('Y-m-d');
            if ($this->usuario_logado->validade < date('Y-m-d')) {
                $this->alert("Contrato expirado. Efetue uma nova compra para continuar utilizando nosso serviço");
                $this->encaminha('../inicio/compra_passe?id=' . $this->usuario_logado->idPessoa);
            } else
                redirect('in/usuario/lista_relatorios');
        }
        else if ($this->usuario_logado->tipoPessoa == 9)
            redirect('in/usuario/lista_relatorios');

        $usuario = $this->usuario_logado->idPessoa;
        $this->load->model('trabalho_model');
        $this->load->model('proposta_model');
        $data['trabalhos_foreach'] = $this->trabalho_model->obter_por_usuario($usuario);
        $data['trabalhos'] = $this->trabalho_model;
        $data['propostas'] = $this->proposta_model;
        $data['usuario'] = $usuario;

        if ($this->input->get_post('proposta', TRUE) !== false) {
            $trabalho = $this->input->get_post('trabalho', TRUE);
            $proposta = $this->input->get_post('proposta', TRUE);

            if ((int) $proposta > 0 && (int) $trabalho > 0) {
                $data['trabalhos_foreach'] = $this->trabalho_model->obter_por_trabalho_e_proposta('', '', $proposta, $usuario, $trabalho);
            } else if ((int) $proposta > 0) {
                $data['trabalhos_foreach'] = $this->trabalho_model->obter_proposta_por_usuario('', '', $proposta, $usuario);
            } else if ((int) $trabalho > 0) {
                $data['trabalhos_foreach'] = $this->trabalho_model->obter_por_trabalho('', '', null, $usuario, $trabalho);
            }
        }
        $data['title'] = "Physis - Usuário";
        $data['main'] = 'usuario/visualiza_propostas';
        $data['login'] = $this->login;
        $this->load->view('in/template', $data);
    }

    function programas() {
        $this->load->model('programa_model');
        $this->load->model('usuario_model');
        $logado = $this->usuario_model->logged(3);
        $usuario_logado = $this->usuario_model->get_by_login($this->session->userdata('login'));
        $data['lista'] = null;

        if ($this->input->get_post('operation', TRUE) !== false) {
            $this->programa_model->apagar_dados_usuario_programa_nao_aceitos($usuario_logado->idPessoa);
            foreach ($this->input->post(NULL, TRUE) as $key => $prog) {
                if ($key != 'operation' && $key != 'todos_estado' && $key != 'todos_municipio' && $key != 'usuario') {
                    //echo $key.' => '.$prog.'<br />';
                    $data = array(
                        'idPessoa' => $usuario_logado->idPessoa,
                        'codigoPrograma' => $prog
                    );
                    $inserido = $this->programa_model->aceita_programa_usuario($data);
                }
            }
        }
        $permite_edicao = $this->programa_model->programa_usuario_permissao($usuario_logado->idPessoa);
        if ($permite_edicao == true) {
            $data['lista'] = $this->programa_model->obter_programas_por_usuario($usuario_logado->idPessoa);
            $data['main'] = 'usuario/listar_programas';
        } else {
            $data['lista'] = $this->programa_model->obter_programas_por_usuario_aceito($usuario_logado->idPessoa);
            $data['main'] = 'usuario/programas';
        }
        $data['login'] = "Olá, " . $usuario_logado->nome . " - <a href=\"" . base_url() . "index.php/in/login/sair\">Sair</a>";
        $data['title'] = "Physis - Gestão de Usuários e Propostas";
        $this->load->view('in/template', $data);
    }

    function relatorio() {
        $data['title'] = "Physis - Gestão de Usuários e Propostas";
        $data['main'] = 'usuario/relatorio';
        $this->load->model('programa_model');
        $this->load->model('usuario_model');
        $logado = $this->usuario_model->logged(3);
        $usuario_logado = $this->usuario_model->get_by_login($this->session->userdata('login'));
        $data['login'] = "Olá, " . $usuario_logado->nome . " - <a href=\"" . base_url() . "index.php/in/login/sair\">Sair</a>";
        $data['lista'] = null;
        if ($logado == true)
            $data['lista'] = $this->programa_model->obter_programas_por_usuario_aceito($usuario_logado->idPessoa);
        $this->load->view('in/template', $data);
    }

    function solicitar_mudanca() {
        $this->load->model('programa_model');
        $this->load->model('usuario_model');
        $usuario_logado = $this->usuario_model->get_by_login($this->session->userdata('login'));
        $texto = "O usuário " . $usuario_logado->nome . " solicitou uma permissão de alteração de dados de programas";
        $assunto = "Solicitação de permissão de alteração dos programas";
        //$this->programa_model->envia_email('adm@physisbrasil.com.br', $usuario_logado->email, $texto, $assunto);
        $this->programa_model->envia_email('adm@physisbrasil.com.br', $usuario_logado->email, $texto, $assunto);

        $this->alert("O gestor será avisado sobre a solicitação, por favor aguarde a definição do gestor");
        $this->voltaPagina();
    }

    function gerapdf() {
        //$this->load->library('FPDF');
        $this->load->library('mPDF');
        //$this->load->model('pdf_model');

        $data['title'] = "Physis - Gestão de Usuários e Propostas";
        $this->load->model('programa_model');
        $this->load->model('usuario_model');
        $logado = $this->usuario_model->logged(3);
        $usuario_logado = $this->usuario_model->get_by_login($this->session->userdata('login'));
        $programas = null;
        if ($logado == true)
            $programas = $this->programa_model->obter_programas_por_usuario_aceito($usuario_logado->idPessoa);

        ob_start();  //inicia o buffer
        $tabela = utf8_encode($tabela);

        $mpdf = new mPDF();
        $mpdf->allow_charset_conversion = true;
        $mpdf->charset_in = 'UTF-8';
        $tabela = "PHYSIS BRASIL CONSULTORIA E ASSESSORIA SOLIDÁRIA";
        foreach ($programas as $programa) {
            $tabela .= "<br /><br /><table style=\"font-size: 13px;\">";
            $tabela .= "<tr><td><b>CÓDIGO DO PROGRAMA:</b></td><td colspan=3>" . $programa->codigo . "</td></tr>";
            $tabela .= "<tr><td><b>ÓRGÃO SUPERIOR: </b></td><td colspan=3 bgcolor=\"grey\">" . $programa->orgao . "</td></tr>";
            $tabela .= "<tr><td><b>ÓRGÃO PROVENENTE: </b></td><td colspan=3>" . $programa->orgao_vinculado . "</td></tr>";
            $tabela .= "<tr><td><b>INÍCIO VIGÊNCIA: </b></td><td>" . implode("/", array_reverse(explode("-", $programa->data_inicio))) . "</td>";
            $tabela .= "<td><b>FIM VIGÊNCIA: </b> " . implode("/", array_reverse(explode("-", $programa->data_fim))) . "</td>";
            $tabela .= "<td><b>QUALIFICAÇÃO: </b> " . $programa->qualificacao . "</td></tr>";
            $tabela .= "<tr><td><b>PROGRAMA: </b></td><td colspan=3>" . $programa->nome . "</td></tr>";
            $tabela .= "</table> <font style=\"font-size: 13px;\">";
            $tabela .= $programa->descricao . "<br>";
            $tabela .= "<b>Obs.: </b> " . $programa->observacao . "<br>";
            $tabela .= "<b>INDICADO PARA: </b> " . $programa->atende . "<br></font>";
        }
        $mpdf->WriteHTML($tabela);
        $mpdf->Output();
        /*
          $pdf = new pdf_model();
          $pdf->insereDados($programas);
          $pdf->SetXY(1,2,true);
          $pdf->AliasNbPages();
          $pdf->SetFont('Times','',16); */
        $pdf->Output();

        die();
    }

    function carrega_programas() {
        $data['title'] = "Physis - Gestão de Usuários e Propostas";
        $data['main'] = 'in/programas_tabela';
        $anterior = array();
        $remotePageUrl = 'https://www.convenios.gov.br/siconv/ListarProgramasPrincipal/ListarProgramasPrincipal.do';
        $this->obter_paginaLogin();
        $this->obter_pagina($remotePageUrl);
        $documento = $this->obter_pagina($remotePageUrl);

        preg_match_all("/href\=\"([a-zA-Z_\.0-9\/\-\! :\&\-\;\@\$\=\?]*)\"/i", $documento, $matches);
        $orgaos = $this->getTextBetweenTags($documento, "<\/span> ", "<\/a><\/li>");

        foreach ($matches[1] as $key => $pag) {
            if ($key > 0 && $key < 4) {
                echo "https://www.convenios.gov.br" . $pag . " - " . $orgaos[$key - 1] . "<br />";
                $pag = str_replace("&amp;", "&", $pag);
                $anterior[0] = $remotePageUrl;
                $this->imprimeDetalhePrograma_bd("https://www.convenios.gov.br" . $pag, $orgaos[$key - 1], $listaPrograma, $anterior);
                $this->obter_pagina($remotePageUrl);
                $documento1 = $this->obter_pagina("https://www.convenios.gov.br" . $pag);

                preg_match_all("/href\=\"([a-zA-Z_\.0-9\/\-\! :\&\-\;\@\$\=\?]*)\"/i", $documento1, $matches1);
                foreach ($matches1[1] as $pag1) {
                    if (strstr($pag1, 'resultado-da-consulta-de-programas-de-convenio.jsp?id=') !== false) {
                        $this->imprimeDetalhePrograma_bd(str_replace("&amp;", "&", $pag1), $orgaos[$key - 1], $listaPrograma, $anterior);
                    }
                }
                $url_sem_espaco = $this->removeSpaceSurplus($documento1);
                $prox = $this->getTextBetweenTags($url_sem_espaco, " \[<a href=\"", "\">Pr");
                //echo count($prox);
                $anterior1 = array();
                $anterior2 = array();
                while (count($prox) > 0) {
                    //echo "https://www.convenios.gov.br".$prox[0]."<br />";
                    $prox[0] = str_replace("&amp;", "&", $prox[0]);
                    $anterior1[0] = "https://www.convenios.gov.br" . $pag;
                    $anterior2[0] = "https://www.convenios.gov.br" . $pag;
                    $this->imprimeDetalhePrograma_bd("https://www.convenios.gov.br" . $prox[0], $orgaos[$key - 1], $listaPrograma, $anterior1);
                    $this->obter_pagina("https://www.convenios.gov.br" . $pag);
                    $documento1 = $this->obter_pagina("https://www.convenios.gov.br" . $prox[0]);
                    $anterior2[1] = "https://www.convenios.gov.br" . $prox[0];
                    preg_match_all("/href\=\"([a-zA-Z_\.0-9\/\-\! :\&\-\;\@\$\=\?]*)\"/i", $documento1, $matches1);
                    foreach ($matches1[1] as $pag1) {
                        if (strstr($pag1, 'resultado-da-consulta-de-programas-de-convenio.jsp?id=') !== false) {
                            $this->imprimeDetalhePrograma_bd(str_replace("&amp;", "&", $pag1), $orgaos[$key - 1], $listaPrograma, $anterior2);
                        }
                    }
                    $this->obter_pagina("https://www.convenios.gov.br" . $pag);
                    $documento2 = $this->obter_pagina("https://www.convenios.gov.br" . $prox[0]);
                    $prox = $this->getTextBetweenTags($documento2, " \[<a href=\"", "\">Pr");
                }
            }
        }

        die();
    }

    function adiciona() {

        $data['title'] = "Physis - Adicionar Usuário";
        $data['main'] = 'in/adiciona_usuario';
        $data['login'] = $this->login;
        $this->load->view('in/template', $data);
    }

    function visualiza_proposta() {
        $this->load->model('proposta_model');
        $this->load->model('trabalho_model');
        $this->load->model('cnpj_siconv');
        $this->load->model('usuariomodel');
        $this->load->model('programa_proposta_model');
        $id = $this->input->get_post('id', TRUE);
        $proposta = $this->proposta_model->get_by_id($id);
        $justificativa = $this->trabalho_model->obter_justificativa_por_proposta($id);

        $this->session->set_userdata('linha_panel', $this->input->get('id', TRUE));

        $bens = $this->trabalho_model->obter_despesas($id);

        if ($proposta->padrao && $this->session->userdata('nivel') != 1) {
            if ($this->session->userdata('sistema') != 'P') {
                $cnpj = $this->usuariomodel->get_cnpjs_by_usuario($this->session->userdata('id_usuario'));
                $codigo_cidade_endereco = $this->cnpj_siconv->get_cidade_by_cnpj_siconv($cnpj[0]->cnpj)->Codigo;
                $justificativa->Justificativa = $this->subtitui_tags_justificativa($codigo_cidade_endereco, $justificativa->Justificativa);
            }
        }

        $data['programa_proposta_model'] = $this->programa_proposta_model;
        $data['login'] = $this->login;
        $data['proposta'] = $proposta;
        $data['justificativa'] = $justificativa;
        $data['bens'] = $bens;
        $data['id'] = $id;
        $data['trabalho_model'] = $this->trabalho_model;
        $data['main'] = 'usuario/visualiza_proposta';
        $data['bancos'] = $this->proposta_model->obter_bancos();
        $data['title'] = 'Physis - Visualizar Proposta';
        $this->load->vars($data);
        $this->load->view('in/template_projeto');
    }

    function subtitui_tags_justificativa($codigo_cidade_endereco, $texto) {

        $this->load->model('cidade_tag');
        $cidade_tag = $this->cidade_tag->get_cidade_tag_from_nome_cidade($codigo_cidade_endereco);

        if ($cidade_tag != null) {
            $texto = str_replace('[cod_ibge]', $cidade_tag->cod_ibge, $texto);
            $texto = str_replace('[cidade]', $cidade_tag->cidade, $texto);
            $texto = str_replace('[estado]', $cidade_tag->estado, $texto);
            $texto = str_replace('[gentilico]', $cidade_tag->gentilico, $texto);
            $texto = str_replace('[mesorregiao]', $cidade_tag->mesoregiao, $texto);
            $texto = str_replace('[microrregiao]', $cidade_tag->microregiao, $texto);
            $texto = str_replace('[area]', number_format($cidade_tag->area, 2, ",", "."), $texto);
            $texto = str_replace('[densidade]', number_format($cidade_tag->densidade, 2, ",", "."), $texto);
            $texto = str_replace('[populacao]', number_format($cidade_tag->populacao, 0, ",", "."), $texto);
            $texto = str_replace('[idhm]', number_format($cidade_tag->idhm, 2, ",", "."), $texto);
            $texto = str_replace('[pib_corrente]', number_format($cidade_tag->pib_corrente, 2, ",", "."), $texto);
            $texto = str_replace('[pib_per_capita]', number_format($cidade_tag->pib_per_capita, 2, ",", "."), $texto);
            $texto = str_replace('[ano_estimativa]', $cidade_tag->ano_estimativa, $texto);

            return $texto;
        } else {
            return $texto;
        }
    }

    function incluir_justificativa() {
        $this->load->model('proposta_model');
        $this->load->model('trabalho_model'); //localhost/physisSiconv/index.php/in/usuario/incluir_justificativa?id=1&edit=1&justificativa=1

        $data['texto_padrao'] = "O Município de [cidade] - [estado], está localizado na Mesorregião [mesorregiao], inserida na Microrregião [microrregiao]. Possui uma área territorial de [area] Km², com densidade demográfica de [densidade] (hab/Km²). Segundo dados estimados pelo IBGE ([ano_estimativa]), sua população residente é de [populacao] habitantes, com Índice de Desenvolvimento Humano Municipal - 2010 (IDHM 2010) de [idhm] e o PIB do município é de R$ [pib_corrente] a preços correntes, com um PIB per capita de R$ [pib_per_capita].";

        if ($this->session->userdata('nivel') != 1) {
            $cnpj = $this->usuariomodel->get_cnpjs_by_usuario($this->session->userdata('id_usuario'));
            if ($cnpj[0]->id_cidade == '16115') {
                $codigo_cidade_endereco = $cnpj[0]->id_cidade;
            } else {
                $codigo_cidade_endereco = $this->cnpj_siconv->get_cidade_by_cnpj_siconv($cnpj[0]->cnpj)->Codigo;
            }
            $data['texto_padrao'] = $this->subtitui_tags_justificativa($codigo_cidade_endereco, $data['texto_padrao']);
        }

        if ($this->input->get_post('id', TRUE) !== false && $this->input->get_post('edit', TRUE) !== false && $this->input->get_post('justificativa', TRUE) !== false) {
            $id = $this->input->get_post('id', TRUE);
            $justificativa = $this->input->get_post('justificativa', TRUE);
            $proposta = $this->proposta_model->get_by_id($id);
            $justificativa = $this->trabalho_model->obter_justificativa_por_id($justificativa);
            $options = array(
                'Proposta_idProposta' => $this->input->get_post('id', TRUE),
                'Justificativa' => $this->proposta_model->replace_chars($this->input->get_post('Justificativa', TRUE)),
                'objeto' => $this->proposta_model->replace_chars($this->input->get_post('objeto', TRUE)),
                'capacidade' => $this->input->get_post('capacidade', TRUE),
                'necessita_completar' => $this->input->post('necessita_completar', TRUE)
            );
            switch ($this->get_post_action('cadastra', 'envia', 'aprova', 'corrige', 'avanca')) {
                case 'cadastra':
                    if (isset($justificativa->idJustificativa) !== false)
                        $idJustificativa = $justificativa->idJustificativa;
                    else
                        $idJustificativa = null;
                    $this->trabalho_model->add_justificativa($options, $idJustificativa);
                    $justificativa = $this->trabalho_model->obter_justificativa_por_proposta($id);
                    break;
                case 'avanca':
                    if (isset($justificativa->idJustificativa) !== false)
                        $idJustificativa = $justificativa->idJustificativa;
                    else
                        $idJustificativa = null;
                    $this->trabalho_model->add_justificativa($options, $idJustificativa);
                    $justificativa = $this->trabalho_model->obter_justificativa_por_proposta($id);

                    redirect('in/usuario/listar_metas?id=' . $id . '&edita_gestor=1');
                    break;
                case 'envia':
                    $this->trabalho_model->altera_status_trabalho($id, null, 1, 3);
                    break;
                case 'aprova':
                    $this->trabalho_model->altera_status_trabalho($id, null, 1, 5);
                    break;
                case 'corrige':
                    $this->trabalho_model->altera_status_trabalho($id, null, 1, 4);
                    break;
            }

            $idTrabalho = $this->trabalho_model->obter_por_trabalho('', '', $id, null, 1);
            $data['observacao'] = $this->trabalho_model->obter_observacao($idTrabalho[0]->idTrabalho);
            if ($this->usuario_logado->id_usuario == $this->proposta_model->get_by_id($id)->idGestor)
                $data['voltar_gestor'] = 1;
            else
                $data['voltar_gestor'] = 0;
            $data['edita_gestor'] = 0;
            if ($this->input->get_post('edita_gestor', TRUE) == 1)
                $data['edita_gestor'] = 1;

            $data['idTrabalho'] = $idTrabalho[0];
            $data['proposta'] = $proposta;
            $data['justificativa'] = $justificativa;
            $data['id'] = $id;
            $data['title'] = "Physis - Incluir Justificativa";
            $data['main'] = 'in/incluir_justificativa';
            $data['login'] = $this->login;
            $this->load->view('in/template_projeto', $data);
        } else if ($this->input->get_post('id', TRUE) !== false) {
            $id = $this->input->get_post('id', TRUE);
            $proposta = $this->proposta_model->get_by_id($id);
            $justificativa = $this->trabalho_model->obter_justificativa_por_proposta($id);
            $options = array(
                'Proposta_idProposta' => $this->input->get_post('id', TRUE),
                'Justificativa' => $this->proposta_model->replace_chars($this->input->get_post('Justificativa', TRUE)),
                'objeto' => $this->proposta_model->replace_chars($this->input->get_post('objeto', TRUE)),
                'capacidade' => $this->input->get_post('capacidade', TRUE),
                'necessita_completar' => $this->input->post('necessita_completar', TRUE)
            );
            switch ($this->get_post_action('cadastra', 'envia', 'aprova', 'corrige', 'avanca')) {
                case 'cadastra':
                    if (isset($justificativa->idJustificativa) !== false)
                        $idJustificativa = $justificativa->idJustificativa;
                    else
                        $idJustificativa = null;
                    $this->trabalho_model->add_justificativa($options, $idJustificativa);
                    $justificativa = $this->trabalho_model->obter_justificativa_por_proposta($id);
                    break;
                case 'avanca':
                    if (isset($justificativa->idJustificativa) !== false)
                        $idJustificativa = $justificativa->idJustificativa;
                    else
                        $idJustificativa = null;
                    $this->trabalho_model->add_justificativa($options, $idJustificativa);
                    $justificativa = $this->trabalho_model->obter_justificativa_por_proposta($id);

                    $this->encaminha(base_url() . 'index.php/in/usuario/listar_metas?id=' . $id . '&edita_gestor=1');
                    break;
                case 'envia':
                    if (count($this->trabalho_model->obter_justificativa_por_proposta($id)) > 0)
                        $this->trabalho_model->altera_status_trabalho($id, null, 1, 3);
                    else {
                        $this->alert("Pimeiro cadastre a justificativa, depois envie para o gestor.");
                        $this->voltaPagina();
                    }
                    break;
                case 'aprova':
                    $this->trabalho_model->altera_status_trabalho($id, null, 1, 5);
                    break;
                case 'corrige':
                    $this->trabalho_model->altera_status_trabalho($id, null, 1, 4, $this->input->get_post('obs_gestor', TRUE));
                    break;
            }
            $idTrabalho = $this->trabalho_model->obter_por_trabalho('', '', $id, null, 1);
            if ($idTrabalho != null)
                $data['observacao'] = $this->trabalho_model->obter_observacao($idTrabalho[0]->idTrabalho);
            if ($this->usuario_logado->id_usuario == $this->proposta_model->get_by_id($id)->idGestor)
                $data['voltar_gestor'] = 1;
            else
                $data['voltar_gestor'] = 0;
            $data['edita_gestor'] = 0;
            if ($this->input->get_post('edita_gestor', TRUE) == 1)
                $data['edita_gestor'] = 1;
            if ($idTrabalho != null)
                $data['idTrabalho'] = $idTrabalho[0];
            $data['leitura_pessoa'] = false;

            $data['justificativa'] = $justificativa;
            $data['proposta'] = $proposta;
            $data['id'] = $id;
            $data['title'] = "Physis - Incluir Justificativa";
            $data['main'] = 'in/incluir_justificativa';
            $data['login'] = $this->login;
            $this->load->view('in/template_projeto', $data);
        }
        else {
            $this->voltaPagina();
        }
    }

    function apaga_meta() {
        $this->load->model('trabalho_model');

        if ($this->input->get_post('meta', TRUE) !== false) {
            if ($this->trabalho_model->verifica_meta_foi_associado($this->input->get_post('meta', TRUE))) {
                $this->alert("Desassocie os valores da meta para poder exclui-la.");
                $this->voltaPagina();
                exit();
            } else {
                $meta = $this->input->get_post('meta', TRUE);
                $this->trabalho_model->apagar_meta($meta);
            }
        }

        if ($this->input->get_post('edita_gestor', TRUE) == 1)
            $this->encaminha('listar_metas?id=' . $this->input->get_post('id', TRUE) . '&edita_gestor=1');
        $this->encaminha('listar_metas?id=' . $this->input->get_post('id', TRUE));
    }

    function apaga_etapa() {
        $this->load->model('trabalho_model');
        if ($this->input->get_post('meta', TRUE) !== false && $this->input->get_post('etapa', TRUE) !== false) {
            $etapa = $this->input->get_post('etapa', TRUE);
            if ($this->trabalho_model->verifica_etapa_foi_associada($etapa)) {
                $this->alert("Desassocie os valores da etapa no cronodesembolso para poder exclui-la");
                $this->voltaPagina();
                exit();
            } else
                $this->trabalho_model->apagar_etapa($etapa);
        }
        if ($this->input->get_post('edita_gestor', TRUE) == 1)
            $this->encaminha('listar_etapas?meta=' . $this->input->get_post('meta', TRUE) . '&id=' . $this->input->get_post('id', TRUE) . '&edita_gestor=1');
        $this->encaminha('listar_etapas?meta=' . $this->input->get_post('meta', TRUE) . '&id=' . $this->input->get_post('id', TRUE));
    }

    function listar_metas() {
        $this->load->model('proposta_model');
        $this->load->model('trabalho_model');
        if ($this->input->get_post('id', TRUE) !== false) {
            $data['valorGlobal'] = $this->proposta_model->get_by_id($this->input->get_post('id', TRUE))->valor_global - $this->proposta_model->get_by_id($this->input->get_post('id', TRUE))->contrapartida_bens;

            $id = $this->input->get_post('id', TRUE);
            $metas = $this->trabalho_model->obter_metas_proposta($id);
            switch ($this->get_post_action('cadastra', 'envia', 'aprova', 'corrige')) {
                case 'envia':
                    $total = 0;
                    $valor_global = $this->proposta_model->get_by_id($id)->valor_global - $this->proposta_model->get_by_id($id)->contrapartida_bens;
                    $flag = false;
                    foreach ($metas as $chave => $meta) {
                        $total += $meta->total;
                        $etapas = $this->trabalho_model->obter_etapas_meta_proposta($meta->idMeta);
                        $total_etapa = 0;
                        foreach ($etapas as $etapa) {
                            $total_etapa += $etapa->total;
                        }

                        if (number_format(doubleval($meta->total), 2, '.', "") != number_format(doubleval($total_etapa), 2, '.', "")) {
                            $this->alert("Valor da meta" . ++$chave . " = " . $meta->total . " e o valor das etapas = " . $total_etapa . ". Reveja as etapas para que o valor seja igual o valor da meta.");
                            $flag = true;
                        }
                    }

                    if (number_format(doubleval($valor_global), 2, '.', "") != number_format(doubleval($total), 2, '.', "")) {
                        $this->alert("Valor global (retirando a contrapartida de bens) = " . $valor_global . " e o valor das metas = " . $total . ". Reveja as metas para que o valor seja igual o valor da proposta.");
                    } else if ($flag == false) {
                        $this->trabalho_model->altera_status_trabalho($id, null, 2, 3);
                        $this->alert("Trabalho enviado para o Gestor, aguarde o retorno.");
                    }
                    break;
                case 'aprova':
                    $this->trabalho_model->altera_status_trabalho($id, null, 2, 5);
                    break;
                case 'corrige':
                    $this->trabalho_model->altera_status_trabalho($id, null, 2, 4, $this->input->get_post('obs_gestor', TRUE));
                    break;
            }
            $idTrabalho = $this->trabalho_model->obter_por_trabalho('', '', $id, null, 2);

            if ($idTrabalho != null)
                $data['observacao'] = $this->trabalho_model->obter_observacao($idTrabalho[0]->idTrabalho);

            if ($this->usuario_logado->id_usuario == $this->proposta_model->get_by_id($id)->idGestor) {
                $data['voltar_gestor'] = 1;
            } else {
                $data['voltar_gestor'] = 0;
            }

            $data['edita_gestor'] = 0;
            if ($this->input->get_post('edita_gestor', TRUE) == 1) {
                $data['edita_gestor'] = 1;
            }
            $data['trabalho'] = $this->trabalho_model;
            if ($idTrabalho != null)
                $data['idTrabalho'] = $idTrabalho[0];
            $data['metas'] = $metas;
            $data['id'] = $id;
            $data['leitura_pessoa'] = false;
            $data['title'] = "Physis - Listar Metas da proposta";
            $data['main'] = 'in/listar_metas';
            $data['login'] = $this->login;
            $this->load->view('in/template_projeto', $data);
        } else {
            $this->voltaPagina();
        }
    }

    function aceitar_trabalho() {
        //$this->load->model('proposta_model');
        $this->load->model('trabalho_model');
        if ($this->input->get_post('idTrabalho', TRUE) !== false) {
            $idTrabalho = $this->input->get_post('idTrabalho', TRUE);
            $this->trabalho_model->altera_status_trabalho_id($idTrabalho, 2);
        }
        $this->encaminha('index');
    }

    function recusar_trabalho() {
        //$this->load->model('proposta_model');
        $this->load->model('trabalho_model');
        if ($this->input->get_post('idTrabalho', TRUE) !== false) {
            $idTrabalho = $this->input->get_post('idTrabalho', TRUE);
            $this->trabalho_model->altera_status_trabalho_id($idTrabalho, 0);
        }
        $this->encaminha('index');
    }

    function meta() {

        $this->load->model('proposta_model');
        $this->load->model('trabalho_model');
        $this->load->model('programa_proposta_model');

        if ($this->input->get_post('id', TRUE) !== false) {
            $data['valorGlobal'] = $this->proposta_model->get_by_id($this->input->get_post('id', TRUE))->valor_global - $this->proposta_model->get_by_id($this->input->get_post('id', TRUE))->contrapartida_bens;
            $data['metas'] = $this->trabalho_model->obter_metas_proposta($this->input->get_post('id', TRUE));

            $data['programas_proposta'] = $this->programa_proposta_model->get_programas_by_proposta($this->input->get_post('id', TRUE));
        }

        $valores_programas = $this->programa_proposta_model->get_valores_programas_meta($this->input->get('id', TRUE));
        $data['valores_programas'] = $valores_programas;

        $proposta = $this->proposta_model->get_by_id($this->input->get_post('id', TRUE));

        if ($this->input->get_post('id', TRUE) !== false && $this->input->get_post('meta', TRUE) === false) {
            $id = $this->input->get_post('id', TRUE);
            $programa = $this->programa_proposta_model->get_by_proposta_programa($id, $this->input->get_post('id_programa', TRUE));
            $metas = $this->trabalho_model->obter_metas_proposta($id, $this->input->get_post('id_programa', TRUE));
            $data_inicio = implode("-", array_reverse(explode("/", $this->input->get_post('data_inicio', TRUE))));
            $data_termino = implode("-", array_reverse(explode("/", $this->input->get_post('data_termino', TRUE))));

            $total = str_replace(".", "", $this->input->get_post('valor', TRUE));
            $total = str_replace(",", ".", $total);
            $quantidade = str_replace(".", "", $this->input->get_post('quantidade', TRUE));
            $quantidade = str_replace(",", ".", $quantidade);
            $valorUnitario = str_replace(".", "", $this->input->get_post('valorUnitario', TRUE));
            $valorUnitario = str_replace(",", ".", $valorUnitario);
            if ($this->trabalho_model->verifica_uf_siconv($this->input->get_post('fornecimento', TRUE)) == false) {
                $this->alert("Unidade de fornecimento incorreto. Reveja esse item");
                $this->voltaPagina();
            }
            $options = array(
                'Proposta_idProposta' => $this->input->get_post('id', TRUE),
                'especificacao' => $this->proposta_model->replace_chars($this->input->get_post('especificacao', TRUE)),
                'fornecimento' => $this->proposta_model->replace_chars($this->input->get_post('fornecimento', TRUE)),
                'total' => $total,
                'quantidade' => $quantidade,
                'valorUnitario' => $valorUnitario,
                'data_inicio' => $data_inicio,
                'data_termino' => $data_termino,
                'UF' => $this->input->get_post('UF', TRUE),
                'municipio_sigla' => $this->input->get_post('municipio_sigla', TRUE),
                'municipio_nome' => $this->input->get_post('municipio_nome', TRUE),
                'endereco' => $this->proposta_model->replace_chars($this->input->get_post('endereco', TRUE)),
                'cep' => $this->input->get_post('cep', TRUE),
                'id_programa' => $this->input->get_post('id_programa', TRUE)
            );

            switch ($this->get_post_action('cadastra', 'envia')) {
                case 'cadastra':
                    $total_soma = 0;
                    $valor_global = $programa->valor_global - $programa->contrapartida_bens;
                    foreach ($metas as $chave => $meta1) {
                        $total_soma += $meta1->total;
                    }
                    $total_soma += doubleval($total);
                    if (strtotime($data_inicio) < strtotime($proposta->data_inicio) || strtotime($data_inicio) > strtotime($proposta->data_termino) || strtotime($data_termino) < strtotime($proposta->data_inicio) || strtotime($data_termino) > strtotime($proposta->data_termino)) {
                        $this->alert("Reveja as datas e insira valores no intervalo da proposta");
                    } else if (number_format(doubleval($valor_global), 2, '.', "") < number_format(doubleval($total_soma), 2, '.', "")) {
                        $this->alert("Valor global do programa (retirando a contrapartida de bens) = " . $valor_global . " e o valor das metas = " . $total_soma . ". Reveja as metas para que o valor seja igual o valor da proposta.");
                    } else {
                        $this->trabalho_model->add_meta($options);

                        if (number_format(doubleval($valor_global), 2, '.', "") == number_format(doubleval($total_soma), 2, '.', ""))
                            $this->alert("Valor das metas alcançou o valor da proposta.");
                        if ($this->input->get_post('edita_gestor', TRUE) == 1)
                            $this->encaminha('listar_metas?id=' . $id . '&edita_gestor=1');
                        $this->encaminha('listar_metas?id=' . $id . '&edita_gestor=1');
                    }

                    $this->voltaPagina();
                    break;
                case 'envia':
                    echo 'envia';
                    break;
            }
            //THOMAS: Forçando sempre editar gestor. Não tem mais que encaminhar para aprovação de outro.
            $data['edita_gestor'] = 1;

            // adicionado para que seja informada a data inicial e final do projeto como sugestão para o cadastro da meta
            $id = $this->input->get_post('id', TRUE);
            $meta = $this->proposta_model->retorna_datas_projeto($id);
            $data['meta'] = $meta;

            /* if ($this->input->get_post('edita_gestor', TRUE) == 1)
              $data['edita_gestor'] = 1; */
            $data['cidades'] = $this->trabalho_model->obter_cidades_siconv();
            $data['ufs'] = $this->trabalho_model->obter_uf_siconv();
            $data['proposta'] = $proposta;
            $data['enderecos'] = $this->trabalho_model->obter_enderecos($this->usuario_logado->id_usuario);
            $data['id'] = $id;
            $data['endereco_proposta'] = $this->trabalho_model->obter_endereco_por_id($id);
            $data['leitura_pessoa'] = false;
            $data['title'] = "Physis - Incluir Justificativa";
            $data['main'] = 'in/meta';
            $data['login'] = $this->login;
            $this->load->view('in/template_projeto', $data);
        } else if ($this->input->get_post('meta', TRUE) !== false) {
            /* Verifica se existe alguma meta associada ao desembolso, caso tenha informa que deverá ser feita a desassociação */
//         	if($this->input->get_post('cadastra', TRUE) != false){
// 	        	if($this->trabalho_model->verifica_meta_foi_associado($this->input->get_post('meta', TRUE))){
// 	        		$this->alert("Desassocie os valores da meta para poder edita-la.");
// 	        		$this->voltaPagina();
// 	        		exit();
// 	        	}
//         	}

            $meta = $this->input->get_post('meta', TRUE);
            $id = $this->input->get_post('id', TRUE);
            $programa = $this->programa_proposta_model->get_by_proposta_programa($id, $this->input->get_post('id_programa', TRUE));
            $metas = $this->trabalho_model->obter_metas_proposta($id, $this->input->get_post('id_programa', TRUE));
            $data_inicio = implode("-", array_reverse(explode("/", $this->input->get_post('data_inicio', TRUE))));
            $data_termino = implode("-", array_reverse(explode("/", $this->input->get_post('data_termino', TRUE))));

            $total = str_replace(".", "", $this->input->get_post('valor', TRUE));
            $total = str_replace(",", ".", $total);
            $quantidade = str_replace(".", "", $this->input->get_post('quantidade', TRUE));
            $quantidade = str_replace(",", ".", $quantidade);
            $valorUnitario = str_replace(".", "", $this->input->get_post('valorUnitario', TRUE));
            $valorUnitario = str_replace(",", ".", $valorUnitario);
            if ($this->trabalho_model->verifica_uf_siconv($this->input->get_post('fornecimento', TRUE)) == false) {
                $this->alert("Unidade de fornecimento incorreto. Reveja esse item");
                $this->voltaPagina();
            }
            $options = array(
                'Proposta_idProposta' => $this->input->get_post('id', TRUE),
                'especificacao' => $this->proposta_model->replace_chars($this->input->get_post('especificacao', TRUE)),
                'fornecimento' => $this->proposta_model->replace_chars($this->input->get_post('fornecimento', TRUE)),
                'total' => $total,
                'quantidade' => $quantidade,
                'valorUnitario' => $valorUnitario,
                'data_inicio' => $data_inicio,
                'data_termino' => $data_termino,
                'UF' => $this->input->get_post('UF', TRUE),
                'municipio_sigla' => $this->input->get_post('municipio_sigla', TRUE),
                'municipio_nome' => $this->input->get_post('municipio_nome', TRUE),
                'endereco' => $this->proposta_model->replace_chars($this->input->get_post('endereco', TRUE)),
                'cep' => $this->input->get_post('cep', TRUE),
                'id_programa' => $this->input->get_post('id_programa', TRUE)
            );

            switch ($this->get_post_action('cadastra', 'envia')) {
                case 'cadastra':

                    /*
                     * Zerando o crono desembolso
                     */

                    $this->db->where('Proposta_idProposta', $id);
                    $this->db->delete('cronograma');

                    /* ------------------------------------------- */


                    $total_soma = 0;
                    $valor_global = $programa->valor_global - $programa->contrapartida_bens;
                    foreach ($metas as $chave => $meta1) {
                        if ($meta1->idMeta != $this->input->get_post('idMeta', TRUE))
                            $total_soma += $meta1->total;
                    }
                    $total_soma += doubleval($total);

                    if (strtotime($data_inicio) < strtotime($proposta->data_inicio) || strtotime($data_inicio) > strtotime($proposta->data_termino) || strtotime($data_termino) < strtotime($proposta->data_inicio) || strtotime($data_termino) > strtotime($proposta->data_termino)) {
                        $this->alert("Reveja as datas e insira valores no intervalo da proposta");
                    } else if (number_format(doubleval($valor_global), 2, '.', "") < number_format(doubleval($total_soma), 2, '.', "")) {
                        $this->alert("Valor global do programa (retirando a contrapartida de bens) = " . $valor_global . " e o valor das metas = " . $total_soma . ". Reveja as metas para que o valor seja igual o valor da proposta.");
                    } else {
                        $this->trabalho_model->add_meta($options, $this->input->get_post('idMeta', TRUE));

                        if (number_format(doubleval($valor_global), 2, '.', "") == number_format(doubleval($total_soma), 2, '.', ""))
                            $this->alert("Valor das metas alcançou o valor da proposta.");
                        if ($this->input->get_post('edita_gestor', TRUE) == 1)
                            $this->encaminha('listar_metas?id=' . $id . '&edita_gestor=1');
                        $this->encaminha('listar_metas?id=' . $id . '&edita_gestor=1');
                    }
                    $this->voltaPagina();
                    break;
                case 'envia':
                    echo 'envia';
                    break;
            }
            $meta = $this->trabalho_model->obter_meta_por_id($meta);
            //THOMAS: Forçando para testes editar como padrão. Se for o correto remover 
            $data['edita_gestor'] = 1;
            /* if ($this->input->get_post('edita_gestor', TRUE) == 1)
              $data['edita_gestor'] = 1; */
            $data['cidades'] = $this->trabalho_model->obter_cidades_siconv();
            $data['ufs'] = $this->trabalho_model->obter_uf_siconv();
            $data['proposta'] = $proposta;
            $data['enderecos'] = $this->trabalho_model->obter_enderecos($this->usuario_logado->id_usuario);
            $data['id'] = $id;
            $data['meta'] = $meta;
            $data['leitura_pessoa'] = false;
            $data['title'] = "Physis - Incluir Meta";
            $data['main'] = 'in/meta';
            $data['login'] = $this->login;
            $this->load->view('in/template_projeto', $data);
        }
        else {
            $this->voltaPagina();
        }
    }

    function listar_etapas() {
        $this->load->model('trabalho_model');
        if ($this->input->get_post('meta', TRUE) !== false) {
            $dadosEtapa = $this->trabalho_model->obter_etapas_meta_proposta($this->input->get_post('meta', TRUE));
            $valorTotalEtapa = 0;
            foreach ($dadosEtapa as $dEtapa) {
                $valorTotalEtapa += $dEtapa->total;
            }

            $meta = $this->input->get_post('meta', TRUE);
            $etapas = $this->trabalho_model->obter_etapas_meta_proposta($meta);
            //THOMAS: Fixado olhar outros
            $data['edita_gestor'] = 1;
            /* if ($this->input->get_post('edita_gestor', TRUE) == 1)
              $data['edita_gestor'] = 1; */

            $data['valorTotalEtapa'] = $valorTotalEtapa;

            $data['dadosMeta'] = $this->trabalho_model->obter_meta_id($meta);
            $data['etapas'] = $etapas;
            $data['meta'] = $meta;
            $data['id'] = $this->input->get_post('id', TRUE);
            $data['leitura_pessoa'] = false;
            $data['title'] = "Physis - Listar Etapas da Meta";
            $data['main'] = 'in/listar_etapas';
            $data['login'] = $this->login;
            $this->load->view('in/template_projeto', $data);
        } else {
            $this->voltaPagina();
        }
    }

    function incluir_etapa_da_meta() {
        $this->load->model('proposta_model');
        $this->load->model('trabalho_model');
        $id = $this->input->get_post('id', TRUE);

//         if($this->input->get_post('etapa', TRUE) !== false){
//         	if($this->trabalho_model->verifica_etapa_foi_associada($this->input->get_post('etapa', TRUE))){
//         		$this->alert("Para poder editar esta etapa, é necessário desassociá-la do Crono Desembolso.");
//         		$this->voltaPagina();
//         	}
//         }

        $proposta = $this->proposta_model->get_by_id($id);

        if ($this->input->get_post('meta', TRUE) !== false) {
            $dadosEtapa = $this->trabalho_model->obter_etapas_meta_proposta($this->input->get_post('meta', TRUE));
            $valorTotalEtapa = 0;
            $dadosEtapas = array();
            foreach ($dadosEtapa as $dEtapa) {
                $valorTotalEtapa += $dEtapa->total;
                $dadosEtapas[] = array('especificacao' => $dEtapa->especificacao, 'total' => $dEtapa->total);
            }
        }

        if ($this->input->get_post('meta', TRUE) !== false && $this->input->get_post('etapa', TRUE) === false) {
            $meta_id = $this->input->get_post('meta', TRUE);
            $meta = $this->trabalho_model->obter_meta_id($meta_id);
            $etapas = $this->trabalho_model->obter_etapas_meta_proposta($meta->idMeta);
            $data_inicio = implode("-", array_reverse(explode("/", $this->input->get_post('data_inicio', TRUE))));
            $data_termino = implode("-", array_reverse(explode("/", $this->input->get_post('data_termino', TRUE))));

            $total = str_replace(".", "", $this->input->get_post('valor', TRUE));
            $total = str_replace(",", ".", $total);
            $quantidade = str_replace(".", "", $this->input->get_post('quantidade', TRUE));
            $quantidade = str_replace(",", ".", $quantidade);
            $valorUnitario = str_replace(".", "", $this->input->get_post('valorUnitario', TRUE));
            $valorUnitario = str_replace(",", ".", $valorUnitario);
            if ($this->trabalho_model->verifica_uf_siconv($this->input->get_post('fornecimento', TRUE)) == false) {
                $this->alert("Unidade de fornecimento incorreto. Reveja esse item");
                $this->voltaPagina();
            }
            $options = array(
                'Meta_idMeta' => $this->input->get_post('meta', TRUE),
                'especificacao' => $this->proposta_model->replace_chars($this->input->get_post('especificacao', TRUE)),
                'fornecimento' => $this->proposta_model->replace_chars($this->input->get_post('fornecimento', TRUE)),
                'total' => $total,
                'quantidade' => $quantidade,
                'valorUnitario' => $valorUnitario,
                'data_inicio' => $data_inicio,
                'data_termino' => $data_termino,
                'UF' => $this->input->get_post('UF', TRUE),
                'municipio_sigla' => $this->input->get_post('municipio_sigla', TRUE),
                'municipio_nome' => $this->input->get_post('municipio_nome', TRUE),
                'endereco' => $this->proposta_model->replace_chars($this->input->get_post('endereco', TRUE)),
                'cep' => $this->input->get_post('cep', TRUE),
            );

            switch ($this->get_post_action('cadastra', 'envia')) {
                case 'cadastra':
                    $total_etapa = 0;
                    foreach ($etapas as $etapa) {
                        $total_etapa += $etapa->total;
                    }
                    $total_etapa += doubleval($total);

                    if (strtotime($data_inicio) < strtotime($meta->data_inicio) || strtotime($data_inicio) > strtotime($meta->data_termino) || strtotime($data_termino) < strtotime($meta->data_inicio) || strtotime($data_termino) > strtotime($meta->data_termino)) {
                        $this->alert("Reveja as datas e insira valores no intervalo da meta");
                    } else if (number_format(doubleval($meta->total), 2, '.', "") < number_format(doubleval($total_etapa), 2, '.', "")) {
                        $this->alert("Valor da meta = " . $meta->total . " e o valor das etapas = " . $total_etapa . ". Reveja as etapas para que o valor seja igual o valor da meta.");
                    } else {
                        $this->trabalho_model->add_etapa($options);

                        if (number_format(doubleval($meta->total), 2, '.', "") == number_format(doubleval($total_etapa), 2, '.', ""))
                            $this->alert("Valor das etapas alcançou o valor da meta.");
                        if ($this->input->get_post('edita_gestor', TRUE) == 1)
                            $this->encaminha('listar_etapas?meta=' . $meta_id . '&id=' . $this->input->get_post('id', TRUE) . '&edita_gestor=1');
                        $this->encaminha('listar_etapas?meta=' . $meta_id . '&id=' . $this->input->get_post('id', TRUE) . '&edita_gestor=1');
                    }
                    $this->voltaPagina();
                    break;
                case 'envia':
                    echo 'envia';
                    break;
            }
            //THOMAS: Padrão sempre gestor
            $data['edita_gestor'] = 1;

            //adicionado para que seja informado a data inicial e final da meta como sugestão para o cadastro da etapa
            $etapa = $this->trabalho_model->retorna_datas_meta($meta_id);
            $data['etapa'] = $etapa;

            /* if ($this->input->get_post('edita_gestor', TRUE) == 1)
              $data['edita_gestor'] = 1; */
            $data['valorTotalEtapa'] = $valorTotalEtapa;
            $data['dadosEtapas'] = $dadosEtapas;
            $data['proposta'] = $proposta;
            $data['cidades'] = $this->trabalho_model->obter_cidades_siconv();
            $data['ufs'] = $this->trabalho_model->obter_uf_siconv();
            $data['endereco_proposta'] = $this->trabalho_model->obter_endereco_por_id($id);
            $data['meta'] = $meta;
            $data['meta_id'] = $meta_id;
            $data['enderecos'] = $this->trabalho_model->obter_enderecos($this->usuario_logado->id_usuario);
            $data['id'] = $this->input->get_post('id', TRUE);
            $data['leitura_pessoa'] = false;
            $data['title'] = "Physis - Incluir Etapa da Meta";
            $data['main'] = 'in/incluir_etapa_da_meta';
            $data['login'] = $this->login;
            $this->load->view('in/template_projeto', $data);
        } else if ($this->input->get_post('etapa', TRUE) !== false) {
            $etapa = $this->input->get_post('etapa', TRUE);
            $meta = $this->input->get_post('meta', TRUE);
            $meta_trabalho = $this->trabalho_model->obter_meta_id($meta);
            $etapas = $this->trabalho_model->obter_etapas_meta_proposta($meta_trabalho->idMeta);
            $data_inicio = implode("-", array_reverse(explode("/", $this->input->get_post('data_inicio', TRUE))));
            $data_termino = implode("-", array_reverse(explode("/", $this->input->get_post('data_termino', TRUE))));

            $total = str_replace(".", "", $this->input->get_post('valor', TRUE));
            $total = str_replace(",", ".", $total);
            $quantidade = str_replace(".", "", $this->input->get_post('quantidade', TRUE));
            $quantidade = str_replace(",", ".", $quantidade);
            $valorUnitario = str_replace(".", "", $this->input->get_post('valorUnitario', TRUE));
            $valorUnitario = str_replace(",", ".", $valorUnitario);
            if ($this->trabalho_model->verifica_uf_siconv($this->input->get_post('fornecimento', TRUE)) == false) {
                $this->alert("Unidade de fornecimento incorreto. Reveja esse item");
                $this->voltaPagina();
            }
            $options = array(
                'Meta_idMeta' => $this->input->get_post('meta', TRUE),
                'especificacao' => $this->proposta_model->replace_chars($this->input->get_post('especificacao', TRUE)),
                'fornecimento' => $this->proposta_model->replace_chars($this->input->get_post('fornecimento', TRUE)),
                'total' => $total,
                'quantidade' => $quantidade,
                'valorUnitario' => $valorUnitario,
                'data_inicio' => $data_inicio,
                'data_termino' => $data_termino,
                'UF' => $this->input->get_post('UF', TRUE),
                'municipio_sigla' => $this->input->get_post('municipio_sigla', TRUE),
                'municipio_nome' => $this->input->get_post('municipio_nome', TRUE),
                'endereco' => $this->proposta_model->replace_chars($this->input->get_post('endereco', TRUE)),
                'cep' => $this->input->get_post('cep', TRUE),
            );

            switch ($this->get_post_action('cadastra', 'envia')) {
                case 'cadastra':

                    /*
                     * Zerando o crono desembolso
                     */

                    $this->db->where('Proposta_idProposta', $id);
                    $this->db->delete('cronograma');

                    /* ------------------------------------------- */

                    $total_etapa = 0;
                    foreach ($etapas as $etapa1) {
                        if ($etapa1->idEtapa != $etapa)
                            $total_etapa += $etapa1->total;
                    }
                    $total_etapa += doubleval($total);

                    if (strtotime($data_inicio) < strtotime($meta_trabalho->data_inicio) || strtotime($data_inicio) > strtotime($meta_trabalho->data_termino) || strtotime($data_termino) < strtotime($meta_trabalho->data_inicio) || strtotime($data_termino) > strtotime($meta_trabalho->data_termino)) {
                        $this->alert("Reveja as datas e insira valores no intervalo da meta");
                    } else if (number_format(doubleval($meta_trabalho->total), 2, '.', "") < number_format(doubleval($total_etapa), 2, '.', "")) {
                        $this->alert("Valor da meta = " . $meta_trabalho->total . " e o valor das etapas = " . $total_etapa . ". Reveja as etapas para que o valor seja igual o valor da meta.");
                    } else {
                        $this->trabalho_model->add_etapa($options, $this->input->get_post('idEtapa', TRUE));

                        if (number_format(doubleval($meta_trabalho->total), 2, '.', "") == number_format(doubleval($total_etapa), 2, '.', ""))
                            $this->alert("Valor das etapas alcançou o valor da meta.");
                        if ($this->input->get_post('edita_gestor', TRUE) == 1)
                            $this->encaminha('listar_etapas?meta=' . $meta . '&id=' . $this->input->get_post('id', TRUE) . '&edita_gestor=1');
                        $this->encaminha('listar_etapas?meta=' . $meta . '&id=' . $this->input->get_post('id', TRUE) . '&edita_gestor=1');
                    }
                    $this->voltaPagina();
                    break;
                case 'envia':
                    echo 'envia';
                    break;
            }
            $etapa = $this->trabalho_model->obter_etapa_por_id($etapa);
            //THOMAS: Deixando padrão sempre gestor
            $data['edita_gestor'] = 1;
            /* if ($this->input->get_post('edita_gestor', TRUE) == 1)
              $data['edita_gestor'] = 1; */
            $data['valorTotalEtapa'] = $valorTotalEtapa;
            $data['dadosEtapas'] = $dadosEtapas;
            $data['proposta'] = $proposta;
            $data['cidades'] = $this->trabalho_model->obter_cidades_siconv();
            $data['ufs'] = $this->trabalho_model->obter_uf_siconv();
            $data['endereco_proposta'] = $this->trabalho_model->obter_endereco_por_id($id);
            $data['etapa'] = $etapa;
            $data['meta_id'] = $meta;
            $data['meta'] = $this->trabalho_model->obter_meta_id($meta);
            $data['enderecos'] = $this->trabalho_model->obter_enderecos($this->usuario_logado->id_usuario);
            $data['id'] = $this->input->get_post('id', TRUE);
            $data['leitura_pessoa'] = false;
            $data['title'] = "Physis - Incluir Etapa";
            $data['main'] = 'in/incluir_etapa_da_meta';
            $data['login'] = $this->login;
            $this->load->view('in/template_projeto', $data);
        } else {
            $this->voltaPagina();
        }
    }

    function listar_cronograma() {
        $this->load->model('proposta_model');
        $this->load->model('trabalho_model');
        if ($this->input->get_post('id', TRUE) !== false) {
            $id = $this->input->get_post('id', TRUE);
            $cronograma = $this->trabalho_model->obter_cronograma($id);
            switch ($this->get_post_action('cadastra', 'envia', 'aprova', 'corrige')) {
                case 'envia':
                    $total = 0;
                    $valor_global = $this->proposta_model->get_by_id($id)->valor_global - $this->proposta_model->get_by_id($id)->contrapartida_bens;
                    $repasse = $this->proposta_model->get_by_id($id)->repasse;
                    $contrapartida_financeira = $this->proposta_model->get_by_id($id)->contrapartida_financeira;
                    $total_concedente = 0;
                    $total_convenente = 0;
                    $flag = false;
                    foreach ($cronograma as $chave => $cr) {
                        $total += $cr->parcela;
                        if ($cr->responsavel == 'CONCEDENTE')
                            $total_concedente += $cr->parcela;
                        else
                            $total_convenente += $cr->parcela;

                        $metas = $this->trabalho_model->obter_metas_proposta($id);
                        //$crono = $this->trabalho_model->obter_cronograma_por_id($cr->idCronograma);

                        foreach ($metas as $meta_aux) {
                            //$valor1 = $this->trabalho_model->obter_meta_cronograma_valor($cronograma_id, $meta->idMeta);

                            $meta = $this->trabalho_model->obter_meta_cronograma($cr->idCronograma, $meta_aux->idMeta);
                            $etapas = $this->trabalho_model->obter_etapas_meta_proposta($meta_aux->idMeta);
                            $idCronograma = $this->trabalho_model->obter_meta_cronograma_id($cr->idCronograma, $meta_aux->idMeta);
                            $total_etapa = 0;

                            foreach ($etapas as $etapa) {
                                $valor_aux = $this->trabalho_model->obter_etapa_cronograma_valor($idCronograma, $etapa->idEtapa);
                                if ($valor_aux != null) {
                                    $total_etapa += $valor_aux;
                                    $total_etapa1 = $total_etapa;
                                }
                            }
                        }

                        if (number_format(doubleval($cr->parcela), 2, '.', "") != number_format(doubleval($total_etapa1), 2, '.', "")) {
                            $this->alert("Valor da meta" . ++$chave . " = " . $cr->parcela . " e o valor das etapas = " . $total_etapa1 . ". Reveja as etapas para que o valor seja igual o valor da meta.");
                            $flag = true;
                        }
                    }
                    //O total das parcelas desembolsadas pelo concedente deve ser inferior ao valor total de repasse

                    if (number_format(doubleval($contrapartida_financeira), 2, '.', "") != number_format(doubleval($total_convenente), 2, '.', "")) {
                        $this->alert("O total das parcelas desembolsadas pelo convenente deve ser igual ao valor de contrapartida financeira.");
                    } else if (number_format(doubleval($repasse), 2, '.', "") < number_format(doubleval($total_concedente), 2, '.', "")) {
                        $this->alert("O total das parcelas desembolsadas pelo concedente deve ser inferior ou igual ao valor total de repasse.");
                    } else if (number_format(doubleval($valor_global), 2, '.', "") != number_format(doubleval($total), 2, '.', "")) {
                        $this->alert("Valor global = " . $valor_global . " e o valor das metas = " . $total . ". Reveja as metas para que o valor seja igual o valor da proposta.");
                    } else if ($flag == false) {
                        $this->trabalho_model->altera_status_trabalho($id, null, 3, 3);
                        $this->alert("Trabalho enviado para o Gestor, aguarde o retorno.");
                    }
                    break;
                case 'aprova':
                    $this->trabalho_model->altera_status_trabalho($id, null, 3, 5);
                    break;
                case 'corrige':
                    $this->trabalho_model->altera_status_trabalho($id, null, 3, 4, $this->input->get_post('obs_gestor', TRUE));
                    break;
            }
            $idTrabalho = $this->trabalho_model->obter_por_trabalho('', '', $id, null, 3);
            if ($idTrabalho != null)
                $data['observacao'] = $this->trabalho_model->obter_observacao($idTrabalho[0]->idTrabalho);
            if ($this->usuario_logado->id_usuario == $this->proposta_model->get_by_id($id)->idGestor)
                $data['voltar_gestor'] = 1;
            else
                $data['voltar_gestor'] = 0;
            //THOMAS: Travado em 1 para sempre editar como gestor
            $data['edita_gestor'] = 1;
            /* if ($this->input->get_post('edita_gestor', TRUE) == 1)
              $data['edita_gestor'] = 1; */
            $data['trabalho_model'] = $this->trabalho_model;
            $data['proposta'] = $this->proposta_model->get_by_id($id);
            if ($idTrabalho != null)
                $data['idTrabalho'] = $idTrabalho[0];
            $data['cronograma'] = $cronograma;
            $data['metas'] = $this->trabalho_model->obter_metas_proposta($id);
            $data['id'] = $id;
            $data['title'] = "Physis - Listar Crono Desembolso";
            $data['leitura_pessoa'] = false;
            $data['main'] = 'in/listar_cronograma';
            $data['login'] = $this->login;
            $this->load->view('in/template_projeto', $data);
        }
        else {
            $this->voltaPagina();
        }
    }

    function incluir_parcela_do_cronograma_de_desembolso_automaticamente() {
        $this->load->model('proposta_model');
        $this->load->model('trabalho_model');
        $id = $this->input->get_post('id', TRUE);

        /*
         * Zerando o crono desembolso
         */

        $this->db->where('Proposta_idProposta', $id);
        $this->db->delete('cronograma');

        /* ------------------------------------------- */

        /*
         * Data
         */

        $proposta = $this->proposta_model->get_by_id($this->input->get_post('id', TRUE));
        $mes = SubStr($proposta->data_inicio, 5, 2);
        $ano = SubStr($proposta->data_inicio, 0, 4);

        /*
         * Valores
         */

        $total_concedente = $this->trabalho_model->obter_total_cronograma_utilizado($id, 'CONCEDENTE');
        $total_convenente = $this->trabalho_model->obter_total_cronograma_utilizado($id, 'CONVENENTE');

        /*
         * Cadastro Covenente
         */

        $options = array(
            'Proposta_idProposta' => $id,
            'responsavel' => 'CONVENENTE',
            'mes' => $mes,
            'parcela' => $total_convenente,
            'ano' => $ano,
            'idCronograma' => null
        );

        $this->trabalho_model->add_cronograma($options);

        /*
         * Cadastro Concedente
         */

        $options = array(
            'Proposta_idProposta' => $id,
            'responsavel' => 'CONCEDENTE',
            'mes' => $mes,
            'parcela' => $total_concedente,
            'ano' => $ano,
            'idCronograma' => null
        );

        $this->trabalho_model->add_cronograma($options);

        /*
         * Associação das metas e etapas do convenente
         */

        $metas = $this->trabalho_model->obter_metas_proposta($id);
        $cronograma = $this->trabalho_model->obter_cronograma($id);
        $convenente_metas = $total_convenente / count($metas);

        /*
         * Metas
         */
        $valor_associado = 0;
        $aux_metas = $metas;
        do {
            $sobra_meta = 0;
            foreach ($metas as $key => $meta) {
                $options_meta = array(
                    'Cronograma_idCronograma' => $cronograma[1]->idCronograma,
                    'Meta_idMeta' => $meta->idMeta
                );
                if ($key == count($aux_metas) - 1) {
                    //print_r('Total: ' . $total_convenente .'-' . 'utilizado: ' .$valor_associado . '=' . $total_convenente - $valor_associado);die;
                    $options_meta['valor'] = $total_convenente - $valor_associado;
                } else {
                    $atual_meta = $this->trabalho_model->obter_meta_cronograma_valor($cronograma[1]->idCronograma, $meta->idMeta);

                    if ($meta->total >= $atual_meta + round($convenente_metas, 2)) {
                        $options_meta['valor'] = $atual_meta + round($convenente_metas, 2);
                        $valor_associado += round($convenente_metas, 2);
                    } else {
                        unset($metas[$key]);
                        $sobra_meta += round($convenente_metas, 2) - round($meta->total, 2);
                        $options_meta['valor'] = round($meta->total, 2);
                        $valor_associado += round($meta->total, 2);
                    }
                }
                $this->trabalho_model->add_cronograma_meta($options_meta);
            }
            $convenente_metas = $sobra_meta / count($metas);
        } while ($sobra_meta != 0);

        /*
         * Etapas
         */

        $metas = $this->trabalho_model->obter_metas_proposta($id);

        foreach ($metas as $meta) {
            $etapas = $this->trabalho_model->obter_etapas_meta_proposta($meta->idMeta);
            $aux_etapas = $etapas;
            $atual_meta = $this->trabalho_model->obter_meta_cronograma_valor($cronograma[1]->idCronograma, $meta->idMeta);
            $convenente_etapas = $atual_meta / count($etapas);
            $valor_associado = 0;
            do {
                $sobra_etapa = 0;
                foreach ($etapas as $key => $etapa) {
                    $meta_cronograma = $this->trabalho_model->obter_meta_cronograma($cronograma[1]->idCronograma, $meta->idMeta);
                    $options_etapa = array(
                        'Cronograma_meta_idCronograma_meta' => $meta_cronograma->idCronograma_meta,
                        'Etapa_idEtapa' => $etapa->idEtapa);

                    if ($key == count($aux_etapas) - 1) {
                        $options_etapa['valor'] = $atual_meta - round($valor_associado, 2);
                    } else {
                        $atual_etapa = $this->trabalho_model->obter_etapa_cronograma_valor($meta_cronograma->idCronograma_meta, $etapa->idEtapa);
                        if ($etapa->total >= $atual_etapa + round($convenente_etapas, 2)) {
                            $options_etapa['valor'] = $atual_etapa + round($convenente_etapas, 2);
                            $valor_associado += round($convenente_etapas, 2);
                        } else {
                            unset($etapas[$key]);
                            $sobra_etapa += round($convenente_etapas, 2) - round($etapa->total, 2);
                            $options_etapa['valor'] = round($etapa->total, 2);
                            $valor_associado += round($etapa->total, 2);
                        }
                    }
                    $this->trabalho_model->add_cronograma_etapa($options_etapa);
                }
                $convenente_etapas = $sobra_etapa / count($etapas);
            } while ($sobra_etapa != 0);
        }

        /*
         * Associação das metas e etapas do concedente
         */

        foreach ($metas as $meta) {
            $restante = $this->trabalho_model->obter_restante_meta($meta->idMeta);
            $options_meta = array(
                'Cronograma_idCronograma' => $cronograma[0]->idCronograma,
                'Meta_idMeta' => $meta->idMeta,
                'valor' => $restante
            );

            $this->trabalho_model->add_cronograma_meta($options_meta);


            $etapas = $this->trabalho_model->obter_etapas_meta_proposta($meta->idMeta);
            foreach ($etapas as $etapa) {
                $restante_etapa = $this->trabalho_model->obter_restante_etapa($etapa->idEtapa);
                $meta_cronograma = $this->trabalho_model->obter_meta_cronograma($cronograma[0]->idCronograma, $meta->idMeta);
                $options_etapa = array(
                    'Cronograma_meta_idCronograma_meta' => $meta_cronograma->idCronograma_meta,
                    'Etapa_idEtapa' => $etapa->idEtapa,
                    'valor' => $restante_etapa);
                $this->trabalho_model->add_cronograma_etapa($options_etapa);
            }
        }

        if ($this->input->get_post('edita_gestor', 0) == 1)
            $this->encaminha('listar_cronograma?id=' . $id . '&edita_gestor=1');
        $this->encaminha('listar_cronograma?id=' . $id . '&edita_gestor=1');
    }

    function incluir_parcela_do_cronograma_de_desembolso() {
        $this->load->model('trabalho_model');
        $this->load->model('proposta_model');
        $id = $this->input->get_post('id', TRUE);
        $proposta = $this->proposta_model->get_by_id($id);

//         if($this->input->get('cronograma', TRUE) != false){
//         	if($this->trabalho_model->verifica_crono_foi_associado($this->input->get('cronograma', TRUE))){
//         		$this->alert("Desassocie os valores de metas e etapas para poder editar este desembolso.");
//         		$this->voltaPagina();
//         		exit();
//         	}
//         }

        if ($this->input->get_post('id', TRUE) != false) {
            $dados_proposta = $this->proposta_model->get_by_id($this->input->get_post('id', TRUE));
            $data['data_ini'] = implode("/", array_reverse(explode("-", $dados_proposta->data_inicio)));
            $data['data_fim'] = implode("/", array_reverse(explode("-", $dados_proposta->data_termino)));
        }

        if ($this->input->get_post('idProposta', TRUE) !== false) {
            $id = $this->input->get_post('idProposta', TRUE);
            $metas = $this->trabalho_model->obter_metas_proposta($id);
            $valorParcela = str_replace(".", "", $this->input->get_post('valorParcela', TRUE));
            $valorParcela = str_replace(",", ".", $valorParcela);

            $options = array(
                'Proposta_idProposta' => $this->input->get_post('idProposta', TRUE),
                'responsavel' => $this->input->get_post('responsavel', TRUE),
                'mes' => $this->input->get_post('mes', TRUE),
                'parcela' => $valorParcela,
                'ano' => $this->input->get_post('ano', TRUE)
            );

            $options['idCronograma'] = $this->input->get_post('cronograma', TRUE);

            switch ($this->get_post_action('cadastra', 'envia')) {
                case 'cadastra':
                    $cronograma = $this->trabalho_model->obter_cronograma($id);
                    $total = 0;
                    $valor_global = $this->proposta_model->get_by_id($id)->valor_global - $this->proposta_model->get_by_id($id)->contrapartida_bens;
                    $repasse = $this->proposta_model->get_by_id($id)->repasse;
                    $contrapartida_financeira = $this->proposta_model->get_by_id($id)->contrapartida_financeira;
                    $total_concedente = 0;
                    $total_convenente = 0;

                    foreach ($cronograma as $chave => $cr) {
                        $total += $cr->parcela;
                        if ($cr->idCronograma != $options['idCronograma']) {
                            if ($cr->responsavel == 'CONCEDENTE')
                                $total_concedente += $cr->parcela;
                            else
                                $total_convenente += $cr->parcela;
                        }
                    }
                    if ($this->input->get_post('responsavel', TRUE) == 'CONCEDENTE')
                        $total_concedente += $valorParcela;
                    else
                        $total_convenente += $valorParcela;
                    //O total das parcelas desembolsadas pelo concedente deve ser inferior ao valor total de repasse
                    $ano_comparacao_inicio = SubStr($proposta->data_inicio, 0, 4) . SubStr($proposta->data_inicio, 5, 2);
                    $ano_comparacao_termino = SubStr($proposta->data_termino, 0, 4) . SubStr($proposta->data_termino, 5, 2);
                    $ano_atual = $this->input->get_post('ano', TRUE) . $this->input->get_post('mes', TRUE);

                    if ($ano_atual < $ano_comparacao_inicio || $ano_atual > $ano_comparacao_termino) {
                        $this->alert("Reveja as datas e insira valores no intervalo da proposta");
                        $this->voltaPagina();
                        die();
                    } else if (($this->input->get_post('responsavel', TRUE) == 'CONCEDENTE') && (number_format(doubleval($repasse), 2, '.', "") < number_format(doubleval($total_concedente), 2, '.', ""))) {
                        $this->alert("O total das parcelas desembolsadas pelo concedente deve ser inferior ou igual ao valor total de repasse: Repasse = " . $repasse . ", Concedente = " . $total_concedente);
                        $this->voltaPagina();
                        die();
                    } else if (($this->input->get_post('responsavel', TRUE) == 'CONVENENTE') && (number_format(doubleval($contrapartida_financeira), 2, '.', "") < number_format(doubleval($total_convenente), 2, '.', ""))) {
                        $this->alert("O total das parcelas desembolsadas pelo convenente não deve ser maior ao valor de contrapartida financeira: Contrapartida = " . $contrapartida_financeira . ", Convenente = " . $total_convenente);
                        $this->voltaPagina();
                        die();
                    }

                    if (($this->input->get_post('responsavel', TRUE) == 'CONCEDENTE') && (number_format(doubleval($repasse), 2, '.', "") == number_format(doubleval($total_concedente), 2, '.', "")))
                        $this->alert("O total das parcelas desembolsadas pelo concedente atingiu o repasse.");
                    else if (($this->input->get_post('responsavel', TRUE) == 'CONVENENTE') && (number_format(doubleval($contrapartida_financeira), 2, '.', "") == number_format(doubleval($total_convenente), 2, '.', "")))
                        $this->alert("O total das parcelas desembolsadas pelo convenente atingiu a contrapartida financeira.");

                    $retorno = $this->trabalho_model->add_cronograma($options);
                    if ($retorno == true) {
                        if ($this->input->get_post('edita_gestor', 0) == 1)
                            $this->encaminha('listar_cronograma?id=' . $id . '&edita_gestor=1');
                        $this->encaminha('listar_cronograma?id=' . $id . '&edita_gestor=1');
                    }
                    else {
                        $this->alert("Responsável já tem um cronograma nesse mês");
                        $this->voltaPagina();
                    }
                    break;
                case 'envia':
                    echo 'envia';
                    break;
            }
            $cronograma = $this->trabalho_model->obter_cronograma_por_id($this->input->get_post('cronograma', TRUE));
            $data['edita_gestor'] = 1;
            /* if ($this->input->get_post('edita_gestor', TRUE) == 1)
              $data['edita_gestor'] = 1; */
            $data['cronograma'] = $cronograma;
            $data['metas'] = $metas;
            $data['id'] = $id;
            $data['leitura_pessoa'] = false;
            $data['title'] = "Physis - Incluir Crono Desembolso";
            $data['main'] = 'in/incluir_parcela_do_cronograma_de_desembolso';
            $data['login'] = $this->login;
            $this->load->view('in/template_projeto', $data);
        } else if ($this->input->get_post('id', TRUE) !== false) {
            $this->db->flush_cache();
            $id = $this->input->get_post('id', TRUE);
            $metas = $this->trabalho_model->obter_metas_proposta($id);
            $cronograma = $this->trabalho_model->obter_cronograma_por_id($this->input->get_post('cronograma', TRUE));
            $data['edita_gestor'] = 1;

            $data['total_concedente'] = $this->trabalho_model->obter_total_cronograma_utilizado($id, 'CONCEDENTE');
            $data['total_convenente'] = $this->trabalho_model->obter_total_cronograma_utilizado($id, 'CONVENENTE');

            $data['contrapartida'] = $this->proposta_model->get_by_id($id)->contrapartida_financeira;
            $data['tconcedente'] = $this->proposta_model->get_by_id($id)->repasse - $data['total_concedente'];
            $data['tconvenente'] = $data['contrapartida'] - $data['total_convenente'];

            /* if ($this->input->get_post('edita_gestor', TRUE) == 1)
              $data['edita_gestor'] = 1; */
            $data['meta_id'] = $this->trabalho_model->obter_meta_id_cronograma($this->input->get_post('cronograma', TRUE));
            $data['cronograma'] = $cronograma;
            $data['metas'] = $metas;
            $data['id'] = $id;
            $data['title'] = "Physis - Incluir Crono Desembolso";
            $data['leitura_pessoa'] = false;
            $data['main'] = 'in/incluir_parcela_do_cronograma_de_desembolso';
            $data['login'] = $this->login;
            $this->load->view('in/template_projeto', $data);
        } else {
            $this->voltaPagina();
        }
    }

    function excluir_parcela_do_cronograma_de_desembolso() {
        $this->load->model('trabalho_model');
        if ($this->input->get_post('cronograma', TRUE) !== false) {
            $cronograma = $this->input->get_post('cronograma', TRUE);
            $metas = $this->trabalho_model->excluir_parcela_do_cronograma_de_desembolso($cronograma);
            if ($this->input->get_post('edita_gestor', TRUE) == 1)
                $this->encaminha('listar_cronograma?id=' . $this->input->get_post('id', TRUE) . '&edita_gestor=1');
            $this->encaminha('listar_cronograma?id=' . $this->input->get_post('id', TRUE) . '&edita_gestor=1');
        }
        else {
            $this->voltaPagina();
        }
    }

    function incluir_meta_do_cronograma_de_desembolso() {

        $this->load->model('trabalho_model');
        if ($this->input->get_post('cronograma', TRUE) !== false) {
            if ($this->input->get_post('cronograma_id', TRUE) !== false) {
                if ($this->input->post('Desassociar', TRUE) !== false) {
                    $options = array('valor' => 0.00);

                    $this->trabalho_model->desassocia_valor($this->input->get_post('cronograma_id', TRUE), $this->input->get_post('idMeta', TRUE), $options);
                } else {
                    if ($this->input->get_post('idMeta', TRUE) !== false && $this->input->get_post('cronograma_id', TRUE) != false) {
                        if ($this->trabalho_model->verifica_meta_foi_associada_por_crono($this->input->get_post('idMeta', TRUE), $this->input->get_post('cronograma_id', TRUE))) {
                            $this->alert("Desassocie a meta para poder associa-la novamente.");
                            $this->voltaPaginaSemCache();
                            exit();
                        }
                    }

                    $valor = str_replace(".", "", $this->input->get_post('valor', TRUE));
                    $valor = str_replace(",", ".", $valor);
                    $options = array(
                        'Cronograma_idCronograma' => $this->input->get_post('cronograma_id', TRUE),
                        'Meta_idMeta' => $this->input->get_post('idMeta', TRUE),
                        'valor' => $valor
                    );
                    $atual = $this->trabalho_model->obter_meta_cronograma_valor($this->input->get_post('cronograma_id', TRUE), $this->input->get_post('idMeta', TRUE));

                    $cronograma_id = $this->input->get_post('cronograma', TRUE);
                    $metas = $this->trabalho_model->obter_metas_proposta($this->input->get_post('id', TRUE));
                    $crono = $this->trabalho_model->obter_cronograma_por_id($cronograma_id);

                    $total_meta = 0;
                    $total_todas_metas = 0;

                    foreach ($metas as $meta) {
                        $valor1 = $this->trabalho_model->obter_meta_cronograma_valor($cronograma_id, $meta->idMeta);
                        if ($meta->idMeta == $this->input->get_post('idMeta', TRUE)) {
                            $total_meta += $valor1;
                        }
                        $total_todas_metas += $valor1;
                    }
                    $total_meta += doubleval($valor);
                    $total_todas_metas += doubleval($valor);
                    $val_aux = doubleval($valor) - $atual;
                    $val_aux = number_format(doubleval($val_aux), 2, '.', "");
                    $val_aux1 = number_format($this->trabalho_model->obter_restante_meta($this->input->get_post('idMeta', TRUE)), 2, '.', "");
                    if (number_format(doubleval($crono->parcela), 2, '.', "") < number_format(doubleval($total_meta), 2, '.', "")) {
                        $this->alert("Valor maior do que o permitido. Valor da parcela = " . $crono->parcela);
                    } else if (number_format(doubleval($val_aux1), 2, '.', "") < number_format(doubleval($val_aux), 2, '.', "")) {
                        $this->alert("Valor maior do que o permitido.");
                        $this->alert("Valores: " . $val_aux1 . " - " . $val_aux);
                    } else if (number_format(doubleval($crono->parcela), 2, '.', "") < number_format(doubleval($total_todas_metas), 2, '.', "")) {
                        $this->alert("Valor das metas atingiu o valor do desembolso.");
                    } else {
                        $this->trabalho_model->add_cronograma_meta($options);
                    }
                }
            }

            $cronograma_id = $this->input->get_post('cronograma', TRUE);
            //Verifica se é metas associadas(0) ou se é associar metas (1)
            if ($this->input->get_post('action', TRUE) == 0) {
                $metas = $this->trabalho_model->obter_metas_proposta($this->input->get_post('id', TRUE));
            } else {
                $metas = $this->trabalho_model->obter_metas_crono(NULL, $this->input->get_post('id', TRUE));
            }
            $data['edita_gestor'] = 1;
            /* if ($this->input->get_post('edita_gestor', TRUE) == 1)
              $data['edita_gestor'] = 1; */
            $data['trabalho_model'] = $this->trabalho_model;
            $data['metas'] = $metas;
            $data['cronograma_id'] = $cronograma_id;
            $data['id'] = $this->input->get_post('id', TRUE);
            $data['cronograma'] = $this->trabalho_model->obter_cronograma_por_id($cronograma_id);
            $data['leitura_pessoa'] = false;
            $data['title'] = "Physis - Incluir Meta";
            $data['main'] = 'in/incluir_meta_do_cronograma_de_desembolso';
            $data['login'] = $this->login;
            $this->load->view('in/template_projeto', $data);
        } else {
            $this->voltaPagina();
        }
    }

    function incluir_etapa_do_cronograma_de_desembolso() {
        //print_r($this->input->get_post('meta_cronograma_id', TRUE));die;
        $this->load->model('trabalho_model');
        if ($this->input->get_post('cronograma', TRUE) !== false) {
            if ($this->input->get_post('meta_cronograma_id', TRUE) !== false) {
                if ($this->input->post('Desassociar', TRUE) !== FALSE) {
                    $this->trabalho_model->desassocia_etapa($this->input->get_post('meta_cronograma_id', TRUE), $this->input->get_post('idEtapa', TRUE));
                } else {
                    if ($this->input->get_post('meta', TRUE) !== false && $this->input->get_post('cronograma', TRUE) != false) {
                        if ($this->trabalho_model->verifica_etapa_foi_associada_por_crono($this->input->get_post('meta', TRUE), $this->input->get_post('cronograma', TRUE), $this->input->get_post('idEtapa', TRUE))) {
                            $this->alert("Desassocie os valores da meta para poder edita-la.");
                            $this->voltaPaginaSemCache();
                            exit();
                        }
                    }

                    $meta_id = $this->input->get_post('meta', TRUE);
                    $valor = str_replace(".", "", $this->input->get_post('valor', TRUE));
                    $valor = str_replace(",", ".", $valor);
                    $options = array(
                        'Cronograma_meta_idCronograma_meta' => $this->input->get_post('meta_cronograma_id', TRUE),
                        'Etapa_idEtapa' => $this->input->get_post('idEtapa', TRUE),
                        'valor' => $valor);
                    $atual = $this->trabalho_model->obter_etapa_cronograma_valor($this->input->get_post('meta_cronograma_id', TRUE), $this->input->get_post('idEtapa', TRUE));

                    $cronograma = $this->input->get_post('cronograma', TRUE);
                    $meta = $this->trabalho_model->obter_meta_por_id($meta_id);
                    //echo $cronograma;
                    $meta_cronograma = $this->trabalho_model->obter_meta_cronograma($cronograma, $meta_id);
                    $etapas = $this->trabalho_model->obter_etapas_meta_proposta($meta->idMeta);
                    //$crono = $this->trabalho_model->obter_cronograma_por_id($cronograma);

                    $total_etapa = 0;
                    $total_todas_etapas = 0;

                    foreach ($etapas as $etapa) {
                        $valor1 = $this->trabalho_model->obter_etapa_cronograma_valor($meta_cronograma->idCronograma_meta, $etapa->idEtapa);
                        if ($etapa->idEtapa != $this->input->get_post('idEtapa', TRUE))
                            $total_etapa += doubleval($valor1);

                        $total_todas_etapas += $valor1;
                    }
                    $total_etapa += doubleval($valor);
                    $total_todas_etapas += doubleval($valor);
                    $val_aux = doubleval($valor) - doubleval($atual);
                    $val_aux = number_format(doubleval($val_aux), 2, '.', "");
                    $val_aux1 = number_format($this->trabalho_model->obter_restante_etapa($this->input->get_post('idEtapa', TRUE)), 2, '.', "");
                    if (number_format(doubleval($meta_cronograma->valor), 2, '.', "") < number_format(doubleval($total_etapa), 2, '.', "")) {
                        $this->alert("Valor maior do que o permitido. Valor da parcela = " . $meta_cronograma->valor);
                    } else if (number_format(doubleval($val_aux1), 2, '.', "") < number_format(doubleval($val_aux), 2, '.', "")) {
                        $this->alert("Valor maior do que o permitido.");
                        $this->alert("Valores: " . $val_aux1 . " - " . $val_aux);
                    } else if (number_format(doubleval($meta_cronograma->valor), 2, '.', "") < number_format(doubleval($total_todas_etapas), 2, '.', "")) {
                        $this->alert("O total das etapas atingiu o valor da meta.");
                    } else {
                        $this->trabalho_model->add_cronograma_etapa($options);
                    }
                }
            }
            $cronograma = $this->input->get_post('cronograma', TRUE);
            $meta_id = $this->input->get_post('meta', TRUE);
            $meta = $this->trabalho_model->obter_meta_por_id($meta_id);
            //echo $cronograma;
            $meta_cronograma = $this->trabalho_model->obter_meta_cronograma($cronograma, $meta_id);
            //$meta_cronograma_id = $this->trabalho_model->obter_meta_cronograma_id($cronograma, $meta_id);
            $etapas = $this->trabalho_model->obter_etapas_meta_proposta($meta->idMeta);
            $data['edita_gestor'] = 1;
            /* if ($this->input->get_post('edita_gestor', TRUE) == 1)
              $data['edita_gestor'] = 1; */
            $data['trabalho_model'] = $this->trabalho_model;
            $data['etapas'] = $etapas;
            $data['meta_cronograma'] = $meta_cronograma;
            $data['id'] = $this->input->get_post('id', TRUE);
            $data['cronograma'] = $this->trabalho_model->obter_cronograma_por_id($cronograma);
            $data['cronograma_id'] = $cronograma;
            $data['meta'] = $meta;
            $data['leitura_pessoa'] = false;
            $data['title'] = "Physis - Incluir Etapa";
            $data['main'] = 'in/incluir_etapa_do_cronograma_de_desembolso';
            $data['login'] = $this->login;
            $this->load->view('in/template_projeto', $data);
        } else {
            $this->voltaPagina();
        }
    }

    function listar_obras() {
        $this->load->model('proposta_model');
        $this->load->model('trabalho_model');
        if ($this->input->get_post('id', TRUE) !== false && $this->input->get_post('acao', TRUE) === false) {
            $total = array();
            $id = $this->input->get_post('id', TRUE);
            $tipo_despesas = $this->trabalho_model->obter_tipo_despesas();
            $despesas = $this->trabalho_model->obter_despesas($id);

            foreach ($despesas as $despesa) {
                if (isset($total[$despesa->Tipo_despesa_idTipo_despesa][0]) !== false)
                    $total[$despesa->Tipo_despesa_idTipo_despesa][0] += $despesa->total;
                else
                    $total[$despesa->Tipo_despesa_idTipo_despesa][0] = $despesa->total;

                if (isset($total[$despesa->Tipo_despesa_idTipo_despesa][$despesa->natureza_aquisicao]) !== false)
                    $total[$despesa->Tipo_despesa_idTipo_despesa][$despesa->natureza_aquisicao] += $despesa->total;
                else
                    $total[$despesa->Tipo_despesa_idTipo_despesa][$despesa->natureza_aquisicao] = $despesa->total;

                if (isset($total[0][0]) !== false)
                    $total[0][0] += $despesa->total;
                else
                    $total[0][0] = $despesa->total;

                if (isset($total[0][$despesa->natureza_aquisicao]) !== false)
                    $total[0][$despesa->natureza_aquisicao] += $despesa->total;
                else
                    $total[0][$despesa->natureza_aquisicao] = $despesa->total;
            }

            if ($this->input->get_post('tipoDespesa', TRUE) !== false) {
                $despesas = $this->trabalho_model->obter_despesas_tipo($id, $this->input->get_post('tipoDespesa', TRUE));
            }
            if (count($despesas) == 0)
                $total[0][0] = 0;
            switch ($this->get_post_action('cadastra', 'envia', 'aprova', 'corrige')) {
                case 'envia':
                    $valor_global = $this->proposta_model->get_by_id($id)->valor_global;

                    if (number_format(doubleval($total[0][0]), 2, '.', "") != number_format(doubleval($valor_global), 2, '.', "")) {
                        $this->alert("Valor total cadastrado na proposta = " . $valor_global . " e o valor da soma dos valores = " . $total[0][0] . ". Reveja os valores para que seja igual o valor da proposta.");
                    } else {
                        $this->trabalho_model->altera_status_trabalho($id, null, 4, 3);
                        $this->alert("Trabalho enviado para o Gestor, aguarde o retorno.");
                    }

                    break;
                case 'aprova':
                    $this->trabalho_model->altera_status_trabalho($id, null, 4, 5);
                    break;
                case 'corrige':
                    $this->trabalho_model->altera_status_trabalho($id, null, 4, 4, $this->input->get_post('obs_gestor', TRUE));
                    break;
            }

            $idTrabalho = $this->trabalho_model->obter_por_trabalho('', '', $id, null, 4);

            if ($idTrabalho != null) {
                $data['observacao'] = $this->trabalho_model->obter_observacao($idTrabalho[0]->idTrabalho);
            }
            if ($this->usuario_logado->id_usuario == $this->proposta_model->get_by_id($id)->idGestor)
                $data['voltar_gestor'] = 1;
            else
                $data['voltar_gestor'] = 0;
            $data['edita_gestor'] = 1;
            /* if ($this->input->get_post('edita_gestor', TRUE) == 1)
              $data['edita_gestor'] = 1; */

            foreach ($despesas as $d) {
                $d->UF = $this->obterEstadoNome($d->UF);
            }

            $data['valor_global'] = $this->proposta_model->get_by_id($id)->valor_global;
            if ($idTrabalho != null)
                $data['idTrabalho'] = $idTrabalho[0];
            $data['leitura_pessoa'] = false;
            $data['tipo_despesas'] = $tipo_despesas;
            $data['total'] = $total;
            $data['despesas'] = $despesas;
            $data['id'] = $id;
            $data['title'] = "Physis - Listar Obras";
            $data['main'] = 'in/listar_obras';
            $data['login'] = $this->login;
            $this->load->view('in/template_projeto', $data);
        } else if ($this->input->get_post('id', TRUE) !== false && $this->input->get_post('despesa', TRUE) !== false && $this->input->get_post('acao', TRUE) !== false) {

            $id = $this->input->get_post('id', TRUE);
            $despesa = $this->input->get_post('despesa', TRUE);
            $acao = $this->input->get_post('acao', TRUE);
            if ($acao == 'apagar')
                $this->trabalho_model->apagar_despesa($despesa);
            if ($this->input->get_post('edita_gestor', TRUE) == 1)
                $this->encaminha('listar_obras?id=' . $id . '&edita_gestor=1');
            $this->encaminha('listar_obras?id=' . $id . '&edita_gestor=1');
        } else {
            $this->voltaPagina();
        }
    }

    function incluir_bens_da_proposta() {
        ini_set("max_execution_time", 0);
        ini_set("memory_limit", "-1");

        $this->db->flush_cache();
        $this->load->model('trabalho_model');
        $this->load->model('proposta_model');
        $this->load->model('programa_model');
        $this->load->model('programa_proposta_model');

        if ($this->input->get_post('id', TRUE) !== false) {
            $metas = $this->trabalho_model->obter_metas_proposta($this->input->get_post('id', TRUE));
            $data['programas_proposta'] = $this->programa_proposta_model->get_programas_by_proposta($this->input->get_post('id', TRUE));

            $dadosMetas = array();
            $i = 0;
            foreach ($metas as $meta) {
                $dadosEtapas = array();

                $dadosEtapas[0] = $meta->especificacao;
                $dadosEtapa = $this->trabalho_model->obter_etapas_meta_proposta($meta->idMeta);
                $valorTotalEtapa = 0;
                foreach ($dadosEtapa as $dEtapa) {
                    $valorTotalEtapa += $dEtapa->total;
                    $dadosEtapas[1][] = array('especificacao' => $dEtapa->especificacao, 'total' => $dEtapa->total);
                }

                $dadosMetas[$i] = $dadosEtapas;

                $i++;
            }
        }

        $valores_programas = $this->programa_proposta_model->get_valores_programas_despesa($this->input->get('id', TRUE));
        $data['valores_programas'] = $valores_programas;

        if ($this->input->get_post('tipoDespesa', TRUE) !== false && $this->input->get_post('id', TRUE) !== false && $this->input->get_post('idDespesa_update', TRUE) === false) {
            $tipoDespesa = $this->input->get_post('tipoDespesa', TRUE);
            $id = $this->input->get_post('id', TRUE);
            $proposta = $this->proposta_model->get_by_id($id);
            $tipo_despesa = $this->trabalho_model->obter_nome_despesa($this->input->get_post('tipoDespesa', TRUE));


            $proposta = $this->proposta_model->get_by_id($this->input->get_post('id', TRUE));
            $despesas = $this->trabalho_model->obter_despesas($this->input->get_post('id', TRUE));
            $total_despesa = 0;
            foreach ($despesas as $d)
                $total_despesa += $d->total;

            $dadosProposta[] = array('valor_global' => $proposta->valor_global, 'valor_despesa' => $total_despesa);


            $data['edita_gestor'] = 1;
            /* if ($this->input->get_post('edita_gestor', TRUE) == 1)
              $data['edita_gestor'] = 1; */
            $data['dadosProposta'] = $dadosProposta;
            $data['dadosEtapas'] = $dadosMetas;
            $data['proposta'] = $proposta;
            $data['cidades'] = $this->trabalho_model->obter_cidades_siconv();
            $data['endereco_proposta'] = $this->trabalho_model->obter_endereco_por_id($id);
            $data['ufs'] = $this->trabalho_model->obter_uf_siconv();
            $data['natureza'] = $this->trabalho_model->obter_natureza_siconv($tipo_despesa->idTipo_despesa);
            $data['despesa'] = null;
            $data['enderecos'] = $this->trabalho_model->obter_enderecos($this->usuario_logado->id_usuario);
            $data['tipo_despesa'] = $tipo_despesa;
            $data['programa_model'] = $this->programa_model;
            $data['id'] = $id;
            $data['leitura_pessoa'] = false;
            $data['title'] = "Physis - Incluir Bens da Proposta";
            $data['main'] = 'in/incluir_bens_da_proposta';
            $data['login'] = $this->login;
            $this->load->view('in/template_projeto', $data);
        } else if ($this->input->get_post('idProposta', TRUE) !== false) {
            $this->db->flush_cache();
            $id = $this->input->get_post('idProposta', TRUE);
            $total = str_replace(".", "", $this->input->get_post('total', TRUE));
            $total = str_replace(",", ".", $total);
            $valor_unitario = str_replace(".", "", $this->input->get_post('valor_unitario', TRUE));
            $valor_unitario = str_replace(",", ".", $valor_unitario);
            $quantidade = str_replace(".", "", $this->input->get_post('quantidade', TRUE));
            $quantidade = str_replace(",", ".", $quantidade);
            //$this->encaminha("incluir_bens_da_proposta?tipoDespesa=".$this->input->get_post('tipoDespesa', TRUE));
            if ($this->trabalho_model->verifica_natureza_siconv($this->input->get_post('tipoDespesa', TRUE), $this->input->get_post('natureza_despesa', TRUE)) == false) {
                $this->alert("Cod. natureza do bem incorreto. Reveja esse item");
                $this->voltaPagina();
            }
            if ($this->trabalho_model->verifica_uf_siconv($this->input->get_post('fornecimento', TRUE)) == false) {
                $this->alert("Unidade de fornecimento incorreto. Reveja esse item");
                $this->voltaPagina();
            }

            $options = array(
                'Proposta_idProposta' => $this->input->get_post('idProposta', TRUE),
                'descricao' => $this->proposta_model->replace_chars($this->input->get_post('descricao', TRUE)),
                'natureza_aquisicao' => $this->input->get_post('natureza_aquisicao', TRUE),
                'natureza_despesa' => $this->input->get_post('natureza_despesa', TRUE),
                //'natureza_despesa_descricao' => $this->input->get_post('natureza_despesa_descricao', TRUE),
                'fornecimento' => $this->proposta_model->replace_chars($this->input->get_post('fornecimento', TRUE)),
                'total' => $total,
                'quantidade' => $quantidade,
                'valor_unitario' => $valor_unitario,
                'endereco' => $this->proposta_model->replace_chars($this->input->get_post('endereco', TRUE)),
                'cep' => $this->input->get_post('cep', TRUE),
                'municipio' => $this->input->get_post('municipio', TRUE),
                'UF' => $this->input->get_post('UF', TRUE),
                'observacao' => $this->proposta_model->replace_chars($this->input->get_post('observacao', TRUE)),
                'Tipo_despesa_idTipo_despesa' => $this->input->get_post('tipoDespesa', TRUE),
                'id_programa' => $this->input->post('id_programa', TRUE)
            );
            $options['idDespesa'] = $this->input->get_post('idDespesa_update', TRUE);
            switch ($this->get_post_action('cadastra', 'envia')) {
                case 'cadastra':
                    $this->db->flush_cache();
                    $total_bens = 0;
                    $id = $this->input->get_post('id', TRUE);
                    $despesas = $this->trabalho_model->obter_despesas($id, $this->input->get_post('natureza_aquisicao', TRUE), $this->input->post('id_programa', TRUE));

                    foreach ($despesas as $d) {
                        if ($d->idDespesa != $this->input->get_post('idDespesa', TRUE))
                            $total_bens += $d->total;
                    }

                    $natureza_aquisicao = $this->input->get_post('natureza_aquisicao', TRUE);
                    $valor_global = $this->programa_proposta_model->get_by_proposta_programa($id, $this->input->post('id_programa', TRUE))->valor_global;
                    $valor_contra_bens = $this->programa_proposta_model->get_by_proposta_programa($id, $this->input->post('id_programa', TRUE))->contrapartida_bens;

                    if ($natureza_aquisicao == 1) {
                        $valor_global = $valor_global - $valor_contra_bens;
                        $desc_natureza = "Recursos do convênio";
                    } else {
                        $valor_global = $valor_contra_bens;
                        $desc_natureza = "Contrapartida bens e serviços";
                    }

                    $total_bens += doubleval($total);

                    if (number_format(doubleval($total_bens), 2, '.', "") > number_format(doubleval($valor_global), 2, '.', "")) {
                        $this->alert("Valor total cadastrado em {$desc_natureza} no programa = " . number_format(doubleval($valor_global), 2, '.', "") . " e o valor da soma dos valores = " . number_format(doubleval($total_bens), 2, '.', "") . ". Reveja os valores para que não ultrapasse o valor da proposta.");
                        $this->voltaPagina();
                    } else {
                        $this->trabalho_model->add_despesa($options);
                    }

                    break;
                case 'envia':
                    $this->db->flush_cache();
                    echo 'envia';
                    break;
            }
            if ($this->input->get_post('edita_gestor', TRUE) == 1) {
                $this->encaminha('listar_obras?id=' . $id . '&edita_gestor=1');
            }

            $tipoDespesa = $this->input->get_post('tipoDespesa', TRUE);
            $id = $this->input->get_post('id', TRUE);
            $proposta = $this->proposta_model->get_by_id($id);
            $tipo_despesa = $this->trabalho_model->obter_nome_despesa($this->input->get_post('tipoDespesa', TRUE));


            $proposta = $this->proposta_model->get_by_id($this->input->get_post('id', TRUE));
            $despesas = $this->trabalho_model->obter_despesas($this->input->get_post('id', TRUE));
            $total_despesa = 0;
            foreach ($despesas as $d)
                $total_despesa += $d->total;

            $dadosProposta[] = array('valor_global' => $proposta->valor_global, 'valor_despesa' => $total_despesa);


            $data['edita_gestor'] = 1;
            /* if ($this->input->get_post('edita_gestor', TRUE) == 1)
              $data['edita_gestor'] = 1; */
            $data['dadosProposta'] = $dadosProposta;
            $data['dadosEtapas'] = $dadosMetas;
            $data['proposta'] = $proposta;
            $data['cidades'] = $this->trabalho_model->obter_cidades_siconv();
            $data['endereco_proposta'] = $this->trabalho_model->obter_endereco_por_id($id);
            $data['ufs'] = $this->trabalho_model->obter_uf_siconv();
            $data['natureza'] = $this->trabalho_model->obter_natureza_siconv($tipo_despesa->idTipo_despesa);
            $data['despesa'] = null;
            $data['programa_model'] = $this->programa_model;
            $data['enderecos'] = $this->trabalho_model->obter_enderecos($this->usuario_logado->id_usuario);
            $data['tipo_despesa'] = $tipo_despesa;
            $data['id'] = $id;
            $data['leitura_pessoa'] = false;
            $data['title'] = "Physis - Incluir Bens da Proposta";
            $data['main'] = 'in/incluir_bens_da_proposta';
            $data['login'] = $this->login;
            $this->load->view('in/template_projeto', $data);
        } else if ($this->input->get_post('idDespesa', TRUE) !== false) {
            $id = $this->input->get_post('id', TRUE);
            $proposta = $this->proposta_model->get_by_id($id);
            $idDespesa = $this->input->get_post('idDespesa', TRUE);
            $despesa = $this->trabalho_model->obter_despesa_por_id($idDespesa);
            $tipo_despesa = $this->trabalho_model->obter_nome_despesa($despesa->Tipo_despesa_idTipo_despesa);
            $data['edita_gestor'] = 1;
            $despesa->UF = $this->obterEstadoNome($despesa->UF);


            $proposta = $this->proposta_model->get_by_id($this->input->get_post('id', TRUE));
            $despesas = $this->trabalho_model->obter_despesas($this->input->get_post('id', TRUE));
            $total_despesa = 0;
            foreach ($despesas as $d)
                $total_despesa += $d->total;

            $dadosProposta[] = array('valor_global' => $proposta->valor_global, 'valor_despesa' => $total_despesa);


            /* if ($this->input->get_post('edita_gestor', TRUE) == 1)
              $data['edita_gestor'] = 1; */
            $data['dadosProposta'] = $dadosProposta;
            $data['dadosEtapas'] = $dadosMetas;
            $data['proposta'] = $proposta;
            $data['endereco_proposta'] = $this->trabalho_model->obter_endereco_por_id($id);
            $data['cidades'] = $this->trabalho_model->obter_cidades_siconv();
            $data['ufs'] = $this->trabalho_model->obter_uf_siconv();
            $data['natureza'] = $this->trabalho_model->obter_natureza_siconv($tipo_despesa->idTipo_despesa);
            $data['id'] = $id;
            $data['enderecos'] = $this->trabalho_model->obter_enderecos($this->usuario_logado->id_usuario);
            $data['tipo_despesa'] = $tipo_despesa;
            $data['despesa'] = $despesa;
            $data['programa_model'] = $this->programa_model;
            $data['leitura_pessoa'] = false;
            $data['title'] = "Physis - Incluir Bens da Proposta";
            $data['main'] = 'in/incluir_bens_da_proposta';
            $data['login'] = $this->login;
            $this->load->view('in/template_projeto', $data);
        } else {
            $this->voltaPagina();
        }
    }

    function visualiza_propostas() {

        $data['title'] = "Physis - Visualiza propostas";
        $data['main'] = 'in/visualiza_propostas';
        $data['login'] = $this->login;
        $this->load->view('in/template_projeto', $data);
    }

    function gerencia_proposta_usuario() {

        $this->load->model('trabalho_model');
        $this->load->model('proposta_model');
        $data['log_trabalho'] = $this->trabalho_model->obter_log_trabalho($this->input->get_post('idTrabalho', TRUE));
        $data['trabalhos'] = $this->trabalho_model;
        $data['propostas'] = $this->proposta_model;

        $data['login'] = $this->login;
        $data['title'] = "Physis - Gerencia propostas";
        $data['main'] = 'usuario/gerencia_proposta_usuario';

        $this->load->view('in/template', $data);
    }

    function cadastrar() {

        //header('Content-type: application/json');
        $data = array(
            'nome' => $this->input->get_post('nome', TRUE),
            'cpf' => $this->input->get_post('cpf', TRUE),
            'login' => $this->input->get_post('login', TRUE),
            'senha' => $this->input->get_post('senha', TRUE),
            'email' => $this->input->get_post('email', TRUE),
            'telefone' => $this->input->get_post('telefone', TRUE),
            'escolaridade' => $this->input->get_post('escolaridade', TRUE),
            'nomeInstituicao ' => $this->input->get_post('nomeInstituicao', TRUE),
            'endereco' => $this->input->get_post('endereco', TRUE),
            'tipoPessoa' => 1,
            'ativo' => 1,
        );

        $this->load->model('usuario_model');
        $inserido = $this->usuario_model->add_records($data);
        if ($inserido == false) {
            $this->alert("Login já existe, tente outro.");
            $this->voltaPagina();
        }
        $this->encaminha('index');
        /*
          if($usuario){
          $dataRet['success'] = false;
          $dataRet['msg'] = 'J&aacute; existe um usu&aacute;rio cadastrado com o email informado!';
          $dataRet['usuario'] = $usuario;
          print json_encode($dataRet);
          }else{
          $usuario = $this->MUsuario->findByUserName($data['loginUsuario']);
          if($usuario){
          $dataRet['success'] = false;
          $dataRet['msg'] = 'J&aacute; existe um usu&aacute;rio cadastrado com o login informado!';
          $dataRet['usuario'] = $usuario;
          print json_encode($dataRet);
          }else{
          if(!isset($data['ativo']) || empty($data['ativo'])){
          $data['ativo'] = true;
          }

          if(!isset($data['idPerfil']) || $data['idPerfil'] < 1){
          $data['idPerfil'] = 1;
          }

          $usuarioEmail = $data;

          $data['senhaUsuario'] = md5($data['senhaUsuario']);

          $usuario = $this->MUsuario->incluirAlterar($data);

          $dataRet['success'] = true;
          $dataRet['usuario'] = $usuario;
          print json_encode($dataRet);
          $this-> cadastrarCategoriasPadroes($usuario['idUsuario']);
          $this -> enviarEmailBemVindo($usuarioEmail);
          }
          } */
    }

    function create() {
        $this->load->helper('url');

        $this->load->model('MStudent', '', TRUE);
        $this->MStudent->addStudent($_POST);
        redirect('in/student/add', 'refresh');
    }

    function listing() {
        $this->load->library('table');

        $this->load->model('MStudent', '', TRUE);
        $students_qry = $this->MStudent->listStudents();

        // generate HTML table from query results
        $tmpl = array(
            'table_open' => '<table border="0" cellpadding="3" cellspacing="0">',
            'heading_row_start' => '<tr bgcolor="#66cc44">',
            'row_start' => '<tr bgcolor="#dddddd">'
        );
        $this->table->set_template($tmpl);

        $this->table->set_empty("&nbsp;");

        $this->table->set_heading('', 'Child Name', 'Parent Name', 'Address', 'City', 'State', 'Zip', 'Phone', 'Email');


        $table_row = array();
        foreach ($students_qry->result() as $student) {
            $table_row = NULL;
            $table_row[] = '<nobr>' .
                    anchor('in/student/edit/' . $student->id, 'edit') . ' | ' .
                    anchor('in/student/delete/' . $student->id, 'delete', "onClick=\" return confirm('Are you sure you want to '
            + 'delete the record for $student->s_name?')\"") .
                    '</nobr>';
            // replaced above :: $table_row[] = anchor('student/edit/' . $student->id, 'edit');
            $table_row[] = $student->s_name;
            $table_row[] = $student->p_name;
            $table_row[] = $student->address;
            $table_row[] = $student->city;
            $table_row[] = $student->state;
            $table_row[] = $student->zip;
            $table_row[] = $student->phone;
            $table_row[] = mailto($student->email);

            $this->table->add_row($table_row);
        }

        $students_table = $this->table->generate();

        // generate HTML table from query results
        // replaced above :: $students_table = $this->table->generate($students_qry);
        // display information for the view
        $data['title'] = "Classroom: Student Listing";
        $data['headline'] = "Student Listing";
        $data['include'] = 'in/student_listing';

        $data['data_table'] = $students_table;

        $this->load->view('in/template1', $data);
    }

    function encaminha($url) {
        echo "<script type='text/javascript'>window.location='" . $url . "';</script>";
        exit();
    }

    function alert($text) {
        echo "<script type='text/javascript'>alert('" . utf8_decode($text) . "');</script>";
    }

    function voltaPagina() {
        echo "<script type='text/javascript'>history.back();</script>";
        exit();
    }

    function voltaPaginaSemCache() {
        echo "<script type='text/javascript'>location.href=document.URL;</script>";
        exit();
    }

    function get_post_action($name) {
        $params = func_get_args();

        foreach ($params as $name) {
            if (isset($_POST[$name])) {
                return $name;
            }
        }
    }

    function getTextBetweenTags($string, $tag1, $tag2) {
        $pattern = "/$tag1([\w\W]*?)$tag2/";
        preg_match_all($pattern, $string, $matches);
        return $matches[1];
    }

    function imprimeDetalhePrograma_bd($pag, $orgao, $listaPrograma, $anterior) {
        $superior = '';
        $flag = false; //verificar se pagina ja pegou o superior
        $validacao = array();
        $this->load->model('programa_model');
        foreach ($anterior as $key => $aux) {
            $this->obter_pagina($aux);
            //echo $aux." ant<br />";
        }

        $url1 = $this->obter_pagina($pag);
        $url1 = $this->removeSpaceSurplus($url1);
        preg_match_all("/href\=\"([a-zA-Z_\.0-9\/\-\! :\&\-\;\@\$\=\?]*)\"/i", $url1, $matches1);

        foreach ($matches1[1] as $key => $pag1) {
            if (strstr($pag1, 'ResultadoDaConsultaDeProgramasDeConvenioDetalhar') !== false) {
                $this->obter_pagina($pag);
                $url1_ = $this->obter_pagina("https://www.convenios.gov.br" . $pag1);
                $url_sem_espaco = $this->removeSpaceSurplus($url1_);

                $orgao_executor = '';
                $orgao = '';
                $codigo = $this->getTextBetweenTags($url_sem_espaco, "Código do Programa<\/td> <td class=\"field\">", "<\/td>");
                $sup = $this->getTextBetweenTags($url_sem_espaco, "Órgão<\/td> <td class=\"field\">", "<\/td>");
                $exec = $this->getTextBetweenTags($url_sem_espaco, "Órgão Executor<\/td> <td class=\"field\">", "<\/td>");
                $qualificacao = $this->getTextBetweenTags($url_sem_espaco, "Qualificação da proposta<\/td> <td class=\"field\">", "<\/td>");
                $atende = $this->getTextBetweenTags($url_sem_espaco, "Programa Atende a<\/td> <td class=\"field\">", "<\/td>");
                $nome = $this->getTextBetweenTags($url_sem_espaco, "Nome do Programa<\/td> <td class=\"field\">", "<\/td>");
                $descricao = $this->getTextBetweenTags($documento, "Descrição <\/td> <tr> <td class=fieldCaixa colspan=2>", "<\/td>");
                $observacao = $this->getTextBetweenTags($documento, "Observação <\/tr> <tr> <td class=fieldCaixa colspan=2>", "<\/td>");

                $data_inicio = $this->getTextBetweenTags($documento, "\"dataInicioVigencia\" value=\"", "\" onmouseup");
                if (count($data_inicio) == 0)
                    $data_inicio = $this->getTextBetweenTags($documento, "\"dataInicioBeneficiarioEspecifico\" value=\"", "\" onmouseup");
                if (count($data_inicio) == 0)
                    $data_inicio = $this->getTextBetweenTags($documento, "\"dataInicioEmendaParlamentar\" value=\"", "\" onmouseup");
                if (count($data_inicio) == 0)
                    $data_inicio = $this->getTextBetweenTags($documento, "\"dataDeDisponibilizacao\" value=\"", "\" onmouseup");

                $data_fim = $this->getTextBetweenTags($documento, "\"dataFimdeVigencia\" value=\"", "\" onmouseup");
                if (count($data_fim) == 0)
                    $data_fim = $this->getTextBetweenTags($documento, "\"dataFimBeneficiarioEspecifico\" value=\"", "\" onmouseup");
                if (count($data_fim) == 0)
                    $data_fim = $this->getTextBetweenTags($documento, "\"dataFimEmendaParlamentar\" value=\"", "\" onmouseup");
                if (count($data_fim) == 0)
                    $data_fim = $this->getTextBetweenTags($documento, "\"dataDeDisponibilizacao\" value=\"", "\" onmouseup");

                $estados = $this->getTextBetweenTags($url_sem_espaco, "Estados Habilitados<\/td> <td class=\"field\">", "<\/td>");

                if (isset($sup[0]) !== false) {
                    $orgao = strtok($sup[0], "-");
                    $orgao = trim(strtok("-"));
                    break;
                }
                if (isset($exec[0]) !== false) {
                    $orgao_executor = strtok($exec[0], "-");
                    $orgao_executor = trim(strtok("-"));
                    break;
                }

                $data = array(
                    'codigo' => $codigo[0],
                    'nome' => $nome[0],
                    'orgao' => $orgao,
                    'orgao_executor' => $orgao_executor,
                    'qualificacao' => $qualificacao[0],
                    'atende' => $atende[0],
                    'descricao' => $descricao[0],
                    'observacao' => $observacao[0],
                    'data_inicio' => $data_inicio[0],
                    'data_fim' => $data_fim[0],
                    'estados' => $estados[0]
                );
                var_dump($data);
                die();
                $inserido = $this->usuario_model->add_records($data);
            } else if (strstr($pag1, 'error-page') !== false) {
                echo "Siconv encaminhando para página com erro: " . $pag . "<br />";
            }
        }
        $anos = $this->getTextBetweenTags($url1, "<div class=\"anoPrograma\">", "<\/div>");
        foreach ($anos as $key => $ano) {
            if (isset($listaPrograma[$superior][$orgao][$ano]) === false)
                $listaPrograma[$superior][$orgao][$ano] = 1;
            else
                $listaPrograma[$superior][$orgao][$ano] ++;
        }
        return $listaPrograma;
    }

    function imprimeDetalhePrograma($pag, $orgao, $listaPrograma, $anterior) {
        $superior = '';
        $flag = false; //verificar se pagina ja pegou o superior
        $validacao = array();

        //$this->obter_paginaLogin();
        foreach ($anterior as $key => $aux) {
            $this->obter_pagina($aux);
            //echo $aux." ant<br />";
        }

        //$this->obter_pagina($remotePageUrl);
        $url1 = $this->obter_pagina($pag);
        //echo $anterior."<br>";
        //echo $pag." p1<br>";
        $url1 = $this->removeSpaceSurplus($url1);
        //echo "1<br>";
        //echo $url1;
        preg_match_all("/href\=\"([a-zA-Z_\.0-9\/\-\! :\&\-\;\@\$\=\?]*)\"/i", $url1, $matches1);
        //preg_match_all("/href\=\"(*)\"/i", $url1, $matches1);
        //echo "2<br>";
        foreach ($matches1[1] as $key => $pag1) {
            if ($flag == true) {
                //echo "flag";
                break;
            }
            //echo $pag1."..".$key." - 3<br>";
            //if ($pag1 == 'siconv/principal/ListarProgramasPrincipal/ConsultaOrgaosConsultar.do?id=') //erro intermitente de causa desconhecida
            //	$listaPrograma = $this->imprimeDetalhePrograma($pag, $orgao, $listaPrograma);
            if (strstr($pag1, 'ResultadoDaConsultaDeProgramasDeConvenioDetalhar') !== false) {
                //$this->obter_paginaLogin();
                $this->obter_pagina($pag);
                $url1_ = $this->obter_pagina("https://www.convenios.gov.br" . $pag1);
                $url_sem_espaco = $this->removeSpaceSurplus($url1_);
                //echo $pag1." 4<br>";
                $sup = $this->getTextBetweenTags($url_sem_espaco, "Órgão<\/td> <td class=\"field\">", "<\/td>");
                //$superior = $sup[0];
                //echo"-1-";
                if (isset($sup[0]) !== false) {
                    $superior = strtok($sup[0], "-");
                    $superior = trim(strtok("-"));
                    $flag = true;
                    break;
                } else {
                    echo "Página com erro: " . $pag . "<br />";
                    echo "Página com erro: https://www.convenios.gov.br" . $pag1 . "<br />";
                    echo $url1_;
                    /* $remotePageUrl = 'https://www.convenios.gov.br/siconv/ListarProgramasPrincipal/ListarProgramasPrincipal.do';
                      //$this->cookie_file_path = tempnam ("/tmp", "CURLCOOKIE".);
                      $this->obter_paginaLogin();
                      $this->obter_pagina($remotePageUrl);
                      return $this->imprimeDetalhePrograma($pag, $orgao, $listaPrograma, $anterior); */
                    //$listaPrograma = $this->imprimeDetalhePrograma($pag, $orgao, $listaPrograma, $anterior);
                    //echo "Siconv deu um erro fatal!<br />Por favor atualize a página."; die();
                }
                //echo $superior." -5<br>";
            }//resolvendo problemas de somas erraas
            else if (strstr($pag1, 'error-page') !== false) {
                /* unlink("application/views/configuracoes/cookie.txt");
                  $cria = fopen("application/views/configuracoes/cookie.txt","w+");
                  fclose($cria);
                  $login            = '43346880559';
                  $senha            = 'Laisa_M2012';
                  $url         	  = "https://www.convenios.gov.br/siconv/secure/EntrarLoginValidar.do?login=$login&senha=$senha";
                  $this->obter_pagina($url); */
                echo "Siconv encaminhando para página com erro: " . $pag . "<br />";
                //$listaPrograma = $this->imprimeDetalhePrograma($pag, $orgao, $listaPrograma);
            }
        }
        //echo "6<br>";
        $anos = $this->getTextBetweenTags($url1, "<div class=\"anoPrograma\">", "<\/div>");
        foreach ($anos as $key => $ano) {
            if (isset($listaPrograma[$superior][$orgao][$ano]) === false)
                $listaPrograma[$superior][$orgao][$ano] = 1;
            else
                $listaPrograma[$superior][$orgao][$ano] ++;
            //echo $superior." - ".$orgao." - ".$key ." - ".$ano." codnao<br>";
        }
        //echo "7<br>";
        return $listaPrograma;
    }

    function imprimeDetalhe($pag, $dataInicio, $dataFim, $estado, $cnpj, $anterior) {
        foreach ($anterior as $key => $aux) {
            $this->obter_pagina($aux);
            //echo $aux." ant<br />";
        }
        //echo $pag." ant<br />";

        $url1 = $this->obter_pagina($pag);
        //echo $url1;
        $url_sem_espaco = $this->removeSpaceSurplus($url1);
        $dataFim_Programa1 = $this->getTextBetweenTags($url_sem_espaco, $this->removeSpaceSurplus("\"dataFimdeVigencia\" value=\""), "\"");
        if (count($dataFim_Programa1) > 0 && trim($dataFim_Programa1[0]) != '') {
            $dataFim_Programa = $dataFim_Programa1[0];
        } else {
            $dataFim_Programa2 = $this->getTextBetweenTags($url_sem_espaco, $this->removeSpaceSurplus("\"dataFimBeneficiarioEspecifico\" value=\""), "\"");
            if (count($dataFim_Programa2) > 0 && trim($dataFim_Programa2[0]) != '') {
                $dataFim_Programa = $dataFim_Programa2[0]; //echo ".1.";
            } else {
                $dataFim_Programa2 = $this->getTextBetweenTags($url_sem_espaco, $this->removeSpaceSurplus("\"dataFimEmendaParlamentar\" value=\""), "\"");
                if (count($dataFim_Programa2) > 0 && trim($dataFim_Programa2[0]) != '') {
                    $dataFim_Programa = $dataFim_Programa2[0]; //echo ".2.";
                } else {
                    $dataFim_Programa2 = $this->getTextBetweenTags($url_sem_espaco, $this->removeSpaceSurplus("\"dataDeDisponibilizacao\" value=\""), "\"");
                    if (count($dataFim_Programa2) > 0 && trim($dataFim_Programa2[0]) != '') {
                        $dataFim_Programa = $dataFim_Programa2[0]; //echo ".3.";
                    } else {
                        echo "Página com erro: " . $pag . "<br />";
                        $remotePageUrl = 'https://www.convenios.gov.br/siconv/ListarProgramasPrincipal/ListarProgramasPrincipal.do';
                        //$this->cookie_file_path = tempnam ("/tmp", "CURLCOOKIE".rand());
                        $this->obter_paginaLogin();
                        $this->obter_pagina($remotePageUrl);
                        $this->imprimeDetalhe($pag, $orgao, $listaPrograma, $anterior);
                    }
                }
            }
        }
        //echo $dataFim_Programa.">=".$dataInicio." && ".$dataFim_Programa."<=".$dataFim."<br>";
        if (strtotime(str_replace("/", "-", $dataFim_Programa)) >= strtotime(str_replace("/", "-", $dataInicio)) && strtotime(str_replace("/", "-", $dataFim_Programa)) <= strtotime(str_replace("/", "-", $dataFim))) {
            //echo ".1.";
            $nome = $this->getTextBetweenTags($url_sem_espaco, "Nome do Programa<\/td> <td class=\"field\">", "<\/td>");
            $codigo = $this->getTextBetweenTags($url_sem_espaco, "Código do Programa<\/td> <td class=\"field\">", "<\/td>");
            $this->codigoEstado[] = trim($codigo[0]);
            $this->pagEstado[] = $pag;
            $this->listaEstado[] = "<a href='" . $pag . "'>" . $codigo[0] . "</a> - " . $nome[0];
            //echo strtotime(str_replace("/","-",$dataFim_Programa)).">=".strtotime(str_replace("/","-",$dataInicio))." && ".strtotime(str_replace("/","-",$dataFim_Programa))."<=".strtotime(str_replace("/","-",$dataFim))."<br>";
            //echo $dataFim_Programa." -dt- ".$dataInicio.",".$dataFim."<br>";
            $estados_habilitados = $this->getTextBetweenTags($url_sem_espaco, "Estados Habilitados<\/td> <td class=\"field\">", "<\/td>");
            //echo $estado." -Habilitado ".count($estados_habilitados).".".$estados_habilitados[0]."<br />";
            //echo $indiciePagina."https://www.convenios.gov.br".$pag1."<br>";
            /* if (strstr($estados_habilitados[0], $estado) !== false || strstr($estados_habilitados[0], 'Todos os Estados estão Aptos') !== false ){
              //echo $indiciePagina."https://www.convenios.gov.br".$pag1." estado<br>";
              $this->codigoEstado[] = $codigo[0];
              $this->listaEstado[] = "<a href='https://www.convenios.gov.br".$pag."'>".$codigo[0]."</a> - ".$nome[0];
              } */
            //echo ".2.";
            $cidades_habilitadas = $this->getTextBetweenTags($url_sem_espaco, "class=\"cnpjBeneficiario\">", "<\/div>");
            if (count($cidades_habilitadas) == 0)
                $cidades_habilitadas = $this->getTextBetweenTags($url_sem_espaco, "class=\"cnpj\">", "<\/div>");
            $flag = true;
            //echo count($cidades_habilitadas)."--cidade--<br>";
            foreach ($cidades_habilitadas as $cidade_habilitada) {
                foreach ($cnpj as $cidade) {
                    if (strstr($cidade_habilitada, $cidade['cnpj']) !== false) {
                        //echo $indiciePagina."https://www.convenios.gov.br".$pag1." cidade<br>";
                        $this->codigoCidade[] = trim($codigo[0]);
                        $this->pagCidade[] = $pag;
                        $this->listaCidade[] = "<a href='" . $pag . "'>" . $codigo[0] . "</a> - " . $nome[0];
                        //echo $codigo[0]; die();
                        $flag = false;
                        break;
                    }
                }
                if ($flag == false)
                    break; //saindo do foreach do meio
            }
            //echo ".3.";
            $paginas = $this->getTextBetweenTags($url1, "span class=\"pagelinks\">Página ", "<\/span>");
            if (isset($paginas[0])) {
                $tok = strtok($paginas[0], "(");
                $tok = strtok($tok, "de");
                $tok = (int) trim(strtok("de"));
                //pegando o id para as subpaginas
                $pos = strripos($pag, "id");
                $id = '';
                if ($pos !== false) {
                    $id = substr($dataInicio, $pos + 2);
                }
                $flag1 = true;
                //echo $id." - id<br>";
                for ($i = 2; $i <= $tok && $flag1 == true; $i++) {
                    //$this->obter_paginaLogin();
                    $this->obter_pagina("https://www.convenios.gov.br" . $pag);
                    //echo "https://www.convenios.gov.br".$pag."<br />";
                    //echo "https://www.convenios.gov.br/siconv/DetalharPrograma/DetalharPrograma.do?id=$id&d-16544-t=cnpjsEmendaParlamentar&d-16544-p=$i&d-16544-g=$i"."<br />";
                    $url1_aux = $this->obter_pagina("https://www.convenios.gov.br/siconv/DetalharPrograma/DetalharPrograma.do?id=$id&d-16544-t=cnpjsEmendaParlamentar&d-16544-p=$i&d-16544-g=$i");
                    $cidades_habilitadas = $this->getTextBetweenTags($url1_aux, "class=\"cnpjBeneficiario\">", "<\/div>");
                    if (count($cidades_habilitadas) == 0)
                        $cidades_habilitadas = $this->getTextBetweenTags($url1_aux, "class=\"cnpj\">", "<\/div>");
                    foreach ($cidades_habilitadas as $cidade_habilitada) {
                        foreach ($cnpj as $cidade) {
                            if (strstr($cidade_habilitada, $cidade['cnpj']) !== false) {
                                //echo $indiciePagina."https://www.convenios.gov.br".$pag1." cidade1<br>";
                                $this->codigoCidade[] = trim($codigo[0]);
                                $this->pagCidade[] = $pag;
                                $this->listaCidade[] = "<a href='" . $pag . "'>" . $codigo[0] . "</a> - " . $nome[0];
                                //echo $codigo[0]; die();
                                $flag1 = false;
                                break;
                            }
                        }
                        if ($flag1 == false)
                            break; //saindo do foreach do meio
                    }
                }
            }
            //echo ".4.";
        }
        //echo ".5.";
    }

    function obter_paginaLogin() {
        $login = $this->login;
        $senha = $this->senha;
        $url = "https://www.convenios.gov.br/siconv/secure/EntrarLoginValidar.do?login=$login&senha=$senha";
        //$cookie_file_path = "application/views/configuracoes/cookie.txt";
        $cookie_file_path = $this->cookie_file_path;
        $agent = "Mozilla/5.0 (Windows; U; Windows NT 5.0; en-US; rv:1.4) Gecko/20030624 Netscape/7.1 (ax)";
        $ch = curl_init();
        // extra headers
        $headers[] = "Accept: */*";
        $headers[] = "Connection: Keep-Alive";

        // basic curl options for all requests
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_USERAGENT, $agent);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file_path);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file_path);

        // set first URL
        curl_setopt($ch, CURLOPT_URL, $url);

        // execute session to get cookies and required form inputs
        $content = curl_exec($ch);
        curl_close($ch);
        if (strstr($content, 'ELIUMAR CARLOS DE SOUSA SILVA') === false) {
            $this->senha = 'Laisa_M2012';
            return $this->obter_paginaLogin();
        }
        return $content;
    }

    function obter_paginaLogin_escolha($login_usuario, $senha, $pagina) {
        if ($this->usuario_logado->tipoPessoa == 6) {
            $this->load->model('proposta_model');
            $proposta = $this->proposta_model->obter_cnpj_aberto($this->usuario_logado->idPessoa);
            $login_usuario = $proposta->usuario_siconv;
            $senha = $this->desencripta($proposta->senha_siconv);
        }

        $login_usuario = urlencode($login_usuario);
        $senha = urlencode($senha);
        $url = "https://www.convenios.gov.br/siconv/secure/EntrarLoginValidar.do?login=$login_usuario&senha=$senha";
        //$cookie_file_path = "application/views/configuracoes/cookie.txt";

        $cookie_file_path = $this->cookie_file_path;
        $agent = "Mozilla/5.0 (Windows; U; Windows NT 5.0; en-US; rv:1.4) Gecko/20030624 Netscape/7.1 (ax)";
        $ch = curl_init();
        // extra headers
        $headers[] = "Accept: */*";
        $headers[] = "Connection: Keep-Alive";

        // basic curl options for all requests
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_USERAGENT, $agent);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file_path);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file_path);

        // set first URL
        curl_setopt($ch, CURLOPT_URL, $url);

        // execute session to get cookies and required form inputs
        $content = curl_exec($ch);
        curl_close($ch);
//echo $content; die();
        if (strstr($content, '<span style="float:left">Erro') !== false || strstr($content, 'ova senha') !== false) {
            $this->alert("Verifique se a senha do usuário está correta ou atualizada, por favor, verifique se a senha está atualizada no siconv (deve ser atualizada de tempos em tempos)");
            $this->encaminha($pagina);
        }
        return $content;
    }

    function obter_pagina($url) {
        //$cookie_file_path = "application/views/configuracoes/cookie.txt";
        $cookie_file_path = $this->cookie_file_path;
        $agent = "Mozilla/5.0 (Windows; U; Windows NT 5.0; en-US; rv:1.4) Gecko/20030624 Netscape/7.1 (ax)";
        $ch = curl_init();
        // extra headers
        $headers[] = "Accept: */*";
        $headers[] = "Connection: Keep-Alive";

        // basic curl options for all requests
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_USERAGENT, $agent);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file_path);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file_path);

        // set first URL
        curl_setopt($ch, CURLOPT_URL, $url);

        // execute session to get cookies and required form inputs
        $content = curl_exec($ch);
        curl_close($ch);

        return $content;
    }

    function obter_pagina_post($url, $fields, $fields_string) {

        //$cookie_file_path = "application/views/configuracoes/cookie.txt";
        $cookie_file_path = $this->cookie_file_path;
        $agent = "Mozilla/5.0 (Windows; U; Windows NT 5.0; en-US; rv:1.4) Gecko/20030624 Netscape/7.1 (ax)";
        $ch = curl_init();
        // extra headers
        $headers[] = "Accept: */*";
        $headers[] = "Connection: Keep-Alive";
        //$headers[] = 'Content-type: multipart/form-data';
        // basic curl options for all requests
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_USERAGENT, $agent);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file_path);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file_path);
        curl_setopt($ch, CURLOPT_POST, count($fields));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);

        // set first URL
        curl_setopt($ch, CURLOPT_URL, $url);

        // execute session to get cookies and required form inputs
        $content = curl_exec($ch);

        curl_close($ch);

        return $content;
    }

    function obter_paginaLogin_exporta($login_usuario, $senha, $id) {
        /* if ($this->usuario_logado->tipoPessoa == 6) {
          $proposta = $this->proposta_model->obter_cnpj_aberto($this->usuario_logado->idPessoa);
          $login_usuario = $proposta->usuario_siconv;
          $senha = $this->desencripta($proposta->senha_siconv);
          } */
        $login_usuario = urlencode($login_usuario);
        $senha = urlencode($senha);
        $url = "https://www.convenios.gov.br/siconv/secure/EntrarLoginValidar.do?login=$login_usuario&senha=$senha";

        //$cookie_file_path = "application/views/configuracoes/cookie.txt";
        $cookie_file_path = $this->cookie_file_path;
        $agent = "Mozilla/5.0 (Windows; U; Windows NT 5.0; en-US; rv:1.4) Gecko/20030624 Netscape/7.1 (ax)";
        $ch = curl_init();
        // extra headers
        $headers[] = "Accept: */*";
        $headers[] = "Connection: Keep-Alive";

        // basic curl options for all requests
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_USERAGENT, $agent);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file_path);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file_path);

        // set first URL
        curl_setopt($ch, CURLOPT_URL, $url);

        // execute session to get cookies and required form inputs
        $content = curl_exec($ch);
        curl_close($ch);

        if (strstr($content, '<span style="float:left">Erro') !== false || strstr($content, 'ova senha') !== false) {
            $this->alert("Verifique se a senha do usuário está correta ou atualizada, por favor, verifique se a senha está atualizada no siconv (deve ser atualizada de tempos em tempos)");
            $this->encaminha('alterar_senha?id_programa=' . $id);
        }
        return $content;
    }

    function removeSpaceSurplus($str) {
        return preg_replace("/\s+/", ' ', trim($str));
    }

    function obterNomeOrgao($id, $numero) {
        $numero = trim($numero);
        $remotePageUrl = 'https://www.convenios.gov.br/siconv/ForwardAction.do?modulo=Principal&path=/MostraPrincipalConsultarProposta.do';
        $remotePageUrl1 = 'https://www.convenios.gov.br/siconv/ConsultarProposta/PreenchaOsDadosDaConsultaConsultar.do?tipo_consulta=CONSULTA_COMPLETA&numeroProposta=' . $numero;
        $nome = '';
        $jsonurl = "http://api.convenios.gov.br/siconv/dados/orgao/$id.json";

        $json = file_get_contents($jsonurl);
        if (trim($json) == '' || !isset($json)) {
            $jsonurl = "http://api.convenios.gov.br/siconv/dados/orgao/$id.html";
            $json = file_get_contents($jsonurl);
            $nome = $this->getTextBetweenTags($json, "<dt>Nome<\/dt>
          <dd>", "<\/dd>");
            $nome = $nome[0];
        } else {
            $json_output = json_decode($json);
            $nome = $json_output->orgaos[0]->nome;
        }
        //caso não encontre em local nenhum, varre direto na página do siconv
        if (trim($nome) == '' || trim($nome) == 'NÃO SE APLICA') {
            $this->obter_paginaLogin();
            echo $remotePageUrl1;
            $this->obter_pagina($remotePageUrl);
            $documento = $this->obter_pagina($remotePageUrl1);
            preg_match_all("/href\=\"([a-zA-Z_\.0-9\/\-\! :\&\-\;\@\$\=\?]*)\"/i", $documento, $matches1);
            foreach ($matches1[1] as $pag1) {
                if (strstr($pag1, 'ResultadoDaConsultaDePropostaDetalharProposta') !== false) {
                    $pag1 = str_replace("&amp;", "&", $pag1);
                    //$listaPrograma = $this->imprimeDetalhePrograma(str_replace("&amp;","&",$pag1), $orgaos[$key-1], $listaPrograma, $anterior);
                    $url1_ = $this->obter_pagina("https://www.convenios.gov.br" . $pag1);
                    $url_sem_espaco = $this->removeSpaceSurplus($url1_);
                    $sup = $this->getTextBetweenTags($url_sem_espaco, "<td class=\"label\">Órgão<\/td> <td class=\"field\" colspan=\"4\">", "<\/td>");
                    $superior = strtok($sup[0], "-");
                    $superior = trim(strtok("-"));
                    return $superior;
                }
            }
        }
        return $nome;
    }

    function obterNomeSituacao($id, $numero) {
        $jsonurl = "http://api.convenios.gov.br/siconv/dados/proposta/$numero.json";
        $json = file_get_contents($jsonurl);
        $json_output = json_decode($json);
        $programa = $json_output->propostas[0]->programas[0]->associacao[0]->Programa->id;
        $numero = $json_output->propostas[0]->numero_proposta;

        $remotePageUrl = 'https://www.convenios.gov.br/siconv/ForwardAction.do?modulo=Principal&path=/MostraPrincipalConsultarProposta.do';
        $remotePageUrl1 = 'https://www.convenios.gov.br/siconv/ConsultarProposta/PreenchaOsDadosDaConsultaConsultar.do?tipo_consulta=CONSULTA_COMPLETA&numeroProposta=' . $numero;

        $id = trim($id);
        $nome = '';
        $jsonurl = "http://api.convenios.gov.br/siconv/dados/situacao_proposta/$id.json";
        //echo $jsonurl."<br>";
        $json = file_get_contents($jsonurl);
        if (trim($json) == '' || !isset($json)) {
            $jsonurl = "http://api.convenios.gov.br/siconv/dados/situacao_proposta/$id.html";
            $json = file_get_contents($jsonurl);
            $nome = $this->getTextBetweenTags($json, "<dt>Nome<\/dt>
			  <dd>", "<\/dd>");
            $nome = $nome[0];
        } else {
            $json_output = json_decode($json);
            $nome = $json_output->situacaopropostas[0]->nome;
        }

        //caso não encontre em local nenhum, varre direto na página do siconv
        if (trim($nome) == '' || trim($nome) == 'NÃO SE APLICA') {
            $this->obter_paginaLogin();
            $this->obter_pagina($remotePageUrl);
            //echo $remotePageUrl1;
            $documento = $this->obter_pagina($remotePageUrl1);
            preg_match_all("/href\=\"([a-zA-Z_\.0-9\/\-\! :\&\-\;\@\$\=\?]*)\"/i", $documento, $matches1);
            foreach ($matches1[1] as $pag1) {
                if (strstr($pag1, 'ResultadoDaConsultaDePropostaDetalharProposta') !== false) {
                    $pag1 = str_replace("&amp;", "&", $pag1);
                    //$listaPrograma = $this->imprimeDetalhePrograma(str_replace("&amp;","&",$pag1), $orgaos[$key-1], $listaPrograma, $anterior);
                    $url1_ = $this->obter_pagina("https://www.convenios.gov.br" . $pag1);
                    $url_sem_espaco = $this->removeSpaceSurplus($url1_);
                    $sup = $this->getTextBetweenTags($url_sem_espaco, "Situação<\/td> <td colspan=\"4\"> <table cellpadding=\"0\" cellspacing=\"0\"> <td class=\"field\" width=\"40\%\">", "<\/td>");
                    return strtoupper($sup[0]);
                }
            }
        }
        return $nome;
    }

    function obterOrgaoSuperior($id) {

        $id = trim($id);
        $jsonurl = "http://api.convenios.gov.br/siconv/dados/proposta/$id.json";
        $json = file_get_contents($jsonurl);
        //echo $jsonurl;
        if (trim($json) == '' || !isset($json)) {
            $jsonurl = "http://api.convenios.gov.br/siconv/dados/proposta/$id.html";
            $json = file_get_contents($jsonurl);
            //echo $jsonurl;
            $nome = $this->getTextBetweenTags($json, "\">Programa ", ": ");
            $numero = $this->getTextBetweenTags($json, "Numero Proposta<\/dt>
          <dd>", "<\/dd>");
            //if (trim($nome[0]) == '' || !isset($nome[0])) $nome[0] = obterOrgaoSuperior($id);
            //echo "!!".$numero[0]."--".$id."__";
            $nome = $this->obterNomeOrgao(substr($nome[0], 0, 5), $numero[0]);
        }
        if (trim($nome) == '') {
            $json_output = json_decode($json);
            $programa = $json_output->propostas[0]->programas[0]->associacao[0]->Programa->id;
            $numero = $json_output->propostas[0]->numero_proposta;
            //echo "&&".$numero."**";
            $nome = obterNomeOrgao(substr($programa, 0, 5), $numero);
        }

        return $nome;
    }

    function imprimeProposta($jsonurl, $ano, $listaPropostas) {
        $json = file_get_contents($jsonurl, 0, null, null);
        $json_output = json_decode($json);

        if ($json == '') {
            echo "Siconv com problemas internos, por favor tente novamente mais tarde.";
        }
        foreach ($json_output->propostas as $orgaos) {
            //echo $orgaos->valor_global."-".$orgaos->situacao->SituacaoProposta->id."<br />";
            $situacao = null;
            if ($orgaos->situacao == null)
                $situacao = 0;
            else
                $situacao = $orgaos->situacao->SituacaoProposta->id;
            //echo trim(substr($orgaos->data_cadastramento_proposta, 0, 4))."-".trim($ano)."<br />";
            if (trim(substr($orgaos->data_cadastramento_proposta, 0, 4)) == trim($ano) || trim($ano) == '') {

                $listaPropostas[trim(substr($orgaos->data_cadastramento_proposta, 0, 4))][$orgaos->id][$situacao]['valor_global'] = $orgaos->valor_global;
                $listaPropostas[trim(substr($orgaos->data_cadastramento_proposta, 0, 4))][$orgaos->id][$situacao]['valor_repasse'] = $orgaos->valor_repasse;
                $listaPropostas[trim(substr($orgaos->data_cadastramento_proposta, 0, 4))][$orgaos->id][$situacao]['valor_contra_partida'] = $orgaos->valor_contra_partida;
                //$listaPropostas[trim(substr($orgaos->data_cadastramento_proposta, 0, 4))][$orgaos->id][$situacao]['id'] = $orgaos->id;
            }
        }
        if (isset($json_output->metadados->proximos))
            $listaPropostas = $this->imprimeProposta($json_output->metadados->proximos, $ano, $listaPropostas);
        return $listaPropostas;
    }

    function obterEstado($estado) {

        switch ($estado) {
            case 7: return 27;
            case 8: return 7;
            case 10: return 8;
            case 11: return 9;
            case 14: return 12;
            case 13: return 11;
            case 12: return 10;
            case 15: return 13;
            case 16: return 14;
            case 18: return 16;
            case 19: return 17;
            case 17: return 15;
            case 20: return 18;
            case 21: return 19;
            case 23: return 21;
            case 9: return 22;
            case 22: return 20;
            case 25: return 23;
            case 27: return 25;
            case 26: return 24;
            case 24: return 26;
        }
        return $estado;
    }

    function obterEstadoNome($estado) {

        switch ($estado) {

            case "1": return "AC";
            case "2": return "AL";
            case "4": return "AM";
            case "3": return "AP";
            case "5": return "BA";
            case "6": return "CE";
            case "7": return "DF";
            case "8": return "ES";
            case "10": return "GO";
            case "11": return "MA";
            case "14": return "MG";
            case "13": return "MS";
            case "12": return "MT";
            case "15": return "PA";
            case "16": return "PB";
            case "18": return "PE";
            case "19": return "PI";
            case "17": return "PR";
            case "20": return "RJ";
            case "21": return "RN";
            case "23": return "RO";
            case "9": return "RR";
            case "22": return "RS";
            case "25": return "SC";
            case "27": return "SE";
            case "26": return "SP";
            case "24": return "TO";
        }
        return $estado;
    }

    function obterEstadoInverso($estado) {

        switch ($estado) {
            case 27: return 7;
            case 7: return 8;
            case 8: return 10;
            case 9: return 11;
            case 12: return 14;
            case 11: return 13;
            case 10: return 12;
            case 13: return 15;
            case 14: return 16;
            case 16: return 18;
            case 17: return 19;
            case 15: return 17;
            case 18: return 20;
            case 19: return 21;
            case 21: return 23;
            case 22: return 9;
            case 20: return 22;
            case 23: return 25;
            case 25: return 27;
            case 24: return 26;
            case 26: return 24;
        }
        return $estado;
    }

    public function getListaEstados() {
        return array("1" => "AC",
            "2" => "AL",
            "4" => "AM",
            "3" => "AP",
            "5" => "BA",
            "6" => "CE",
            "27" => "DF",
            "7" => "ES",
            "8" => "GO",
            "9" => "MA",
            "12" => "MG",
            "11" => "MS",
            "10" => "MT",
            "13" => "PA",
            "14" => "PB",
            "16" => "PE",
            "17" => "PI",
            "15" => "PR",
            "18" => "RJ",
            "19" => "RN",
            "21" => "RO",
            "22" => "RR",
            "20" => "RS",
            "23" => "SC",
            "25" => "SE",
            "24" => "SP",
            "26" => "TO");
    }

    function encripta($str) {
        for ($i = 0; $i < 5; $i++) {
            $str = strrev(base64_encode($str)); //apply base64 first and then reverse the string
        }
        return $str;
    }

//function to decrypt the string
    function desencripta($str) {
        for ($i = 0; $i < 5; $i++) {
            $str = base64_decode(strrev($str)); //apply base64 first and then reverse the string}
        }
        return $str;
    }

    function validar_cnpj($cnpj) {
        $cnpj = preg_replace('/[^0-9]/', '', (string) $cnpj);

        // Valida tamanho
        if (strlen($cnpj) != 14)
            return false;

        // Valida primeiro dígito verificador
        for ($i = 0, $j = 5, $soma = 0; $i < 12; $i++) {
            $soma += $cnpj{$i} * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }

        $resto = $soma % 11;

        if ($cnpj{12} != ($resto < 2 ? 0 : 11 - $resto))
            return false;

        // Valida segundo dígito verificador
        for ($i = 0, $j = 6, $soma = 0; $i < 13; $i++) {
            $soma += $cnpj{$i} * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }

        $resto = $soma % 11;

        return $cnpj{13} == ($resto < 2 ? 0 : 11 - $resto);
    }

    public function busca_dados_endereco() {
        $this->load->model('trabalho_model');

        $cidades = $this->trabalho_model->getCidadePorCnpj($this->input->get_post('cnpjProp', TRUE));

        $optionCidade = array();
        $optionCidade[$cidades['cidade_codigo']] = $cidades['cidade_nome'];

        $optionUF = array();
        $optionUF[$this->obter_codigo_estado($cidades['estado'])] = $cidades['estado'];

        $data['estado'] = $optionUF;
        $data['municipio'] = $optionCidade;

        echo json_encode($data);
    }

    public function obter_codigo_estado($estado) {
        $lista = array("AC" => 1,
            "AL" => 2,
            "AM" => 4,
            "AP" => 3,
            "BA" => 5,
            "CE" => 6,
            "DF" => 27,
            "ES" => 7,
            "GO" => 8,
            "MA" => 9,
            "MG" => 12,
            "MS" => 11,
            "MT" => 10,
            "PA" => 13,
            "PB" => 14,
            "PE" => 16,
            "PI" => 17,
            "PR" => 15,
            "RJ" => 18,
            "RN" => 19,
            "RO" => 21,
            "RR" => 22,
            "RS" => 20,
            "SC" => 23,
            "SE" => 25,
            "SP" => 24,
            "TO" => 26);
        return $lista[$estado];
    }

    public function busca_dados_proposta() {
        $this->load->model('proposta_model');

        $proposta = $this->proposta_model->busca_dados_proposta($this->input->get_post('id', TRUE));

        $data['percentual'] = number_format($proposta->percentual, 2, ",", ".");
        $data['valor_global'] = number_format($proposta->valor_global, 2, ",", ".");

        echo json_encode($data);
    }

    public function envia_email_suporte() {
        $this->session->set_userdata("pagAtual", "envia_email_suporte");

        if ($this->input->get_post('assunto', TRUE) !== false && $this->input->get_post('mensagem', TRUE) !== false) {
            $this->load->model("usuariomodel");
//            $this->load->library('email');
//
//            $this->email->initialize($this->usuariomodel->inicializa_config_email("suporte@physisbrasil.com.br"));

            $this->load->library('email', $this->usuariomodel->inicializa_config_email_gmail("physisbrasil@gmail.com"));
            $this->email->set_newline("\r\n");

            $dadosUsusario = $this->usuariomodel->get_by_id($this->session->userdata('id_usuario'));
            $this->email->from($dadosUsusario->email, $dadosUsusario->nome);
            $this->email->to("suporte@physisbrasil.com.br");

            if ($this->input->get_post('email', TRUE) !== false)
                $this->email->cc($this->input->get_post('email', TRUE));

            $this->email->subject($this->input->get_post('assunto', TRUE));
            $this->email->message($this->input->get_post('mensagem', TRUE));

            $retornoEnvio = $this->email->send();

            if ($retornoEnvio) {
                $this->usuariomodel->envia_email_automatico($dadosUsusario->email, $dadosUsusario->nome);

                $data['retornoEnvio'] = "Email enviado com sucesso.";
                $this->load->model('system_logs');
                $this->system_logs->add_log(ENVIO_EMAIL_SUPORTE . " - Assunto: " . $this->input->get_post('assunto', TRUE));
            } else
                $data['retornoEnvio'] = "Erro ao enviar email";
        } else {
            $data['retornoEnvio'] = "";
        }

        $data['title'] = "Physis - Suporte";
        $data['main'] = 'usuario/envia_email_suporte';
        $data['login'] = $this->login;
        $this->load->vars($data);
        $this->load->view('in/template');
    }

    public function modelos_documentos() {
        $this->load->model('arquivos_model');

        $this->session->set_userdata('pagAtual', 'modelos_documentos');

        $data['title'] = 'Physis - Modelos de Documentos';
        $data['main'] = 'usuario/lista_arquivos';
        $data['arquivos'] = $this->arquivos_model->lista_arquivos();
        $data['arquivos_informacoes'] = $this->arquivos_model->lista_arquivos(null, null, 'I');
        $data['arquivos_model'] = $this->arquivos_model;

        $this->load->view('in/template', $data);
    }

    public function novo_documento() {
        if (isset($_FILES['arquivo']) && $_FILES['arquivo']['tmp_name'] != "") {
            $this->load->model('arquivos_model');

            $file_tmp = $_FILES["arquivo"]["tmp_name"];
            $file_name = $_FILES["arquivo"]["name"];
            $file_size = $_FILES["arquivo"]["size"];
            $file_type = $_FILES["arquivo"]["type"];

            $fp = fopen($file_tmp, "rb");
            $dados_documento = fread($fp, filesize($file_tmp));
            fclose($fp);
            $binario = bin2hex($dados_documento);

            $options = array(
                'nome_arquivo' => $file_name,
                'arquivo' => $binario,
                'tipo' => $file_type,
                'tamanho' => $file_size,
                'descricao' => $this->input->get_post('descricao', TRUE),
                'tipo_arquivo' => $this->input->get_post('tipo_arquivo', TRUE)
            );

            if (isset($_FILES['print_arquivo']) && $_FILES['print_arquivo']['tmp_name'] != "") {
                $config['upload_path'] = './uploads/';
                $config['allowed_types'] = 'gif|jpg|png|jpeg';
                $this->load->library('upload', $config);

                $this->upload->do_upload('print_arquivo');

                $options['print_arquivo'] = base_url('uploads') . "/" . $this->upload->file_name;
            }

            $ultimo_id = $this->arquivos_model->insere_arquivo($options);

            if ($ultimo_id > 0) {
                $data['retornoEnvio'] = "Arquivo salvo com sucesso.";
                $this->load->model('system_logs');
                $this->system_logs->add_log(ARQUIVO_INCLUIDO . " - ID: " . $ultimo_id . ", Descrição: " . $this->input->get_post('descricao', TRUE));
            } else
                $data['retornoEnvio'] = "Erro ao salvar arquivo.";
        } else
            $data['retornoEnvio'] = "";

        $data['title'] = 'Physis - Novo Documento';
        $data['main'] = 'usuario/novo_documento';


        $this->load->view('in/template', $data);
    }

    public function abre_arquivo() {
        $this->load->model('arquivos_model');
        $arquivo = $this->arquivos_model->retorna_arquivo($this->input->get_post('id', TRUE));
        $arquivo->arquivo = $this->arquivos_model->hex2bin($arquivo->arquivo);
        $data['arquivo'] = $arquivo;
        $this->load->view('usuario/abre_arquivo', $data);
    }

    public function deleta_arquivo() {
        $id = $this->input->get_post('id', TRUE);
        $this->load->model('arquivos_model');
        $retorno = $this->arquivos_model->deleta_arquivo($id);

        if ($retorno > 0) {
            $this->alert("Arquivo deletado com sucesso.");
            $this->load->model('system_logs');
            $this->system_logs->add_log(ARQUIVO_DELETADO . " - ID: " . $id . ", Descrição: " . $this->arquivos_model->retorna_arquivo($id)->descricao);
        } else
            $this->alert("Falha ao deletar arquivo.");

        $this->encaminha("modelos_documentos");
    }

    public function consultar_parecer() {
        $this->session->set_userdata('projEnviado', 'S');
        $this->session->set_userdata('linha_panel', $this->input->get('id', TRUE));

        if ($this->input->get('id', TRUE) != false && $this->input->get('idProposta', TRUE) != false && $this->input->get('idParecer', TRUE) != false) {
            $this->load->model('parecer_proposta_model');
            $parecer = $this->parecer_proposta_model->get_by_id($this->input->get('idProposta', TRUE), $this->input->get('idParecer', TRUE));

            echo $parecer->parecer . ($parecer->tem_anexo ? "<br><br><a style='color:red;' target='_blank' href='https://www.convenios.gov.br/siconv/DetalharParecerProposta/ParecerPropostaVisualizarParecer.do?path=/MostraPrincipalConsultarPrograma.do&Usr=guest&Pwd=guest&idProposta=" . $parecer->id_proposta . "&idParecer=" . $parecer->id_parecer . "'>Visualizar Parecer</a>" : "");
        } else if ($this->input->get('id', TRUE) != false) {
            $this->load->model('proposta_model');

            $proposta = $this->proposta_model->get_by_id($this->input->get('id', TRUE));
            $data['proposta'] = $proposta;

            $data['title'] = "Physis - Consulta Parecer";
            $data['main'] = 'usuario/consulta_parecer';

            $this->load->view('in/template', $data);
        } else
            $this->voltaPagina();
    }

    public function links_uteis() {
        $this->session->set_userdata('pagAtual', 'links_uteis');

        $data['title'] = "Physis - Links Úteis";
        $data['main'] = 'usuario/links_uteis';

        $this->load->view('in/template', $data);
    }

    public function tutoriais() {
        $this->session->set_userdata('pagAtual', 'tutoriais');

        $data['title'] = "Physis - Tutoriais";
        $data['main'] = 'usuario/tutoriais';

        $this->load->view('in/template', $data);
    }

    public function suporte() {
        $this->session->set_userdata("pagAtual", "suporte");

        $data['title'] = "Physis - Suporte";
        $data['main'] = 'usuario/suporte';

        $this->load->view('in/template', $data);
    }

    public function acessar_suporte() {
        $this->session->set_userdata("pagAtual", "suporte");

        $data['title'] = "Physis - Suporte";
        $data['main'] = 'usuario/pagina_suporte';
        $data['link'] = $this->input->get('tipo', TRUE) == 'N' ? "http://suporte.physisbrasil.com.br/suporte/account.php?do=create" : "http://suporte.physisbrasil.com.br/suporte/open.php";

        $this->load->view('in/template', $data);
    }

    public function utilizacao_sistema_cliente() {
        $this->load->model('usuariomodel');
        $this->load->model('proponente_siconv_model');

        $data['usuariomodel'] = $this->usuariomodel;
        $data['proponente_siconv_model'] = $this->proponente_siconv_model;
        $data['usuarios'] = $this->usuariomodel->get_usuarios_vendedor_cadastrou();
        $data['title'] = "Physis - Área do Vendedor";
        $data['main'] = "usuario/utilizacao_sistema_cliente";

        $this->load->view('in/template', $data);
    }

    public function lista_sugestoes() {
        $this->session->set_userdata('pagAtual', 'sugestoes');

        $this->load->model('sugestao_model');
        $this->load->model('usuariomodel');
        $this->load->model('proponente_siconv_model');

        $data['sugestoes'] = $this->sugestao_model->get_all();
        $data['usuariomodel'] = $this->usuariomodel;
        $data['proponente_siconv_model'] = $this->proponente_siconv_model;
        $data['sugestao_model'] = $this->sugestao_model;

        $data['title'] = "Physis - Sugestões";
        $data['main'] = "usuario/lista_sugestoes";

        $this->load->view('in/template', $data);
    }

    public function cria_sugestao() {
        $this->load->model('sugestao_model');
        $this->load->model('usuariomodel');

        $data['title'] = "Physis - Sugestões";
        $data['main'] = "usuario/cria_sugestao";

        if ($this->input->post(null, TRUE)) {
            $this->form_validation->set_rules('sugestao', 'Sugestão', 'required');

            $this->form_validation->set_message('required', 'O campo %s é obrigatório');

            $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

            if ($this->form_validation->run()) {
                $cnpj = $this->usuariomodel->get_cnpjs_by_usuario($this->session->userdata('id_usuario'));

                $this->sugestao_model->insere(array(
                    'sugestao' => $this->input->post('sugestao', TRUE),
                    'id_usuario' => $this->session->userdata('id_usuario'),
                    'uf' => isset($cnpj) ? $cnpj[0]->sigla : '',
                    'id_municipio' => isset($cnpj) ? $cnpj[0]->id_cidade : 0,
                    'data_envio' => date('Y-m-d H:i:s')
                ));

                $usuario = $this->usuariomodel->get_by_id($this->session->userdata('id_usuario'));

                $this->sugestao_model->envia_email($usuario->email, $usuario->nome, $this->input->post('sugestao', TRUE));

                $this->usuariomodel->envia_email_automatico_sugestao($usuario->email, $usuario->nome);

                $this->alert('Sugestão enviada com sucesso');
                $this->encaminha(base_url('index.php/in/usuario/lista_sugestoes'));
            }
        }

        $this->load->view('in/template', $data);
    }

    public function responde_sugestao() {
        $this->load->model('sugestao_model');
        $this->load->model('usuariomodel');

        $data['title'] = "Physis - Sugestões";
        $data['main'] = "usuario/responde_sugestao";

        $sugestao = $this->sugestao_model->get_by_id($this->input->get('id', TRUE));
        $data['sugestao'] = $sugestao;

        if ($this->input->post(null, TRUE)) {
            $this->form_validation->set_rules('resposta_sugestao', 'Resposta da Sugestão', 'required');

            $this->form_validation->set_message('required', 'O campo %s é obrigatório');

            $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

            if ($this->form_validation->run()) {
                $this->sugestao_model->insere_resposta(array(
                    'resposta_sugestao' => $this->input->post('resposta_sugestao', TRUE),
                    'id_usuario' => $this->session->userdata('id_usuario'),
                    'id_sugestao' => $this->input->get('id', TRUE),
                    'data_envio' => date('Y-m-d H:i:s')
                ));

                $usuario = $this->usuariomodel->get_by_id($sugestao->id_usuario);
                $usuario_admin = $this->usuariomodel->get_by_id($this->session->userdata('id_usuario'));

                $this->sugestao_model->envia_email($usuario_admin->email, $usuario_admin->nome, $this->input->post('resposta_sugestao', TRUE), "Resposta de Sugestão e-SICAR", $usuario->email);

                $this->alert('Resposta enviada com sucesso');
                $this->encaminha(base_url('index.php/in/usuario/lista_sugestoes'));
            }
        }

        $this->load->view('in/template', $data);
    }

}
