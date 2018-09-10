<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class CadastroUnico extends CI_Controller {

    function __construct() {
        parent::__construct();
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
        $this->load->model('Cadastro_Unico_Model');

        //Carregando os dados dos usuários
        $cidade = $this->Cadastro_Unico_Model->get_dados_cidade(1);
        $familias = $this->Cadastro_Unico_Model->get_familias_completo();
        $json_mapa = json_encode($familias);

        $data['cidade'] = $cidade;
        $data['familias'] = $familias;
        $data['json_familias'] = $json_mapa;

        $this->load->view('cadu/index', $data);
    }

    public function detalhar_familia() {
        ini_set("max_execution_time", 0);
        ini_set("memory_limit", "-1");

        //Carregando modulos
        $this->load->model('Cadastro_Unico_Model');

        $familia = $this->input->get_post('id', TRUE);

        if ($familia != NULL) {
            $familia = $this->Cadastro_Unico_Model->get_familia_from_id($familia);
            $integrantes = $this->Cadastro_Unico_Model->get_integrantes_familia($familia->id);
            $responsavel_detalhar = $this->Cadastro_Unico_Model->get_responsavel_familia($familia->id);

            if ($this->input->get('idpessoa', TRUE) != FALSE) {
                $pessoa_detalhar = $this->Cadastro_Unico_Model->get_pessoa_from_id($this->input->get('idpessoa', TRUE));
            } else {
                $pessoa_detalhar = $responsavel_detalhar;
            }

            if ($familia != NULL && $integrantes != NULL && $pessoa_detalhar != NULL) {
                $pessoas_familia = $this->Cadastro_Unico_Model->get_pessoas_from_ids($integrantes);
                $pessoa_detalhar = $this->Cadastro_Unico_Model->get_pessoa_from_id($pessoa_detalhar->id);

                $renda_familia = floatval(0);
                foreach ($pessoas_familia as $pessoa) {
                    $renda_familia = $renda_familia + floatval($pessoa->renda);
                }
//                $endereco_familia = $this->Cadastro_Unico_Model->get_pessoa_completo_from_id($id);

                $integrantes_formatado = array();
                foreach ($integrantes as $integra) {
                    array_push($integrantes_formatado, array('id' => $integra->id, 'nome' => $integra->nome, 'descricao' => $integra->descricao , 'responsavel' => $integra->flag_responsavel));
                } 

//                var_dump($pessoa_detalhar); die();
                //enviando dados para a view
                $data['familia'] = $familia;
                $data['renda_familia'] = $renda_familia;
                $data['integrantes_familia'] = $integrantes_formatado;
                $data['pessoa_detalhar'] = $pessoa_detalhar;
                $data['model_cad_unico'] = $this->Cadastro_Unico_Model;

                $this->load->view('cadu/detalhes', $data);
            }
        }
    }

}
