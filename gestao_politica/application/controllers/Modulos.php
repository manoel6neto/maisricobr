<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Modulos extends CI_Controller {

    function __construct() {
        parent::__construct();
        if ($this->session->userdata('sessao') == FALSE) {
            redirect('/login');
        }
    }

    function encaminha($url) {
        echo "<script type='text/javascript'>window.location='" . $url . "';</script>";
        exit();
    }

    function alert($text) {
        echo "<script type='text/javascript'>alert('" . $text . "');</script>";
    }

    public function index() {
        ini_set("max_execution_time", 0);
        ini_set("memory_limit", "-1");

        //Carregando modulos
        $this->load->model('Usuario_Sistema_Model');

        //Lendo dados do usuário
        $array_sistemas_do_usuario = $this->Usuario_Sistema_Model->get_sistemas_usuario_from_id_usuario_sistema($this->session->userdata("sessao")['id_usuario_sistema']);

        //variaveis para manipular na view
        $data['usuario_cad_unico'] = $array_sistemas_do_usuario['usuario_cad_unico'];
        $data['usuario_saude'] = $array_sistemas_do_usuario['usuario_saude'];
        $data['usuario_educacao'] = $array_sistemas_do_usuario['usuario_educacao'];
        $data['usuario_ass_social'] = $array_sistemas_do_usuario['usuario_assistencia_social'];
        $data['usuario_cad_imobiliario'] = $array_sistemas_do_usuario['usuario_cad_imobiliario'];
        $data['usuario_esicar'] = $array_sistemas_do_usuario['usuario_convenios'];
        $data['usuario_terceiro_setor'] = $array_sistemas_do_usuario['usuario_terceiro_setor'];
        $data['usuario_comunicacao_social'] = $array_sistemas_do_usuario['usuario_comunicacao_social'];
        $data['usuario_aplicativo_cidadao'] = $array_sistemas_do_usuario['usuario_aplicativo_cidadao'];
        $data['usuario_politicas_publicas'] = $array_sistemas_do_usuario['usuario_politica_publica'];
        $data['usuario_pesquisa'] = $array_sistemas_do_usuario['usuario_pesquisa'];

        $this->load->view('modulos/index', $data);
    }

    public function openPage($url) {
        $cookie = tempnam("/tmp", "CURLCOOKIE" . rand());
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_COOKIESESSION, 0);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $page = curl_exec($ch);
        curl_close($ch);

        return $page;
    }

}
