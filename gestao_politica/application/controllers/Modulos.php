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

    public function login_radarcidadao() {
        $uname = "manoel.carvalho.neto@gmail.com";
        $upswd = "manoelcarvalho321";
        $lang = "pt-BR";
        $option = "com_login";
        $task = "login";
        $return = "aW5kZXgucGhw";

        $url_get_key = "http://radarcidadao.com.br/administrator/index.php?option=com_users&task=user.login&lang=en"; //MOD REWRITE Disabled
        //GET return & key
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url_get_key);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_COOKIEJAR, dirname(__FILE__) . '/cookie.txt');
        curl_setopt($ch, CURLOPT_COOKIEFILE, dirname(__FILE__) . '/cookie.txt');
        curl_setopt($ch, CURLOPT_HEADER, 0);

        $results = curl_exec($ch);
        preg_match_all("(<input type=\"hidden\" name=\"return\" value=\"(.*)\" />)siU", $results, $matches1);
        preg_match_all("(<input type=\"hidden\" name=\"(.*)\" value=\"1\" />(.*)</fieldset>)iU", $results, $matches2);
        //var_dump($results);
        var_dump(str_replace('"', '', trim(explode('/', $matches1[1][0])[0])));
        var_dump(trim($matches2[1][0]));
        // POST
        $url_post = "http://radarcidadao.com.br/administrator/index.php?option=com_users&task=user.login&lang=en";
        $postdata = "username=" . urlencode($uname) . "&password=" . urlencode($upswd) . "&return=" . urlencode(str_replace('"', '', trim(explode('/', $matches1[1][0])[0]))) . "&" . urlencode(trim($matches2[1][0])) . "=1";
        curl_setopt($ch, CURLOPT_URL, $url_post);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
        $results1 = curl_exec($ch);
        //var_dump($results1);

        $url_data = "http://radarcidadao.com.br/administrator/index.php?option=com_users&lang=en"; //MOD REWRITE Disabled
        curl_setopt($ch, CURLOPT_URL, $url_data);
        $results2 = curl_exec($ch);
        $error = curl_error($ch);
        $errno = curl_errno($ch);
        //var_dump($error);

        curl_close($ch);
        //IF incorrect password
//        if (@preg_match('#<div id="system-message">(.*)<p>(.*)</p>#siU', $results2, $matches3)) {
//            @preg_match('#<p>(.*)</p>#i', $matches3[0], $matches4);
//            //var_dump($matches4[0]);
//        }
//
//        //IF Logged In
//        if (@preg_match('#<div class="login-greeting">(.*)</div>#siU', $results2, $matches5)) {
//            //var_dump($matches5[1]);
//        }

        redirect('http://radarcidadao.com.br/administrator/index.php?option=com_users&lang=pt-BR');
        //$this->encaminha('http://radarcidadao.com.br/administrator/index.php?option=com_users&lang=pt-BR');
    }

}
