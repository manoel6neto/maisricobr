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
        $familias = $this->Cadastro_Unico_Model->get_familias();
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
        $pessoa_detalhar = $this->input->get_post('idpessoa', TRUE);
        
        if ($familia != NULL) {
            $familia = $this->Cadastro_Unico_Model->get_familia_from_id($familia);
            $familia = $familia[0];
            $integrantes = $this->Cadastro_Unico_Model->get_integrantes_familia($familia->id);
            if ($pessoa_detalhar == NULL) {
                $pessoa_detalhar = $this->Cadastro_Unico_Model->get_responsavel_familia($familia->id);
                $pessoa_detalhar = $pessoa_detalhar[0];
            } else {
                $pessoa_detalhar = $this->Cadastro_Unico_Model->get_pessoa_from_id($pessoa_detalhar);
                $pessoa_detalhar = $pessoa_detalhar[0];
            }

            if ($familia != NULL && $integrantes != NULL && $pessoa_detalhar != NULL) {
                $renda_familia = $this->Cadastro_Unico_Model->get_renda_familia($familia->id);
                $consultas_pessoa_detalhar = $this->Cadastro_Unico_Model->get_consultas_pessoa($pessoa_detalhar->id);
//                $zoonoses_pessoa_detalhar = $this->Cadastro_Unico_Model->get_zoonoses_pessoa($pessoa_detalhar->id);
                $integrantes_formatado = array();
                foreach ($integrantes as $integ) {
                    array_push($integrantes_formatado, array('id' => $integ->id, 'nome' => $integ->nome, 'funcao' => $integ->funcao));
                }

                //enviando dados para a view
                $data['familia'] = $familia;
                $data['renda_familia'] = $renda_familia;
                $data['integrantes_familia'] = $integrantes_formatado;
                $data['pessoa_detalhar'] = $pessoa_detalhar;
                $data['consultas_pessoa_detalhar'] = $consultas_pessoa_detalhar;
//                $data['zoonoses_pessoa_detalhar'] = $zoonoses_pessoa_detalhar;
                $data['model_cad_unico'] = $this->Cadastro_Unico_Model;

                $this->load->view('cadu/detalhes', $data);
            }
        }
    }

}
