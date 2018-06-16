<?php

include 'application/libraries/PagSeguroLibrary/PagSeguroLibrary.php';

class compra extends CI_Controller {

    //UGh5NWk1X0MwbVByYXMy - Token criptografado
    var $token = "Phy5i5_C0mPras2";

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $token_url = $this->input->get('token', TRUE);

        if (base64_decode($token_url) === $this->token) {
            $data['PA'] = $this->input->get('PA', TRUE);
            $data['ED'] = $this->input->get('ED', TRUE);
            $data['SP'] = $this->input->get('SP', TRUE);

            $data['TP'] = $this->input->get('TP', TRUE);

            $data['http_query'] = "";
            if ($this->input->get() !== FALSE)
                $data['http_query'] = http_build_query($this->input->get());

            if ($this->input->post()) {
                //var_dump($this->input->post());
            }

            $data['title'] = "e-SICAR - Compra";
            $data['main'] = 'compra/index';
            $this->load->view('in/template_login', $data);
        } else {
            $this->alert("Token inválido");
            $this->encaminha("http://physisbrasil.com.br");
        }
    }

    public function resumo_pedido() {
        if ($this->input->post()) {
            //$data['http_query'] = http_build_query($this->input->post());

            $data['obj_dados'] = serialize($this->input->post());

            switch ($this->input->post('tipo_plano', TRUE)) {
                case "M":
                    $data['tipo_plano'] = "Mensal";
                    break;
                case "T":
                    $data['tipo_plano'] = "Trimestral";
                    break;
                case "S":
                    $data['tipo_plano'] = "Semestral";
                    break;
                case "A":
                    $data['tipo_plano'] = "Anual";
                    break;
            }

            $data['valor_pagar'] = $this->input->post('valor_pagar', TRUE);

            if ($this->input->post('tipo_servico_PA', TRUE)) {
                $data['tipo_servico'][] = "Programas Abertos";
            }

            if ($this->input->post('tipo_servico_SP', TRUE)) {
                $data['tipo_servico'][] = "Status e Pareceres";
            }

            if ($this->input->post('tipo_servico_ED', TRUE)) {
                $data['tipo_servico'][] = "Emendas Disponíveis";
            }

            if ($this->input->post('tipo_servico_PA', TRUE) && $this->input->post('tipo_servico_SP', TRUE)) {
                $data['tipo_servico'][] = "Emendas Disponíveis";
            }
        }

        $data['title'] = "e-SICAR - Compra";
        $data['main'] = 'compra/resumo_pedido';
        $this->load->view('in/template_login', $data);
    }

    public function cadastro_usuario() {
        $this->load->model('usuariomodel');
        $this->load->model('proponente_siconv_model');
        $this->load->model('permissoes_usuario');

        $data['proponente_siconv_model'] = $this->proponente_siconv_model;

        if ($this->input->get_post('gratuito', TRUE) == false) {
            $data['obj_dados'] = $this->input->post('obj_dados', TRUE);
        }

        if ($this->input->get_post('gratuito', TRUE) != false) {
            $data['gratuito'] = 1;
        }

        if ($this->input->post('login', TRUE)) {
            $this->form_validation->set_rules('login', 'CPF', 'required||is_unique[usuario.login]|min_length[11]|callback_valid_cpf');
            $this->form_validation->set_rules('nome', 'Nome', 'required');
            $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|is_unique[usuario.email]|matches[confirmar_email]');
            $this->form_validation->set_rules('confirmar_email', 'Confirmar Email', '');
            $this->form_validation->set_rules('telefone', 'Telefone', '');
            $this->form_validation->set_rules('celular', 'Celular', '');
            $this->form_validation->set_rules('estado', 'Estado', 'required');
            $this->form_validation->set_rules('municipio', 'Municipio', 'required');
            $this->form_validation->set_rules('proponente', 'Proponente', 'required|callback_valida_quantidade');

            $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

            $this->form_validation->set_message('required', 'O campo %s é obrigatório.');
            $this->form_validation->set_message('valid_email', 'O campo %s deve conter um endereço de email válido.');
            $this->form_validation->set_message('is_unique', '%s já cadastrado.');
            $this->form_validation->set_message('min_length', '%s deve possuir %s digitos.');
            $this->form_validation->set_message('matches', '%s e %s devem ser iguais');
            $this->form_validation->set_message('max_length', 'O campo %s deve conter %s dígitos.');

            if ($this->form_validation->run()) {
                $optionsUsuario = $this->input->post();

                $check_existe_cpf_or_email = $this->usuariomodel->check_existe_cpf_or_email($optionsUsuario);
                if ($check_existe_cpf_or_email) {
                    $this->alert("Email ou CPF ja cadastrados no banco de dados !!! Favor utilizar cadastro existente !!");
                    $this->encaminha(base_url() . "/index.php/compra?token=UGh5NWk1X0MwbVByYXMy");
                }

                if ($this->input->get_post('gratuito', TRUE) == false) {
                    $obj_dados = unserialize($optionsUsuario['obj_dados']);
                }

                if ($this->input->get_post('gratuito', TRUE) != false) {
                    $retorno = null;
                } else {
                    $retorno = $this->gera_pagamento($optionsUsuario, $obj_dados);
                }

                if (!is_null($retorno) || $this->input->get_post('gratuito', TRUE) != false) {
                    $optionsUsuario['senha'] = rand(1000, 9999999);
                    unset($optionsUsuario['confirmar_email']);
                    unset($optionsUsuario['obj_dados']);
                    unset($optionsUsuario['senha_siconv']);
                    unset($optionsUsuario['gratuito']);

                    $optionsUsuario['status'] = 'I';

                    $optionsUsuario['usuario_sistema'] = "M";
                    $optionsUsuario['id_nivel'] = 2;

                    $ultimo_usuario = $this->usuariomodel->insere_usuario($optionsUsuario);

                    if ($ultimo_usuario != null) {
                        $this->load->model('gestor');
                        $this->load->model('data_model');

                        $validade = 1;
                        if ($this->input->get_post('gratuito', TRUE) == false) {
                            switch ($obj_dados['tipo_plano']) {
                                case "M":
                                    $validade = 1;
                                    break;
                                case "T":
                                    $validade = 3;
                                    break;
                                case "S":
                                    $validade = 6;
                                    break;
                                case "A":
                                    $validade = 12;
                                    break;
                            }
                        } else {
                            $validade = 7;
                        }

                        if ($this->input->get_post('gratuito', TRUE) != false) {
                            $optionsGestor = array(
                                'validade' => $this->data_model->retornaNovaData(date("Y-m-d"), 7),
                                'quantidade_cnpj' => 1,
                                'id_usuario' => $ultimo_usuario,
                                'inicio_vigencia' => date("Y-m-d"),
                                'tipo_gestor' => 10
                            );
                        } else {
                            $optionsGestor = array(
                                'validade' => $this->data_model->retornaNovaData(date("Y-m-d"), $validade, true),
                                'quantidade_cnpj' => $obj_dados['qtd_cnpj'],
                                'id_usuario' => $ultimo_usuario,
                                'inicio_vigencia' => date("Y-m-d"),
                                'tipo_gestor' => 10
                            );
                        }

                        $this->gestor->insere_gestor($optionsGestor);

                        if ($this->input->get_post('gratuito', TRUE) == false) {
                            //Ativando as permissoes do usuario
                            $permissions = array(
                                'consultar_programa' => array_key_exists('tipo_servico_PA', $obj_dados) ? 1 : 0,
                                'relatorio_programa' => array_key_exists('tipo_servico_PA', $obj_dados) ? 1 : 0,
                                'criar_usuario' => 0,
                                'editar_usuario' => 0,
                                'ativar_usuario' => 0,
                                'vincular_cnpj_usuario' => 0,
                                'editar_cnpj_usuario' => 0,
                                'desativar_usuario' => 0,
                                'criar_projeto' => 0,
                                'editar_projeto' => 0,
                                'alterar_end_projeto' => 0,
                                'apagar_projeto' => 0,
                                'apagar_projeto_padrao' => 0,
                                'tornar_proj_padrao' => 0,
                                'utilizar_padrao' => 0,
                                'duplicar_projeto' => 0,
                                'exportar_siconv' => 0,
                                'consultar_proposta' => array_key_exists('tipo_servico_SP', $obj_dados) ? 1 : 0,
                                'relatorio_proposta' => array_key_exists('tipo_servico_SP', $obj_dados) ? 1 : 0,
                                'status_proposta' => array_key_exists('tipo_servico_SP', $obj_dados) ? 1 : 0,
                                'parecer_proposta' => array_key_exists('tipo_servico_SP', $obj_dados) ? 1 : 0,
                                'visualiza_emendas' => (array_key_exists('tipo_servico_SP', $obj_dados) && array_key_exists('tipo_servico_PA', $obj_dados)) ? 1 : 0,
                                'visualiza_prop_parecer' => array_key_exists('tipo_servico_SP', $obj_dados) ? 1 : 0,
                                'visualiza_minhas_propostas' => 0
                            );
                        } else {
                            //Ativa os 3 pacotes
                            $permissions = array(
                                'consultar_programa' => 1,
                                'relatorio_programa' => 1,
                                'criar_usuario' => 0,
                                'editar_usuario' => 0,
                                'ativar_usuario' => 0,
                                'vincular_cnpj_usuario' => 0,
                                'editar_cnpj_usuario' => 0,
                                'desativar_usuario' => 0,
                                'criar_projeto' => 0,
                                'editar_projeto' => 0,
                                'alterar_end_projeto' => 0,
                                'apagar_projeto' => 0,
                                'apagar_projeto_padrao' => 0,
                                'tornar_proj_padrao' => 0,
                                'utilizar_padrao' => 0,
                                'duplicar_projeto' => 0,
                                'exportar_siconv' => 0,
                                'consultar_proposta' => 1,
                                'relatorio_proposta' => 1,
                                'status_proposta' => 1,
                                'parecer_proposta' => 1,
                                'visualiza_emendas' => 1,
                                'visualiza_prop_parecer' => 1,
                                'visualiza_minhas_propostas' => 0
                            );
                        }

                        $this->permissoes_usuario->update_by_usuario_id($ultimo_usuario, $permissions);

                        $this->load->model('cnpj_siconv');
                        $this->load->model('usuario_cnpj');

                        $proponentes = $this->input->post('proponente');

                        foreach ($proponentes as $proponente) {
                            $options_cnpj = array(
                                'cnpj' => preg_replace('/[^0-9]/', '', (string) $proponente),
                                'id_cidade' => $this->input->post('municipio'),
                                'cnpj_instituicao' => $this->proponente_siconv_model->get_instituicao_nome($proponente),
                                'sigla' => $this->input->post('estado'),
                                'esfera_administrativa' => $this->proponente_siconv_model->get_instituicao_esfera($proponente)
                            );

                            $id_cnpj = $this->cnpj_siconv->insere_cnpj($options_cnpj);
                            if ($id_cnpj > 0)
                                $this->usuario_cnpj->insere_usuario_cnpj($ultimo_usuario, $id_cnpj);
                        }

                        $this->usuariomodel->realaciona_usuarios_cadastro(array('id_usuario_cadastrado' => $ultimo_usuario, 'id_usuario_cadastrou' => 1));

                        $temUsuarioSiconv = true;

                        $this->usuariomodel->envia_email_confirma_cadastro($optionsUsuario['email'], $optionsUsuario['nome'], $optionsUsuario['login'], $temUsuarioSiconv, $optionsUsuario['senha']);

                        if ($this->input->get_post('gratuito', TRUE) != false) {
                            $array_pagseguro = array(
                                'id_usuario' => $ultimo_usuario,
                                'codigo_ref_compra' => null,
                                'data_compra' => date("Y-m-d"),
                                'validade_plano' => 1,
                                'tipo_servico' => 'PA,SP,ED',
                                'compra_paga' => 1
                            );
                        } else {
                            $array_pagseguro = array(
                                'id_usuario' => $ultimo_usuario,
                                'codigo_ref_compra' => $retorno['refCod'],
                                'data_compra' => date("Y-m-d"),
                                'validade_plano' => $validade,
                                'tipo_servico' => $retorno['tipoServico']
                            );
                        }

                        $this->db->insert('pagseguro_usuario', $array_pagseguro);

                        if ($this->input->get_post('gratuito', TRUE) != false) {
                            $this->alert("Usuário cadastrado com sucesso. Aguarde o email de ativacao do usuario.");
                            $this->encaminha("http://physisbrasil.com.br");
                        } else {
                            $this->alert("Usuário cadastrado com sucesso. Você será redirecionado para efetuar o pagamento.");
                            $this->encaminha($retorno['url']);
                        }
                    }
                }
            }
        }

        $data['title'] = "e-SICAR - Cadastro de Usuário";
        $data['main'] = 'compra/cadastro_usuario';
        $this->load->view('in/template_login', $data);
    }

    public function reativa_plano() {
        $this->load->model('usuariomodel');
        $this->load->model('proponente_siconv_model');

        $data['proponente_siconv_model'] = $this->proponente_siconv_model;

        $data['obj_dados'] = $this->input->post('obj_dados', TRUE);

        if ($this->input->post()) {
            $optionsUsuario = $this->input->post();

            $this->db->where('login', $this->input->post('login', TRUE));
            $this->db->where('senha', sha1($this->input->post('senha', TRUE)));
            $this->db->where('id_nivel', 2);
            $dados_usuario = $this->db->get('usuario')->row(0);

            $this->db->flush_cache();

            if (!is_null($dados_usuario)) {
                $obj_dados = unserialize($optionsUsuario['obj_dados']);

                $retorno = $this->gera_pagamento((array) $dados_usuario, $obj_dados);

                if (!is_null($retorno)) {
                    if ($dados_usuario->id_usuario != null) {
                        $this->load->model('gestor');
                        $this->load->model('data_model');

                        $validade = 1;
                        switch ($obj_dados['tipo_plano']) {
                            case "M":
                                $validade = 1;
                                break;
                            case "T":
                                $validade = 3;
                                break;
                            case "S":
                                $validade = 6;
                                break;
                            case "A":
                                $validade = 12;
                                break;
                        }

                        $this->db->insert('pagseguro_usuario', array(
                            'id_usuario' => $dados_usuario->id_usuario,
                            'codigo_ref_compra' => $retorno['refCod'],
                            'data_compra' => date("Y-m-d"),
                            'validade_plano' => $validade,
                            'tipo_servico' => $retorno['tipoServico']
                        ));

                        $this->alert("Usuário cadastrado com sucesso. Você será redirecionado para efetuar o pagamento.");
                        $this->encaminha($retorno['url']);
                    }
                }
            }
        }
    }

    private function gera_pagamento($usuario, $obj_dados) {
        $this->load->model('programa_model');

        $servico = array();
        $tipo_servicos = array();
        if (isset($obj_dados['tipo_servico_PA'])) {
            $servico[] = "Programas Abertos";
            $tipo_servicos[] = "PA";
        }

        if (isset($obj_dados['tipo_servico_ED'])) {
            $servico[] = "Emendas Disponíveis";
            $tipo_servicos[] = "ED";
        }

        if (isset($obj_dados['tipo_servico_SP'])) {
            $servico[] = "Status e Pareceres";
            $tipo_servicos[] = "SP";
        }

        if (isset($obj_dados['tipo_servico_PA']) && isset($obj_dados['tipo_servico_SP'])) {
            $servico[] = "Emendas Disponíveis";
            $tipo_servicos[] = "ED";
        }

        $tipo_plano = "";
        switch ($obj_dados['tipo_plano']) {
            case "M":
                $tipo_plano = "Mensal";
                break;
            case "T":
                $tipo_plano = "Trimestral";
                break;
            case "S":
                $tipo_plano = "Semestral";
                break;
            case "A":
                $tipo_plano = "Anual";
                break;
        }

        $paymentRequest = new PagSeguroPaymentRequest();
        $paymentRequest->addItem('0001', implode(", ", $servico) . " - Plano: " . $tipo_plano, 1, $obj_dados['valor_pagar']);

        $paymentRequest->setCurrency("BRL");

        $paymentRequest->setSender(
                $usuario['nome'], $usuario['email'], substr($usuario['telefone'], 0, 2), substr($usuario['telefone'], 2), 'CPF', $this->programa_model->formatCPFCNPJ($usuario['login'])
        );

        // Referenciando a transação do PagSeguro em seu sistema
        $paymentRequest->setReference(substr($usuario['login'], 0, 2) . substr($usuario['login'], -2, 2) . rand(1000, 99999));

        // URL para onde o comprador será redirecionado (GET) após o fluxo de pagamento
        $paymentRequest->setRedirectUrl(base_url('index.php/compra/retorno'));

        // URL para onde serão enviadas notificações (POST) indicando alterações no status da transação
        //$paymentRequest->setNotificationURL(base_url('index.php/compra/retorno'));

        try {
            $credentials = PagSeguroConfig::getAccountCredentials();
            $checkoutUrl = $paymentRequest->register($credentials);

            return array('url' => $checkoutUrl, 'refCod' => $paymentRequest->getReference(), 'tipoServico' => implode(",", $tipo_servicos));
        } catch (PagSeguroServiceException $e) {
            $this->alert($e->getCode() . " - " . $e->getMessage());
            $this->alert("Existem erros no cadastro, favor verifique os dados informados.");

            return null;
        }
    }

    public function retorno() {
        echo '<meta charset="UTF-8">';

        $this->alert("Compra realizada com sucesso. Após a confirmação do pagamento, um email será enviado com os dados do acesso. Um email com as informações da compra foi enviado para o seu email.");
        $this->encaminha("http://physisbrasil.com.br");
    }

    function valid_cpf($cpf) {
        $this->form_validation->set_message('valid_cpf', 'O %s informado não é válido.');

        $cpf = preg_replace('/[^0-9]/', '', $cpf);

        if (strlen($cpf) != 11 || preg_match('/^([0-9])\1+$/', $cpf)) {
            return false;
        }

        // 9 primeiros digitos do cpf
        $digit = substr($cpf, 0, 9);

        // calculo dos 2 digitos verificadores
        for ($j = 10; $j <= 11; $j++) {
            $sum = 0;
            for ($i = 0; $i < $j - 1; $i++) {
                $sum += ($j - $i) * ((int) $digit[$i]);
            }

            $summod11 = $sum % 11;
            $digit[$j - 1] = $summod11 < 2 ? 0 : 11 - $summod11;
        }
        return $digit[9] == ((int) $cpf[9]) && $digit[10] == ((int) $cpf[10]);
    }

    public function valida_quantidade($proponente) {
        $this->load->model('gestor');
        $this->load->model('usuario_cnpj');

        $this->form_validation->set_message('valida_quantidade', 'Quantidade máxima de CNPJs é 1');

        if (count($proponente) > 1)
            return false;

        return true;
    }

    public function get_lista_cidades() {
        $this->load->model('proponente_siconv_model');

        $municipio = $this->input->post('municipio', TRUE);

        $listaCidades = $this->proponente_siconv_model->get_municipio($this->input->post('uf', TRUE));

        $option = "<option value=''>Escolha</option>";
        foreach ($listaCidades as $cidade) {
            if ($cidade->codigo_municipio == $municipio)
                $selected = "selected";
            else
                $selected = "";
            $option .= "<option " . $selected . " value='" . $cidade->codigo_municipio . "'>" . $cidade->municipio . "</option>";
        }

        echo $option;
    }

    public function get_lista_proponentes() {
        $this->load->model('proponente_siconv_model');

        $listaCidades = $this->proponente_siconv_model->get_proponentes($this->input->post('esfera', TRUE), $this->input->post('municipio', TRUE), $this->input->post('uf', TRUE), $this->input->post('tipo', TRUE), $this->input->post('id', TRUE), ($this->session->userdata('nivel') == 2 && $this->session->userdata('usuario_sistema') != "P"));

        $option = array();
        foreach ($listaCidades as $cidade) {
            $option[] = array("label" => $cidade->cnpj . " - " . $cidade->nome, "value" => $cidade->cnpj);
        }

        echo json_encode($option);
    }

    function alert($text) {
        echo "<script type='text/javascript'>alert('" . $text . "');</script>";
    }

    function encaminha($url) {
        echo "<script type='text/javascript'>window.location='" . $url . "';</script>";
    }

}
