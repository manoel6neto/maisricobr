<?php

class confirma_email extends CI_Controller {

    public function index() {
        if ($this->input->post()) {
            $this->load->model('confirma_cadastro');
            $this->load->model('usuariomodel');

            if ($this->input->get('token', TRUE) !== false) {
                $email = base64_decode($this->input->get('token', TRUE));

                $dados_cadastro = $this->confirma_cadastro->busca_dados_validar($email, $this->input->post('cpf', TRUE));
                if ($dados_cadastro == null || count($dados_cadastro) <= 0)
                    $this->alert("CPF ou token inválidos. Entre em contato com o suporte@physisbrasil.com.br");
                else {
                    $usuario = $this->usuariomodel->get_dados_cpf_email($this->input->post('cpf', TRUE), $email);
                    $this->confirma_cadastro->atualiza_cadastro_confirmado($email, $this->input->post('cpf', TRUE));
                    if ($usuario->id_nivel == 2) {
                        $this->db->select('tipo_gestor');
                        $this->db->where('id_usuario', $usuario->id_usuario);
                        $query_g = $this->db->get('gestor');

                        if ($query_g->num_rows > 0) {
                            //Se o gestor for gratuito ativa o usuario automaticamente
                            if ($query_g->row(0)->tipo_gestor == 10) {

                                //Chama funcao de ativar o usuario
                                $this->ativa_usuario_avulso_gratuito_direto($usuario->id_usuario);
                                $this->alert("Usuario ativado com sucesso. Aguarde o email com os dados de acesso.");
                                redirect('in/login');
                                //$this->encaminha(base_url());
                            } else {
                                $this->envia_sms($usuario->nome);
                                $this->alert("Email confirmado com sucesso. Em até 48 horas um email será enviado com os dados de login e senha.");
                                redirect('in/login');
                                //$this->encaminha(base_url());
                            }
                        }
                    } else {
                        $this->usuariomodel->envia_email_usuario_cadastrado($dados_cadastro->email_usuario, $dados_cadastro->nome_usuario, $dados_cadastro->cpf_usuario, $dados_cadastro->tem_login_siconv, base64_decode($dados_cadastro->senha_usuario));
                        $this->alert("Email confirmado com sucesso. Um email foi enviado com os dados de login e senha.");
                        redirect('in/login');
                        //$this->encaminha(base_url());
                    }
                }
            } else {
                $this->alert("Token de identificação inválido. Entre em contato com o suporte@physisbrasil.com.br");
            }
        }

        $data['title'] = 'SIHS - Confirmação de Cadastro';
        $data['main'] = "confirma_email/index";

        $this->load->view('in/template_login', $data);
    }

    public function ativa_usuario_avulso_gratuito_direto($id_usuario) {
        if ($id_usuario != null) {
            $this->load->model('usuariomodel');
            $num_rows = $this->usuariomodel->muda_status_usuario($id_usuario, 'A');

            if ($num_rows > 0) {
                $dados_usuario = $this->usuariomodel->get_by_id($id_usuario);
                if ($dados_usuario->id_nivel == 2) {
                    if ($dados_usuario->usuario_novo == 'S') {
                        $this->load->model('confirma_cadastro');
                        $this->load->model('programa_model');

                        $dados_cadastro = $this->confirma_cadastro->busca_dados_validar($dados_usuario->email, $dados_usuario->login);

                        $senha_gerada = rand(1000, 9999999);
                        $array_senha_id = array(
                            'id_usuario' => $id_usuario,
                            'senha' => $senha_gerada
                        );

                        $this->usuariomodel->atualiza_senha($array_senha_id);

                        $this->usuariomodel->envia_email_usuario_cadastrado($dados_cadastro->email_usuario, $dados_cadastro->nome_usuario, $dados_cadastro->cpf_usuario, $dados_cadastro->tem_login_siconv, $senha_gerada);

                        $this->usuariomodel->marca_usuario_nao_novo($id_usuario, false);

                        $this->programa_model->programas_abertos(true, $id_usuario);
                    }
                }

                $this->load->model('system_logs');
                $this->system_logs->add_log(USUARIO_ATIVADO . " - ID: " . $id_usuario . ", Nome: " . $this->usuariomodel->get_by_id($id_usuario)->nome);
            } else if ($num_rows === "SEM_PERMISSAO") {
                $this->alert("Erro ao ativar usuário.");
                $this->encaminha(base_url());
            } else {
                $this->alert("Erro ao ativar usuário.");
                $this->encaminha(base_url());
            }
        }
    }

    function alert($text) {
        echo "<script type='text/javascript'>alert('" . $text . "');</script>";
    }

    function encaminha($url) {
        echo "<script type='text/javascript'>window.location='" . $url . "';</script>";
    }

    public function finaliza_cadastro_importacao() {
        $this->load->model('usuariomodel');

        $idUsuario = $this->input->get('id', TRUE);
        $senha = rand(1000, 9999999);

        $this->db->where('id_usuario', $idUsuario);
        $this->db->update('usuario', array('senha' => sha1($senha)));

        $this->db->flush_cache();

        $dadosUsuario = $this->usuariomodel->get_by_id($idUsuario);

        $temUsuarioSiconv = false;

        if ($dadosUsuario->id_nivel == 2)
            $this->envia_sms($dadosUsuario->nome);

        $this->usuariomodel->envia_email_confirma_cadastro_importa($dadosUsuario->email, $dadosUsuario->nome, $dadosUsuario->login, $temUsuarioSiconv, $senha);
    }

    public function envia_sms($nome_usuario) {
        //  		Eliumar	   EU
        $telefones = array('5573988462781', '5573991192425');

        for ($i = 0; $i <= 4; $i++) {
            $credencial = URLEncode("218565391A8CE4A44253ABF179EBC1505B7A0A3F"); //**Credencial da Conta 40 caracteres
            $principal = URLEncode("ESICAR");  //* SEU CODIGO PARA CONTROLE, não colocar e-mail
            $auxuser = URLEncode("USER_ATIVACAO"); //* SEU CODIGO PARA CONTROLE, não colocar e-mail
            $mobile = URLEncode($telefones[$i]); //* Numero do telefone  FORMATO: PAÍS+DDD(DOIS DÍGITOS)+NÚMERO
            $sendproj = URLEncode("N"); //* S = Envia o Remetente do SMS antes da mensagem , N = Não envia o Remetente do SMS
            $msg = "Caro administrador,\r\n O usuário {$nome_usuario} confirmou os dados de cadastro no sistema e-SICAR - SIHS"; // Mensagem
            $msg = mb_convert_encoding($msg, "UTF-8"); // Converte a mensagem para não ocorrer erros com caracteres semi-gráficos
            $msg = URLEncode($msg);
            $response = fopen("http://www.mpgateway.com/v_2_00/smspush/enviasms.aspx?CREDENCIAL=" . $credencial . "&PRINCIPAL_USER=" . $principal . "&AUX_USER=" . $auxuser . "&MOBILE=" . $mobile . "&SEND_PROJECT=" . $sendproj . "&MESSAGE=" . $msg, "r");
            $status_code = fgets($response, 4);
            echo "Codigo retornando do fopen=";
            echo $status_code;
        }
    }

}
