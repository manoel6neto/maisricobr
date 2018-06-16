<?php
include 'application/controllers/BaseController.php';

class Gestor extends BaseController {

    function __construct() {
        parent::__construct();

        $this->load->model('usuariomodel');
        $this->usuario_logado = $this->usuariomodel->get_by_id($this->session->userdata('id_usuario'));
        $this->nivel_usuario = $this->usuariomodel->get_tipo_by_id($this->usuario_logado->id_usuario);
        $this->login_usuario = $this->usuario_logado->login_siconv;
        $this->senha = base64_decode($this->usuario_logado->senha_siconv);
        $this->cookie_file_path = tempnam("/tmp", "CURLCOOKIE" . rand());

        $this->login = $this->usuario_logado->nome;

        $this->login_siconv = null;
    }

    function teste() {
        echo file_get_contents("https://www.convenios.gov.br/siconv/Principal/Principal.do");
        die();
    }

    function ocultaMenu() {
        $ocultar = $this->input->post('ocultar', TRUE);

        if ($ocultar == "true")
            $this->session->set_userdata("escondeMenu", "S");
        else
            $this->session->set_userdata("escondeMenu", "N");
    }

    function index($offSet = 0) {
        $this->load->model('programa_model');
        $this->load->model('proposta_model');

        $this->session->set_userdata('pagAtual', '');

        // display information for the view
        $data['title'] = "SIHS - Gestão de Usuários e Propostas";
        $data['main'] = 'in/home_gestor2';
        $data['login'] = $this->login;
        $result = $this->programa_model->busca_programa();
        $data['lista'] = $result['lista'];
        $data['total_rows'] = $result['total_rows'];
        $data['num_rows'] = $result['num_rows'];
        $data['offSet'] = $offSet;
        $data['propostas'] = $this->proposta_model->get_all_ativo_padrao();
        $data['propostas_enviadas'] = $this->proposta_model->get_all_ativo_enviadas($this->session->userdata('id_usuario'));
        $data['propostas_cadastradas'] = $this->proposta_model->get_all_ativo_cadastradas($this->session->userdata('id_usuario'));

        $data['vencimento_dia'] = $this->programa_model->get_programas_vencimento(0);
        $data['vencimento_cinco_dias'] = $this->programa_model->get_programas_vencimento(5);
        $data['vencimento_dez_dias'] = $this->programa_model->get_programas_vencimento(10);

        $this->load->view('in/template', $data);
    }

    function cidades_ajax() {
        $cod_estados = mysql_real_escape_string($_REQUEST['cod_estados']);

        $this->load->model('proposta_model');
        $cidades_aux = $this->proposta_model->obter_cidades($cod_estados);
        foreach ($cidades_aux as $cidade) {
            $cidades[] = array(
                'cod_cidades' => $cidade['cod_cidades'],
                'Codigo' => $cidade['cod_cidades'],
                'nome' => $cidade['nome'],
            );
        }
        echo( json_encode($cidades) );
    }

    function criar_sala_conferencia() {
        $nome = urlencode($this->usuario_logado->nome . " - SIHS");
        $documento = utf8_decode($this->obter_pagina("http://demo.bigbluebutton.org/demo/create.jsp?username1=" . $nome . "&action=create"));
        //echo $documento;
        $documento = $this->removeSpaceSurplus($documento);
        $txt1 = $this->getTextBetweenTags($documento, "<center><a href=\"", "\">Start Meeting<\/a>");
        redirect($txt1[0]);
    }

    function adiciona() {
        if ($this->input->get_post('tipo_usuario', TRUE) === false) {
            $this->alert("Escolha um tipo de usuário");
            $this->voltaPagina();
        }
        $this->load->model('usuario_model');
        $data['title'] = "SIHS - Adicionar Usuário";
        $data['main'] = 'in/adiciona_usuario';
        $data['login'] = $this->login;
        $data['tipo_usuario'] = $this->input->get_post('tipo_usuario', TRUE);
        $data['tipos'] = $this->usuario_model->get_all_tipos_usuarios();
        $this->load->view('in/template', $data);
    }

    public function testa_sms() {
        $dados = array('de' => 'Usuário', 'tel' => '7388887500', 'msg' => 'Essa é uma mensagem de teste!!', 'data' => date('d/m/Y'), 'hora' => date('H:m'));
        $f = http_build_query($dados);
        $url = "http://www.dbmkt.com.br/__envio/gw.php?chave=1be5b5cdc52fdde974dac1ef46e25680&" . $f;
        echo $url;
        $this->obter_pagina($url);
    }

    function gerencia_usuarios() {
        if ($this->input->get_post('id', TRUE) !== false) {
            // echo $this->input->get_post('id', TRUE); die();
            $this->load->model('usuario_model');
            $data_programa = implode("-", array_reverse(explode("/", $this->input->get_post('validade', TRUE))));
            $data = array(
                'idPessoa' => $this->input->get_post('id', TRUE),
                'quantidade' => $this->input->get_post('quantidade', TRUE),
                'validade' => $data_programa
            );

            #$inserido = $this->usuario_model->update_record($data);
            $this->usuario_model->update_record($data);
            $this->alert("Atualizado.");
        }

        $data['title'] = "SIHS - Gerencia Usuários";
        $this->load->model('usuario_model');
        $data['usuarios'] = $this->usuario_model->get_all();
        $data['usuario_model'] = $this->usuario_model;
        $data['main'] = 'in/gerencia_usuarios';
        $data['login'] = $this->login;
        $this->load->view('in/template', $data);
    }

    function remover_padrao() {

        $data = array(
            'padrao' => 0
        );

        $this->load->model('proposta_model');
        #$inserido = $this->proposta_model->torna_padrao($data, $this->input->get_post('id'));
        $this->proposta_model->torna_padrao($data, $this->input->get_post('id'));

        $this->alert("Projeto transformado em normal!");
        $this->encaminha('gerencia_proposta');
    }

    //THOMAS: Movendo os métodos de usuario para gestor. No primeiro momento estou duplicando. Depois de todos os testes funcionando OK. Pode ser removido.
    function incluir_justificativa() {
        $this->load->model('proposta_model');
        $this->load->model('trabalho_model'); //localhost/physisSiconv/index.php/in/usuario/incluir_justificativa?id=1&edit=1&justificativa=1

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
            );
            switch ($this->get_post_action('cadastra', 'envia', 'aprova', 'corrige')) {
                case 'cadastra':
                    if (isset($justificativa->idJustificativa) !== false)
                        $idJustificativa = $justificativa->idJustificativa;
                    else
                        $idJustificativa = null;
                    $this->trabalho_model->add_justificativa($options, $idJustificativa);
                    $justificativa = $this->trabalho_model->obter_justificativa_por_proposta($id);
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
            $data['title'] = "SIHS - Incluir Justificativa";
            $data['main'] = 'in/incluir_justificativa';
            $data['login'] = $this->login;
            $this->load->view('in/template', $data);
        } else if ($this->input->get_post('id', TRUE) !== false) {
            $id = $this->input->get_post('id', TRUE);
            $proposta = $this->proposta_model->get_by_id($id);
            $justificativa = $this->trabalho_model->obter_justificativa_por_proposta($id);
            $options = array(
                'Proposta_idProposta' => $this->input->get_post('id', TRUE),
                'Justificativa' => $this->proposta_model->replace_chars($this->input->get_post('Justificativa', TRUE)),
                'objeto' => $this->proposta_model->replace_chars($this->input->get_post('objeto', TRUE)),
                'capacidade' => $this->input->get_post('capacidade', TRUE),
            );
            switch ($this->get_post_action('cadastra', 'envia', 'aprova', 'corrige')) {
                case 'cadastra':
                    if (isset($justificativa->idJustificativa) !== false)
                        $idJustificativa = $justificativa->idJustificativa;
                    else
                        $idJustificativa = null;
                    $this->trabalho_model->add_justificativa($options, $idJustificativa);
                    $justificativa = $this->trabalho_model->obter_justificativa_por_proposta($id);
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
            $data['observacao'] = $this->trabalho_model->obter_observacao($idTrabalho[0]->idTrabalho);
            if ($this->usuario_logado->id_usuario == $this->proposta_model->get_by_id($id)->idGestor)
                $data['voltar_gestor'] = 1;
            else
                $data['voltar_gestor'] = 0;
            $data['edita_gestor'] = 0;
            if ($this->input->get_post('edita_gestor', TRUE) == 1)
                $data['edita_gestor'] = 1;
            $data['idTrabalho'] = $idTrabalho[0];
            $data['leitura_pessoa'] = false;

            $data['justificativa'] = $justificativa;
            $data['proposta'] = $proposta;
            $data['id'] = $id;
            $data['title'] = "SIHS - Incluir Justificativa";
            $data['main'] = 'in/incluir_justificativa';
            $data['login'] = $this->login;
            $this->load->view('in/template_projeto', $data);
        }
        else {
            $this->voltaPagina();
        }
    }

    function listar_metas() {
        $this->load->model('proposta_model');
        $this->load->model('trabalho_model');
        if ($this->input->get_post('id', TRUE) !== false) {
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
            $data['idTrabalho'] = $idTrabalho[0];
            $data['metas'] = $metas;
            $data['id'] = $id;
            $data['leitura_pessoa'] = false;
            $data['title'] = "SIHS - Listar Metas da proposta";
            $data['main'] = 'in/listar_metas';
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
            $data['observacao'] = $this->trabalho_model->obter_observacao($idTrabalho[0]->idTrabalho);
            if ($this->usuario_logado->id_usuario == $this->proposta_model->get_by_id($id)->idGestor)
                $data['voltar_gestor'] = 1;
            else
                $data['voltar_gestor'] = 0;
            //THOMAS: Travado em 1 para sempre editar como gestor
            $data['edita_gestor'] = 1;
            /* if ($this->input->get_post('edita_gestor', TRUE) == 1)
              $data['edita_gestor'] = 1; */
            $data['idTrabalho'] = $idTrabalho[0];
            $data['cronograma'] = $cronograma;
            $data['id'] = $id;
            $data['title'] = "SIHS - Listar Etapas da Meta";
            $data['leitura_pessoa'] = false;
            $data['main'] = 'in/listar_cronograma';
            $data['login'] = $this->login;
            $this->load->view('in/template_projeto', $data);
        }
        else {
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

            $data['observacao'] = $this->trabalho_model->obter_observacao($idTrabalho[0]->idTrabalho);
            if ($this->usuario_logado->id_usuario == $this->proposta_model->get_by_id($id)->idGestor)
                $data['voltar_gestor'] = 1;
            else
                $data['voltar_gestor'] = 0;
            $data['edita_gestor'] = 1;
            /* if ($this->input->get_post('edita_gestor', TRUE) == 1)
              $data['edita_gestor'] = 1; */
            $data['idTrabalho'] = $idTrabalho[0];
            $data['leitura_pessoa'] = false;
            $data['tipo_despesas'] = $tipo_despesas;
            $data['total'] = $total;
            $data['despesas'] = $despesas;
            $data['id'] = $id;
            $data['title'] = "SIHS - Listar Obras";
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

    function visualiza_proposta() {
        $this->load->model('proposta_model');
        $this->load->model('trabalho_model');
        $this->load->model('programa_proposta_model');

        $id = $this->input->get_post('id', TRUE);
        $proposta = $this->proposta_model->get_by_id($id);
        $justificativa = $this->trabalho_model->obter_justificativa_por_proposta($id);

        $bens = $this->trabalho_model->obter_despesas($id);

        $data['programa_proposta_model'] = $this->programa_proposta_model;
        $data['login'] = $this->login;
        $data['proposta'] = $proposta;
        $data['justificativa'] = $justificativa;
        $data['bens'] = $bens;
        $data['id'] = $id;
        $data['trabalho_model'] = $this->trabalho_model;
        $data['main'] = 'usuario/visualiza_proposta';
        $data['bancos'] = $this->proposta_model->obter_bancos();
        $data['title'] = 'SIHS - Visualizar Proposta';
        $this->load->vars($data);
        $this->load->view('in/template_projeto');
    }

    function get_post_action($name) {
        $params = func_get_args();

        foreach ($params as $name) {
            if (isset($_POST[$name])) {
                return $name;
            }
        }
    }

    // FIM MOVER Métodos de controle Usuario para Gestor

    function tornar_padrao() {
        $this->load->model('programa_model');
        if ($this->input->get_post('login', TRUE) !== false && $this->input->get_post('senha', TRUE) !== false) {
            $this->obter_paginaLogin($this->input->get_post('login', TRUE), $this->input->get_post('senha', TRUE));
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

            $data['id'] = $this->input->get_post('id', TRUE);
            $data['usuario_siconv'] = $this->input->get_post('login', TRUE);
            $data['senha_siconv'] = $this->encripta($this->input->get_post('senha', TRUE));
            $data['login'] = $this->login;
            $data['title'] = "SIHS - Finaliza Trabalho";
            $data['main'] = 'gestor/escolher_proponente_padrao';

            $this->load->view('in/template', $data);
        } else {
            $data['title'] = 'SIHS - gerenciamento de usuários';
            $data['id'] = $this->input->get_post('id', TRUE);
            $data['main'] = 'gestor/login_usuario_padrao';
            $data['login'] = $this->login;
            $this->load->vars($data);
            $this->load->view('in/template');
        }
        /*
          $data = array(
          'padrao' => 1
          );

          $this->load->model('proposta_model');
          $inserido = $this->proposta_model->torna_padrao($data, $this->input->get_post('id'));

          $this->alert("Projeto transformado em padrão!");
          $this->encaminha('gerencia_proposta'); */
    }

    function selecionar_objetos_padrao() {
        $this->load->model('trabalho_model');
        if ($this->input->get_post('cnpjProponente', TRUE) !== false && $this->input->get_post('orgao', TRUE) !== false && $this->input->get_post('objetos', TRUE) === false) {
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
            $data['title'] = "SIHS - Finaliza Trabalho";
            $data['main'] = 'gestor/selecionar_objetos_padrao';

            $this->load->view('in/template', $data);
        } else if ($this->input->get_post('objetos', TRUE) !== false) {

            $objetos_string = '';
            foreach ($this->input->get_post('objetos', TRUE) as $key => $value) {
                if ($key == 0)
                    $objetos_string .= $value;
                else
                    $objetos_string .= ',' . $value;
            }

            $data = array(
                'id_programa' => $this->input->get_post('idRowSelectionAsArray', TRUE),
                'validade' => $this->input->get_post('validade', TRUE),
                'padrao' => 1,
                'objeto' => $objetos_string
            );
            $this->load->model('proposta_model');
            $inserido = $this->proposta_model->torna_padrao($data, $this->input->get_post('id'));

            $this->alert("Programa e validade anexados! Projeto tornado padrão");

            $this->encaminha('gerencia_proposta');
        } else {
            $this->voltaPagina();
        }
    }

    function selecionar_programas_padrao() {
        $this->load->model('trabalho_model');
        if ($this->input->get_post('cnpjProponente', TRUE) !== false && $this->input->get_post('orgao', TRUE) !== false && $this->input->get_post('validade', TRUE) === false) {
            if ($this->input->get_post('orgao', TRUE) === '') {
                $this->alert("Escolha o órgão");
                $this->voltaPagina();
            }
            $pagina = "https://www.convenios.gov.br/siconv/IncluirProgramasProposta/EscolherProponenteEscolherProponente.do";
            $this->obter_paginaLogin($this->input->get_post('usuario_siconv', TRUE), $this->desencripta($this->input->get_post('senha_siconv', TRUE)));
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
            $data['id'] = $this->input->get_post('id', TRUE);
            $data['cnpjProponente'] = $this->input->get_post('cnpjProponente', TRUE);
            $data['usuario_siconv'] = $this->input->get_post('usuario_siconv', TRUE);
            $data['senha_siconv'] = $this->input->get_post('senha_siconv', TRUE);
            $data['orgao'] = $this->input->get_post('orgao', TRUE);
            $data['tabela'] = $txt1[0];
            $data['login'] = $this->login;
            $data['title'] = "SIHS - Finaliza Trabalho";
            $data['main'] = 'gestor/selecionar_programas_padrao';

            $this->load->view('in/template', $data);
        } else {
            $this->voltaPagina();
        }
    }

    function desativa_usuario() {

        $data = array(
            'ativo' => 0
        );

        $this->load->model('usuario_model');
        $inserido = $this->usuario_model->ativa_usuario($data, $this->input->get_post('usuario'));

        $this->alert("Usuário desativado com sucesso!");
        $this->encaminha('gerencia_usuarios');
    }

    function ativa_usuario() {

        $data = array(
            'ativo' => 1
        );

        $this->load->model('usuario_model');
        $inserido = $this->usuario_model->ativa_usuario($data, $this->input->get_post('usuario'));

        $this->alert("Usuário ativado com sucesso!");
        $this->encaminha('gerencia_usuarios');
    }

    function cadastrar() {

        $data = array(
            'nome' => $this->input->get_post('nome', TRUE),
            'cpf' => $this->input->get_post('cpf', TRUE),
            'login' => $this->input->get_post('login', TRUE),
            'senha' => hash('sha1', $this->input->get_post('senha', TRUE)),
            'email' => $this->input->get_post('email', TRUE),
            'telefone' => $this->input->get_post('telefone', TRUE),
            'escolaridade' => $this->input->get_post('escolaridade', TRUE),
            'nomeInstituicao ' => $this->input->get_post('nomeInstituicao', TRUE),
            'endereco' => $this->input->get_post('endereco', TRUE),
            'tipoPessoa' => $this->input->get_post('tipoPessoa', TRUE),
            'ativo' => 1
        );

        $this->load->model('usuario_model');
        $inserido = $this->usuario_model->add_records($data);
        if ($inserido == false) {
            $this->alert("Login já existe, tente outro.");
            $this->voltaPagina();
        }
        $this->encaminha('index');
    }

    function atribui_permissoes() {

        $this->load->model('trabalho_model');
        if ($this->input->get_post('id', TRUE) !== false) {

            $data_programa = implode("-", array_reverse(explode("/", $this->input->get_post('data', TRUE))));
            $data = array(
                'Status_idstatus' => 1, //esperando aceitação
                'Tipo_trabalho_idTrabalho' => 1,
                'Pessoa_idPessoa' => $this->input->get_post('justificativa', TRUE),
                'id_correspondente' => $this->input->get_post('id', TRUE), //id da proposta
                'data' => $data_programa
            );
            $inserido = $this->trabalho_model->add_records($data);

            $data_programa = implode("-", array_reverse(explode("/", $this->input->get_post('data', TRUE))));
            $data = array(
                'Status_idstatus' => 1, //esperando aceitação
                'Tipo_trabalho_idTrabalho' => 2,
                'Pessoa_idPessoa' => $this->input->get_post('cronograma', TRUE),
                'id_correspondente' => $this->input->get_post('id', TRUE), //id da proposta
                'data' => $data_programa
            );
            $inserido = $this->trabalho_model->add_records($data);

            $data_programa = implode("-", array_reverse(explode("/", $this->input->get_post('data', TRUE))));
            $data = array(
                'Status_idstatus' => 1, //esperando aceitação
                'Tipo_trabalho_idTrabalho' => 3,
                'Pessoa_idPessoa' => $this->input->get_post('cronograma', TRUE),
                'id_correspondente' => $this->input->get_post('id', TRUE), //id da proposta
                'data' => $data_programa
            );
            $inserido = $this->trabalho_model->add_records($data);

            $data_programa = implode("-", array_reverse(explode("/", $this->input->get_post('data', TRUE))));
            $data = array(
                'Status_idstatus' => 1, //esperando aceitação
                'Tipo_trabalho_idTrabalho' => 4,
                'Pessoa_idPessoa' => $this->input->get_post('bens', TRUE),
                'id_correspondente' => $this->input->get_post('id', TRUE), //id da proposta
                'data' => $data_programa
            );

            $inserido = $this->trabalho_model->add_records($data);
            $this->encaminha('visualiza_propostas');
        }
        $usuario = $this->usuario_logado->idPessoa;
        $this->load->model('proposta_model');
        $this->load->model('usuario_model');
        if ($this->usuario_logado->tipoPessoa == 7)
            $data['usuarios'] = $this->usuario_model->get_all_trabalho($usuario);
        else
            $data['usuarios'] = $this->usuario_model->get_all();
        if ($this->usuario_logado->tipoPessoa != 2)
            $data['propostas'] = $this->proposta_model->get_all_por_id($usuario);
        else
            $data['propostas'] = $this->proposta_model->get_all($usuario);
        $data['proposta_model'] = $this->proposta_model;
        $data['trabalhos'] = $this->trabalho_model;
        $data['login'] = $this->login;
        $data['title'] = "SIHS - Atribui Permissões";
        $data['main'] = 'gestor/atribui_permissoes';
        $this->load->view('in/template', $data);
    }

    function gerencia_usuario() {

        $data['title'] = 'SIHS - gerenciamento de usuários';

        $data['main'] = 'gestor/gerencia_usuario';
        $data['login'] = $this->login;
        $this->load->vars($data);
        $this->load->view('in/template');
    }

    function escolher_proponente() {
        $this->load->model('programa_model');
        $this->load->model('usuariomodel');

        if ($this->input->get_post('padrao', TRUE) === "1")
            $this->session->set_userdata('pagAtual', 'visualiza_banco_propostas');
        else if ($this->input->get_post('id', TRUE) === false)
            $this->session->set_userdata('pagAtual', 'escolher_proponente');

        if ($this->input->get_post('id', TRUE) !== false) {
            $this->load->model('proposta_model');
            $data['proposta'] = $this->proposta_model->get_by_id($this->input->get_post('id', TRUE));
        }

        $ehParlamentar = false;
        if ($this->nivel_usuario == "GESTOR")
            $ehParlamentar = $this->usuariomodel->verifica_eh_parlamentar();

        if ($this->nivel_usuario == "ADMIN" || $this->nivel_usuario == "VENDEDOR" || $ehParlamentar) {

            if ($this->input->get_post('login', TRUE) !== false && $this->input->get_post('senha', TRUE) !== false) {
                $this->usuariomodel->add_login_senha_siconv_vendedor_admin($this->usuario_logado->id_usuario, $this->input->get_post('login', TRUE), $this->input->get_post('senha', TRUE));
                $this->login_usuario = $this->input->get_post('login', TRUE);
                $this->senha = $this->input->get_post('senha', TRUE);
            }

            if ($this->login_usuario != "" && $this->senha != "" && $this->session->userdata('falha_login') != 'S') {

                //$this->obter_paginaLogin($this->login_usuario, $this->senha);
                $this->autentica_siconv->new_init_siconv_do_login($this->login_usuario, $this->senha, $this->login_siconv, $this->cookie_file_path);

                $pagina = "https://www.convenios.gov.br/siconv/proposta/IncluirDadosProposta/IncluirDadosProposta.do";
                //$documento = $this->obter_pagina($pagina);
                $documento = $this->autentica_siconv->new_obter_pagina($pagina, $this->login_siconv, $this->cookie_file_path);
                $documento = $this->removeSpaceSurplus($documento);

                if (strstr($documento, "Service Temporarily Unavailable") == true) {
                    $this->alert("Falha na comunicação com o SICONV.");
                    $this->encaminha(base_url() . "index.php/in/gestor/escolher_proponente");
                } else {
                    $txt1 = $this->getTextBetweenTags($documento, "<table> <tr>", "<\/tr> <\/table>");
                    //var_dump($txt1);
                    if (count($txt1) > 0) {
                        if ($this->nivel_usuario == "GESTOR" || $this->nivel_usuario == "VENDEDOR") {
                            ##Thomas: Filtrando para exibir apenas as cidades (CNPJ's que o usuário tem acesso ou pelo limite do pacote)
                            $this->load->model('usuario_cnpj');
                            $ids_cnpjs = $this->usuario_cnpj->get_all_by_usuario($this->usuario_logado->id_usuario);
                            $this->load->model('cnpj_siconv');
                            $cnpjs_user = array();

                            foreach ($ids_cnpjs as $ids) {
                                array_push($cnpjs_user, $this->cnpj_siconv->get_by_id($ids->id_cnpj));
                            }
                            $data['tabela'] = $txt1[0];

                            $matches = null;
                            $ret = preg_match_all('#<td ([\\w\\W]*?)</td>#', $data['tabela'], $matches);
                            #include('simple_html_dom.php');
                            #$html = str_get_html($data['tabela']);
                            #$ret = $html->find('td');
                            $data_filtered = array();
                            foreach ($matches[0] as $row) {
                                foreach ($cnpjs_user as $cnpj) {
                                    if (strstr($row, $cnpj->cnpj) !== false) {
                                        array_push($data_filtered, $row);
                                        break;
                                    }
                                }
                            }

                            $data['tabela'] = '';
                            foreach ($data_filtered as $string) {
                                if (count($data['tabela']) > 0) {
                                    $data['tabela'] = $data['tabela'] . '<tr>' . $string;
                                }
                            }
                        } else {
                            if (!empty($txt1))
                                $data['tabela'] = $txt1[0];
                            else {
                                //bolar logica para o admin
                            }
                        }
                    } else { //quando o cpf esta vinculado a uma so prefeitura
                        $pagina = "https://www.convenios.gov.br/siconv/participe/VisualizarCadastramento/VisualizarCadastramento.do";
                        //$documento = $this->obter_pagina($pagina);
                        $documento = $this->autentica_siconv->new_obter_pagina($pagina, $this->login_siconv, $this->cookie_file_path);
                        $documento = $this->removeSpaceSurplus($documento);

                        if (strstr($documento, "Service Temporarily Unavailable") == true) {
                            $this->alert("Falha na comunicação com o SICONV.");
                            $this->encaminha(base_url() . "index.php/in/gestor/escolher_proponente");
                        } else {
                            $txt1 = $this->getTextBetweenTags($documento, "align=right>Identificação<\/td> <td class=\"field\" colspan=2>", "<\/td>");
                            if (empty($txt1)) {
                                $txt1 = $this->getTextBetweenTags($documento, "<div id=\"listaProponentes\" class=\"table\">", "<\/table><\/div>");
                                $txt1 = $this->getTextBetweenTags($txt1[0], "<tbody id=\"tbodyrow\">", "<\/tbody>");
                                $txt1 = $this->getTextBetweenTags($txt1[0], "<div class=\"identificacao\">CNPJ ", "<\/div>");

                                $this->load->model('usuario_cnpj');
                                $ids_cnpjs = $this->usuario_cnpj->get_all_by_usuario($this->usuario_logado->id_usuario);
                                $this->load->model('cnpj_siconv');
                                $cnpjs_user = array();

                                foreach ($ids_cnpjs as $ids) {
                                    array_push($cnpjs_user, $this->cnpj_siconv->get_by_id($ids->id_cnpj));
                                }

                                $data_filtered = array();
                                foreach ($txt1 as $row) {
                                    foreach ($cnpjs_user as $cnpj) {
                                        if (strstr($row, $cnpj->cnpj) !== false) {
                                            array_push($data_filtered, $row);
                                            break;
                                        }
                                    }
                                }
                                $this->load->model('proponente_siconv_model');
                                $data['tabela'] = '<table class="table">';
                                foreach ($data_filtered as $string) {
                                    if (count($data['tabela']) > 0) {
                                        $data['tabela'] = $data['tabela'] . '<tr><td style="font-family: Arial; font-size: 14px; color: rgb(255, 0, 0); font-weight: normal;"><input type="radio" name="cnpjProponente" value="' . $string . '" id="escolherProponenteCnpjProponente">&nbsp;CNPJ ' . $string . " - " . $this->proponente_siconv_model->get_instituicao_nome($string, true) . "</td>";
                                    }
                                }
                                $data['tabela'] = $data['tabela'] . '</table>';
                            } else {
                                $carac = array(".", "-", "/", "&nbsp;");
                                $cnpj = str_replace($carac, "", trim($txt1[0]));
                                $data['tabela'] = "<input type=\"hidden\" name=\"cnpjProponente\" value=\"$cnpj\">";
                                $data['ocultaEntidade'] = 1;
                            }
                        }
                    }
                }

                $data['orgaos'] = $this->programa_model->get_all_orgaos();
                if ($this->input->get_post('id', TRUE) !== false) {
                    $data['id'] = $this->input->get_post('id', TRUE);
                }
                $data['usuario_siconv'] = $this->input->get_post('login', TRUE);
                $data['senha_siconv'] = $this->encripta($this->input->get_post('senha', TRUE));
                $data['login'] = $this->login;
                $data['title'] = "Physis - Escolher Proponente";
                $data['main'] = 'gestor/escolher_proponente';

                $this->load->view('in/template_projeto', $data);
            } else {
                $data['title'] = 'Physis - Novo projeto';
                $data['main'] = 'gestor/login_usuario';
                $data['login'] = $this->login;
                $this->load->vars($data);
                $this->load->view('in/template');
            }
        } else {
            $documento = "";
            $startoff = false;
            $retorno = $this->autentica_siconv->new_init_siconv_do_login(utf8_decode($this->login_usuario), utf8_decode($this->senha), $this->login_siconv, $this->cookie_file_path, false);
            if ($retorno != null) {
                $this->alert('Login do siconv invalido / siconv fora do ar entrando no modo offline.');
                $startoff = true;
            } else {
                $pagina = "https://www.convenios.gov.br/siconv/proposta/IncluirDadosProposta/IncluirDadosProposta.do";
                //$documento = $this->obter_pagina($pagina);
                $documento = $this->autentica_siconv->new_obter_pagina($pagina, $this->login_siconv, $this->cookie_file_path);
                $documento = $this->removeSpaceSurplus($documento);
            }

            if ($startoff == true || strstr($documento, "Service Temporarily Unavailable") == true) {
//            if (true) { //debug
                if ($startoff == false) {
                    $this->alert('Siconv fora do ar entrando no modo offline.');
                }

                $data['tabela'] = '';
                $this->load->model('proponente_siconv_model');
                $data['tabela'] = '<table class="table">';

                $this->load->model('usuario_cnpj');
                if ($this->session->userdata('nivel') == 2 || $this->session->userdata('nivel') == 3)
                    $ids_cnpjs = $this->usuario_cnpj->get_all_by_usuario($this->usuario_logado->id_usuario);
                else if ($this->session->userdata('nivel') == 6 || $this->session->userdata('nivel') == 7)
                    $ids_cnpjs = $this->usuario_cnpj->get_all_by_subgestor($this->usuario_logado->id_usuario);
                $this->load->model('cnpj_siconv');
                $cnpjs_user = array();

                foreach ($ids_cnpjs as $ids) {
                    array_push($cnpjs_user, $this->cnpj_siconv->get_by_id($ids->id_cnpj));
                }

                foreach ($cnpjs_user as $cn) {
                    $data['tabela'] = $data['tabela'] . '<tr><td style="font-family: Arial; font-size: 14px; color: rgb(255, 0, 0); font-weight: normal;"><input type="radio" name="cnpjProponente" value="' . $cn->cnpj . '" id="escolherProponenteCnpjProponente">&nbsp;CNPJ ' . $cn->cnpj . " - " . $cn->cnpj_instituicao . "</td>";
                }

                $data['tabela'] = $data['tabela'] . '</table>';
                $data['offline'] = true;
            } else {
                $txt1 = $this->getTextBetweenTags($documento, "<table> <tr>", "<\/tr> <\/table>");
                if (count($txt1) > 0) {
                    ##Thomas: Filtrando para exibir apenas as cidades (CNPJ's que o usuário tem acesso ou pelo limite do pacote)
                    $this->load->model('usuario_cnpj');
                    if ($this->session->userdata('nivel') == 2 || $this->session->userdata('nivel') == 3)
                        $ids_cnpjs = $this->usuario_cnpj->get_all_by_usuario($this->usuario_logado->id_usuario);
                    else if ($this->session->userdata('nivel') == 6 || $this->session->userdata('nivel') == 7)
                        $ids_cnpjs = $this->usuario_cnpj->get_all_by_subgestor($this->usuario_logado->id_usuario);
                    $this->load->model('cnpj_siconv');
                    $cnpjs_user = array();

                    foreach ($ids_cnpjs as $ids) {
                        array_push($cnpjs_user, $this->cnpj_siconv->get_by_id($ids->id_cnpj));
                    }
                    $data['tabela'] = $txt1[0];

                    $matches = null;
                    $ret = preg_match_all('#<td ([\\w\\W]*?)</td>#', $data['tabela'], $matches);
                    #include('simple_html_dom.php');
                    #$html = str_get_html($data['tabela']);
                    #$ret = $html->find('td');
                    $data_filtered = array();
                    foreach ($matches[0] as $row) {
                        foreach ($cnpjs_user as $cnpj) {
                            if (strstr($row, $cnpj->cnpj) !== false) {
                                array_push($data_filtered, $row);
                                break;
                            }
                        }
                    }

                    $data['tabela'] = '';
                    foreach ($data_filtered as $string) {
                        if (count($data['tabela']) > 0) {
                            $data['tabela'] = $data['tabela'] . '<tr>' . $string;
                        }
                    }
                } else { //quando o cpf esta vinculado a uma so prefeitura
                    $data['tabela'] = '';
                    $this->load->model('proponente_siconv_model');
                    $data['tabela'] = '<table class="table">';

                    $this->load->model('usuario_cnpj');
                    if ($this->session->userdata('nivel') == 2 || $this->session->userdata('nivel') == 3)
                        $ids_cnpjs = $this->usuario_cnpj->get_all_by_usuario($this->usuario_logado->id_usuario);
                    else if ($this->session->userdata('nivel') == 6 || $this->session->userdata('nivel') == 7)
                        $ids_cnpjs = $this->usuario_cnpj->get_all_by_subgestor($this->usuario_logado->id_usuario);
                    $this->load->model('cnpj_siconv');
                    $cnpjs_user = array();

                    foreach ($ids_cnpjs as $ids) {
                        array_push($cnpjs_user, $this->cnpj_siconv->get_by_id($ids->id_cnpj));
                    }

                    foreach ($cnpjs_user as $cn) {
                        $data['tabela'] = $data['tabela'] . '<tr><td style="font-family: Arial; font-size: 14px; color: rgb(255, 0, 0); font-weight: normal;"><input type="radio" name="cnpjProponente" value="' . $cn->cnpj . '" id="escolherProponenteCnpjProponente">&nbsp;CNPJ ' . $cn->cnpj . " - " . $cn->cnpj_instituicao . "</td>";
                    }

                    $data['tabela'] = $data['tabela'] . '</table>';
//                    $pagina = "https://www.convenios.gov.br/siconv/participe/VisualizarCadastramento/VisualizarCadastramento.do";
//                    //$documento = $this->obter_pagina($pagina);
//                    $documento = $this->autentica_siconv->new_obter_pagina($pagina, $this->login_siconv, $this->cookie_file_path);
//                    $documento = $this->removeSpaceSurplus($documento);
//
//                    if (strstr($documento, "Service Temporarily Unavailable") == true) {
//                        $this->alert("Falha na comunicação com o SICONV. Ativando modo offline.");
//                        $data['tabela'] = '';
//                    } else {
////                        $txt1 = $this->getTextBetweenTags($documento, "align=right>Identificação<\/td> <td class=\"field\" colspan=2>", "<\/td>");
////                        if (empty($txt1)) {
////                            $txt1 = $this->getTextBetweenTags($documento, "<div id=\"listaProponentes\" class=\"table\">", "<\/table><\/div>");
////                            $txt1 = $this->getTextBetweenTags($txt1[0], "<tbody id=\"tbodyrow\">", "<\/tbody>");
////                            $txt1 = $this->getTextBetweenTags($txt1[0], "<div class=\"identificacao\">CNPJ ", "<\/div>");
////
////                            $this->load->model('usuario_cnpj');
////                            if ($this->session->userdata('nivel') == 2 || $this->session->userdata('nivel') == 3)
////                                $ids_cnpjs = $this->usuario_cnpj->get_all_by_usuario($this->usuario_logado->id_usuario);
////                            else if ($this->session->userdata('nivel') == 6 || $this->session->userdata('nivel') == 7)
////                                $ids_cnpjs = $this->usuario_cnpj->get_all_by_subgestor($this->usuario_logado->id_usuario);
////                            $this->load->model('cnpj_siconv');
////                            $cnpjs_user = array();
////
////                            foreach ($ids_cnpjs as $ids) {
////                                array_push($cnpjs_user, $this->cnpj_siconv->get_by_id($ids->id_cnpj));
////                            }
////
////                            $data_filtered = array();
////                            foreach ($txt1 as $row) {
////                                foreach ($cnpjs_user as $cnpj) {
////                                    if (strstr($row, $cnpj->cnpj) !== false) {
////                                        array_push($data_filtered, $row);
////                                        break;
////                                    }
////                                }
////                            }
////                            $this->load->model('proponente_siconv_model');
////                            $data['tabela'] = '<table class="table">';
////                            foreach ($data_filtered as $string) {
////                                if (count($data['tabela']) > 0) {
////                                    $data['tabela'] = $data['tabela'] . '<tr><td style="font-family: Arial; font-size: 14px; color: rgb(255, 0, 0); font-weight: normal;"><input type="radio" name="cnpjProponente" value="' . $string . '" id="escolherProponenteCnpjProponente">&nbsp;CNPJ ' . $string . " - " . $this->proponente_siconv_model->get_instituicao_nome($string, true) . "</td>";
////                                }
////                            }
////                            $data['tabela'] = $data['tabela'] . '</table>';
////                        } else {
////                            $carac = array(".", "-", "/", "&nbsp;");
////                            $cnpj = str_replace($carac, "", trim($txt1[0]));
////                            $data['tabela'] = "<input type=\"hidden\" name=\"cnpjProponente\" value=\"$cnpj\">";
////                            $data['ocultaEntidade'] = 1;
////                        }
////                    }
                }
            }

            $data['orgaos'] = $this->programa_model->get_all_orgaos();
            if ($this->input->get_post('id', TRUE) !== false) {
                $data['id'] = $this->input->get_post('id', TRUE);
            }
            $data['usuario_siconv'] = $this->login_usuario;
            $data['senha_siconv'] = $this->senha;
            $data['login'] = $this->login;
            $data['title'] = "Physis - Escolher Proponente";
            $data['main'] = 'gestor/escolher_proponente';

            $this->load->view('in/template_projeto', $data);
        }
    }

    function alterar_senha() {
        if ($this->input->get_post('login', TRUE) !== false && $this->input->get_post('senha', TRUE) !== false) {
            $this->obter_paginaLogin_exporta($this->input->get_post('login', TRUE), $this->input->get_post('senha', TRUE), $this->input->get_post('id_programa', TRUE));
            $this->load->model('proposta_model');
            $data1 = array(
                'usuario_siconv' => $this->input->get_post('login', TRUE),
                'senha_siconv' => $this->encripta($this->input->get_post('senha', TRUE))
            );

            $inserido = $this->proposta_model->update_record($this->input->get_post('id_programa', TRUE), $data1);
            $this->voltaPagina();
        } else if ($this->input->get_post('id_programa', TRUE) !== false) {
            $data['title'] = 'SIHS - alterar senha';
            $data['main'] = 'gestor/alterar_senha';
            $data['login'] = $this->login;
            $data['id_programa'] = $this->input->get_post('id_programa', TRUE);
            $this->load->vars($data);
            $this->load->view('in/template');
        } else {
            $this->voltaPagina();
        }
    }

    function selecionar_programas() {
        $this->load->model('trabalho_model');
        $this->load->model('programa_proposta_model');
        if ($this->input->get_post('cnpjProponente', TRUE) != false && $this->input->get_post('orgao', TRUE) !== false) {
            $options_programa = array();
            if ($this->input->post('id_programa', TRUE) != false) {
                $valores = $this->input->post();

                for ($i = 0; $i < count($valores['id_programa']); $i++) {
                    $percentual = str_replace(".", "", $valores['percentual'][$i]);
                    $percentual = str_replace(",", ".", $percentual);

                    $total_contrapartida = str_replace(".", "", $valores['valorContrapartida'][$i]);
                    $total_contrapartida = str_replace(",", ".", $total_contrapartida);

                    $valor_global = str_replace(".", "", $valores['valorGlobal'][$i]);
                    $valor_global = str_replace(",", ".", $valor_global);

                    $contrapartida_financeira = str_replace(".", "", $valores['valorContrapartidaFinanceira'][$i]);
                    $contrapartida_financeira = str_replace(",", ".", $contrapartida_financeira);

                    $contrapartida_bens = str_replace(".", "", $valores['valorContrapartidaBensServicos'][$i]);
                    $contrapartida_bens = str_replace(",", ".", $contrapartida_bens);

                    $repasse = str_replace(".", "", $valores['valorRepasse'][$i]);
                    $repasse = str_replace(",", ".", $repasse);

                    $repasse_voluntario = str_replace(".", "", $valores['valorRepassevoluntario'][$i]);
                    $repasse_voluntario = str_replace(",", ".", $repasse_voluntario);

                    $options_programa[$i] = array(
                        'nome_programa' => $valores['nome_programa_modal'][$i],
                        'codigo_programa' => $valores['codigo_programa'][$i],
                        'programa' => "https://www.convenios.gov.br/siconv/programa/DetalharPrograma/DetalharPrograma.do?id=" . $valores['id_programa'][$i],
                        'percentual' => ($contrapartida_financeira / $valor_global) * 100,
                        'valor_global' => $valor_global,
                        'total_contrapartida' => $total_contrapartida,
                        'contrapartida_financeira' => $contrapartida_financeira,
                        'contrapartida_bens' => $contrapartida_bens,
                        'repasse' => $repasse,
                        'repasse_voluntario' => $repasse_voluntario,
                        'id_programa' => $valores['id_programa'][$i],
                        'objeto' => $valores['objeto'][$i],
                        'qualificacao' => $valores['qualificacao'][$i],
                        'id_programa_proposta' => $valores['id_programa_proposta'][$i]
                    );
                }

                $lista_programas = array();
                foreach ($options_programa as $programa) {
                    $valores_programa = new programa_proposta_model();
                    foreach ($programa as $k => $v)
                        $valores_programa->$k = $v;
                    $lista_programas[] = $valores_programa;
                }

                $data['valores_programa'] = $lista_programas;
            } else if ($this->input->get_post('id', TRUE) !== false) {
                $data['valores_programa'] = $this->programa_proposta_model->get_programas_by_proposta($this->input->get('id', TRUE));
            }

            $data['options_programa'] = serialize($options_programa);

            if ($this->session->userdata('nivel') == 2 || $this->session->userdata('nivel') == 3 || $this->session->userdata('nivel') == 6 || $this->session->userdata('nivel') == 7) {
                $cnpjValido = false;
                $this->load->model('usuario_cnpj');

                if ($this->session->userdata('nivel') == 2 || $this->session->userdata('nivel') == 3)
                    $ids_cnpjs = $this->usuario_cnpj->get_all_by_usuario($this->usuario_logado->id_usuario);
                else if ($this->session->userdata('nivel') == 6 || $this->session->userdata('nivel') == 7)
                    $ids_cnpjs = $this->usuario_cnpj->get_all_by_subgestor($this->usuario_logado->id_usuario);

                foreach ($ids_cnpjs as $cnpj) {
                    if ($this->input->get_post('cnpjProponente', TRUE) != false) {
                        if ($cnpj->cnpj == $this->input->get_post('cnpjProponente', TRUE))
                            $cnpjValido = true;
                    } else {
                        $data['cnpjProponente'] = $cnpj->cnpj;
                        $cnpjValido = true;
                        break;
                    }
                }

                if (!$cnpjValido) {
                    $this->alert("O CNPJ relacionado a conta SICONV informada não corresponde a um dos CNPJs vinculádos.");
                    $this->voltaPagina();
                    exit();
                }
            }

//         	if($this->input->get('id', TRUE) != false && $this->input->get('edit', TRUE) != false){
//         		$this->load->model('proposta_model');
//         		$data['proposta'] = $this->proposta_model->get_by_id($this->input->get_post('id', TRUE));
//         	}

            if ($this->nivel_usuario == "VENDEDOR") {

                $this->load->model('proposta_model');
                $result = $this->proposta_model->get_all_by_usuario_cnpj($this->usuario_logado->id_usuario, $this->input->get_post('cnpjProponente', TRUE));
                if ($result != null && count($result) > 10) {
                    //$this->alert("Você já possui uma proposta para esse CNPJ.");
                    $this->alert("Você excedeu a quantidade de propostas para este CNPJ");
                    $this->encaminha(base_url() . "index.php/in/gestor/visualiza_propostas");
                }
            }

            if ($this->input->get_post('orgao', TRUE) === '') {
                $this->alert("Escolha o órgão");
                $this->voltaPagina();
            }


            if ($this->input->get_post('cnpjProponente', TRUE) != false && $this->input->get_post('offline', TRUE) == false) {
                $pagina = "https://www.convenios.gov.br/siconv/IncluirProgramasProposta/EscolherProponenteEscolherProponente.do";
                //$this->obter_paginaLogin($this->login_usuario, $this->senha);
                $this->autentica_siconv->new_init_siconv_do_login($this->login_usuario, $this->senha, $this->login_siconv, $this->cookie_file_path);
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
            }

            $data['trabalho_model'] = $this->trabalho_model;
            if ($this->input->get_post('cnpjProponente', TRUE) != false)
                $data['cnpjProponente'] = $this->input->get_post('cnpjProponente', TRUE);
            $data['usuario_siconv'] = $this->login_usuario;
            $data['senha_siconv'] = $this->senha;
            if ($this->input->get_post('id', TRUE) !== false) {
                $data['id'] = $this->input->get_post('id', TRUE);
            }
            $data['orgao'] = $this->input->get_post('orgao', TRUE);

            //TODO: Caso seja offline tem que montar a tabela com os programas abertos desse orgao
            if ($this->input->get_post('offline', TRUE) == false) {
                $numPaginas = $this->getTextBetweenTags($documento, "<span class=\"pagelinks\">", "<\/span>");

                if (empty($numPaginas)) {
                    $txt1 = $this->getTextBetweenTags($documento, "<table id=\"row\">", "<\/table>");

                    if (count($txt1) > 0) {
                        $data['tabela'] = $txt1[0];
                        $data['tabela'] = str_replace('/siconv/IncluirProgramasProposta/SelecionarProgramasVerDados.do?', 'https://www.convenios.gov.br/siconv/ConsultarPrograma/ResultadoDaConsultaDeProgramaDeConvenioDetalhar.do?', $data['tabela']);
                    } else {
                        $data['tabela'] = "Nenhum programa disponível.";
                    }
                } else {
                    $documentoPaginas = $documento;
                    $numPaginas = explode(",", $numPaginas[0]);
                    //var_dump(array($numPaginas, count($numPaginas)));

                    $tabela = "";
                    for ($i = 1; $i <= count($numPaginas); $i++) {
                        if ($i == 1) {
                            $txt1 = $this->getTextBetweenTags($documentoPaginas, "<tbody id=\"tbodyrow\">", "<\/tbody>");
                            if (count($txt1) > 0)
                                $tabela .= $txt1[0];
                        }else {
                            //$documentoPaginas = $this->obter_pagina("https://www.convenios.gov.br/siconv/br/gov/mp/siconv/uc/proposta/incluirProgramasProposta/selecionar-programas.jsp?d-16544-t=listaProgramasConsulta&d-16544-p=".$i);
                            $documentoPaginas = $this->autentica_siconv->new_obter_pagina("https://www.convenios.gov.br/siconv/br/gov/mp/siconv/uc/proposta/incluirProgramasProposta/selecionar-programas.jsp?d-16544-t=listaProgramasConsulta&d-16544-p=" . $i, $this->login_siconv, $this->cookie_file_path);
                            $documentoPaginas = $this->removeSpaceSurplus($documentoPaginas);

                            $txt1 = $this->getTextBetweenTags($documentoPaginas, "<tbody id=\"tbodyrow\">", "<\/tbody>");
                            if (count($txt1) > 0) {
                                $tabela .= utf8_decode($txt1[0]);
                            }
                        }
                    }

                    if (!empty($tabela)) {
                        $tabela = '<thead><tr><th></th><th class="codigo">Código</th><th class="anoPrograma">Ano Programa</th><th class="nome">Nome</th><th class="modalidade">Modalidade</th></tr></thead><tbody id="tbodyrow">' . $tabela . '</tbody>';
                        $data['tabela'] = $tabela;
                        $data['tabela'] = str_replace('/siconv/IncluirProgramasProposta/SelecionarProgramasVerDados.do?', 'https://www.convenios.gov.br/siconv/ConsultarPrograma/ResultadoDaConsultaDeProgramaDeConvenioDetalhar.do?', $data['tabela']);
                    } else {
                        $data['tabela'] = "Nenhum programa disponível.";
                    }
                }
            } else {
                $fake_programs = $this->trabalho_model->get_dummy_programs($this->input->get_post('cnpjProponente', TRUE), $data['orgao']);
                if ($fake_programs != null) {

                    $tabela = '<thead> <tr> <th></th> <th class="codigo">Código</th> <th class="anoPrograma">Ano Programa</th> <th class="nome">Nome</th> <th class="modalidade">Modalidade</th></tr></thead><tbody id="tbodyrow">';
                    foreach ($fake_programs as $fake_program) {
                        $id_prog_siconv = explode('id=', $fake_program->link);
                        $id_prog_siconv = trim($id_prog_siconv[1]);
                        $temp = '<tr class="odd"> <td> <input type="checkbox" name="idRowSelectionAsArray" value="' . $id_prog_siconv . '" title=\'Selecionar/Desselecionar a linha\'/> </td> <td> <a href="/siconv/IncluirProgramasProposta/SelecionarProgramasVerDados.do?id="' . $id_prog_siconv . ' target="_blank" onmouseover="hints.show(\'verDados\')" onmouseout="hints.hide()">' . $fake_program->codigo . '</a> </td> <td> <div class="anoPrograma">' . $fake_program->ano . '</div> </td> <td> <div class="nome">' . $fake_program->nome . '</div> </td> <td> <div class="modalidade">' . $fake_program->qualificacao . '</div> </td></tr>';
                        $temp = str_replace('/siconv/IncluirProgramasProposta/SelecionarProgramasVerDados.do?', 'https://www.convenios.gov.br/siconv/ConsultarPrograma/ResultadoDaConsultaDeProgramaDeConvenioDetalhar.do?', $temp);
                        $tabela = $tabela . $temp;
                    }

                    $tabela = $tabela . '</tbody>';
                    $data['tabela'] = $tabela;
                } else {
                    $data['tabela'] = "Nenhum programa disponível.";
                }
            }

            if ($this->input->get_post('offline', TRUE) != false) {
                $data['offline'] = $this->input->get_post('offline', TRUE);
            }

            $data['login'] = $this->login;
            $data['title'] = "SIHS - Selecionar Programas";
            $data['main'] = 'gestor/selecionar_programas';

            $this->load->view('in/template_projeto', $data);
        } else {
            $this->alert("É necessário o orgão");
            $this->voltaPagina();
        }
    }

    public function informa_valores_programa() {
        $this->load->model('trabalho_model');
        $this->load->model('proposta_model');
        $this->load->model('programa_proposta_model');
        $this->load->model('programa_model');
        $this->load->model('emenda_programa_proposta_model');

        if (($this->input->get_post('cnpjProponente', TRUE) != false && $this->input->get_post('orgao', TRUE) !== false) || ($this->input->get('id', TRUE) != false && $this->input->get('edit', TRUE) != false)) {

            $force_offline = true;
            if ($this->session->userdata('nivel') == 1 || $this->session->userdata('nivel') == 4 || $this->session->userdata('nivel') == 2) {
                $force_offline = false;
            }

            if ($retorno = $this->autentica_siconv->new_init_siconv_do_login(utf8_decode($this->login_usuario), utf8_decode($this->senha), $this->login_siconv, $this->cookie_file_path, false) == null) {
                $force_offline = false;
            } else {
                if ($this->input->get('edit', TRUE) != false) {
                    $this->alert('Login do siconv invalido / siconv fora do ar entrando no modo offline.');
                    $force_offline = true;
                }
            }

            $orgao = $this->input->get_post('orgao', TRUE);

            $cnpjProponente = $this->input->get_post('cnpjProponente', TRUE);

            if ($this->input->get('id', TRUE) != false && $this->input->get('edit', TRUE) != false && ($this->input->post('finaliza_selecao') == FALSE)) {
                $proposta = $this->proposta_model->get_by_id($this->input->get_post('id', TRUE));

                $data['proposta'] = $proposta;

                $orgao = $proposta->orgao;
                $cnpjProponente = $proposta->proponente;
            }

            if ($this->input->get('padrao', TRUE) != false) {
                $proposta = $this->programa_proposta_model->get_programas_by_proposta_padrao($this->input->get_post('id', TRUE));

                $data['proposta'] = $proposta;
            }

            if ($this->input->get_post('offline', TRUE) == false && $force_offline == false) {

                $pagina = "https://www.convenios.gov.br/siconv/IncluirProgramasProposta/EscolherProponenteEscolherProponente.do";
                //$this->obter_paginaLogin($this->login_usuario, $this->senha);
                $this->autentica_siconv->new_init_siconv_do_login($this->login_usuario, $this->senha, $this->login_siconv, $this->cookie_file_path);
                $fields = array(
                    'invalidatePageControlCounter' => '1',
                    'cnpjProponente' => $cnpjProponente
                );
                $fields_string = null;
                foreach ($fields as $key => $value) {
                    $fields_string .= $key . '=' . $value . '&';
                }
                rtrim($fields_string, '&');
                $this->obter_pagina_post($pagina, $fields, $fields_string);
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
                $documento = $this->obter_pagina_post($pagina, $fields, $fields_string);
                $documento = $this->removeSpaceSurplus($documento);

                $txt1 = $this->getTextBetweenTags($documento, "<table id=\"row\">", "<\/table>");
            }

            $data['trabalho_model'] = $this->trabalho_model;
            $data['cnpjProponente'] = $cnpjProponente;
            $data['usuario_siconv'] = $this->login_usuario;
            $data['senha_siconv'] = $this->senha;
            $data['usuario_logado'] = $this->usuario_logado->id_usuario;
            $data['id'] = $this->input->get_post('id', TRUE);
            $data['orgao'] = $orgao;

            $theadTable = "<th></th><th>Código</th><th>Nome</th>";

            if ($this->input->get_post('offline', TRUE) == false && $force_offline == false) {
                $txt1 = $this->getTextBetweenTags($documento, '<tbody id="tbodyrow"> <tr class="odd"> ', "<\/tr><\/tbody>");
            }

            $ids_programa = array();
            if ($this->input->post('idRowSelectionAsArray', TRUE) != false)
                $ids_programa = $this->input->post('idRowSelectionAsArray', TRUE);

            if ($this->input->get('id', TRUE) != false && !isset($_GET['padrao'])) {
                $programas = $this->programa_proposta_model->get_programas_by_proposta($this->input->get('id', TRUE));
                foreach ($programas as $p)
                    $ids_programa[] = $p->id_programa;
            } else if ($this->input->post('obj_programa', TRUE) != false) {
                $programas = unserialize($this->input->post('obj_programa'));
                unset($programas['valores_emendas']);
                for ($i = 0; $i < count($programas); $i++)
                    $ids_programa[] = $programas[$i]['id_programa'];
            }


            $tabelaProgramas = "";

            if ($this->input->get_post('offline', TRUE) == false && $force_offline == false) {
                $numPaginas = $this->getTextBetweenTags($documento, "<span class=\"pagelinks\">", "<\/span>");

                if (empty($numPaginas)) {
                    $txt1 = $this->getTextBetweenTags($documento, "<table id=\"row\">", "<\/table>");

                    if (count($txt1) > 0) {
                        $tabelaProgramas = $txt1[0];
                        $tabelaProgramas = str_replace('/siconv/IncluirProgramasProposta/SelecionarProgramasVerDados.do?', 'https://www.convenios.gov.br/siconv/ConsultarPrograma/ResultadoDaConsultaDeProgramaDeConvenioDetalhar.do?', $tabelaProgramas);
                    }
                } else {
                    $documentoPaginas = $documento;
                    $numPaginas = explode(",", $numPaginas[0]);
                    //var_dump(array($numPaginas, count($numPaginas)));

                    $tabela = "";
                    for ($i = 1; $i <= count($numPaginas); $i++) {
                        if ($i == 1) {
                            $txt1 = $this->getTextBetweenTags($documentoPaginas, "<tbody id=\"tbodyrow\">", "<\/tbody>");
                            if (count($txt1) > 0)
                                $tabela .= $txt1[0];
                        }else {
                            //$documentoPaginas = $this->obter_pagina("https://www.convenios.gov.br/siconv/br/gov/mp/siconv/uc/proposta/incluirProgramasProposta/selecionar-programas.jsp?d-16544-t=listaProgramasConsulta&d-16544-p=".$i);
                            $documentoPaginas = $this->autentica_siconv->new_obter_pagina("https://www.convenios.gov.br/siconv/br/gov/mp/siconv/uc/proposta/incluirProgramasProposta/selecionar-programas.jsp?d-16544-t=listaProgramasConsulta&d-16544-p=" . $i, $this->login_siconv, $this->cookie_file_path);
                            $documentoPaginas = $this->removeSpaceSurplus($documentoPaginas);

                            $txt1 = $this->getTextBetweenTags($documentoPaginas, "<tbody id=\"tbodyrow\">", "<\/tbody>");
                            if (count($txt1) > 0) {
                                $tabela .= utf8_decode($txt1[0]);
                            }
                        }
                    }

                    if (!empty($tabela)) {
                        $tabela = '<thead><tr><th></th><th class="codigo">Código</th><th class="anoPrograma">Ano Programa</th><th class="nome">Nome</th><th class="modalidade">Modalidade</th></tr></thead><tbody id="tbodyrow">' . $tabela . '</tbody>';
                        $tabelaProgramas = $tabela;
                        $tabelaProgramas = str_replace('/siconv/IncluirProgramasProposta/SelecionarProgramasVerDados.do?', 'https://www.convenios.gov.br/siconv/ConsultarPrograma/ResultadoDaConsultaDeProgramaDeConvenioDetalhar.do?', $tabelaProgramas);
                    }
                }

                if (!empty($tabelaProgramas)) {
                    $data['tabela'] = $tabelaProgramas;

                    $matches = null;
                    $ret = preg_match_all('#<td> ([\\w\\W]*?)</td>#', $data['tabela'], $matches);
                    #include('simple_html_dom.php');
                    #$html = str_get_html($data['tabela']);
                    #$ret = $html->find('td');

                    $data_filtered = array("<tr>" . $theadTable . "<th>Valor Global</th><th></th></tr>");
                    for ($i = 0; $i < count($matches[0]); $i+=5) {
                        $row = $matches[0][$i];
                        $valor = null;
                        foreach ($ids_programa as $id_programa) {
                            $codigo = "";
                            if (strstr($row, $id_programa) !== false) {
                                $codigo = explode(">", trim($matches[1][$i + 1]));
                                $codigo = explode("<", trim($codigo[1]));
                                $codigo = trim($codigo[0]);
                                $disponivel = $this->proposta_model->check_valor_disp_beneficiario($data['cnpjProponente'], $codigo);
                                $valor = $this->getTextBetweenTags($matches[0][$i + 3], '<div class="nome">', "<\/div>");
                                array_push($data_filtered, "<tr><td><input type='hidden' value='" . $disponivel . "' name='disponivel_programa[]'> <input type='hidden' value='{$id_programa}' name='idRowSelectionAsArray[]'> <input type='hidden' value='" . $valor[0] . "' name='nome_programa[]'></td>" . $matches[0][$i + 1] . "<td>" . $valor[0] . "</td><td>0,00</td><td><a class='inc_valores' href='#'>Incluir Valores</a>&nbsp;|&nbsp;<a class='del_valores' href='#'>Excluir Seleção</a></td></tr>");
                                break;
                            }
                        }
                    }

                    $data['tabela'] = '';
                    foreach ($data_filtered as $string) {
                        if (count($data['tabela']) > 0) {
                            $data['tabela'] = $data['tabela'] . $string;
                        }
                    }

                    $data['tabela'] = str_replace('/siconv/IncluirProgramasProposta/SelecionarProgramasVerDados.do?', 'https://www.convenios.gov.br/siconv/ConsultarPrograma/ResultadoDaConsultaDeProgramaDeConvenioDetalhar.do?', $data['tabela']);
                } else {
                    $data['tabela'] = "Nenhum programa disponível.";
                }
            } else {
                $fake_programs = $this->trabalho_model->get_dummy_programs($cnpjProponente, $orgao);
                if ($fake_programs != null) {

                    $data_filtered = array('<tr><th></th><th>Código</th><th>Nome</th><th>Valor Global</th><th></th></tr>');
                    foreach ($fake_programs as $fake_program) {
                        $codigo = "";
                        $id_prog_siconv = explode('id=', $fake_program->link);
                        $id_prog_siconv = trim($id_prog_siconv[1]);
                        $codigo = $fake_program->codigo;
                        if (in_array($id_prog_siconv, $ids_programa)) {
                            $disponivel = $this->proposta_model->check_valor_disp_beneficiario($data['cnpjProponente'], $fake_program->codigo);
                            $valor = '0';
                            array_push($data_filtered, "<tr><td><input type='hidden' value='" . $disponivel . "' name='disponivel_programa[]'> <input type='hidden' value='{$id_prog_siconv}' name='idRowSelectionAsArray[]'> <input type='hidden' value='" . $fake_program->nome . "' name='nome_programa[]'></td><td><a href='https://www.convenios.gov.br/siconv/ConsultarPrograma/ResultadoDaConsultaDeProgramaDeConvenioDetalhar.do?id={$id_prog_siconv}' target='_blank' onmouseover=\"hints.show('verDados')\" onmouseout='hints.hide()'>" . $codigo . "</a></td><td>" . $fake_program->nome . "</td><td>0,00</td><td><a class='inc_valores' href='#'>Incluir Valores</a>&nbsp;|&nbsp;<a class='del_valores' href='#'>Excluir Seleção</a></td></tr>");
                        }
                    }

                    $data['tabela'] = '';
                    foreach ($data_filtered as $string) {
                        if (count($data['tabela']) > 0) {
                            $data['tabela'] = $data['tabela'] . $string;
                        }
                    }

                    $data['tabela'] = str_replace('/siconv/IncluirProgramasProposta/SelecionarProgramasVerDados.do?', 'https://www.convenios.gov.br/siconv/ConsultarPrograma/ResultadoDaConsultaDeProgramaDeConvenioDetalhar.do?', $data['tabela']);
                } else {
                    $data['tabela'] = "Nenhum programa disponível.";
                }
            }


            foreach ($ids_programa as $id_programa) {
                if ($this->input->get_post('offline', TRUE) == false && $force_offline == false) {
                    //página inicial para a inserção dos primeiros dados******************************************************
                    //$documento = utf8_decode($this->obter_pagina("https://www.convenios.gov.br/siconv/IncluirProgramasProposta/ConsultarProgramasEditar.do?idPrograma=" . $id_programa));
                    $documento = utf8_decode($this->autentica_siconv->new_obter_pagina("https://www.convenios.gov.br/siconv/IncluirProgramasProposta/ConsultarProgramasEditar.do?idPrograma=" . $id_programa, $this->login_siconv, $this->cookie_file_path));
                    $documento = $this->removeSpaceSurplus($documento);

                    $objetos_tabela = $this->getTextBetweenTags($documento, "<tr class=\"objetos\" id=\"tr-salvarObjetos\" >", "<\/tr>");

                    $objetos_tabela = $this->getTextBetweenTags($objetos_tabela[0], "<td class=\"field\">", "<\/td>");

                    $qualificacao_tabela = $this->getTextBetweenTags($documento, "<tr class=\"qualificacaoProponente\" id=\"tr-salvarQualificacaoProponente\" >", "<\/tr>");
                    if (count($qualificacao_tabela) > 0)
                        $qualificacao_tabela = $this->getTextBetweenTags($qualificacao_tabela[0], "<td class=\"field\">", "<\/td>");

                    $data['objetos_tabela'][$id_programa] = utf8_encode($objetos_tabela[0]);
                    $data['qualificacao_tabela'][$id_programa] = count($qualificacao_tabela) > 0 ? utf8_encode($qualificacao_tabela[0]) : (count($objetos_tabela) > 0 ? "<span style='color:red;'>Este programa não exige contrapartida mínima</span>" : "");
                } else {
                    $data['objetos_tabela'][$id_programa] = '';
                    $data['qualificacao_tabela'][$id_programa] = '';
                }
            }

            if ($this->input->get('id', TRUE) != false && !isset($_GET['padrao'])) {
                $data['valores_programa'] = $this->programa_proposta_model->get_programas_by_proposta($this->input->get('id', TRUE));
                $data['emendas_disponiveis'] = $this->emenda_programa_proposta_model->get_all_emendas_from_proposta($this->input->get('id', TRUE));
            } else if ($this->input->post('obj_programa', TRUE) != false) {
                $data['valores_programa'] = unserialize($this->input->post('obj_programa'));
            }

            $data['login'] = $this->login;
            $data['title'] = "SIHS - Selecionar Programas";

            //Emendas parlamentar
            if ($codigo != "" && $this->emenda_programa_proposta_model->get_emendas_disponiveis_from_programa_beneficiario($codigo, $data['cnpjProponente']) != null) {
                if ($this->input->get('id', TRUE) != false) {
                    $data['emendas_usadas'] = $this->emenda_programa_proposta_model->get_all_emendas_from_proposta($this->input->get('id', TRUE));
                } else if (isset($data['valores_programa']['valores_emendas'])) {
                    $data['emendas_usadas'] = $data['valores_programa']['valores_emendas'];
                }
                $data['emendas_disponiveis'] = $this->emenda_programa_proposta_model->get_emendas_disponiveis_from_programa_beneficiario($codigo, $data['cnpjProponente']);
                $data['codigo_programa'] = $codigo;
                $data['emenda_programa_proposta_model'] = $this->emenda_programa_proposta_model;
                $data['main'] = 'gestor/informa_valores_programa_emenda';
            } elseif ($codigo != "" && $this->programa_model->check_tem_beneficiario($data['cnpjProponente'], $codigo)) { //emenda proponente especifico
                //$data['valorDisponível'] = $this->proposta_model->check_valor_disp_beneficiario($data['cnpjProponente'], $codigo_primeiro_programa);
                $data['main'] = 'gestor/informa_valores_programa_proponente_especifico';
            } else {
                $data['main'] = 'gestor/informa_valores_programa';
            }

            if ($this->input->get_post('offline', TRUE) != false) {
                $data['offline'] = $this->input->get_post('offline', TRUE);
            }

            $this->load->view('in/template_projeto', $data);
        } else {
            $this->voltaPagina();
        }
    }

    function incluir_proposta() {
        $usuario = $this->usuario_logado->id_usuario;
        $this->load->model('proposta_model');
        $this->load->model('cidades_model');
        $this->load->model('trabalho_model');
        $this->load->model('programa_proposta_model');
        $this->load->model('emenda_programa_proposta_model');

        $pagina_proj_padrao = $this->input->get('padrao', TRUE) == 1 ? "gestor/inc_prop_padrao" : "";
        $array_emendas_programa = null;

        if ($this->input->get_post('edit', TRUE) !== false) {

            if ($this->input->post('id_programa_proposta', TRUE) != FALSE) {
                ################################
                $valores = $this->input->post();

                for ($i = 0; $i < count($valores['id_programa']); $i++) {
                    if (in_array($valores['id_programa'][$i], $valores['idRowSelectionAsArray'])) {
                        $percentual = str_replace(".", "", $valores['percentual'][$i]);
                        $percentual = str_replace(",", ".", $percentual);

                        $total_contrapartida = str_replace(".", "", $valores['valorContrapartida'][$i]);
                        $total_contrapartida = str_replace(",", ".", $total_contrapartida);

                        $valor_global = str_replace(".", "", $valores['valorGlobal'][$i]);
                        $valor_global = str_replace(",", ".", $valor_global);

                        $contrapartida_financeira = str_replace(".", "", $valores['valorContrapartidaFinanceira'][$i]);
                        $contrapartida_financeira = str_replace(",", ".", $contrapartida_financeira);

                        $contrapartida_bens = str_replace(".", "", $valores['valorContrapartidaBensServicos'][$i]);
                        $contrapartida_bens = str_replace(",", ".", $contrapartida_bens);

                        $repasse = str_replace(".", "", $valores['valorRepasse'][$i]);
                        $repasse = str_replace(",", ".", $repasse);

                        $repasse_especifico = "";
                        $repasse_voluntario = "";

                        if (array_key_exists('valorRepassevoluntario', $valores)) {
                            $repasse_voluntario = str_replace(".", "", $valores['valorRepassevoluntario'][$i]);
                            $repasse_voluntario = str_replace(",", ".", $repasse_voluntario);
                        } elseif (array_key_exists('valorRepasseespecifico', $valores)) {
                            $repasse_especifico = str_replace(".", "", $valores['valorRepasseespecifico'][$i]);
                            $repasse_especifico = str_replace(",", ".", $repasse_especifico);
                        }

                        $options_programa[$i] = array(
                            'nome_programa' => $valores['nome_programa_modal'][$i],
                            'codigo_programa' => $valores['codigo_programa'][$i],
                            'programa' => "https://www.convenios.gov.br/siconv/programa/DetalharPrograma/DetalharPrograma.do?id=" . $valores['id_programa'][$i],
                            'percentual' => ($contrapartida_financeira / $valor_global) * 100,
                            'valor_global' => $valor_global,
                            'total_contrapartida' => $total_contrapartida,
                            'contrapartida_financeira' => $contrapartida_financeira,
                            'contrapartida_bens' => $contrapartida_bens,
                            'repasse' => $repasse,
                            'repasse_voluntario' => $repasse_voluntario,
                            'repasse_especifico' => $repasse_especifico,
                            'id_programa' => $valores['id_programa'][$i],
                            'objeto' => $valores['objeto'][$i],
                            'qualificacao' => $valores['qualificacao'][$i],
                            'id_programa_proposta' => $valores['id_programa_proposta'][$i]
                        );
                    } else {
                        if ($this->input->get_post('id', TRUE) != FALSE) {
                            $this->db->where('id_proposta', $this->input->get_post('id', TRUE));
                            $this->db->where('id_programa', $valores['id_programa'][$i]);
                            $this->db->delete('programa_proposta');
                        }
                    }
                }

                $options_proposta = array(
                    'percentual' => (array_sum(array_map(function($var) {
                                        return $var['total_contrapartida'];
                                    }, $options_programa)) / array_sum(array_map(function($var) {
                                        return $var['valor_global'];
                                    }, $options_programa))) * 100,
                    'valor_global' => array_sum(array_map(function($var) {
                                        return $var['valor_global'];
                                    }, $options_programa)),
                    'total_contrapartida' => array_sum(array_map(function($var) {
                                        return $var['total_contrapartida'];
                                    }, $options_programa)),
                    'contrapartida_financeira' => array_sum(array_map(function($var) {
                                        return $var['contrapartida_financeira'];
                                    }, $options_programa)),
                    'contrapartida_bens' => array_sum(array_map(function($var) {
                                        return $var['contrapartida_bens'];
                                    }, $options_programa)),
                    'repasse' => array_sum(array_map(function($var) {
                                        return $var['repasse'];
                                    }, $options_programa)),
                    'repasse_voluntario' => array_sum(array_map(function($var) {
                                        return $var['repasse_voluntario'];
                                    }, $options_programa)),
                    'repasse_especifico' => array_sum(array_map(function($var) {
                                        return $var['repasse_especifico'];
                                    }, $options_programa)),
                    'orgao' => $valores['orgao']
                );

                $id = $this->input->get_post('id', TRUE);

                $this->programa_proposta_model->update_or_delete_programa($id, $options_programa);

                $inserido = $this->proposta_model->update_record($id, $options_proposta);
            }
            #################################

            if ($this->input->get_post('data', TRUE) !== false) {

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

                $repasse_especifico = "";
                $repasse_voluntario = "";

                if ($this->input->get_post('valorRepassevoluntario', TRUE) != FALSE) {
                    $repasse_voluntario = str_replace(".", "", $this->input->get_post('valorRepassevoluntario', TRUE));
                    $repasse_voluntario = str_replace(",", ".", $repasse_voluntario);
                } elseif ($this->input->get_post('valorRepasseespecifico', TRUE) != FALSE) {
                    $repasse_especifico = str_replace(".", "", $this->input->get_post('valorRepasseespecifico', TRUE));
                    $repasse_especifico = str_replace(",", ".", $repasse_especifico);
                }

                $cidade_cnpj = $this->cidades_model->obter_cidade_por_cnpj($this->input->get_post('cnpjProponente', TRUE));
                $data1 = array(
                    'nome' => $this->proposta_model->replace_chars($this->input->get_post('proposta', TRUE)),
                    'percentual' => $percentual,
                    'cidade' => $cidade_cnpj,
                    'valor_global' => $valor_global,
                    'total_contrapartida' => $total_contrapartida,
                    'contrapartida_financeira' => $contrapartida_financeira,
                    'contrapartida_bens' => $contrapartida_bens,
                    'repasse' => $repasse,
                    'repasse_voluntario' => $repasse_voluntario,
                    'repasse_especifico' => $repasse_especifico,
                    'agencia ' => $this->input->get_post('agencia', TRUE) . "-" . $this->input->get_post('digito', TRUE),
                    'data' => $data_programa,
                    'data_inicio' => $data_inicio,
                    'data_termino' => $data_termino,
                    'area' => $this->input->get_post('area', TRUE),
                    'banco' => $this->input->get_post('banco', TRUE)
                );

                if ($this->input->get_post('cnpjProponente', TRUE) != "" && $this->input->get_post('orgao', TRUE) != "") {
                    $data1 = array_merge($data1, array('proponente' => $this->input->get_post('cnpjProponente', TRUE),
                        'orgao' => $this->input->get_post('orgao', TRUE)
                            )
                    );
                }

                $id = $this->input->get_post('id', TRUE);

                $options_programa = unserialize($this->input->post('obj_programa', TRUE));
                $this->programa_proposta_model->update_or_delete_programa($id, $options_programa);

                $inserido = $this->proposta_model->update_record($id, $data1);

                $this->load->model('system_logs');
                $this->system_logs->add_log(EDT_DADOS_PROJETO . " - ID: " . $id . ", Proposta: " . substr($this->input->get_post('proposta', TRUE), 0, 150));
                //$this->trabalho_model->update_datas_meta_etapa($id, $data_inicio, $data_termino);

                if ($this->get_post_action('avancar') == 'avancar') {
                    $this->encaminha(base_url() . 'index.php/in/usuario/incluir_justificativa?id=' . $id . '&edita_gestor=1');
                }
            }

            $this->load->model('trabalho_model');
            $this->load->model('proposta_model');
            $id = $this->input->get_post('id', TRUE);
            if ($this->input->get_post('id', TRUE) !== false) {

                $tela1 = $this->trabalho_model->obter_saida_tela1_online($this->input->get_post('id', TRUE));
//                 $this->obter_paginaLogin_exporta($this->login_usuario, $this->senha, $this->input->get_post('id', TRUE));
//                 $pagina = "https://www.convenios.gov.br/siconv/IncluirProgramasProposta/EscolherProponenteEscolherProponente.do";
//                 $fields = array(
//                     'invalidatePageControlCounter' => '1',
//                     'cnpjProponente' => $tela1->proponente
//                 );
//                 $fields_string = null;
//                 foreach ($fields as $key => $value) {
//                     $fields_string .= $key . '=' . $value . '&';
//                 }
//                 rtrim($fields_string, '&');
//                 utf8_decode($this->obter_pagina("https://www.convenios.gov.br/siconv/proposta/IncluirDadosProposta/IncluirDadosProposta.do"));
//                 //echo $pagina."?".$fields_string;
//                 utf8_decode($this->obter_pagina_post($pagina, $fields, $fields_string));
                //consultar programas****************************************************
//                 $pagina = "https://www.convenios.gov.br/siconv/IncluirProgramasProposta/ConsultarProgramasConsultar.do";
//                 $fields = array(
//                     'orgao' => $tela1->orgao,
//                     'qualificacaoProponenteColAsArray' => '',
//                     'numeroEmendaParlamentar' => '',
//                     'anoPrograma' => '',
//                     'codigoPrograma' => '',
//                     'nomePrograma' => '',
//                     'descricaoPrograma' => '',
//                     'objetoPrograma' => '',
//                     'modalidade' => ''
//                 );
//                 $fields_string = null;
//                 foreach ($fields as $key => $value) {
//                     $fields_string .= $key . '=' . $value . '&';
//                 }
//                 rtrim($fields_string, '&');
//                 //echo $pagina."?".$fields_string;
//                 utf8_decode($this->obter_pagina_post($pagina, $fields, $fields_string));
//                 $pagina = "https://www.convenios.gov.br/siconv/IncluirProgramasProposta/SelecionarProgramasSelecionar.do";
//                 if($tela1->id_programa != "" && $this->input->get_post('edit', TRUE) !== false && $this->input->get_post('idRowSelectionAsArray', TRUE) == false) 
//                 	$id_busca_programa = $tela1->id_programa;
//                 else if($this->input->get_post('idRowSelectionAsArray', TRUE) != false)
//                 	$id_busca_programa = $this->input->get_post('idRowSelectionAsArray', TRUE);
//                 else
//                 	$id_busca_programa = $this->input->get_post('id_programa', TRUE);
//                 $fields = array(
//                     'idRowSelectionAsArray' => $id_busca_programa
//                 );
//                 $fields_string = null;
//                 foreach ($fields as $key => $value) {
//                     $fields_string .= $key . '=' . $value . '&';
//                 }
//                 rtrim($fields_string, '&');
//                 $documento = utf8_decode($this->obter_pagina_post($pagina, $fields, $fields_string));
//                 //echo $pagina."?".$fields_string;
//                 //echo $documento;
//                 $documento = $this->removeSpaceSurplus($documento);
//                 $txt1 = $this->getTextBetweenTags($documento, "<a href=\"javascript:document.location='", "';\" class=\"buttonLink\">Selecionar Objetos");
//                 //página inicial para a inserção dos primeiros dados******************************************************
//                 $documento = utf8_decode($this->obter_pagina("https://www.convenios.gov.br" . $txt1[0]));
//                 //echo $documento;
//                 $documento = $this->removeSpaceSurplus($documento);
//                 $objetos = $this->getTextBetweenTags($documento, "<input type=\"checkbox\" name=\"objetos\" value=\"", "\" onmouseover");
//                 $qualificacao = $this->getTextBetweenTags($documento, "name=\"qualificacaoProponente\" value=\"", "<br \/>");
//                 $objetos_tabela = $this->getTextBetweenTags($documento, "<tr class=\"objetos\" id=\"tr-salvarObjetos\" >", "<\/tr>");
//                 $objetos_tabela = $this->getTextBetweenTags($objetos_tabela[0], "<td class=\"field\">", "<\/td>");
//                 $qualificacao_tabela = $this->getTextBetweenTags($documento, "<tr class=\"qualificacaoProponente\" id=\"tr-salvarQualificacaoProponente\" >", "<\/tr>");
//                 if(count($qualificacao_tabela) > 0)
//                 	$qualificacao_tabela = $this->getTextBetweenTags($qualificacao_tabela[0], "<td class=\"field\">", "<\/td>");
//                 $quali_proponente = "";
//                 foreach ($qualificacao as $quali) {
//                     $aux1 = explode(" | ", $quali);
//                     /* echo intval(substr($aux1[0], -5, 4))." - ";
//                       echo intval($tela1->percentual)."<br>"; */
//                     if (intval(substr($aux1[0], -5, 4)) == intval($tela1->percentual)) {
//                         //echo $quali_proponente." quali ";
//                         $quali_proponente = strtok($quali, "\"");
//                         break;
//                     }
//                 }
//                 $pagina = "https://www.convenios.gov.br/siconv/ManterProgramaProposta/ValoresDoProgramaSalvar.do";
                ################################
                if ($this->input->post('id_programa', TRUE) != false) {
                    $valores = $this->input->post();

                    for ($i = 0; $i < count($valores['id_programa']); $i++) {
                        if (in_array($valores['id_programa'][$i], $valores['idRowSelectionAsArray'])) {
                            $percentual = str_replace(".", "", $valores['percentual'][$i]);
                            $percentual = str_replace(",", ".", $percentual);

                            $total_contrapartida = str_replace(".", "", $valores['valorContrapartida'][$i]);
                            $total_contrapartida = str_replace(",", ".", $total_contrapartida);

                            $valor_global = str_replace(".", "", $valores['valorGlobal'][$i]);
                            $valor_global = str_replace(",", ".", $valor_global);

                            $contrapartida_financeira = str_replace(".", "", $valores['valorContrapartidaFinanceira'][$i]);
                            $contrapartida_financeira = str_replace(",", ".", $contrapartida_financeira);

                            $contrapartida_bens = str_replace(".", "", $valores['valorContrapartidaBensServicos'][$i]);
                            $contrapartida_bens = str_replace(",", ".", $contrapartida_bens);

                            $repasse = str_replace(".", "", $valores['valorRepasse'][$i]);
                            $repasse = str_replace(",", ".", $repasse);

                            $repasse_especifico = "";
                            $repasse_voluntario = "";

                            if (array_key_exists('valorRepassevoluntario', $valores)) {
                                $repasse_voluntario = str_replace(".", "", $valores['valorRepassevoluntario'][$i]);
                                $repasse_voluntario = str_replace(",", ".", $repasse_voluntario);
                            } elseif (array_key_exists('valorRepasseespecifico', $valores)) {
                                $repasse_especifico = str_replace(".", "", $valores['valorRepasseespecifico'][$i]);
                                $repasse_especifico = str_replace(",", ".", $repasse_especifico);
                            }

                            if ($this->emenda_programa_proposta_model->get_emendas_disponiveis_from_programa_beneficiario($valores['codigo_programa'][$i], $this->input->post('cnpjProponente', TRUE)) != null) {
                                $array_emendas_programa = array();
                                $emenda_array = array();
                                foreach ($this->emenda_programa_proposta_model->get_emendas_disponiveis_from_programa_beneficiario($valores['codigo_programa'][$i], $this->input->post('cnpjProponente', TRUE)) as $emenda) {
                                    if (array_key_exists('valorEmenda' . $emenda->emenda, $valores)) {
                                        $emenda_array['numero_emenda'] = $emenda->emenda;
                                        if ($valores['valorEmenda' . $emenda->emenda][$i] != null) {
                                            $emenda_array['valor_utilizado'] = str_replace(".", "", $valores['valorEmenda' . $emenda->emenda][$i]);
                                            $emenda_array['valor_utilizado'] = str_replace(",", ".", $emenda_array['valor_utilizado']);
                                        } else {
                                            $emenda_array['valor_utilizado'] = doubleval(0);
                                        }
                                        $emenda_array['numero_programa'] = $valores['codigo_programa'][$i];

                                        $array_emendas_programa[] = $emenda_array;
                                    }
                                }
                            }

                            $options_programa[$i] = array(
                                'nome_programa' => $valores['nome_programa_modal'][$i],
                                'codigo_programa' => $valores['codigo_programa'][$i],
                                'programa' => "https://www.convenios.gov.br/siconv/programa/DetalharPrograma/DetalharPrograma.do?id=" . $valores['id_programa'][$i],
                                'percentual' => ($contrapartida_financeira / $valor_global) * 100,
                                'valor_global' => $valor_global,
                                'total_contrapartida' => $total_contrapartida,
                                'contrapartida_financeira' => $contrapartida_financeira,
                                'contrapartida_bens' => $contrapartida_bens,
                                'repasse' => $repasse,
                                'repasse_voluntario' => $repasse_voluntario,
                                'repasse_especifico' => $repasse_especifico,
                                'id_programa' => $valores['id_programa'][$i],
                                'objeto' => $valores['objeto'][$i],
                                'qualificacao' => $valores['qualificacao'][$i],
                                'id_programa_proposta' => $valores['id_programa_proposta'][$i]
                            );
                        }
                    }
                } else
                    $options_programa = $this->programa_proposta_model->get_programa_by_proposta_array($this->input->get('id', TRUE));

                $data['options_programa'] = serialize($options_programa);

                $options_proposta = array(
                    'percentual' => (array_sum(array_map(function($var) {
                                        return $var['total_contrapartida'];
                                    }, $options_programa)) / array_sum(array_map(function($var) {
                                        return $var['valor_global'];
                                    }, $options_programa))) * 100,
                    'valor_global' => array_sum(array_map(function($var) {
                                        return $var['valor_global'];
                                    }, $options_programa)),
                    'total_contrapartida' => array_sum(array_map(function($var) {
                                        return $var['total_contrapartida'];
                                    }, $options_programa)),
                    'contrapartida_financeira' => array_sum(array_map(function($var) {
                                        return $var['contrapartida_financeira'];
                                    }, $options_programa)),
                    'contrapartida_bens' => array_sum(array_map(function($var) {
                                        return $var['contrapartida_bens'];
                                    }, $options_programa)),
                    'repasse' => array_sum(array_map(function($var) {
                                        return $var['repasse'];
                                    }, $options_programa)),
                    'repasse_voluntario' => array_sum(array_map(function($var) {
                                        return $var['repasse_voluntario'];
                                    }, $options_programa)),
                    'repasse_especifico' => array_sum(array_map(function($var) {
                                        return $var['repasse_especifico'];
                                    }, $options_programa))
                );
                #################################
            }

            $proposta = $this->proposta_model->get_by_id($id);
            foreach ($options_proposta as $k => $v)
                $proposta->$k = $v;
            $data['cnpjProponente'] = ($this->input->get_post('cnpjProponente', TRUE) != false) ? $this->input->get_post('cnpjProponente', TRUE) : $proposta->proponente;
            $data['orgao'] = ($this->input->get_post('orgao', TRUE) != false) ? $this->input->get_post('orgao', TRUE) : $proposta->orgao;
//             $data['id_programa'] = ($this->input->get_post('idRowSelectionAsArray', TRUE) != false) ? $this->input->get_post('idRowSelectionAsArray', TRUE) : $proposta->id_programa;
//             $data['objetos_tabela'] = utf8_encode($objetos_tabela[0]);
//             $data['qualificacao_tabela'] = count($qualificacao_tabela) > 0 ? utf8_encode($qualificacao_tabela[0]) : (count($objetos_tabela) > 0 ? "<span style='color:red;'>Este programa não exige contra partida mínima</span>" : "");
            $data['senha_siconv'] = $this->senha;
            $data['usuario_siconv'] = $this->login_usuario;
            $data['areas'] = $this->trabalho_model->obter_areas();
            $data['login'] = $this->login;
            $data['proposta'] = $proposta;
//             $data['main'] = $pagina_proj_padrao != "" ? $pagina_proj_padrao : 'gestor/incluir_proposta';
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

            $repasse_especifico = "";
            $repasse_voluntario = "";

            if ($this->input->get_post('valorRepassevoluntario', TRUE) != FALSE) {
                $repasse_voluntario = str_replace(".", "", $this->input->get_post('valorRepassevoluntario', TRUE));
                $repasse_voluntario = str_replace(",", ".", $repasse_voluntario);
            } elseif ($this->input->get_post('valorRepasseespecifico', TRUE) != FALSE) {
                $repasse_especifico = str_replace(".", "", $this->input->get_post('valorRepasseespecifico', TRUE));
                $repasse_especifico = str_replace(",", ".", $repasse_especifico);
            }

//            if ($this->emenda_programa_proposta_model->get_emendas_disponiveis_from_programa_beneficiario($valores['codigo_programa'][$i], $cnpjProponente) != null) {
//                $array_emendas_programa = array();
//                $emenda_array = array();
//                foreach ($this->emenda_programa_proposta_model->get_emendas_disponiveis_from_programa_beneficiario($valores['codigo_programa'][$i], $cnpjProponente) as $emenda) {
//                    if (array_key_exists('valorEmenda' . $emenda->emenda, $valores)) {
//                        $emenda_array['numero_emenda'] = $emenda->emenda;
//                        $emenda_array['valor_utilizado'] = str_replace(".", "", $valores['valorEmenda' . $emenda->emenda][$i]);
//                        $emenda_array['valor_utilizado'] = str_replace(",", ".", $emenda_array['valor_utilizado']);
//                        $emenda_array['numero_programa'] = $valores['codigo_programa'][$i];
//
//                        $array_emendas_programa[] = $emenda_array;
//                    }
//                }
//            }

            $cidade_cnpj = $this->cidades_model->obter_cidade_por_cnpj($this->input->get_post('cnpjProponente', TRUE));

            $data1 = array(
                'cidade' => $cidade_cnpj,
                'nome' => $this->proposta_model->replace_chars($this->input->get_post('proposta', TRUE)),
                'percentual' => $percentual,
                'valor_global' => $valor_global,
                'total_contrapartida' => $total_contrapartida,
                'contrapartida_financeira' => $contrapartida_financeira,
                'contrapartida_bens' => $contrapartida_bens,
                'repasse' => $repasse,
                'repasse_voluntario' => $repasse_voluntario,
                'repasse_especifico' => $repasse_especifico,
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
                'senha_siconv' => base64_encode($this->senha)
            );

            $data['login'] = $this->login;
            $this->load->model('system_logs');
            if ($this->input->get_post('id', TRUE) !== false) {
                $id = $this->input->get_post('id', TRUE);
                $inserido = $this->proposta_model->update_record($id, $data1);

                $this->system_logs->add_log(EDT_DADOS_PROJETO . " - ID: " . $id . ", Projeto: " . substr($this->input->get_post('proposta', TRUE), 0, 150));
            } else {
                $inserido = $this->proposta_model->add_records($data1);

                $array_emendas_programa = array();
                $options_programa = unserialize($this->input->post('obj_programa', TRUE));
                if (isset($options_programa['valores_emendas'])) {
                    $array_emendas_programa = $options_programa['valores_emendas'];
                    unset($options_programa['valores_emendas']);
                }

                for ($i = 0; $i < count($options_programa); $i++)
                    $options_programa[$i]['id_proposta'] = $inserido;

                $this->programa_proposta_model->insere_programa($options_programa);

                //Get programa
                $programas = $this->programa_proposta_model->get_programas_by_proposta($inserido);
                foreach ($programas as $prog) {
                    foreach ($array_emendas_programa as $indice => $e) {
                        if ($e['numero_programa'] == $prog->codigo_programa) {
                            $e['id_programa_proposta'] = $prog->id_programa_proposta;
                            unset($e['numero_programa']);
                            $array_emendas_programa[$indice] = $e;
                        }
                    }
                }

                //Insert emendas por programa
                if (!empty($array_emendas_programa))
                    $this->emenda_programa_proposta_model->insert_emenda_programa_proposta($array_emendas_programa);

                $this->system_logs->add_log(INC_DADOS_PROJETO . " - ID: " . $inserido . ", Projeto: " . substr($this->input->get_post('proposta', TRUE), 0, 150));
            }

            if ($this->get_post_action('avancar') == 'avancar') {
                ?>
                <script>
                    window.location = 'atribui_permissoes_todos?pessoa=<?php echo $this->usuario_logado->id_usuario ?>&proposta=<?php echo $inserido ?>&avancar=1';
                </script>
                <?php
                exit();
            } else {
                ?>
                <script>
                    window.location = 'atribui_permissoes_todos?pessoa=<?php echo $this->usuario_logado->id_usuario ?>&proposta=<?php echo $inserido ?>';
                </script>
                <?php
                exit();
            }
        } else {
            if ($this->input->get_post('idRowSelectionAsArray', TRUE) === false && $this->input->get_post('padrao', TRUE) === false) {
                $this->alert("Escolha o programa");
                $this->voltaPagina();
            }

            $this->load->model('trabalho_model');
            $this->load->model('proposta_model');

            $cnpjProponente = $this->input->get_post('cnpjProponente', TRUE);
            $usuario_siconv = $this->login_usuario;
            $senha_siconv = $this->senha;
            $orgao = $this->input->get_post('orgao', TRUE);
//             $id_programa = $this->input->get_post('idRowSelectionAsArray', TRUE);
//             $this->obter_paginaLogin_exporta($usuario_siconv, $senha_siconv, "");
//             $pagina = "https://www.convenios.gov.br/siconv/IncluirProgramasProposta/EscolherProponenteEscolherProponente.do";
//             $fields = array(
//                 'invalidatePageControlCounter' => '1',
//                 'cnpjProponente' => $cnpjProponente
//             );
//             $fields_string = null;
//             foreach ($fields as $key => $value) {
//                 $fields_string .= $key . '=' . $value . '&';
//             }
//             rtrim($fields_string, '&');
//             utf8_decode($this->obter_pagina("https://www.convenios.gov.br/siconv/proposta/IncluirDadosProposta/IncluirDadosProposta.do"));
//             utf8_decode($this->obter_pagina_post($pagina, $fields, $fields_string));
            //consultar programas****************************************************
//             $pagina = "https://www.convenios.gov.br/siconv/IncluirProgramasProposta/ConsultarProgramasConsultar.do";
//             $fields = array(
//                 'orgao' => $orgao,
//                 'qualificacaoProponenteColAsArray' => '',
//                 'numeroEmendaParlamentar' => '',
//                 'anoPrograma' => '',
//                 'codigoPrograma' => '',
//                 'nomePrograma' => '',
//                 'descricaoPrograma' => '',
//                 'objetoPrograma' => '',
//                 'modalidade' => ''
//             );
//             $fields_string = null;
//             foreach ($fields as $key => $value) {
//                 $fields_string .= $key . '=' . $value . '&';
//             }
//             rtrim($fields_string, '&');
//             utf8_decode($this->obter_pagina_post($pagina, $fields, $fields_string));
            //escolhendo programas
//             if ($this->input->get_post('id_programa', TRUE) !== false) {
//                 $id_programa = $this->input->get_post('id_programa', TRUE);
//             }
//             $pagina = "https://www.convenios.gov.br/siconv/IncluirProgramasProposta/SelecionarProgramasSelecionar.do";
//             $fields = array(
//                 'idRowSelectionAsArray' => $id_programa
//             );
//             $fields_string = null;
//             foreach ($fields as $key => $value) {
//                 $fields_string .= $key . '=' . $value . '&';
//             }
//             rtrim($fields_string, '&');
//             $documento = utf8_decode($this->obter_pagina_post($pagina, $fields, $fields_string));
//             $documento = $this->removeSpaceSurplus($documento);
//             $txt1 = $this->getTextBetweenTags($documento, "<a href=\"javascript:document.location='", "';\" class=\"buttonLink\">Selecionar Objetos");
//             //página inicial para a inserção dos primeiros dados******************************************************
//             $documento = utf8_decode($this->obter_pagina("https://www.convenios.gov.br" . $txt1[0]));
//             $documento = $this->removeSpaceSurplus($documento);
//             $objetos_tabela = $this->getTextBetweenTags($documento, "<tr class=\"objetos\" id=\"tr-salvarObjetos\" >", "<\/tr>");
//             $objetos_tabela = $this->getTextBetweenTags($objetos_tabela[0], "<td class=\"field\">", "<\/td>");
//             $qualificacao_tabela = $this->getTextBetweenTags($documento, "<tr class=\"qualificacaoProponente\" id=\"tr-salvarQualificacaoProponente\" >", "<\/tr>");
//             if(count($qualificacao_tabela) > 0)
//             	$qualificacao_tabela = $this->getTextBetweenTags($qualificacao_tabela[0], "<td class=\"field\">", "<\/td>");
//             $data['objetos_tabela'] = utf8_encode($objetos_tabela[0]);
//             $data['qualificacao_tabela'] = count($qualificacao_tabela) > 0 ? utf8_encode($qualificacao_tabela[0]) : (count($objetos_tabela) > 0 ? "<span style='color:red;'>Este programa não exige contra partida mínima</span>" : "");

            if ($this->input->post('obj_programa', TRUE) == FALSE) {
                ################################
                $valores = $this->input->post();

                for ($i = 0; $i < count($valores['id_programa']); $i++) {
                    if (in_array($valores['id_programa'][$i], $valores['idRowSelectionAsArray'])) {
                        $percentual = str_replace(".", "", $valores['percentual'][$i]);
                        $percentual = str_replace(",", ".", $percentual);

                        $total_contrapartida = str_replace(".", "", $valores['valorContrapartida'][$i]);
                        $total_contrapartida = str_replace(",", ".", $total_contrapartida);

                        $valor_global = str_replace(".", "", $valores['valorGlobal'][$i]);
                        $valor_global = str_replace(",", ".", $valor_global);

                        $contrapartida_financeira = str_replace(".", "", $valores['valorContrapartidaFinanceira'][$i]);
                        $contrapartida_financeira = str_replace(",", ".", $contrapartida_financeira);

                        $contrapartida_bens = str_replace(".", "", $valores['valorContrapartidaBensServicos'][$i]);
                        $contrapartida_bens = str_replace(",", ".", $contrapartida_bens);

                        $repasse = str_replace(".", "", $valores['valorRepasse'][$i]);
                        $repasse = str_replace(",", ".", $repasse);

                        $repasse_especifico = "";
                        $repasse_voluntario = "";

                        if (array_key_exists('valorRepassevoluntario', $valores)) {
                            $repasse_voluntario = str_replace(".", "", $valores['valorRepassevoluntario'][$i]);
                            $repasse_voluntario = str_replace(",", ".", $repasse_voluntario);
                        } elseif (array_key_exists('valorRepasseespecifico', $valores)) {
                            $repasse_especifico = str_replace(".", "", $valores['valorRepasseespecifico'][$i]);
                            $repasse_especifico = str_replace(",", ".", $repasse_especifico);
                        }

                        $array_emendas_programa = array();
                        if ($this->emenda_programa_proposta_model->get_emendas_disponiveis_from_programa_beneficiario($valores['codigo_programa'][$i], $cnpjProponente) != null) {
                            $emenda_array = array();
                            foreach ($this->emenda_programa_proposta_model->get_emendas_disponiveis_from_programa_beneficiario($valores['codigo_programa'][$i], $cnpjProponente) as $emenda) {
                                if (array_key_exists('valorEmenda' . $emenda->emenda, $valores)) {
                                    $emenda_array['numero_emenda'] = $emenda->emenda;
                                    $emenda_array['valor_utilizado'] = str_replace(".", "", $valores['valorEmenda' . $emenda->emenda]);
                                    $emenda_array['valor_utilizado'] = str_replace(",", ".", $emenda_array['valor_utilizado']);
                                    $emenda_array['numero_programa'] = $valores['codigo_programa'][$i];

                                    $array_emendas_programa[] = $emenda_array;
                                }
                            }
                        }

                        $options_programa[$i] = array(
                            'nome_programa' => $valores['nome_programa_modal'][$i],
                            'codigo_programa' => $valores['codigo_programa'][$i],
                            'programa' => "https://www.convenios.gov.br/siconv/programa/DetalharPrograma/DetalharPrograma.do?id=" . $valores['id_programa'][$i],
                            'percentual' => ($contrapartida_financeira / $valor_global) * 100,
                            'valor_global' => $valor_global,
                            'total_contrapartida' => $total_contrapartida,
                            'contrapartida_financeira' => $contrapartida_financeira,
                            'contrapartida_bens' => $contrapartida_bens,
                            'repasse' => $repasse,
                            'repasse_voluntario' => $repasse_voluntario,
                            'repasse_especifico' => $repasse_especifico,
                            'id_programa' => $valores['id_programa'][$i],
                            'objeto' => $valores['objeto'][$i],
                            'qualificacao' => $valores['qualificacao'][$i]
                        );
                    }
                }
            } else
                $options_programa = unserialize($this->input->post('obj_programa', TRUE));

            if (array_key_exists('valores_emendas', $options_programa)) {
                $array_emendas_programa = array();
                $array_emendas_programa = $options_programa['valores_emendas'];
                unset($options_programa['valores_emendas']);
            }

            $options_proposta = array(
                'percentual' => (array_sum(array_map(function($var) {
                                    return $var['total_contrapartida'];
                                }, $options_programa)) / array_sum(array_map(function($var) {
                                    return $var['valor_global'];
                                }, $options_programa))) * 100,
                'valor_global' => array_sum(array_map(function($var) {
                                    return $var['valor_global'];
                                }, $options_programa)),
                'total_contrapartida' => array_sum(array_map(function($var) {
                                    return $var['total_contrapartida'];
                                }, $options_programa)),
                'contrapartida_financeira' => array_sum(array_map(function($var) {
                                    return $var['contrapartida_financeira'];
                                }, $options_programa)),
                'contrapartida_bens' => array_sum(array_map(function($var) {
                                    return $var['contrapartida_bens'];
                                }, $options_programa)),
                'repasse' => array_sum(array_map(function($var) {
                                    return $var['repasse'];
                                }, $options_programa)),
                'repasse_voluntario' => array_sum(array_map(function($var) {
                                    return $var['repasse_voluntario'];
                                }, $options_programa)),
                'repasse_especifico' => array_sum(array_map(function($var) {
                                    return $var['repasse_especifico'];
                                }, $options_programa)),
                'proponente' => $cnpjProponente,
                'orgao' => $orgao,
                'usuario_siconv' => $this->login_usuario,
                'senha_siconv' => base64_encode($this->senha),
                'data' => date('Y-m-d'),
                'idGestor' => $this->session->userdata('id_usuario')
            );

            if ($array_emendas_programa != null) {
                if (!empty($array_emendas_programa)) {
                    $options_programa['valores_emendas'] = $array_emendas_programa;
                }
            }
            #################################

            if (!isset($_GET['padrao']))
                $proposta = new proposta_model();
            else
                $proposta = $this->proposta_model->get_by_id($this->input->get_post('id', TRUE));
            foreach ($options_proposta as $k => $v)
                $proposta->$k = $v;

            $data['proposta'] = $proposta;
            $data['options_programa'] = serialize($options_programa);

            $data['cnpjProponente'] = $this->input->get_post('cnpjProponente', TRUE);
            $data['usuario_siconv'] = $this->login_usuario;
            $data['senha_siconv'] = $this->senha;
            $data['orgao'] = $this->input->get_post('orgao', TRUE);
            $data['areas'] = $this->trabalho_model->obter_areas();
            $data['login'] = $this->login;

            if ($this->get_post_action('padrao') == 'padrao') {

                # Valores necessários para atualizar o projeto
                $id = $this->input->get_post('id', TRUE);
                $cidade_cnpj = $this->cidades_model->obter_cidade_por_cnpj($this->input->get_post('cnpjProponente', TRUE));
                $codigo_cidade_endereco = $this->input->get_post('municipio_nome', TRUE);

                # Carregar o nome e código do programa
//                 $remotePageUrl = 'https://www.convenios.gov.br/siconv/IncluirProgramasProposta/ConsultarProgramasConsultar.do';
//                 $remotePageUrl1 = "https://www.convenios.gov.br/siconv/ForwardAction.do?modulo=Principal&path=/MostraPrincipalConsultarPrograma.do?Usr=guest&Pwd=guest";
//                 $this->obter_paginaLogin($this->login_usuario, $this->senha);
//                 $this->obter_pagina($remotePageUrl1);
//                 $this->obter_pagina($remotePageUrl);
//                 $documento = $this->obter_pagina("https://www.convenios.gov.br/siconv/programa/DetalharPrograma/DetalharPrograma.do?id=" . $this->input->get_post('id_programa', TRUE));
//                 $documento = $this->removeSpaceSurplus($documento);
//                 $txt1 = $this->getTextBetweenTags($documento, "Nome do Programa<\/td> <td class=\"field\">", "<\/td>");
//                 $txt2 = $this->getTextBetweenTags($documento, "Código do Programa<\/td> <td class=\"field\">", "<\/td>");
//                 $objetos_string = '';
//                 foreach ($this->input->get_post('objetos', TRUE) as $key => $value) {
//                     if ($key == 0) {
//                         $objetos_string .= $value;
//                     } else {
//                         $objetos_string .= ',' . $value;
//                     }
//                 }
                # Valores para inserir no projeto
                $valor_global = str_replace(".", "", $this->input->get_post('valorGlobal', TRUE));
                $valor_global = str_replace(",", ".", $valor_global);

                $dados_atualizar_programa = array(
                    'nome' => $this->proposta_model->replace_chars($this->input->get_post('proposta', TRUE)),
                    'valor_global' => $valor_global,
                    'agencia' => $this->input->get_post('agencia', TRUE) . "-" . $this->input->get_post('digito', TRUE),
                    'idGestor' => $this->usuario_logado->id_usuario,
                    'area' => $this->input->get_post('area', TRUE),
                    'banco' => $this->input->get_post('banco', TRUE),
                    'percentual' => $options_proposta['percentual'],
                    'data' => date('Y-m-d'),
                    'proponente' => $this->input->get_post('cnpjProponente', TRUE),
                    'orgao' => $this->input->get_post('orgao', TRUE)
                );

                # get endereco - verificar como salvar e associar o endereço para poder utilizar OBS: Corrigir form carregamento estados e cidades

                $dados_inserir_endereco = array(
                    'UF' => $this->input->get_post('municipio_uf', TRUE),
                    'municipio_sigla' => $this->input->get_post('municipio_nome', TRUE),
                    'municipio_nome' => $this->input->get_post('municipio_nome', TRUE),
                    'endereco' => $this->proposta_model->replace_chars($this->input->get_post('endereco', TRUE)),
                    'cep' => $this->input->get_post('cep', TRUE)
                );

                $options_programa = unserialize($this->input->post('obj_programa', TRUE));

                # função que copia a padrão transforma em um novo projeto e depois salva a mesma alterando valores, datas, justificativa, cidade, endereço, proponente e todos os dados necessários
                $id_proposta = $this->criar_projeto_from_padrao($id, $data['usuario_siconv'], $data['senha_siconv'], $data['cnpjProponente'], $cidade_cnpj, $codigo_cidade_endereco, $dados_atualizar_programa, $dados_inserir_endereco, $options_programa, $options_proposta);

                $this->load->model('system_logs');
                $this->system_logs->add_log(UTILZADO_PROJETO_PADRAO . " - ID Padrão: " . $id . ", ID Projeto: " . $id_proposta . ", Projeto: " . substr($this->input->get_post('proposta', TRUE), 0, 150));

                $this->alert("Proposta criada com sucesso !!");
                $this->session->set_userdata('pagAtual', 'visualiza_propostas');
                $this->encaminha(base_url() . 'index.php/in/usuario/visualiza_proposta?id=' . $id_proposta);
            }
        }

        if ($this->input->get_post('only_save', TRUE) == 1) {
            $this->alert("Valores salvos com sucesso !!");
            $this->session->set_userdata('pagAtual', 'visualiza_propostas');
            $this->encaminha(base_url() . 'index.php/in/gestor/visualiza_propostas');
        }

        $data['cidades'] = $this->proposta_model->obter_estados();
        $data['bancos'] = $this->proposta_model->obter_bancos();
        $data['title'] = 'SIHS - Dados do Projeto';
        $data['login'] = $this->login;
        $data['areas'] = $this->trabalho_model->obter_areas();
        $data['main'] = $pagina_proj_padrao != "" ? $pagina_proj_padrao : 'gestor/incluir_proposta';
        $this->load->vars($data);
        $this->load->view('in/template_projeto');
    }

    function criar_projeto_from_padrao($id_padrao, $usuario_siconv, $senha_siconv, $proponente, $cidade, $codigo_cidade_endereco, $dados_atualizar_programa, $dados_inserir_endereco, $options_programa, $options_proposta) {
        $this->load->model('trabalho_model');
        $this->load->model('proposta_model');
        $this->load->model('justificativa');
        $this->load->model('programa_model');

        # Copia a padrão em um novo projeto
        $id_proposta = $this->trabalho_model->copia_proposta_usuario($id_padrao, $this->usuario_logado->id_usuario, false, true, $options_proposta);

        $dados_inserir_endereco['Proposta_idProposta'] = $id_proposta;

        $array_emendas = array();
        if (array_key_exists('valores_emendas', $options_programa)) {
            $array_emendas = $options_programa['valores_emendas'];
            unset($options_programa['valores_emendas']);
        }

        for ($i = 0; $i < count($options_programa); $i++)
            $options_programa[$i]['id_proposta'] = $id_proposta;

        if (count($array_emendas) > 0) {
            $options_programa['valores_emendas'] = $array_emendas;
        }

        $this->programa_proposta_model->insere_programa($options_programa);

        # Copia a justificativa e altera as tags
        $idJustificativa = $this->trabalho_model->copia_justificativa($id_padrao, $id_proposta);
        $justificativa = $this->justificativa->get_by_id($idJustificativa);
        $texto = $justificativa->Justificativa;
        $texto = $this->subtitui_tags_justificativa($codigo_cidade_endereco, $texto);
        $this->justificativa->update_texto($idJustificativa, $texto);

        # Copiando as metas, etapas, reembolsos, bens e etc
        $metas = $this->trabalho_model->copia_metas_padrao($id_padrao, $id_proposta, $options_programa[0]['id_programa']);
        $etapas = $this->trabalho_model->copia_etapas($metas);
        $cronogramas = $this->trabalho_model->copia_cronogramas($id_padrao, $id_proposta);
        $cronogramas_meta = $this->trabalho_model->copia_cronogramas_meta($cronogramas, $metas);
        $cronogramas_etapa = $this->trabalho_model->copia_cronogramas_etapa($cronogramas_meta, $etapas);
        $despesas = $this->trabalho_model->copia_despesas_padrao($id_padrao, $id_proposta, $options_programa[0]['id_programa']);
        $despesas = $this->trabalho_model->copia_trabalhos($id_padrao, $id_proposta);

        # Ajustar datas
        $this->programa_model->updateDatasProjeto($id_proposta);

        # Ajustar valores
        $this->trabalho_model->updateValoresProjeto($id_proposta, $dados_atualizar_programa['valor_global'], $dados_atualizar_programa['percentual'], $options_proposta);

        #Insere novo endereco
        $this->trabalho_model->add_endereco($dados_inserir_endereco);

        # Atualizar outros dados projeto
        $this->proposta_model->update_projeto_from_padrao($id_proposta, $dados_atualizar_programa); //Criar metodo
        //
        # Removendo padrão e inserindo os dados restantes
        $this->proposta_model->remove_padrao($id_proposta, $usuario_siconv, $senha_siconv, $proponente, $cidade);

        return $id_proposta;
    }

    public function testa_num() {
        $this->load->model('programa_proposta_model');
        echo $this->programa_proposta_model->get_num_programas_by_proposta(105);
    }

    ##THOMAS: Transforma uma justificativa padrão com tags em justificativa normal apenas texto sem tags com os valores corretos nos lugares

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
            $texto = str_replace('[pib]', number_format($cidade_tag->pib, 2, ",", "."), $texto);
            $texto = str_replace('[renda]', number_format($cidade_tag->renda, 2, ",", "."), $texto);
            $texto = str_replace('[ano_estimativa]', $cidade_tag->ano_estimativa, $texto);

            return $texto;
        } else {
            return $texto;
        }
    }

    function atribui_permissoes_todos() {
        $this->load->model('trabalho_model');

        $inserido = $this->input->get_post('proposta', TRUE);
        $pessoa = $this->input->get_post('pessoa', TRUE);
        $data = array(
            'Status_idstatus' => 2,
            'Tipo_trabalho_idTrabalho' => 1,
            'Pessoa_idPessoa' => $pessoa,
            'id_correspondente' => $inserido
        );
        $inserido1 = $this->trabalho_model->add_records($data);

        $data_programa = implode("-", array_reverse(explode("/", $this->input->get_post('data', TRUE))));
        $data = array(
            'Status_idstatus' => 2,
            'Tipo_trabalho_idTrabalho' => 2,
            'Pessoa_idPessoa' => $pessoa,
            'id_correspondente' => $inserido
        );

        $inserido1 = $this->trabalho_model->add_records($data);

        $data_programa = implode("-", array_reverse(explode("/", $this->input->get_post('data', TRUE))));
        $data = array(
            'Status_idstatus' => 2,
            'Tipo_trabalho_idTrabalho' => 3,
            'Pessoa_idPessoa' => $pessoa,
            'id_correspondente' => $inserido
        );
        $inserido1 = $this->trabalho_model->add_records($data);

        $data_programa = implode("-", array_reverse(explode("/", $this->input->get_post('data', TRUE))));
        $data = array(
            'Status_idstatus' => 2,
            'Tipo_trabalho_idTrabalho' => 4,
            'Pessoa_idPessoa' => $pessoa,
            'id_correspondente' => $inserido
        );

        $inserido1 = $this->trabalho_model->add_records($data);

        if ($this->input->get_post('avancar', TRUE) !== false) {
            $this->encaminha(base_url() . 'index.php/in/usuario/incluir_justificativa?id=' . $this->input->get_post('proposta', TRUE) . '&edita_gestor=1');
        } else {
            $this->encaminha(base_url() . 'index.php/in/gestor/incluir_proposta?edit=1&id=' . $this->input->get_post('proposta', TRUE));
        }

        #$this->encaminha('visualiza_propostas?proposta=' . $inserido);
    }

    function gerencia_proposta() {
        //$usuario = $this->usuario_logado->id_usuario;
        $this->load->model('proposta_model');

        if ($this->input->get_post('delete', TRUE) !== false) {
            $id = $this->input->get_post('id', TRUE);
            $this->proposta_model->delete_record($id);

            $this->load->model('system_logs');
            if ($this->input->get_post('padrao', TRUE) == "1")
                $this->system_logs->add_log(DELETADO_PROJETO_PADRAO . " - ID: " . $id);
            else
                $this->system_logs->add_log(DELETADO_PROJETO . " - ID: " . $id);
        }

        //THOMAS: Removendo notificação de exclusão de enviadas. Depois verificar como fazer de uma maneira melhor.
        /* $enviadas = $this->proposta_model->get_all_enviadas($usuario);

          foreach ($enviadas as $key => $proposta_enviada) {
          //var_dump($proposta_enviada); die();
          $remotePageUrl1 = "https://www.convenios.gov.br/siconv/ForwardAction.do?modulo=Principal&path=/MostraPrincipalConsultarProposta.do";
          $this->obter_paginaLogin_status($this->login_usuario, $this->senha);
          $this->obter_pagina($remotePageUrl1);

          $pagina = "https://www.convenios.gov.br/siconv/ConsultarProposta/PreenchaOsDadosDaConsultaConsultar.do";
          $envio_siconv = "123";
          if (isset($proposta_enviada->id_siconv) !== false && $proposta_enviada->id_siconv != '')
          $envio_siconv = $proposta_enviada->id_siconv;
          $fields = array(
          'invalidatePageControlCounter' => '1',
          'numeroProposta' => $envio_siconv
          );
          $fields_string = null;
          foreach ($fields as $key => $value) {
          $fields_string .= $key . '=' . $value . '&';
          }
          rtrim($fields_string, '&');
          $documento = utf8_decode($this->obter_pagina_post($pagina, $fields, $fields_string));
          //echo $documento;
          $documento = $this->removeSpaceSurplus($documento);

          $txt1 = $this->getTextBetweenTags($documento, "numeroProposta\"> <a href=\"", "\">");
          if (count($txt1) > 0) {
          $documento = $this->obter_pagina("https://www.convenios.gov.br" . $txt1[0]);
          $documento = $this->removeSpaceSurplus($documento);
          $txt1 = $this->getTextBetweenTags($documento, "Situação<\/td> <td colspan=\"4\"> <table cellpadding=\"0\" cellspacing=\"0\">", "<\/table><\/td>");
          $proposta_enviada->situacao_siconv = $txt1[0];
          } else {
          $proposta_enviada->situacao_siconv = '';
          }
          } */

        /* $data['propostas'] = $this->proposta_model->get_all_abertas($usuario);
          $data['propostas_enviadas'] = $enviadas;
          $data['propostas_padrao'] = $this->proposta_model->get_all_padrao();

          $data['usuario_gestor'] = true;
          $data['main'] = 'gestor/gerencia_proposta';
          $data['title'] = 'SIHS - gerenciamento de propostas';
          $data['login'] = $this->login;
          $this->load->vars($data);
          $this->load->view('in/template'); */
        redirect('in/gestor/visualiza_propostas');
    }

    public function delete_proposta() {
        try {
            $this->load->model('proposta_model');
            if ($this->input->get_post('idProposta', TRUE) !== false) {
                $id = $this->input->get_post('idProposta', TRUE);

                $this->proposta_model->delete_proposta($id);

                $this->alert("Proposta apagada com sucesso !!");

                if ($this->input->get_post('origem', TRUE) !== false) {
                    $origem = $this->input->get_post('origem', TRUE);

                    if ($origem == 'vp') {
                        $this->encaminha(base_url('index.php/in/gestor/visualiza_propostas'));
                    } else {
                        $this->encaminha(base_url('index.php/in/gestor/visualiza_banco_propostas'));
                    }
                } else {
                    $this->voltaPagina();
                }
            }
        } catch (Exception $ex) {
            $this->alert("Falha ao apagar proposta !!");
        }
    }

    function gerencia_proposta_usuario() {

        $this->load->model('trabalho_model');
        $this->load->model('proposta_model');
        $data['log_trabalho'] = $this->trabalho_model->obter_log_trabalho($this->input->get_post('idTrabalho', TRUE));
        $data['trabalhos'] = $this->trabalho_model;
        $data['propostas'] = $this->proposta_model;

        $data['login'] = $this->login;
        $data['title'] = "SIHS - Gerencia propostas";
        $data['main'] = 'gestor/gerencia_proposta_usuario';

        $this->load->view('in/template', $data);
    }

    function altera_usuario() {
        $this->load->model('programa_model');
        $this->load->model('proposta_model');

        if ($this->input->get_post('login', TRUE) !== false && $this->input->get_post('senha', TRUE) !== false) {
            $this->obter_paginaLogin_usuario($this->input->get_post('login', TRUE), $this->input->get_post('senha', TRUE), $this->input->get_post('id', TRUE));
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
                        $this->alert("Verifique se o site do siconv se encontra no ar. Caso esteja, entre em contato com a SIHS Brasil.");
                        $this->voltaPagina();
                    }
                    $inserido = $this->proposta_model->update_record($this->input->get_post('id', TRUE), $data2);
                    $this->alert("Alterado para a cidade de: " . $nome);
                    $this->encaminha('gerencia_proposta');
                }
            }
            $data['orgaos'] = $this->programa_model->get_all_orgaos();

            $data['usuario_siconv'] = $this->input->get_post('login', TRUE);
            $data['senha_siconv'] = $this->encripta($this->input->get_post('senha', TRUE));
            $data['login'] = $this->login;
            $data['id'] = $this->input->get_post('id', TRUE);
            $data['title'] = "SIHS - Finaliza Trabalho";
            $data['main'] = 'gestor/escolher_proponente_edicao';

            $this->load->view('in/template', $data);
        } else {
            $data['title'] = 'SIHS - gerenciamento de usuários';
            $data['id'] = $this->input->get_post('id', TRUE);
            $data['main'] = 'gestor/login_usuario_edicao';
            $data['login'] = $this->login;
            $this->load->vars($data);
            $this->load->view('in/template');
        }
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
            $this->alert("Feito.");
            $this->encaminha('gerencia_proposta');
        } else {
            $data['meta'] = $this->trabalho_model->obter_endereco_por_id($this->input->get_post('id', TRUE));
            $data['cidades'] = $this->trabalho_model->obter_cidades_siconv();
            $data['ufs'] = $this->trabalho_model->obter_uf_siconv();
            $data['enderecos'] = $this->trabalho_model->obter_enderecos($this->usuario_logado->idPessoa);
            $data['title'] = 'SIHS - gerenciamento de usuários';
            $data['id'] = $this->input->get_post('id', TRUE);
            $data['main'] = 'gestor/endereco';
            $data['login'] = $this->login;
            $this->load->vars($data);
            $this->load->view('in/template');
        }
    }

    function selecionar_programas_edicao() {
        $this->load->model('trabalho_model');
        if ($this->input->get_post('cnpjProponente', TRUE) !== false && $this->input->get_post('orgao', TRUE) !== false) {
            if ($this->input->get_post('orgao', TRUE) === '') {
                $this->alert("Escolha o órgão");
                $this->voltaPagina();
            }
            $pagina = "https://www.convenios.gov.br/siconv/IncluirProgramasProposta/EscolherProponenteEscolherProponente.do";
            $this->obter_paginaLogin($this->input->get_post('usuario_siconv', TRUE), $this->desencripta($this->input->get_post('senha_siconv', TRUE)));
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
            $data['title'] = "SIHS - Finaliza Trabalho";
            $data['main'] = 'gestor/selecionar_programas_edicao';

            $this->load->view('in/template', $data);
        } else {
            $this->voltaPagina();
        }
    }

    function altera_cidade_usuario() {
        $this->load->model('proposta_model');
        $this->load->model('cidades_model');

        if ($this->input->get_post('id', TRUE) !== false) {
            if ($this->input->get_post('idRowSelectionAsArray', TRUE) === false) {
                $this->alert("Escolha o programa");
                $this->voltaPagina();
            }
            /* $cidade_cnpj = $this->cidades_model->obter_cidade_por_cnpj($this->input->get_post('cnpjProponente', TRUE));
              $data2 = array(
              'usuario_siconv'   => $this->input->get_post('usuario_siconv', TRUE),
              'senha_siconv'   => $this->input->get_post('senha_siconv', TRUE),
              'cidade'   => $cidade_cnpj,
              'proponente'   => $this->input->get_post('cnpjProponente', TRUE),
              'orgao'   => $this->input->get_post('orgao', TRUE),
              'id_programa'   => $this->input->get_post('idRowSelectionAsArray', TRUE)
              );
              $inserido = $this->proposta_model->update_record($this->input->get_post('id', TRUE), $data2);
              $this->alert("Programa alterado e cidade de: ".$cidade_cnpj);
              $this->encaminha('gerencia_proposta'); */

            $remotePageUrl = 'https://www.convenios.gov.br/siconv/IncluirProgramasProposta/ConsultarProgramasConsultar.do';
            $remotePageUrl1 = "https://www.convenios.gov.br/siconv/ForwardAction.do?modulo=Principal&path=/MostraPrincipalConsultarPrograma.do?Usr=guest&Pwd=guest";
            $this->obter_paginaLogin($this->input->get_post('usuario_siconv', TRUE), $this->desencripta($this->input->get_post('senha_siconv', TRUE)));
            $this->obter_pagina($remotePageUrl1);
            $this->obter_pagina($remotePageUrl);
            $documento = $this->obter_pagina("https://www.convenios.gov.br/siconv/programa/DetalharPrograma/DetalharPrograma.do?id=" . $this->input->get_post('idRowSelectionAsArray', TRUE));

            $documento = $this->removeSpaceSurplus($documento);

            $txt1 = $this->getTextBetweenTags($documento, "Nome do Programa<\/td> <td class=\"field\">", "<\/td>");
            $txt2 = $this->getTextBetweenTags($documento, "Código do Programa<\/td> <td class=\"field\">", "<\/td>");
            $data2 = array(
                'nome_programa' => trim($txt1[0]),
                'codigo_programa' => trim($txt2[0]),
                'programa' => "https://www.convenios.gov.br/siconv/programa/DetalharPrograma/DetalharPrograma.do?id=" . $this->input->get_post('idRowSelectionAsArray', TRUE),
                'usuario_siconv' => $this->input->get_post('usuario_siconv', TRUE),
                'senha_siconv' => $this->input->get_post('senha_siconv', TRUE),
                'orgao' => $this->input->get_post('orgao', TRUE),
                'id_programa' => $this->input->get_post('idRowSelectionAsArray', TRUE)
            );
            $inserido = $this->proposta_model->update_record($this->input->get_post('id', TRUE), $data2);
            $this->alert("Programa alterado");
            $this->encaminha('gerencia_proposta');
        } else {
            $this->alert("Sem projeto escolhido.");
            $this->voltaPagina();
        }
    }

    function duplica_trabalho() {
        $this->load->model('trabalho_model');
        if ($this->input->get_post('id', TRUE) !== false) {

            $id_proposta = $this->trabalho_model->copia_proposta($this->input->get_post('id', TRUE));
            $metas = $this->trabalho_model->copia_justificativa($this->input->get_post('id', TRUE), $id_proposta);
            $metas = $this->trabalho_model->copia_metas($this->input->get_post('id', TRUE), $id_proposta);
            $etapas = $this->trabalho_model->copia_etapas($metas);
            $cronogramas = $this->trabalho_model->copia_cronogramas($this->input->get_post('id', TRUE), $id_proposta);
            $cronogramas_meta = $this->trabalho_model->copia_cronogramas_meta($cronogramas, $metas);
            $cronogramas_etapa = $this->trabalho_model->copia_cronogramas_etapa($cronogramas_meta, $etapas);
            $despesas = $this->trabalho_model->copia_despesas($this->input->get_post('id', TRUE), $id_proposta);
            $despesas = $this->trabalho_model->copia_trabalhos($this->input->get_post('id', TRUE), $id_proposta);
            $this->alert("Projeto duplicado com sucesso!");
            $this->encaminha('gerencia_proposta');
        } else {
            $this->voltaPagina();
        }
    }

    function finaliza_trabalho() {
        $this->load->model('trabalho_model');
        if ($this->input->get_post('id', TRUE) !== false) {
            $this->alert('Lembre-se de anexar os arquivos: Capacidade Tec., Dec. Contrapartida e QDD');
            /* if ($this->trabalho_model->verifica_trabalho_finalizado($this->input->get_post('id', TRUE)) === false){
              $this->alert('Finalize os trabalhos antes de terminar.');
              $this->encaminha('visualiza_propostas');
              } */
            if ($this->trabalho_model->verifica_trabalhos($this->input->get_post('id', TRUE)) != true) {
                $this->voltaPagina();
            }
            $data['trabalho_model'] = $this->trabalho_model;
            $data['id'] = $this->input->get_post('id', TRUE);
            $data['tela1'] = $this->trabalho_model->obter_saida_tela1($this->input->get_post('id', TRUE));
            $data['tela2'] = $this->trabalho_model->obter_saida_tela2($this->input->get_post('id', TRUE));
            $data['tela3'] = $this->trabalho_model->obter_saida_tela3($this->input->get_post('id', TRUE));
            $data['tela4'] = $this->trabalho_model->obter_saida_tela4($this->input->get_post('id', TRUE));
            $data['tela5'] = $this->trabalho_model->obter_saida_tela5($this->input->get_post('id', TRUE));
            $data['tela6'] = $this->trabalho_model->obter_saida_tela6($this->input->get_post('id', TRUE));
            $data['login'] = $this->login;
            $data['title'] = "SIHS - Finaliza Trabalho";
            $data['main'] = 'gestor/finaliza_trabalho';

            $this->load->view('in/template', $data);
        } else {
            $this->voltaPagina();
        }
    }

    public function testando() {
        if (isset($_FILES)) {
            $this->obter_paginaLogin_exporta($this->login_usuario, $this->senha, $this->input->get_post('id', TRUE));

            echo utf8_decode($this->obter_pagina("https://www.convenios.gov.br/siconv/ConsultarProposta/ResultadoDaConsultaDePropostaDetalharProposta.do?idProposta=746535"));

            echo utf8_decode($this->obter_pagina("https://www.convenios.gov.br/siconv/EditarDadosProposta/DetalharPropostaAlterar.do"));

            $file = realpath($_FILES['userfile']['tmp_name']);
            //$file = file_get_contents($file);

            $fields = array(
                //     			'anoRepasse' => '',
                //     			'isContratoRepasse' => '',
                //'anexoContrapartida' => '@/home/physi971/public_html/homologa/configuracoes/teste.doc',
                //'idProposta'=>'746535',
                'anexoCapacidadeTecnica' => '@' . $file . ';filename=' . $_FILES['userfile']['name'] . ';type=' . $_FILES['userfile']['type'],
                    //'anexoContrapartida' => $texto,
                    //     			'data' => '',
                    //'anexoCapacidadeTecnica' => $texto,
                    //     			'agencia' => '0006',
                    //     			'remLen1' => '',
                    //     			'remLen2' => '5000',
                    //     			'justificativa' => utf8_decode("dasdaaas"),
                    //     			'nomeOrgao' => '',
                    //     			'concedenteCadastrador' => '',
                    //     			'valorRepasse' => '',
                    //     			'digito' => 'X',
                    //     			'banco' => '1000',
                    //     			'objetoConvenio' => '',
                    //     			'invalidatePageControlCounter' => '3',
                    //     			'terminoVigencia' => '',
                    //     			'idCEF' => '1002',
                    //     			'idProponente' => '',
                    //     			'inicioVigencia' => '01/12/2013',
                    //     			'terminoVigencia' => '12/12/2013',
                    //     			'orgao' => '',
                    //     			'idConvenio' => '',
                    //     			'capacidadeTecnica' => '',
                    //     			'areaAtuacaoONG' => '',
                    //     			'remLen' => 5000 - strlen("dasdaaas"),
                    //     			'mandatarioDefineAgenciaNaAberturaConta' => '',
                    //     			'modalidade' => ''
            );
            var_dump($fields);
            $fields_string = null;
            foreach ($fields as $key => $value) {
                $fields_string .= $key . '=' . $value . '&';
            }
            rtrim($fields_string, '&');
            //echo $pagina . '?' . $fields_string;

            echo utf8_decode($this->obter_pagina_post("https://www.convenios.gov.br/siconv/EditarDadosProposta/EditarPropostaAnexar.do", $fields, $fields_string));
            //     	echo utf8_decode($this->obter_pagina_post("https://www.convenios.gov.br/siconv/EditarDadosProposta/EditarPropostaSalvar.do", $fields, $fields_string));
        }

        echo '<form enctype="multipart/form-data" action="' . base_url('index.php/in/gestor/testando?id=53') . '" method="POST">
				    <!-- O Nome do elemento input determina o nome da array $_FILES -->
				    Enviar esse arquivo: <input name="userfile" type="file" />
				    <input type="submit" value="Enviar arquivo" />
				</form>';
    }

    function exporta_siconv() {
        #Adiconar update no campo data_envio para informar quando a proposta foi enviada para o siconv
        //echo $_SERVER['DOCUMENT_ROOT']; die();
        $this->load->model('trabalho_model');
        $this->load->model('proposta_model');
        $this->load->model('programa_proposta_model');
        $this->load->model('programa_model');
        $this->load->model('system_logs');
        $this->load->model('emenda_programa_proposta_model', 'eppm');

        if ($this->input->get_post('id', TRUE) !== false) {
            if ($this->trabalho_model->verifica_trabalhos($this->input->get_post('id', TRUE)) != true) {
                $this->voltaPagina();
                exit();
                die();
            }

            $retorno = $this->autentica_siconv->new_init_siconv_do_login(utf8_decode($this->login_usuario), utf8_decode($this->senha), $this->login_siconv, $this->cookie_file_path, false);
            if ($retorno != null) {
                $this->alert("Usuario ou senha do siconv invalidos !!");
                $this->encaminha(base_url('index.php/controle_usuarios/atualiza_usuario?id=' . $this->session->userdata('id_usuario')));
            }

            $this->system_logs->add_log(INICIA_EXPORTA_SICONV . " ID: " . $this->input->get_post('id', TRUE));

            $tela1 = $this->trabalho_model->obter_saida_tela1_online($this->input->get_post('id', TRUE));
            //$this->obter_paginaLogin_exporta($this->login_usuario, $this->senha, $this->input->get_post('id', TRUE));
//            $this->autentica_siconv->new_init_siconv_do_login($this->login_usuario, $this->senha, $this->login_siconv, $this->cookie_file_path);
            $pagina = "https://www.convenios.gov.br/siconv/IncluirProgramasProposta/EscolherProponenteEscolherProponente.do";
            $fields = array(
                'invalidatePageControlCounter' => '1',
                'cnpjProponente' => $tela1->proponente
            );
            $fields_string = null;
            foreach ($fields as $key => $value) {
                $fields_string .= $key . '=' . $value . '&';
            }

            //Seta o cnpj proponente da proposta para realizar a verificação se é ou não beneficiario especifico
            $cnpj_proponente = $tela1->proponente;

            rtrim($fields_string, '&');
            //echo utf8_decode($this->obter_pagina("https://www.convenios.gov.br/siconv/proposta/IncluirDadosProposta/IncluirDadosProposta.do"));
            echo utf8_decode($this->autentica_siconv->new_obter_pagina("https://www.convenios.gov.br/siconv/proposta/IncluirDadosProposta/IncluirDadosProposta.do", $this->login_siconv, $this->cookie_file_path));
            //utf8_decode($this->obter_pagina("https://www.convenios.gov.br/siconv/proposta/IncluirDadosProposta/IncluirDadosProposta.do"));
            echo $pagina . "?" . $fields_string;
            echo utf8_decode($this->obter_pagina_post($pagina, $fields, $fields_string));
            //utf8_decode($this->obter_pagina_post($pagina, $fields, $fields_string));
            //consultar programas****************************************************
            //escolhendo programas
            $programas = $this->programa_proposta_model->get_programas_by_proposta($this->input->get_post('id', TRUE));
            foreach ($programas as $programa) {
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
                echo $pagina . "?" . $fields_string;
                echo utf8_decode($this->obter_pagina_post($pagina, $fields, $fields_string));
                //utf8_decode($this->obter_pagina_post($pagina, $fields, $fields_string));
                //FOREACH PROGRAMAS/DADOS PROJETO COMEÇA AQUI
                $pagina = "https://www.convenios.gov.br/siconv/IncluirProgramasProposta/SelecionarProgramasSelecionar.do";
                $fields = array(
                    'idRowSelectionAsArray' => $programa->id_programa
                );
                $fields_string = null;
                foreach ($fields as $key => $value) {
                    $fields_string .= $key . '=' . $value . '&';
                }
                rtrim($fields_string, '&');
                $documento = utf8_decode($this->obter_pagina_post($pagina, $fields, $fields_string));
                echo $pagina . "?" . $fields_string;
                echo $documento;
            }

            $documento2 = $this->removeSpaceSurplus($documento);
            $txt1 = $this->getTextBetweenTags($documento2, '<div id="listaProgramas" class="table">', '<\/div><div id="tableFooter">');
            $botoes = $this->getTextBetweenTags($txt1[0], "<nobr>", "<\/nobr>");
            $i = 0;
            foreach ($programas as $programa) {
                //página inicial para a inserção dos primeiros dados******************************************************
                $txt1 = $this->getTextBetweenTags($botoes[$i], "<a href=\"javascript:document.location='", "';\" class=\"buttonLink\">Selecionar Objetos");
                $id_negativo = explode("id=", $txt1[0]);
                //$documento = utf8_decode($this->obter_pagina("https://www.convenios.gov.br/siconv/IncluirProgramasProposta/ConsultarProgramasEditar.do?idPrograma=" . $programa->id_programa . "&id=" . $id_negativo[1]));
                $documento = utf8_decode($this->autentica_siconv->new_obter_pagina("https://www.convenios.gov.br/siconv/IncluirProgramasProposta/ConsultarProgramasEditar.do?idPrograma=" . $programa->id_programa . "&id=" . $id_negativo[1], $this->login_siconv, $this->cookie_file_path));
                echo $documento;
                $documento = $this->removeSpaceSurplus($documento);

                if ($programa->objeto == '' || $programa->objeto == null) {
                    $objetos1 = $this->getTextBetweenTags($documento, "<input type=\"checkbox\" name=\"objetos\" value=\"", "\" onmouseover");
                    $objetos[0] = $objetos1[0];
                } else {
                    $objetos = explode(",", $programa->objeto);
                }
                $qualificacao = $this->getTextBetweenTags($documento, "name=\"qualificacaoProponente\" value=\"", "<br \/>");
                $repasse_tela = $this->getTextBetweenTags($documento, "name=\"valorRepasse", "\" value=");

                $quali_proponente = "";
                foreach ($qualificacao as $quali) {
                    $aux1 = explode(" | ", $quali);
                    if (intval(substr($aux1[0], -5, 4)) == intval($programa->percentual)) {
                        $quali_proponente = strtok($quali, "\"");
                        break;
                    }
                }

                if ($programa->qualificacao != '' && $programa->qualificacao != null)
                    $quali_proponente = $programa->qualificacao;

                $pagina = "https://www.convenios.gov.br/siconv/ManterProgramaProposta/ValoresDoProgramaSalvar.do";

                $integrantes_aux_span = explode("?", $txt1[0]);
                $integrantes_aux_span1 = explode("&", $integrantes_aux_span[1]);
                $programa_url = explode("=", $integrantes_aux_span1[0]);
                $id_url = explode("=", $integrantes_aux_span1[1]);

                $valor_global = number_format($programa->valor_global, 2, ",", ".");
                $total_contrapartida = number_format($programa->total_contrapartida, 2, ",", ".");
                $contrapartida_financeira = number_format($programa->contrapartida_financeira, 2, ",", ".");
                $contrapartida_bens = number_format($programa->contrapartida_bens, 2, ",", ".");
                $repasse = number_format($programa->repasse, 2, ",", ".");

                $repasse_voluntario = number_format($programa->repasse_voluntario, 2, ",", ".");
                $repasse_especifico = number_format($programa->repasse_especifico, 2, ",", ".");

                $objeto = explode(",", $programa->objeto);
                $fields = array(
                    'invalidatePageControlCounter' => ($i + 1),
                    'idPrograma' => $programa->id_programa,
                    'idPropostaPrograma' => '',
                    'valorMaximoRepasse' => '',
                    'id' => $id_negativo[1],
                    //'objetos' => trim($objeto[0]),
                    'qualificacaoProponente' => trim($quali_proponente),
                    'valorGlobal' => $valor_global,
                    'valorContrapartida' => $total_contrapartida,
                    'valorContrapartidaFinanceira' => $contrapartida_financeira,
                    'valorContrapartidaBensServicos' => $contrapartida_bens,
                    'valorRepasse' => $repasse
                );

                //Gustavo
                //Valida se é beneficiario especifico e seta o valor de repasse especifico, caso contrario vai setar o valor de repasse voluntario
                //Posteriormente deverá ser melhorada a validação checar se pode ser uma emenda parlamentar
                if ($this->programa_model->check_tem_beneficiario($cnpj_proponente, $programa->codigo_programa)) {
                    $fields['valorRepasseespecifico'] = $repasse_especifico;
                } else if ($this->eppm->get_all_emendas_from_proposta($this->input->get_post('id', TRUE)) != null) {
                    $emendas_proposta = $this->eppm->get_all_emendas_from_proposta($this->input->get_post('id', TRUE));
                    foreach ($emendas_proposta as $emenda) {
                        if ($emenda->id_programa_proposta == $programa->id_programa_proposta) {
                            if (doubleval($emenda->valor_utilizado) > doubleval(0)) {
                                $fields['valorRepasse' . $emenda->numero_emenda] = number_format($emenda->valor_utilizado, 2, ",", ".");
                            }
                        }
                    }
                } else {
                    $fields['valorRepassevoluntario'] = $repasse_voluntario;
                }

                //nome de campos é diferente e quantidade variavel
                $contador = count($repasse_tela) - 1;
                $valor_dividido = $programa->repasse / floatval($contador);
                $valor_dividido = number_format($valor_dividido, 2, ",", ".");
                foreach ($repasse_tela as $key => $rep) {
                    if ($key == 0)
                        continue;
                    $fields['valorRepasse' . $rep] = $valor_dividido;
                }
                $fields_string = null;
                foreach ($fields as $key => $value) {
                    $fields_string .= $key . '=' . $value . '&';
                }
                foreach ($objetos as $key => $value) {
                    $fields_string .= 'objetos=' . $value . '&';
                }//varios objetivo

                rtrim($fields_string, '&');
                echo $pagina . "?" . $fields_string;
                echo utf8_decode($this->obter_pagina_post($pagina, $fields, $fields_string));
                //utf8_decode($this->obter_pagina_post($pagina, $fields, $fields_string));

                $i+=2;
            }
            //FOREACH PROGRAMAS/DADOS PROJETO TERMINA AQUI
            //NESSE PONTO ANTERIOR É NECESSRIO BUSCAR O ID DOS PROGRAMAS CADASTRADOS PARA ATUALIZAR NO MOMENTODO ENVIO DAS METAS E PLANO DETALHADO
            //página de inserção dos dados de justificativa**************************************************
            $fields = array(
                'orgao' => '',
                'qualificacaoProponenteColAsArray' => '',
                'numeroEmendaParlamentar' => '',
                'anoPrograma' => '',
                'codigoPrograma' => '',
                'nomePrograma' => '',
                'objetoPrograma' => '',
                'modalidade' => ''
            );

            $fields_string = null;
            foreach ($fields as $key => $value) {
                $fields_string .= $key . '=' . $value . '&';
            }
            rtrim($fields_string, '&');
            $documento = utf8_decode($this->obter_pagina_post("https://www.convenios.gov.br/siconv/IncluirProgramasProposta/ConsultarProgramasConcluirSelecao.do", $fields, $fields_string));
            echo "https://www.convenios.gov.br/siconv/IncluirProgramasProposta/ConsultarProgramasConcluirSelecao.do" . "?" . $fields_string;
            echo $documento;

            $tela2 = $this->trabalho_model->obter_saida_tela2_online($this->input->get_post('id', TRUE));

            $documento = $this->removeSpaceSurplus($documento);
            $modalidade = $this->getTextBetweenTags($documento, "id=\"cadastrarPropostaModalidade\"><option value=\"", "\"");
            $ehContrato = 'false';
            $mandatarioDefine = 'false';
            if ($modalidade[0] == 2)
                $ehContrato = 'true';
            $banco = $tela2['banco'];
            $agencia = strtok($tela2['agencia'], '-');
            $digito = strtok('-');
            if (strstr($documento, '#true">') !== false) {
                $banco_aux = $this->getTextBetweenTags($documento, "id=\"cadastrarPropostaBanco\"><option value=\"", "\"");
                $banco = $banco_aux[0];
                $mandatarioDefine = 'true';
                $agencia = '';
                $digito = '';
            }
            //$pagina = "https://www.convenios.gov.br/siconv/IncluirDadosProposta/IncluirPropostaCadastrarProposta.do";
            $pagina = "https://www.convenios.gov.br/siconv/IncluirDadosProposta/IncluirPropostaCadastrarProposta.do";
            //$data = file_get_contents('/home/physi971/public_html/homologa/configuracoes/teste.doc', true);
            $fields = array(
                'anoRepasse' => '',
                'isContratoRepasse' => $ehContrato,
                //'anexoContrapartida' => '@/home/physi971/public_html/homologa/configuracoes/teste.doc', 
                //'anexoCapacidadeTecnica' => '@/home/physi971/public_html/homologa/configuracoes/teste.doc;application/msword', 
                //'anexoContrapartida' => $texto,
                'data' => date("d/m/Y"),
                //'anexoCapacidadeTecnica' => $texto,
                'agencia' => $agencia,
                'remLen1' => 5000 - strlen($tela2['objeto']),
                'remLen2' => '5000',
                'justificativa' => rawurlencode(utf8_decode($tela2['Justificativa'])),
                'nomeOrgao' => '',
                'concedenteCadastrador' => '',
                'valorRepasse' => '',
                'digito' => $digito,
                'banco' => $banco,
                'objetoConvenio' => rawurlencode(utf8_decode($tela2['objeto'])),
                'invalidatePageControlCounter' => '3',
                'terminoVigencia' => $tela2['data_termino'],
                'idCEF' => '1002',
                'idProponente' => '',
                'inicioVigencia' => $tela2['data_inicio'],
                'orgao' => '',
                'idConvenio' => '',
                'capacidadeTecnica' => '',
                'areaAtuacaoONG' => '',
                'remLen' => 5000 - strlen($tela2['Justificativa']),
                'mandatarioDefineAgenciaNaAberturaConta' => $mandatarioDefine,
                'modalidade' => $modalidade[0]
            );

            $fields_string = null;
            foreach ($fields as $key => $value) {
                $fields_string .= $key . '=' . $value . '&';
            }
            $fields_string = rtrim($fields_string, '&');
            echo $pagina . '?' . $fields_string;
            //echo $this->obter_pagina("https://www.convenios.gov.br/siconv/IncluirDadosProposta/IncluirPropostaCadastrarProposta.do?valorRepasse=20&anoRepasse=2013&isContratoRepasse=false&anexoContrapartida=&data=19/08/2013&anexoCapacidadeTecnica=&agencia=0006&remLen1=5000&remLen2=5000&justificativa=fds&nomeOrgao=&concedenteCadastrador=&digito=x&banco=1000&objetoConvenio=fds&invalidatePageControlCounter=1&terminoVigencia=12/12/2014&idCEF=1002&idProponente=&inicioVigencia=01/12/2013&orgao=&idConvenio=&capacidadeTecnica=&areaAtuacaoONG=&remLen=5000&mandatarioDefineAgenciaNaAberturaConta=false&modalidade=1");
            //echo $this->obter_pagina("https://www.convenios.gov.br/siconv/IncluirDadosProposta/IncluirPropostaCadastrarProposta.do?anoRepasse=2013&isContratoRepasse=false&anexoContrapartida=&modalidade=1&digito=X&banco=1000&agencia=0006&idCEF=1002&isContratoRepasse=false");
            //echo utf8_decode($this->obter_pagina("https://www.convenios.gov.br/siconv/IncluirDadosProposta/IncluirPropostaAdicionarRepasse.do?valorRepasse=" . $tela2['repasse'] . "&anoRepasse=" . $tela2['anoRepasse']));
            echo utf8_decode($this->autentica_siconv->new_obter_pagina("https://www.convenios.gov.br/siconv/IncluirDadosProposta/IncluirPropostaAdicionarRepasse.do?valorRepasse=" . $tela2['repasse'] . "&anoRepasse=" . $tela2['anoRepasse'], $this->login_siconv, $this->cookie_file_path));
            //utf8_decode($this->obter_pagina("https://www.convenios.gov.br/siconv/IncluirDadosProposta/IncluirPropostaAdicionarRepasse.do?valorRepasse=" . $tela2['repasse'] . "&anoRepasse=" . $tela2['anoRepasse']));
            $documento = utf8_decode($this->obter_pagina_post($pagina, $fields, $fields_string));
            echo $documento;

            $documento = $this->removeSpaceSurplus($documento);
            $objetos = $this->getTextBetweenTags($documento, "div id=\"convenio\"> <img src=\"\/siconv\/layout\/default\/imagens\/seta_branco.gif\" \/>Proposta ", "<\/div>");
            $id_proposta_atual = $this->getTextBetweenTags($documento, '<input type="hidden" id="idPropostaAtualHidden" value="', '"');
            $id_proposta_efetiva = $this->getTextBetweenTags($documento, '<input type="hidden" id="idPropostaEfetivaHidden" value="', '"');

            echo trim($objetos[0]);
            $data1 = array(
                'enviado' => true,
                'id_siconv' => trim($objetos[0]),
                'id_proposta_atual' => $id_proposta_atual[0],
                'id_proposta_efetiva' => $id_proposta_efetiva[0]
            );
            $inserido = $this->proposta_model->update_proposta($this->input->get_post('id', TRUE), $data1);


            //METAS E ETAPAS COMEÇA AQUI
            //metas e etapas******************************************************************
            //echo utf8_decode($this->obter_pagina("https://www.convenios.gov.br/siconv/proposta/SelecionarObjeto/SelecionarObjeto.do?destino=DetalharCronoFisico"));
            echo utf8_decode($this->autentica_siconv->new_obter_pagina("https://www.convenios.gov.br/siconv/proposta/SelecionarObjeto/SelecionarObjeto.do?destino=DetalharCronoFisico", $this->login_siconv, $this->cookie_file_path));
            //utf8_decode($this->obter_pagina("https://www.convenios.gov.br/siconv/proposta/SelecionarObjeto/SelecionarObjeto.do?destino=DetalharCronoFisico"));

            $tela3 = $this->trabalho_model->obter_saida_tela3_online($this->input->get_post('id', TRUE));
            $idMeta = null;
            $idProposta = null;
            $idValidade = null;
            foreach ($tela3 as $keyx => $meta) {

                //$documento = utf8_decode($this->obter_pagina("https://www.convenios.gov.br/siconv/DetalharCronoFisico/DetalheCronogramaFisicoIncluirMeta.do"));
                $documento = utf8_decode($this->autentica_siconv->new_obter_pagina("https://www.convenios.gov.br/siconv/DetalharCronoFisico/DetalheCronogramaFisicoIncluirMeta.do", $this->login_siconv, $this->cookie_file_path));
                echo $documento;
                $documento = $this->removeSpaceSurplus($documento);
                //AQUI TROCAR PELO ID DO PROGRAMA CAPTURADO NO PONTO ANTERIOR
                //$nome_programa = substr($this->programa_proposta_model->get_nome_programa($this->input->get_post('id', TRUE), $meta['id_programa'])->nome_programa, 0, 54);
                //$objetos = $this->getTextBetweenTags($documento, "id=\"incluirPrograma\"><option value=\"", "\">{$nome_programa}");

                $id_programa = $meta['id_programa'];
                $nome_programa = $this->programa_proposta_model->get_nome_programa($this->input->get_post('id', TRUE), $id_programa)->nome_programa;
                $nome_programa = utf8_decode($nome_programa);
                $nome_programa = substr($nome_programa, 0, 54);

                $objetos = $this->getTextBetweenTags($documento, "<select name=\"programa\"", "<\/select>");
                $objetos = explode('>' . $nome_programa, $objetos[0]);
                $objetos = explode('value="', $objetos[0]);
                $objetos = explode('"', $objetos[count($objetos) - 1]);
                var_dump($objetos[0]);

                $objetos1 = $this->getTextBetweenTags($documento, "name=\"idProposta\" value=\"", "\"");
                $objetos2 = $this->getTextBetweenTags($documento, "name=\"ehMetaAplicacao\" value=\"", "\"");
                $objetos3 = $this->getTextBetweenTags($documento, "name=\"invalidatePageControlCounter\" value=\"", "\"");
                $pagina = "https://www.convenios.gov.br/siconv/CadastrarMetaCronoFisico/MetaIncluir.do";

                $total = $meta['total'];
                $quantidade = $meta['quantidade'];
                $valorUnitario = $meta['valorUnitario'];
                $data_inicio = implode("/", array_reverse(explode("-", $meta['data_inicio'])));
                $data_termino = implode("/", array_reverse(explode("-", $meta['data_termino'])));
                $uf = $meta['UF'];
                if ($uf == 0)
                    $uf = null;
                $fields = array(
                    'invalidatePageControlCounter' => $objetos3[0],
                    'idPrograma' => $objetos1[0],
                    'ehMetaAplicacao' => $objetos2[0],
                    'idMeta' => '',
                    'programa' => $objetos[0],
                    'especificacao' => utf8_decode($meta['especificacao']),
                    'remLen' => 5000 - strlen($meta['especificacao']),
                    'codUnidadeFornecimento' => utf8_decode($meta['fornecimento']),
                    'descricaoUnidadeFornecimento' => '',
                    'valor' => $total,
                    'quantidade' => $quantidade,
                    'valorUnitario' => $valorUnitario,
                    'dataInicio' => $data_inicio,
                    'dataFim' => $data_termino,
                    'uf' => $uf,
                    'codigoMunicipio' => utf8_decode(str_pad((int) $meta['municipio_sigla'], 4, "0", STR_PAD_LEFT)),
                    'nomeMunicipio' => utf8_decode($meta['municipio_nome']),
                    'endereco' => utf8_decode($meta['endereco']),
                    'cep' => $meta['cep']
                );

                $fields_string = null;
                foreach ($fields as $key => $value) {
                    $fields_string .= $key . '=' . $value . '&';
                }
                rtrim($fields_string, '&');
                $documento = utf8_decode($this->obter_pagina_post($pagina, $fields, $fields_string));
                echo $documento;

                $this->proposta_model->atualiza_etapas_proposta('meta', array('exportado_siconv' => true), 'idMeta', $meta[0]);

                $documento = $this->removeSpaceSurplus($documento);
                $txt1 = $this->getTextBetweenTags($documento, "<nobr><a href=\"javascript:document.location='", "';\" class=\"buttonLink\">");

                //inserir etapa da meta *****************************************************
                //$documento_etapa = utf8_decode($this->obter_pagina("https://www.convenios.gov.br" . $txt1[$keyx]));
                $documento_etapa = utf8_decode($this->autentica_siconv->new_obter_pagina("https://www.convenios.gov.br" . $txt1[$keyx], $this->login_siconv, $this->cookie_file_path));
                echo $documento_etapa;

                $documento_etapa = $this->removeSpaceSurplus($documento_etapa);
                $objetos1_etapa = $this->getTextBetweenTags($documento_etapa, "name=\"idProposta\" value=\"", "\"");
                $objetos2_etapa = $this->getTextBetweenTags($documento_etapa, "name=\"idMeta\" value=\"", "\"");

                $tela3_etapas = $this->trabalho_model->obter_saida_tela3_etapas_online($meta[0]);

                foreach ($tela3_etapas as $key1 => $etapa) {
                    $documento_etapa = $this->removeSpaceSurplus($documento_etapa);
                    $objetos1_valido = $this->getTextBetweenTags($documento_etapa, "name=\"invalidatePageControlCounter\" value=\"", "\"");
                    $idMeta = $objetos2_etapa[0];
                    $idProposta = $objetos1_etapa[0];
                    $idValidade = $objetos1_valido[0];
                    $pagina = "https://www.convenios.gov.br/siconv/IncluirEtapaMeta/IncluirEtapaMetaIncluirEtapa.do";

                    $total_etapa = $etapa['total'];
                    $quantidade_etapa = $etapa['quantidade'];
                    $valorUnitario_etapa = $etapa['valorUnitario'];
                    $data_inicio_etapa = implode("/", array_reverse(explode("-", $etapa['data_inicio'])));
                    $data_termino_etapa = implode("/", array_reverse(explode("-", $etapa['data_termino'])));
                    $uf = $etapa['UF'];
                    if ($uf == 0)
                        $uf = null;
                    $fields = array(
                        'invalidatePageControlCounter' => $objetos1_valido[0],
                        'idPrograma' => $objetos1_etapa[0],
                        'idMeta' => $objetos2_etapa[0],
                        'especificacao' => utf8_decode($etapa['especificacao']),
                        'remLen' => 5000 - strlen($etapa['especificacao']),
                        'codUnidadeFornecimento' => utf8_decode($etapa['fornecimento']),
                        'descricaoUnidadeFornecimento' => '',
                        'valor' => $total_etapa,
                        'quantidade' => $quantidade_etapa,
                        'valorUnitario' => $valorUnitario_etapa,
                        'dataInicio' => $data_inicio_etapa,
                        'dataFim' => $data_termino_etapa,
                        'uf' => $uf,
                        'codigoMunicipio' => utf8_decode(str_pad((int) $etapa['municipio_sigla'], 4, "0", STR_PAD_LEFT)),
                        'nomeMunicipio' => utf8_decode($etapa['municipio_nome']),
                        'endereco' => utf8_decode($etapa['endereco']),
                        'cep' => $etapa['cep']
                    );

                    $fields_string = null;
                    foreach ($fields as $key => $value) {
                        $fields_string .= $key . '=' . $value . '&';
                    }
                    rtrim($fields_string, '&');
                    $documento_etapa = utf8_decode($this->obter_pagina_post($pagina, $fields, $fields_string));
                    echo $documento_etapa;

                    $this->proposta_model->atualiza_etapas_proposta('etapa', array('exportado_siconv' => true), 'idEtapa', $etapa[0]);
                }
            }
            //METAS E ETAPAS TERMINA AQUI
            $pagina_fim = "https://www.convenios.gov.br/siconv/DetalharMetaCronoFisico/DetalharEtapasVerMetas.do";

            $fields = array(
                'invalidatePageControlCounter' => $idValidade,
                'idMeta' => $idMeta,
                'idProposta' => $idProposta,
                'mudarLabelBotao' => 'false'
            );

            $fields_string = null;
            foreach ($fields as $key => $value) {
                $fields_string .= $key . '=' . $value . '&';
            }
            rtrim($fields_string, '&');

            $documento = utf8_decode($this->obter_pagina_post($pagina_fim, $fields, $fields_string));
            echo $documento;

            //cronograma de desembolso ********************************************************
            $tela4 = $this->trabalho_model->obter_saida_tela4_online($this->input->get_post('id', TRUE));

            foreach ($tela4 as $keyx => $crono) {
                //echo utf8_decode($this->obter_pagina("https://www.convenios.gov.br/siconv/proposta/SelecionarObjeto/SelecionarObjeto.do?destino=DetalharCronoDesembolso"));
                echo utf8_decode($this->autentica_siconv->new_obter_pagina("https://www.convenios.gov.br/siconv/proposta/SelecionarObjeto/SelecionarObjeto.do?destino=DetalharCronoDesembolso", $this->login_siconv, $this->cookie_file_path));
                //utf8_decode($this->obter_pagina("https://www.convenios.gov.br/siconv/proposta/SelecionarObjeto/SelecionarObjeto.do?destino=DetalharCronoDesembolso"));
                //$documento = utf8_decode($this->obter_pagina("https://www.convenios.gov.br/siconv/DetalharCronoDesembolso/CronogramaDesembolsoIncluirParcelaCronogramaDesembolso.do"));
                $documento = utf8_decode($this->autentica_siconv->new_obter_pagina("https://www.convenios.gov.br/siconv/DetalharCronoDesembolso/CronogramaDesembolsoIncluirParcelaCronogramaDesembolso.do", $this->login_siconv, $this->cookie_file_path));
                echo $documento;
                $documento = $this->removeSpaceSurplus($documento);
                $objetos1_invalido = $this->getTextBetweenTags($documento, "name=\"invalidatePageControlCounter\" value=\"", "\"");
                $pagina = "https://www.convenios.gov.br/siconv/IncluirParcelaCronoDesembolso/IncluirParcelaCronogramaDesembolsoIncluirParcela.do";
                $responsavel = 1;
                if ($crono['responsavel'] == 'CONVENENTE')
                    $responsavel = 2;
                $fields = array(
                    'invalidatePageControlCounter' => $objetos1_invalido[0],
                    'idProposta' => $idProposta,
                    'responsavel' => $responsavel,
                    'mes' => $crono['mes'],
                    'ano' => $crono['ano'],
                    'valorParcela' => $crono['parcela']
                );
                $fields_string = null;
                foreach ($fields as $key => $value) {
                    $fields_string .= $key . '=' . $value . '&';
                }
                rtrim($fields_string, '&');

                $documento = utf8_decode($this->obter_pagina_post($pagina, $fields, $fields_string));
                echo $documento;

                $this->proposta_model->atualiza_etapas_proposta('cronograma', array('exportado_siconv' => true), 'idCronograma', $crono[0]);

                $tela5 = $this->trabalho_model->obter_saida_tela5_online($crono['idCronograma']);
                foreach ($tela5 as $keyy => $meta) {
                    $objetos1_valido_fora = array();
                    $documento = $this->removeSpaceSurplus($documento);
                    $objetos = $this->getTextBetweenTags($documento, "name=\"invalidatePageControlCounter\" value=\"", "\"");
                    $objetos1 = $this->getTextBetweenTags($documento, "name=\"idParcela\" value=\"", "\"");

                    $pagina = "https://www.convenios.gov.br/siconv/IncluirMetaParcelaCronoDesemb/AssociarMetasAssociarMeta.do";
                    $responsavel = 1;
                    if ($crono['responsavel'] == 'CONVENENTE')
                        $responsavel = 2;

                    $qualificacao = $this->getTextBetweenTags($documento, "<option value=\"", "<\/option>");
                    $meta_crono = "";
                    foreach ($qualificacao as $quali) {
                        if (strstr(trim($quali), trim(utf8_decode($meta['meta']))) !== false) {
                            $meta_crono = strtok($quali, "\"");
                            break;
                        }
                    }
                    if ($meta_crono == "") {
                        foreach ($qualificacao as $quali) {
                            if (strstr(trim($quali), trim(utf8_decode(substr($meta['meta'], 0, 40)))) !== false) {//nomes muito grandes de metas
                                $meta_crono = strtok($quali, "\"");
                                break;
                            }
                        }
                    }

                    $fields = array(
                        'invalidatePageControlCounter' => $objetos[0],
                        'idProposta' => $idProposta,
                        'idParcela' => $objetos1[0],
                        'meta' => $meta_crono,
                        'valorMeta' => $meta['valor']
                    );
                    $fields_string = null;
                    foreach ($fields as $key => $value) {
                        $fields_string .= $key . '=' . $value . '&';
                    }
                    rtrim($fields_string, '&');
                    $documento = utf8_decode($this->obter_pagina_post($pagina, $fields, $fields_string));
                    echo $documento;

                    $documento = $this->removeSpaceSurplus($documento);
                    $txt1 = $this->getTextBetweenTags($documento, "<nobr><a href=\"javascript:document.location='", "';\" class=\"buttonLink\">");
                    $qualificacao = $this->getTextBetweenTags($documento, "<div class=\"descricao\">", "<\/div>");
                    $chave_etapa_retorno = 0;
                    foreach ($qualificacao as $chave_etapa => $quali) {
                        echo $quali . " quali<br>";
                        if (strstr(trim($quali), trim(utf8_decode($meta['meta']))) !== false) {
                            $chave_etapa_retorno = $chave_etapa;
                            break;
                        }
                    }
                    $val = $chave_etapa_retorno * 2;
                    //$documento_etapa = utf8_decode($this->obter_pagina("https://www.convenios.gov.br" . $txt1[$val]));
                    $documento_etapa = utf8_decode($this->autentica_siconv->new_obter_pagina("https://www.convenios.gov.br" . $txt1[$val], $this->login_siconv, $this->cookie_file_path));
                    echo $txt1[$val] . ":::::::::::::::::::::::::::";
                    echo $documento_etapa;

                    $this->proposta_model->atualiza_etapas_proposta('cronograma_meta', array('exportado_siconv' => true), 'idCronograma_meta', $meta[0]);

                    $documento_etapa = $this->removeSpaceSurplus($documento_etapa);
                    $txt1 = $this->getTextBetweenTags($documento_etapa, "<nobr><a href=\"javascript:document.location='", "';\" class=\"buttonLink\">");

                    $tela5_etapas = $this->trabalho_model->obter_saida_tela5_etapas_online($meta['idCronograma_meta']);

                    foreach ($tela5_etapas as $key1 => $etapa) {

                        $qualificacao = $this->getTextBetweenTags($documento_etapa, "<div class=\"especificacaoEtapa\">", "<\/div>");
                        $chave_etapa_retorno = 0;
                        foreach ($qualificacao as $chave_etapa => $quali) {
                            if (strstr(trim($quali), trim(utf8_decode($this->trabalho_model->obter_etapa_por_id($etapa['Etapa_idEtapa'])->especificacao))) !== false) {
                                $chave_etapa_retorno = $chave_etapa;
                                break;
                            }
                        }
                        $val = $chave_etapa_retorno * 2;
                        echo $val . $txt1[$val] . ";;;;;;;;;;;;;;;;;;;;;;;;;;;;;";
                        //$documento_etapa = utf8_decode($this->obter_pagina("https://www.convenios.gov.br" . $txt1[$val]));
                        $documento_etapa = utf8_decode($this->autentica_siconv->new_obter_pagina("https://www.convenios.gov.br" . $txt1[$val], $this->login_siconv, $this->cookie_file_path));
                        echo $documento_etapa;
                        $documento_etapa = $this->removeSpaceSurplus($documento_etapa);
                        $objetos1_valido = $this->getTextBetweenTags($documento_etapa, "name=\"invalidatePageControlCounter\" value=\"", "\"");
                        $objetos2_valido = $this->getTextBetweenTags($documento_etapa, "name=\"idParcelaMeta\" value=\"", "\"");
                        $objetos3_valido = $this->getTextBetweenTags($documento_etapa, "name=\"etapa\" value=\"", "\"");
                        $pagina = "https://www.convenios.gov.br/siconv/AssociarValorParcelaEtapa/AssociarValorEtapaSalvar.do";

                        $valor = $etapa['valor'];
                        $fields = array(
                            'invalidatePageControlCounter' => $objetos1_valido[0],
                            'idParcelaMeta' => $objetos2_valido[0],
                            'etapa' => $objetos3_valido[0],
                            'valorAVincular' => $valor
                        );

                        $fields_string = null;
                        foreach ($fields as $key => $value) {
                            $fields_string .= $key . '=' . $value . '&';
                        }
                        rtrim($fields_string, '&');
                        $documento_etapa = utf8_decode($this->obter_pagina_post($pagina, $fields, $fields_string));
                        echo $documento_etapa;

                        $documento_etapa = $this->removeSpaceSurplus($documento_etapa);
                        $objetos1_valido_fora = $this->getTextBetweenTags($documento_etapa, "name=\"invalidatePageControlCounter\" value=\"", "\"");

                        $this->proposta_model->atualiza_etapas_proposta('cronograma_etapa', array('exportado_siconv' => true), 'idCronograma_etapa', $etapa[0]);
                    }
                    $pagina = "https://www.convenios.gov.br/siconv/AssociarValorParcelaEtapa/EscolherEtapaVoltar.do";

                    $fields = array(
                        'invalidatePageControlCounter' => $objetos1_valido_fora[0],
                        'idProposta' => $idProposta,
                        'idParcela' => $objetos1[0]
                    );
                    $fields_string = null;
                    foreach ($fields as $key => $value) {
                        $fields_string .= $key . '=' . $value . '&';
                    }
                    rtrim($fields_string, '&');
                    $documento = utf8_decode($this->obter_pagina_post($pagina, $fields, $fields_string));
                    echo "volta..." . $fields_string;
                    echo $documento;
                }
            }

            //PLANO DETALHADO COMEÇA AQUI
            $tela6 = $this->trabalho_model->obter_saida_tela6_online($this->input->get_post('id', TRUE));

            foreach ($tela6 as $key1 => $despesa) {
                $documento = $this->removeSpaceSurplus($documento);
                //PEGAR NESSE MOMENTO O ID DO PROGRAMA A SER INCLUIDO
                $objetos1_valido = $this->getTextBetweenTags($documento, "name=\"invalidatePageControlCounter\" value=\"", "\"");
                $pagina = "https://www.convenios.gov.br/siconv/DetalharBensProposta/ListarBensIncluirBem.do";

                //$valor = number_format($etapa['valor'], 2, ",", ".");
                $fields = array(
                    'invalidatePageControlCounter' => $objetos1_valido[0],
                    'idProposta' => $idProposta,
                    'mudarLabelBotao' => 'tipoDespesa',
                    'tipoDespesa' => utf8_decode($despesa['despesa'])
                );

                $fields_string = null;
                foreach ($fields as $key => $value) {
                    $fields_string .= $key . '=' . $value . '&';
                }
                rtrim($fields_string, '&');
                $documento_etapa = utf8_decode($this->obter_pagina_post($pagina, $fields, $fields_string));
                echo $documento_etapa;
                $documento_etapa = $this->removeSpaceSurplus($documento_etapa);
                //$nome_programa = substr($this->programa_proposta_model->get_nome_programa($this->input->get_post('id', TRUE), $despesa['id_programa'])->nome_programa, 0, 54);

                $id_programa = $despesa['id_programa'];
                $nome_programa = $this->programa_proposta_model->get_nome_programa($this->input->get_post('id', TRUE), $id_programa)->nome_programa;
                $nome_programa = utf8_decode($nome_programa);
                $nome_programa = substr($nome_programa, 0, 54);

                $objetos1_valido = $this->getTextBetweenTags($documento_etapa, "name=\"invalidatePageControlCounter\" value=\"", "\"");
                //$objetos2_valido = $this->getTextBetweenTags($documento_etapa, "id=\"incluirBensPrograma\"><option value=\"", "\">{$nome_programa}");
                $objetos2_valido = $this->getTextBetweenTags($documento_etapa, "<select name=\"programa\"", "<\/select>");
                $objetos2_valido = explode('>' . $nome_programa, $objetos2_valido[0]);
                $objetos2_valido = explode('value="', $objetos2_valido[0]);
                $objetos2_valido = explode('"', $objetos2_valido[count($objetos2_valido) - 1]);
                var_dump($objetos2_valido[0]);

                $pagina = "https://www.convenios.gov.br/siconv/IncluirBensProposta/IncluirBensPropostaIncluirBens.do";

                $this->load->model('cidades_model');
                $this->load->model('cidades_siconv');

                $uf = $despesa['UF'];
                if ($uf == 0)
                    $uf = null;
                else {
                    $estado = $this->cidades_model->sigla_estado($this->obterEstadoInverso($despesa['UF']));
                    $uf = $estado[0]['sigla'];
                }
                $valor = $despesa['total'];
                $fields = array(
                    'invalidatePageControlCounter' => $objetos1_valido[0],
                    'idProposta' => $idProposta,
                    'unidadeDesabilitada' => 'false',
                    'idBem' => '',
                    'valorCATMAT' => '',
                    'programa' => $objetos2_valido[0],
                    'tipoDespesa' => utf8_decode($despesa['despesa']),
                    'descricaoItem' => utf8_decode($despesa['descricao']),
                    'remLen' => 5000 - strlen($despesa['descricao']),
                    'naturezaAquisicao' => utf8_decode($despesa['natureza_aquisicao']),
                    'codigoNaturezaDespesa' => utf8_decode($despesa['natureza_despesa']),
                    'codUnidadeFornecimento' => utf8_decode($despesa['fornecimento']),
                    'descricaoUnidadeFornecimento' => '',
                    'valor' => $valor,
                    'quantidade' => $despesa['quantidade'],
                    'valorUnitario' => $despesa['valor_unitario'],
                    'endereco' => utf8_decode($despesa['endereco']),
                    'CEP' => $despesa['cep'],
                    'codigoMunicipio' => utf8_decode(str_pad((int) $despesa['municipio'], 4, "0", STR_PAD_LEFT)),
                    'nomeMunicipio' => utf8_decode($this->cidades_siconv->get_by_codigo($despesa['municipio'])->Nome),
                    'siglaUf' => $uf,
                    'observacao' => utf8_decode($despesa['observacao']),
                    'remLen1' => 5000 - strlen($despesa['observacao'])
                );

                $fields_string = null;
                foreach ($fields as $key => $value) {
                    $fields_string .= $key . '=' . $value . '&';
                }
                rtrim($fields_string, '&');
                $documento_etapa = utf8_decode($this->obter_pagina_post($pagina, $fields, $fields_string));
                echo $fields_string;
                echo $documento_etapa;
                //$documento = utf8_decode($this->obter_pagina("https://www.convenios.gov.br/siconv/IncluirBensProposta/IncluirBensPropostaVoltar.do"));
                $documento = utf8_decode($this->autentica_siconv->new_obter_pagina("https://www.convenios.gov.br/siconv/IncluirBensProposta/IncluirBensPropostaVoltar.do", $this->login_siconv, $this->cookie_file_path));
                echo $documento;

                $this->proposta_model->atualiza_etapas_proposta('despesa', array('exportado_siconv' => true), 'idDespesa', $despesa[0]);
            }
            //PLANO DETALHADO FINALIZA AQUI
            //echo $programa_url[1]." prop ".$idProposta;
            //https://www.convenios.gov.br/siconv/DetalharBensProposta/ListarBensIncluirBem.do
            echo "FINAL";
            $this->system_logs->add_log(FINALIZA_EXPORTA_SICONV . " ID: " . $this->input->get_post('id', TRUE));

            //GUSTAVO: Incluido para apos finalizar a exportação, encaminhar para a tela de meus projetos com a aba de enviados marcada
            $this->session->set_userdata('projEnviado', 'S');
            $this->session->set_userdata('id_siconv', $id_proposta_efetiva);

            //THOMAS: Alerta para informar e redirecionando para a lista de projetos.
            $this->alert("Cadastrada com sucesso no SICONV ! Confirme o dialogo e feche a janela para continuar !");
            sleep(1);
            die();

            $data['tela2'] = $this->trabalho_model->obter_saida_tela2($this->input->get_post('id', TRUE));
            $data['tela3'] = $this->trabalho_model->obter_saida_tela3($this->input->get_post('id', TRUE));
            $data['tela4'] = $this->trabalho_model->obter_saida_tela4($this->input->get_post('id', TRUE));
            $data['tela5'] = $this->trabalho_model->obter_saida_tela5($this->input->get_post('id', TRUE));
            $data['tela5_etapas'] = $this->trabalho_model->obter_saida_tela5_etapas($this->input->get_post('id', TRUE));
            $data['tela6'] = $this->trabalho_model->obter_saida_tela6($this->input->get_post('id', TRUE));
            $data['login'] = $this->login;
            $data['title'] = "SIHS - Finaliza Trabalho";
            $data['main'] = 'gestor/exporta_siconv';

            $this->load->view('in/template', $data);
        } else {
            $this->voltaPagina();
        }
    }

    public function teste2() {
        $this->session->set_userdata('projEnviado', 'S');
        $this->session->set_userdata('id_siconv', '911175');

        $this->encaminha(base_url('index.php/in/gestor/visualiza_propostas'));
    }

    function envia_email_trabalho() {

        $this->load->library('email');

        $this->email->from('marcelomoura@physisbrasil.com.br', 'SIHS Brasil');
        $this->email->to($email);
        $this->email->cc($email1);

        $this->email->subject('Aviso de trabalho do siconv');
        $this->email->message($texto);

        $this->email->send();
        echo $this->email->print_debugger();
        die();
    }

    function envia_email($email, $email1, $texto) {

        $this->load->library('email');

        $this->email->from('marcelomoura@physisbrasil.com.br', 'SIHS Brasil');
        $this->email->to($email);
        $this->email->cc($email1);

        $this->email->subject('Aviso de trabalho do siconv');
        $this->email->message($texto);

        $this->email->send();
    }

    function visualiza_propostas() {

        $usuario = $this->usuario_logado->id_usuario;
        $this->session->set_userdata('pagAtual', 'visualiza_propostas');

        $this->load->model('proposta_model');
        $this->load->model('usuariomodel');
        $this->load->model('trabalho_model');
        $this->load->model('cnpj_siconv');
        $this->load->model('programa_proposta_model');
        $this->load->model('area_model');
        $this->load->model('proponente_siconv_model');

        $filtro = "";
        if ($this->input->post('filtro', TRUE) != false)
            $filtro = $this->input->post('filtro', TRUE);

        $data['propostas_enviadas'] = $this->proposta_model->get_all_ativo_enviadas($usuario, $filtro);
        $data['propostas_cadastradas'] = $this->proposta_model->get_all_ativo_cadastradas($usuario, $filtro);
        $data['programa_proposta_model'] = $this->programa_proposta_model;
        $data['propostas_importadas'] = $this->proposta_model->get_all_ativo_padrao_padrao_oculto();
        $data['usuariomodel'] = $this->usuariomodel;

        $data['areas'] = $this->area_model->get_all();

        //Olhar falta filtro sistema estado
        if ($this->session->userdata('nivel') == 4) {
            $data['cnpjs'] = $this->usuariomodel->get_municipio_by_vendedor($usuario);
        } else if ($this->session->userdata('nivel') == 1) {
            $data['cnpjs'] = $this->usuariomodel->get_lista_cidade_by_admin();
        } else {
            $data['cnpjs'] = $this->usuariomodel->get_lista_cidades_by_cnpj($usuario);
        }

        //$data['trabalho_model'] = $this->trabalho_model;
        $data['proponente_siconv_model'] = $this->proponente_siconv_model;
        $data['propostas_mais_trinta_dias'] = $this->proposta_model->checa_propostas_trinta_dias();
        $data['title'] = 'SIHS - Gerenciamento de propostas';
        $data['main'] = 'gestor/visualiza_propostas';
        $data['login'] = $this->login;
        $this->load->vars($data);
        $this->load->view('in/template');
    }

    //THOMAS: Carrega o banco de propostas
    function visualiza_banco_propostas() {
        $this->session->set_userdata('pagAtual', 'visualiza_banco_propostas');
        $this->load->model('proposta_model');
        $this->load->model('data_model');
        $data['propostas'] = $this->proposta_model->get_all_ativo_padrao($this->input->post('pesquisar', TRUE));
        $data['title'] = 'SIHS - Banco de propostas';
        $data['main'] = 'gestor/visualiza_banco_propostas';
        $this->load->view('in/template', $data);
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

    function obter_paginaLogin_status($login_usuario, $senha) {
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

        return $content;
    }

    function obter_paginaLogin($login_usuario, $senha) {
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

        if (strstr($content, '<span style="float:left">Erro') !== false) {
            $this->session->set_userdata('falha_login', 'S');
            $this->alert("Senha incorreta, verifique se a senha cadastrada confere com a do SICONV.");

            if ($this->session->userdata('nivel') == 1)
                $this->encaminha('escolher_proponente');
            else
                $this->encaminha(base_url('index.php/controle_usuarios/atualiza_usuario?id=' . $this->session->userdata('id_usuario')));
            die();
        }else if (strstr($content, 'nova senha') !== false) {
            $this->session->set_userdata('falha_login', 'S');
            //Seta um status para informar ao usuário que deve ser alterada a senha do SICONV
            $this->session->set_userdata('altera_senha_siconv', 'S');
            $this->alert("Senha desatualizada, é necessário login no SICONV para atualizá-la");

            if ($this->session->userdata('nivel') == 1)
                $this->encaminha('escolher_proponente');
            else
                $this->encaminha(base_url('index.php/controle_usuarios/atualiza_usuario?id=' . $this->session->userdata('id_usuario')));
            die();
        }
        return $content;
    }

    function obter_paginaLogin_usuario($login_usuario, $senha, $id) {
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
            $this->encaminha('altera_usuario?id=' . $id);
        }
        return $content;
    }

    function obter_paginaLogin_exporta($login_usuario, $senha, $id) {
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

    function obter_pagina($url) {

        //$cookie_file_path = "application/views/configuracoes/cookie.txt";
        $cookie_file_path = $this->cookie_file_path;
        $agent = "Mozilla/5.0 (X11; Ubuntu; Linux i686; rv:20.0) Gecko/20100101 Firefox/20.0";
        $ch = curl_init();
        // extra headers
        $headers[] = "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8";
        $headers[] = "Connection: Keep-Alive";
        $headers[] = "'Content-type: multipart/form-data'";

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

    function obter_pagina_post1($url, $fields, $fields_string) {
        //$MULTIPART_BOUNDARY = '--------------------------'.microtime(true);
        //$cookie_file_path = "application/views/configuracoes/cookie.txt";
        $cookie_file_path = $this->cookie_file_path;
        $agent = "Mozilla/5.0 (Windows; U; Windows NT 5.0; en-US; rv:1.4) Gecko/20030624 Netscape/7.1 (ax)";
        $ch = curl_init();
        // extra headers
        $headers[] = "Accept: */*";
        $headers[] = "Connection: Keep-Alive";
        $headers[] = 'Content-Type: multipart/form-data';
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
        if (strstr($content, '<div class="errors">') !== false) {

            $aux = $this->removeSpaceSurplus($content);
            $erro = $this->getTextBetweenTags($aux, "<div class=\"error\">", "<\/div>");
            $this->alert("Houve um erro de conexão com o siconv. Por favor copie e cole o texto abaixo para adm@physisbrasil.com.br: " . $erro[0]);
            //echo $url;
            $this->encaminha(base_url() . "index.php/in/gestor/visualiza_propostas");
        }

        return $content;
    }

    function removeSpaceSurplus($str) {
        return preg_replace("/\s+/", ' ', trim($str));
    }

    function getTextBetweenTags($string, $tag1, $tag2) {
        $pattern = "/$tag1([\w\W]*?)$tag2/";
        preg_match_all($pattern, $string, $matches);
        return $matches[1];
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

    public function grava_atende_banco_projetos() {
        $this->db->where('idProposta', $this->input->post('idProposta', TRUE));
        $this->db->update('proposta', array('banco_atende' => $this->input->post('banco_atende', TRUE)));
    }

}
