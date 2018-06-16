<?php

class capacitare extends CI_Controller {

    function __construct() {
        parent::__construct();

        $this->cookie_file_path = tempnam("/tmp", "CURLCOOKIE" . rand());
        $this->login = null;
    }

    function alert($text) {
        echo "<script type='text/javascript'>alert('" . $text . "');</script>";
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
                $copia = NULL;
                if (count($selecionados) > 0) {
                    foreach ($selecionados as $pessoa) {
                        $this->envia_email($from, $pessoa, $copia, $assunto, $mensagem);
                    }

                    $this->alert("Finalizado o envio dos emails.");
                } else {
                    $this->alert("Selecione algum destinatário.");
                }
            } else {
                $this->alert("Preencha todos os campos.");
            }
        }

        $data['model'] = $this->capacitare_model;
        $data['capacitare_email'] = $this->capacitare_model->get_all();
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
                    $this->alert("Finalizado o envio dos sms's.");
                } else {
                    $this->alert("Selecione algum destinatário.");
                }
            } else {
                $this->alert("Preencha todos os campos.");
            }
        }

        $data['model'] = $this->capacitare_model;
        $data['capacitare_sms'] = $this->capacitare_model->get_all();
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

    public function envia_email($from, $destino, $copia, $assunto, $mensagem) {
        $this->load->library('email', $this->inicializa_config_email_gmail_capacitare());
        $this->email->set_newline("\r\n");

        $this->email->from($from, "CAPACITARE");
        $this->email->to($destino);
        if ($copia != NULL) {
            $this->email->cc($copia);
        }
        $this->email->subject($assunto);
        $this->email->message($mensagem);
        $this->email->send();
    }

//    public function envia_sms($telefones, $mensagem) {
//        foreach ($telefones as $fone) {
//            $credencial = URLEncode("218565391A8CE4A44253ABF179EBC1505B7A0A3F"); //**Credencial da Conta 40 caracteres
//            $principal = URLEncode("ESICAR");  //* SEU CODIGO PARA CONTROLE, não colocar e-mail
//            $auxuser = URLEncode("USER_ATIVACAO"); //* SEU CODIGO PARA CONTROLE, não colocar e-mail
//            $mobile = URLEncode("55" . $fone); //* Numero do telefone  FORMATO: PAÍS+DDD(DOIS DÍGITOS)+NÚMERO
//            $sendproj = URLEncode("N"); //* S = Envia o Remetente do SMS antes da mensagem , N = Não envia o Remetente do SMS
//            $msg = mb_convert_encoding($mensagem, "UTF-8"); // Converte a mensagem para não ocorrer erros com caracteres semi-gráficos
//            $msg = URLEncode($msg);
//            $response = fopen("http://www.mpgateway.com/v_2_00/smspush/enviasms.aspx?CREDENCIAL=" . $credencial . "&PRINCIPAL_USER=" . $principal . "&AUX_USER=" . $auxuser . "&MOBILE=" . $mobile . "&SEND_PROJECT=" . $sendproj . "&MESSAGE=" . $msg, "r");
//            $status_code = fgets($response, 4);
//        }
//    }
//    public function envia_sms($telefones, $mensagem) {
//        foreach ($telefones as $fone) {
//            $credencial = URLEncode("884A92EF53E432265C4F1685F04653186D290E61"); //**Credencial da Conta 40 caracteres
//            $token = URLEncode("09825f");
//            $principal_user = URLEncode("CAPACITARE");  //* SEU CODIGO PARA CONTROLE, não colocar e-mail
//            $aux_user = URLEncode("CAPACITARE"); //* SEU CODIGO PARA CONTROLE, não colocar e-mail
//            $mobile = URLEncode("55" . $fone); //* Numero do telefone  FORMATO: PAÍS+DDD(DOIS DÍGITOS)+NÚMERO
//            $sendproj = URLEncode("N"); //* S = Envia o Remetente do SMS antes da mensagem , N = Não envia o Remetente do SMS
//            $msg = mb_convert_encoding($mensagem, "UTF-8"); // Converte a mensagem para não ocorrer erros com caracteres semi-gráficos
//            $msg = URLEncode($msg);
//            $response = fopen("http://www.pw-api.com/v_3_00/sms/smspush/enviasms.aspx?CREDENCIAL=" . $credencial . "&TOKEN=" . $token  ."&PRINCIPAL_USER=" . $principal_user . "&AUX_USER=" . $aux_user . "&MOBILE=" . $mobile . "&SEND_PROJECT=" . $sendproj . "&MESSAGE=" . $msg, "r");
//            $status_code = fgets($response, 4);
//        }
//    }

    public function envia_sms($telefones, $mensagem) {
        foreach ($telefones as $fone) {
            $credencial = URLEncode("884A92EF53E432265C4F1685F04653186D290E61"); //**Credencial da Conta 40 caracteres
            $token = URLEncode("09825f");
            $principal_user = URLEncode("CAPACITARE");  //* SEU CODIGO PARA CONTROLE, não colocar e-mail
            $aux_user = URLEncode("CAPACITARE"); //* SEU CODIGO PARA CONTROLE, não colocar e-mail
            $mobile = URLEncode("55" . $fone); //* Numero do telefone  FORMATO: PAÍS+DDD(DOIS DÍGITOS)+NÚMERO
            $sendproj = URLEncode("N"); //* S = Envia o Remetente do SMS antes da mensagem , N = Não envia o Remetente do SMS
            $msg = mb_convert_encoding($mensagem, "UTF-8"); // Converte a mensagem para não ocorrer erros com caracteres semi-gráficos
            $msg = URLEncode($msg);
            $response = fopen("http://www.mpgateway.com/v_2_00/smspush/enviasms.aspx?CREDENCIAL=" . $credencial . "&TOKEN=" . $token . "&PRINCIPAL_USER=" . $principal_user . "&AUX_USER=" . $aux_user . "&MOBILE=" . $mobile . "&SEND_PROJECT=" . $sendproj . "&MESSAGE=" . $msg, "r");
            $status_code = fgets($response, 4);
            echo $status_code;
        }
    }

}
