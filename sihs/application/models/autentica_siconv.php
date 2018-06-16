<?php

class autentica_siconv {

    public function new_init_siconv_do_login($username, $password, $login, $cookie, $verbose = true) {
        $urlBase = "https://idp.convenios.gov.br/idp/";
        $urlLogin = "https://idp.convenios.gov.br/idp/login";

        $this->openPage($urlBase, $cookie);

        // Submete o formulÃ¡rio de login
        $login = $this->doLogin($urlLogin, $username, $password, $cookie);

        //Força uma chamada para verificar se o login foi feito
        $login = $this->openPage("https://www.convenios.gov.br/siconv/Principal/Principal.do", $cookie);

        // "Burla" a falta de javascript do cURL
        while (strstr($login, "Click the button below to continue.") !== false) {
            $formAction = $this->getFormAction($login);
            $inputName = $this->getInputName($login);
            $inputValue = $this->getInputValue($login);

            $login = $this->sendPost($formAction, array($inputName => $inputValue), $cookie);
        }

        // Verifica se algum erro aconteceu no login
        $error = $this->checkLogin($login, $verbose);
        if ($error) {
            return true;
        } else {
            return null;
        }
    }

    public function open_page_programas_livre($cookie) {
        $urlBase = "https://idp.convenios.gov.br/idp/";
        $urlLogin = "https://idp.convenios.gov.br/idp/login";
        $urlPortalAcessoLivre = "https://www.convenios.gov.br/portal/acessoLivre.html";
        $urlProgramas = "https://www.convenios.gov.br/siconv/ForwardAction.do?modulo=Principal&path=/MostraPrincipalConsultarProposta.do&Usr=guest&Pwd=guest";

        $page = $this->openPage($urlBase, $cookie);
        $page = $this->openPage($urlPortalAcessoLivre, $cookie);
        $page = $this->openPage($urlProgramas, $cookie);
    }

    public function new_obter_pagina($url, $login, $cookie) {
        // Abre a pÃ¡gina de consulta
        $query = $this->openPage($url, $cookie);

        // "Burla" a falta de javascript do cURL
        while (strstr($query, "Click the button below to continue.") !== false) {
            $formAction = $this->getFormAction($login);
            $inputName = $this->getInputName($login);
            $inputValue = $this->getInputValue($login);

            $query = $this->sendPost($formAction, array($inputName => $inputValue), $cookie);
        }

        return $query;
    }

    public function openPage($url, $cookie) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_COOKIESESSION, 0);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $page = curl_exec($ch);
        curl_close($ch);

        if ($page === false) {
            //die(json_encode(array("error" => "Could not open the form.")));
            $page = $this->openPage($url, $cookie);
        }

        return $page;
    }

    public function sendPost($url, array $data, $cookie) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_COOKIESESSION, 0);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        $page = curl_exec($ch);
        curl_close($ch);
        return $page;
    }

    public function doLogin($url, $username, $password, $cookie) {
        $data = array(
            "j_username" => $username,
            "j_password" => $password,
        );

        $page = $this->sendPost($url, $data, $cookie);
        if ($page === false) {
            die(json_encode(array("error" => "Could not submit the search form.")));
        }
        return $page;
    }

    public function checkLogin($content, $Verbose) {
        if (strstr($content, "<title>SICONV - Login</title>") !== false) {
            if ($Verbose) {
                CI_Controller::get_instance()->session->set_userdata('falha_login', 'S');
                $this->alert("Senha incorreta, verifique se a senha cadastrada confere com a do SICONV.");

                if (CI_Controller::get_instance()->session->userdata('nivel') == 1) {
                    $this->encaminha('escolher_proponente');
                } else {
                    $this->encaminha(base_url('index.php/controle_usuarios/atualiza_usuario?id=' . CI_Controller::get_instance()->session->userdata('id_usuario')));
                }
                die();
            } else {
                return true;
            }
        }
        if (strstr($content, "nova senha") !== false) {
            if ($Verbose) {
                CI_Controller::get_instance()->session->set_userdata('falha_login', 'S');
                //Seta um status para informar ao usuário que deve ser alterada a senha do SICONV
                CI_Controller::get_instance()->session->set_userdata('altera_senha_siconv', 'S');
                $this->alert("Senha desatualizada, é necessário login no SICONV para atualizá-la");

                if (CI_Controller::get_instance()->session->userdata('nivel') == 1) {
                    $this->encaminha('escolher_proponente');
                } else {
                    $this->encaminha(base_url('index.php/controle_usuarios/atualiza_usuario?id=' . CI_Controller::get_instance()->session->userdata('id_usuario')));
                }
                die();
            } else {
                return true;
            }
        }
        if (strstr($content, "<span id=\"outputText_dataHora\">Erro") !== false) {
            if ($Verbose) {
                return "Unknown error!";
            } else {
                return true;
            }
        }
    }

    public function getFormAction($html) {
        $pattern = '~ACTION="(.[^"]*)"~iU';
        $matches = array();
        if (preg_match($pattern, $html, $matches)) {
            return $matches[1];
        }
        return null;
    }

    public function getInputName($html) {
        $pattern = '~NAME="(.[^"]*)"~iU';
        $matches = array();
        if (preg_match($pattern, $html, $matches)) {
            return $matches[1];
        }
        return null;
    }

    public function getInputValue($html) {
        $pattern = '~VALUE="(.[^"]*)"~iU';
        $matches = array();
        if (preg_match($pattern, $html, $matches)) {
            return $matches[1];
        }
        return null;
    }

    public function getId($html) {
        $pattern = '~<tr class="numeroProposta".[^/]*/td>.[^>]*>(.[^&]*)&~iU';
        $matches = array();
        if (preg_match($pattern, $html, $matches)) {
            return $matches[1];
        }
        return null;
    }

    function encaminha($url) {
        echo "<script type='text/javascript'>window.location='" . $url . "';</script>";
        exit();
    }

    function alert($text) {
        echo "<script type='text/javascript'>alert('" . utf8_decode($text) . "');</script>";
    }

}
