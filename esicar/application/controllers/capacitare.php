<?php

class capacitare extends CI_Controller {

    function __construct() {
        parent::__construct();
    }

    function alert($text) {
        echo "<script type='text/javascript'>alert('" . $text . "');</script>";
    }

    function encaminha($url) {
        echo "<script type='text/javascript'>window.location='" . $url . "';</script>";
    }

    public function index() {
        ini_set("max_execution_time", 0);
        ini_set("memory_limit", "-1");

        $this->db->flush_cache();
        $this->load->model('capacitare_model');

        if ($this->input->post()) {
            $email = $this->input->get_post('email', TRUE);
            $telefone = $this->input->get_post('telefone', TRUE);

            if ($email == NULL || $telefone == NULL || strlen($telefone) != 11 || strpos($email, '@') === false || strpos($email, '.') === false) {
                $this->alert("Insira todas as informações corretamente.");
            } else {
                $resultado = $this->capacitare_model->insert_data($email, $telefone);

                if ($resultado > 0) {
                    redirect("http://192.168.0.1/login?username=free&password=free123&url=http://www.capacitare.com.br/");
                }
            }
        }

        $data['title'] = "CAPACITARE - HOTSPOT";
        $data['main'] = 'capacitare/index';
        $this->load->view('template_capacitare', $data);
    }

    public function principal() {
        ini_set("max_execution_time", 0);
        ini_set("memory_limit", "-1");

        if ($this->input->post() != false) {
            $evento = utf8_decode($this->input->post('eventos', TRUE));
            $acao = $this->input->post('acao', TRUE);

            if ($acao == 'SMS') {
                if ($evento == 0) {
                    $this->encaminha(base_url("index.php/capacitare/sms"));
                } else {
                    $this->encaminha(base_url("index.php/capacitare/sms?evento={$evento}"));
                }
            } elseif ($acao == 'EMAIL') {
                if ($evento == 0) {
                    $this->encaminha(base_url("index.php/capacitare/email"));
                } else {
                    $this->encaminha(base_url("index.php/capacitare/email?evento={$evento}"));
                }
            } else if ($acao == 'EVENTOS') {
                $this->encaminha(base_url("index.php/capacitare/eventos"));
            } else {
                $this->alert("Ação inválida!");
            }
        }

        $this->load->model('capacitare_model');
        $data['model'] = $this->capacitare_model;
        $data['eventos'] = $this->capacitare_model->get_eventos();
        $data['title'] = "CAPACITARE - PRINCIPAL";
        $data['main'] = 'capacitare/principal';
        $this->load->view('template_capacitare', $data);
    }

    public function eventos() {
        ini_set("max_execution_time", 0);
        ini_set("memory_limit", "-1");

        $this->load->model('capacitare_model');

        if ($this->input->post() != false) {
            $post_data = $this->input->post();

            $nome_evento = $post_data['nome'];
            $data_evento = strval(explode('/', $post_data['data'])[2] . '-' . explode('/', $post_data['data'])[1] . '-' . explode('/', $post_data['data'])[0]);
            $retorno = $this->capacitare_model->insert_evento(array('nome' => $nome_evento, 'data_evento' => $data_evento));
            redirect('capacitare/eventos');
        }

        $data['model'] = $this->capacitare_model;
        $data['eventos'] = $this->capacitare_model->get_eventos();
        $data['title'] = "CAPACITARE - EVENTOS";
        $data['main'] = 'capacitare/eventos';
        $this->load->view('template_capacitare', $data);
    }

    public function remove_evento() {
        ini_set("max_execution_time", 0);
        ini_set("memory_limit", "-1");

        $this->load->model('capacitare_model');

        $id_evento = $this->input->get('id');
        $this->capacitare_model->remove_evento($id_evento);
        redirect('capacitare/eventos');
    }

    public function set_evento_ativo() {
        ini_set("max_execution_time", 0);
        ini_set("memory_limit", "-1");

        $this->load->model('capacitare_model');

        $id_evento = $this->input->get('id');
        $this->capacitare_model->set_evento_ativo($id_evento);
        redirect('capacitare/eventos');
    }

    public function email() {
        ini_set("max_execution_time", 0);
        ini_set("memory_limit", "-1");

        $this->db->flush_cache();
        $this->load->model('capacitare_model');

        if ($this->input->post()) {
            $selecionados = $this->input->get_post('emails', TRUE);
            $mensagem = $this->input->get_post('mensagem', TRUE);
            $assunto = $this->input->get_post('assunto', TRUE);
            $selecionados = array_unique($selecionados);
            if ($mensagem != NULL && $assunto != NULL && $selecionados != NULL) {
                $from = 'capacitare.escola.seu.futuro@gmail.com';
                if (count($selecionados) > 0) {
                    foreach ($selecionados as $pessoa) {
                        $this->envia_email($from, $pessoa, nl2br($assunto, true), nl2br($mensagem, true));
                    }

                    $this->alert("Finalizado o envio dos emails.");
                } else {
                    $this->alert("Selecione algum destinatário.");
                }
            } else {
                $this->alert("Preencha todos os campos.");
            }
        }

        $evento = $this->input->get('evento', TRUE);
        if ($evento != NULL) {
            $data['capacitare_email'] = $this->capacitare_model->get_all_by_evento($evento);
        } else {
            $data['capacitare_email'] = $this->capacitare_model->get_all();
        }
        $data['model'] = $this->capacitare_model;
        $data['title'] = "CAPACITARE - EMAIL";
        $data['main'] = 'capacitare/email';
        $this->load->view('template_capacitare', $data);
    }

    public function sms() {
        ini_set("max_execution_time", 0);
        ini_set("memory_limit", "-1");

        $this->db->flush_cache();
        $this->load->model('capacitare_model');

        if ($this->input->post()) {
            $selecionados = $this->input->get_post('celulares', TRUE);
            $mensagem = $this->input->get_post('mensagem', TRUE);
            $selecionados = array_unique($selecionados);
            if ($mensagem != NULL && $selecionados != NULL) {
                if (count($selecionados) > 0) {
                    $this->envia_sms($selecionados, $mensagem);
                    $this->alert("Envio finalizado!");
                } else {
                    $this->alert("Selecione algum destinatário.");
                }
            } else {
                $this->alert("Preencha todos os campos.");
            }
        }

        $evento = $this->input->get('evento', TRUE);
        if ($evento != NULL) {
            $data['capacitare_sms'] = $this->capacitare_model->get_all_by_evento($evento);
        } else {
            $data['capacitare_sms'] = $this->capacitare_model->get_all();
        }
        $data['model'] = $this->capacitare_model;
        $data['title'] = "CAPACITARE - SMS";
        $data['main'] = 'capacitare/sms';
        $this->load->view('template_capacitare', $data);
    }

    public function inicializa_config_email_gmail_capacitare() {
        $config['protocol'] = 'smtp';
        $config['smtp_host'] = 'ssl://smtp.googlemail.com';
        $config['smtp_port'] = '465';
        $config['smtp_user'] = 'capacitare.escola.seu.futuro';
        $config['smtp_pass'] = 'Mxth0m45mx';
        $config['charset'] = 'utf-8';
        $config['mailtype'] = 'html';

        return $config;
    }

    public function envia_email($from, $destino, $assunto, $mensagem) {
        $this->load->library('email', $this->inicializa_config_email_gmail_capacitare());
        $this->email->set_newline("\r\n");

        $this->email->from($from, "CAPACITARE");
        $this->email->to($destino);
        $this->email->subject($assunto);
        $this->email->message($mensagem);
        $this->email->send();
    }

    public function envia_sms($telefones, $mensagem) {
        foreach ($telefones as $fone) {
            $credencial = URLEncode("884A92EF53E432265C4F1685F04653186D290E61"); //**Credencial da Conta 40 caracteres
            $token = URLEncode("0Cd4c7");
            $principal_user = URLEncode("THOMASMX");  //* SEU CODIGO PARA CONTROLE, não colocar e-mail
            $aux_user = URLEncode("CAPACITARE"); //* SEU CODIGO PARA CONTROLE, não colocar e-mail
            $mobile = URLEncode("55" . $fone); //* Numero do telefone  FORMATO: PAÍS+DDD(DOIS DÍGITOS)+NÚMERO
            $sendproj = URLEncode("N"); //* S = Envia o Remetente do SMS antes da mensagem , N = Não envia o Remetente do SMS
            $msg = mb_convert_encoding($mensagem, "UTF-8"); // Converte a mensagem para não ocorrer erros com caracteres semi-gráficos
            $msg = URLEncode($msg);
            $response = fopen("http://www.pw-api.com/sms/v_3_00/smspush/enviasms.aspx?CREDENCIAL=" . $credencial . "&TOKEN=" . $token . "&PRINCIPAL_USER=" . $principal_user . "&AUX_USER=" . $aux_user . "&MOBILE=" . $mobile . "&SEND_PROJECT=" . $sendproj . "&MESSAGE=" . $msg, "r");
            $status_code = fgets($response, 4);
        }
    }

    function mask($val, $mask) {
        $maskared = '';
        $k = 0;
        for ($i = 0; $i <= strlen($mask) - 1; $i++) {
            if ($mask[$i] == '#') {
                if (isset($val[$k]))
                    $maskared .= $val[$k++];
            }
            else {
                if (isset($mask[$i]))
                    $maskared .= $mask[$i];
            }
        }
        return $maskared;
    }

}
