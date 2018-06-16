<?php

include 'application/libraries/PagSeguroLibrary/PagSeguroLibrary.php';

//header('Content-Type: text/html; charset=ISO-8859-1');

class compra_consulta extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function consultar() {
        ini_set('max_execution_time', 0);

        $this->load->model('compram');

        $cnpj = "";
        $estado = "";
        $resultado = "";
        $result = 0;
        $resultado_array = null;
        $mensagem = "";

        if ($this->input->post()) {
            //Emendas
            if (isset($_POST['conscnpj'])) {
                if ($this->input->get_post('cnpj', TRUE) != false) {
                    $cnpj = $this->input->get_post('cnpj', TRUE);
                    if ($this->compram->test_cnpj($cnpj)) {
                        $resultado_array = $this->compram->get_emendas_from_cnpj($cnpj);
                        $this->compram->insert_cnpj_consulta_temporaria($cnpj, 0);
                        if ($resultado_array != null) {
                            $result = 1;

                            //Montando exemplo do email
                            $name = $this->compram->get_nome_from_propoenente($cnpj);
                            $email_text = "";
                            $email_text = $email_text . "<spam>Programa: ***** " . substr($resultado_array[0]->codigo_programa, 5) . "</spam><br>";
//                        $email_text = $email_text . "<p>Emenda: " . $resultado_array[0]->emenda . "</p>";
                            $email_text = $email_text . "<spam>Valor: {$resultado_array[0]->valor } </spam><br>";
                            if ($resultado_array[0]->data_inicio_parlam != null) {
                                $email_text = $email_text . "<spam>Data Início Vigência: " . date("d/m/Y", strtotime($resultado_array[0]->data_inicio_parlam)) . "</spam><br>";
                                $email_text = $email_text . "<spam>Data Fim Vigência: " . date("d/m/Y", strtotime($resultado_array[0]->data_fim_parlam)) . "</spam><br>";
                            } else {
                                $email_text = $email_text . "<spam>Data Início Vigência: " . date("d/m/Y", strtotime($resultado_array[0]->data_inicio_benef)) . "</spam><br>";
                                $email_text = $email_text . "<spam>Data Fim Vigência: " . date("d/m/Y", strtotime($resultado_array[0]->data_fim_benef)) . "</spam><br>";
                            }
//                        $email_text = $email_text . "<p>-----------------</p>";

                            $button = '<button id="compracnpj" name="compracnpj" type="submit" formaction="comprar" style="text-align:center; width: 300px; height: 33px; position: relative; min-height: 21px; margin-top: 20px; margin-left: 33%;" class="s13label s13link">ACESSAR INFORMAÇÃO COMPLETA</button>';
                            $mensagem = "<div style='text-align: left; text-justify: auto; padding-top: 5px;'>
                                        <font size='5'><spam spam style='color: red; text-align: center; text-justify: auto; font-size: 16;'>Quantidade: " . count($resultado_array) . " Emenda(s).</spam></font><br>
                                        <font size='3'><spam>Valor total: R$ " . $this->soma_emendas($resultado_array) . "</spam></font><br>
                                        <font size='3'><spam>CNPJ: {$cnpj}</spam></font><br>
                                        <font size='3'><spam>Proponente: " . substr($name->nome, 0, 40) . "</spam></font><br>
                                        <br>
                                        <br>
                                        {$email_text}
                                        <br>
                                        <br>
                                        {$button}
                                        </div>";

                            $resultado = $mensagem;
                        } else {
                            $result = 0;
                            $resultado = "<div style='text-align: center; text-justify: auto;'><font size='5'><spam style='color: red; text-align: center; text-justify: auto; font-size: 18;'>Nenhuma emenda encontrada no momento</spam></font><br><spam>Obtenha monitoramento automático para sua entidade </spam><a href='http://www.info-convenios.com/#!combo-completo/c12a6'>clique aqui</a><br></div>";
                        }
                    } else {
                        $result = 0;
                        $resultado = "<div style='text-align: center; text-justify: auto;'><font size='5'><spam style='color: red; text-align: center; text-justify: auto; font-size: 18;'>Cnpj informado não consta na base do Siconv</spam></font></div>";
                    }
                } else {
                    $this->alert("Favor informar um cnpj !!");
                }
            }

            //Programa
            if (isset($_POST['consestado'])) {
                if ($this->input->get_post('estado', TRUE) != false) {
                    $estado = $this->input->get_post('estado', TRUE);
                    if ($this->compram->test_cnpj($estado)) {
                        $resultado_array = $this->compram->get_programas_from_cnpj($estado);
                        $this->compram->insert_cnpj_consulta_temporaria($estado, 1);
                        if ($resultado_array != null) {
                            $result = 1;

                            //Montando o email de resultado
                            $name = $this->compram->get_nome_from_propoenente($estado);
                            $email_text = "";
                            $email_text = $email_text . "<spam>Código Programa: " . $resultado_array[0]->codigo . "</spam><br>";
                            $email_text = $email_text . "<spam>Nome: " . $resultado_array[0]->nome . "</spam><br>";
                            $email_text = $email_text . "<spam>Orgão: " . $resultado_array[0]->orgao . "</spam><br>";
                            $email_text = $email_text . "<spam>Qualificação: {$resultado_array[0]->qualificacao} </spam><br>";
                            $email_text = $email_text . "<spam>Atende: {$resultado_array[0]->atende} </spam><br>";
                            $email_text = $email_text . "<spam>Data Início Vigência: " . date("d/m/Y", strtotime($resultado_array[0]->data_inicio)) . "</spam><br>";
                            $email_text = $email_text . "<spam>Data Fim Vigência: " . date("d/m/Y", strtotime($resultado_array[0]->data_fim)) . "</spam><br>";
//                        $email_text = $email_text . "<p>-----------------</p>";

                            $button = '<button id="compraestado" name="compraestado" type="submit" formaction="comprar" style="text-align:center; width: 300px; height: 33px; position: relative; min-height: 21px; margin-top: 20px; margin-left: 33%;" class="s13label s13link">ACESSAR INFORMAÇÃO COMPLETA</button>';
                            $mensagem = "<div style='text-align: left; text-justify: auto; padding-top: 5px;'>
                                        <font size='5'><spam spam style='color: red; text-align: center; text-justify: auto; font-size: 16;'>Quantidade: " . count($resultado_array) . " Programa(s) abertos.</spam></font><br>
                                        <font size='3'><spam>CNPJ: {$estado}</spam></font><br>
                                        <font size='3'><spam>Proponente: " . substr($name->nome, 0, 40) . "</spam></font><br>
                                        <br>
                                        {$email_text}
                                        <br>
                                        {$button}
                                        </div>";

                            $resultado = $mensagem;
                        } else {
                            $result = 0;
                            $resultado = "<div style='text-align: center; text-justify: auto;'><font size='5'><spam style='color: red; text-align: center; text-justify: auto; font-size: 18;'>Nenhum programa aberto encontrado no momento</spam></font><br><spam>Obtenha monitoramento automático para sua entidade  </spam><a href='http://www.info-convenios.com/#!combo-completo/c12a6'>clique aqui</a><br></div>";
                        }
                    } else {
                        $result = 0;
                        $resultado = "<div style='text-align: center; text-justify: auto;'><font size='5'><spam style='color: red; text-align: center; text-justify: auto; font-size: 18;'>Cnpj informado não consta na base do Siconv</spam></font></div>";
                    }
                } else {
                    $this->alert("Favor informar um cnpj !!");
                }
            }
        }

        $data['cnpj_usuario'] = $cnpj;
        $data['estado_usuario'] = $estado;
        $data['resultado'] = $resultado;
        $data['result'] = $result;
        $data['title'] = "Info Convênios - Consultas Individuais";
        $data['main'] = 'compra_consulta/index';
        $this->load->view('in/template_novo', $data);
    }

    public function comprar() {
        ini_set('max_execution_time', 0);

        $this->load->model('compram');

        $cnpj = null;
        $estado = null;
        $resultado_array = null;

        if ($this->input->post()) {
            //Botão de comprar para programas abertos pelo estado
            if (isset($_POST['compraestado']) || isset($_POST['compraestado2'])) {
                if ($this->input->get_post('estado', TRUE) != false) {
                    $estado = $this->input->get_post('estado', TRUE);
                } else {
                    $estado = $this->input->get_post('estado_value2', TRUE);
                }
            }

            //Botão de comprar para emendas pelo cnpj
            if (isset($_POST['compracnpj']) || isset($_POST['compracnpj2'])) {
                if ($this->input->get_post('cnpj', TRUE) != false) {
                    $cnpj = $this->input->get_post('cnpj', TRUE);
                } else {
                    $cnpj = $this->input->get_post('cnpj_value2', TRUE);
                }
            }

            //Botão finalizar compra
            if (isset($_POST['confirmarcompra'])) {
                if ($this->input->get_post('estado', TRUE) != false || $this->input->get_post('cnpj', TRUE) != false) {
                    $estado = $this->input->get_post('estado', TRUE);
                    $cnpj = $this->input->get_post('cnpj', TRUE);
                } else {
                    $estado = $this->input->get_post('estado_value2', TRUE);
                    $cnpj = $this->input->get_post('cnpj_value2', TRUE);
                }
                $cnpj_num = "";
                $nome_proponente = "";

                if ($cnpj != null && $cnpj != "") {
                    $resultado_array = $this->compram->get_emendas_from_cnpj($cnpj);
                    $cnpj_num = $cnpj;
                    $nome_proponente = $this->compram->get_nome_from_propoenente($cnpj);
                } else {
                    $resultado_array = $this->compram->get_programas_from_cnpj($estado);
                    $cnpj_num = $estado;
                    $nome_proponente = $this->compram->get_nome_from_propoenente($estado);
                }

                if ($resultado_array != null) {
                    $email = $this->input->get_post('email', TRUE);
                    if ($email != null && $email != "") {
                        //Carregando todos os valores
                        $resultado = "";
                        //Criando o email de resultado
                        foreach ($resultado_array as $res) {
                            if ($cnpj != null && $cnpj != "") {
                                $resultado = $resultado . "<p>Nome: {$res->nome}</p>";
                                $resultado = $resultado . "<p>Emenda: {$res->emenda}</p>";
                                $resultado = $resultado . "<p>Valor: {$res->valor}</p>";
                                $resultado = $resultado . "<p>Parlamentar: {$res->parlamentar}</p>";
                                if ($res->data_inicio_parlam != null) {
                                    $resultado = $resultado . "<p>Data Início Vigência: " . date("d/m/Y", strtotime($res->data_inicio_parlam)) . "</p>";
                                    $resultado = $resultado . "<p>Data Fim Vigência: " . date("d/m/Y", strtotime($res->data_fim_parlam)) . "</p>";
                                } else {
                                    $resultado = $resultado . "<p>Data Início Vigência: " . date("d/m/Y", strtotime($res->data_inicio_benef)) . "</p>";
                                    $resultado = $resultado . "<p>Data Fim Vigência: " . date("d/m/Y", strtotime($res->data_fim_benef)) . "</p>";
                                }
                                $resultado = $resultado . "<p>-----------------</p>";
                                $resultado = $resultado . "<br>";
                            } else {
                                $resultado = $resultado . "<p>Código Programa: {$res->codigo}</p>";
                                $resultado = $resultado . "<p>Nome: {$res->nome}</p>";
                                $resultado = $resultado . "<p>Orgão: {$res->orgao}</p>";
                                $resultado = $resultado . "<p>Qualificação: {$res->qualificacao}</p>";
                                $resultado = $resultado . "<p>Atende: {$res->atende}</p>";
                                $resultado = $resultado . "<p>Data Início Vigência: " . date("d/m/Y", strtotime($res->data_inicio)) . "</p>";
                                $resultado = $resultado . "<p>Data Fim Vigência: " . date("d/m/Y", strtotime($res->data_fim)) . "</p>";
                                $resultado = $resultado . "<p>-----------------</p>";
                                $resultado = $resultado . "<br>";
                            }
                        }

                        $urlCabecalho = "esicar.physisbrasil.com.br/esicar/layout/assets/images/cab_info_generico.gif";
                        $urlRodape = "esicar.physisbrasil.com.br/esicar/layout/assets/images/rod_info_generico.png";

                        $mensagem = "<html>
                                        <div align='center' style='background-color: #eeeeee;'>
                                            <p style='color: #ffffff;'>&nbsp;</p>
                                            <div align='center' style='background-color: #ffffff; width: 580px; color: #666666; border: solid #aaaaaa; border-width: 1px; border-radius: 1%;'>
                                                <img src='{$urlCabecalho}' style='width: 580px;'/>
                                                <div align='center' style='font-family: arial; margin-left: 20px;'>
                                                    <div align='left' style='width: 550px; margin-top: 50px;'>
                                                        <div style='margin-left: 20px; padding-right:1px; font-size: 14px; width: 500px;'>
                                                            <p><h4>Relatório avulso Info Convênios.</h4></p>
                                                            <p>CNPJ: {$cnpj_num}</p>
                                                            <p>Proponente: {$nome_proponente->nome}</p>
                                                            <br>
                                                            <p>{$resultado}</p>
                                                            <br>
                                                        </div>
                                                        <br>
                                                        <div style='margin-left: 20px; font-size: 14px;'>
                                                            <p>Atenciosamente,</p>
                                                            <p>Sistema Info Convênios</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <img src='{$urlRodape}' style='width: 520px; height: 70px;'/>
                                            </div>
                                            <br><br>
                                        </div>
                                    </html>";

                        if ($cnpj != null && $cnpj != "") {
                            $retorno = $this->gera_pagamento("Consulta Emendas", "10.00", "Cliente Info Convênios", $email);
                        } else {
                            $retorno = $this->gera_pagamento("Consulta Programas Abertos", "10.00", "Cliente Info Convênios", $email);
                        }

                        if (!is_null($retorno)) {
                            //Iniciando os inserts
                            $id_dados = $this->compram->insert_dados_info_compra_avulso($cnpj, $estado, $email, $mensagem);

                            //Interação com o pagseguro
                            $this->compram->insert_pagseguro_dados_info_compra_avulso($id_dados, $retorno['refCod'], date("Y-m-d"));

                            $this->encaminha($retorno['url']);
                        } else {
                            $this->alert("Falha ao realizar processo de compra. Favor entrar em contato com o suporte técnico.");
                        }
                    } else {
                        $this->alert("Por favor insira o endereço de email para envio das informações !!");
                    }
                }
            }
        }

        $data['cnpj_usuario'] = $cnpj;
        $data['estado_usuario'] = $estado;
        $data['cnpj_usuario2'] = $cnpj;
        $data['estado_usuario2'] = $estado;
        if ($cnpj != null) {
            $data['tipo_compra'] = "Consulta Emendas Disponíveis (" . date('d/m/Y') . ")";
            $data['cnpj_num'] = $cnpj;
            $data['nome_proponente'] = $this->compram->get_nome_from_propoenente($cnpj);
        } elseif ($estado != null) {
            $data['tipo_compra'] = "Consulta Programas Abertos (" . date('d/m/Y') . ")";
            $data['cnpj_num'] = $estado;
            $data['nome_proponente'] = $this->compram->get_nome_from_propoenente($estado);
        }
        $data['title'] = "Info Convênios - Consultas Individuais - Compra";
        $data['main'] = 'compra_consulta/compra';
        $this->load->view('in/template_novo', $data);
    }

    private function gera_pagamento($servico, $valor, $nome, $email) {
        $paymentRequest = new PagSeguroPaymentRequest();
        $paymentRequest->addItem('0001', $servico, 1, $valor);

        $paymentRequest->setCurrency("BRL");

        $paymentRequest->setSender($nome, $email);

        // Referenciando a transação do PagSeguro em seu sistema
        $paymentRequest->setReference("007" . rand(1000, 99999) . "2015");

        // URL para onde o comprador será redirecionado (GET) após o fluxo de pagamento
        $paymentRequest->setRedirectUrl(base_url('index.php/compra_consulta/retorno'));

        // URL para onde serão enviadas notificações (POST) indicando alterações no status da transação
        $paymentRequest->setNotificationURL(base_url('index.php/compra_consulta/notification'));

        try {
            $credentials = PagSeguroConfig::getAccountCredentials();
            $checkoutUrl = $paymentRequest->register($credentials);

            return array('url' => $checkoutUrl, 'refCod' => $paymentRequest->getReference());
        } catch (PagSeguroServiceException $e) {
            $this->alert($e->getCode() . " - " . $e->getMessage());
            $this->alert("Existem erros no cadastro, favor verifique os dados informados.");

            return null;
        }
    }

    public function notification() {
        ini_set('max_execution_time', 0);

        $this->load->model('compram');

        $notificationCode = null;
        $notificationType = null;

        if (count($_POST) > 0) {
            if (isset($_POST['notificationCode'])) {
                $notificationCode = $_POST['notificationCode'];
            }

            if (isset($_POST['notificationType'])) {
                $notificationType = $_POST['notificationType'];
            }

            //Processando a notificacao caso os valores sejam validos
            if ($notificationCode != null && $notificationType != null) {
                try {
                    $credentials = PagSeguroConfig::getAccountCredentials();
                    $url = "https://ws.pagseguro.uol.com.br/v3/transactions/notifications/" . $notificationCode . "?email=" . $credentials->getEmail() . "&token=" . $credentials->getToken();
                    $retorno = $this->openPage($url);

                    if ($retorno != null) {
                        if ($retorno != "") {
                            //Parse xml
                            try {
                                $xml = simplexml_load_string($retorno);
                                if ($xml != null) {
                                    $reference = $xml->reference;
                                    $status = $xml->status;
                                    if ($reference != null && $status != null) {
                                        if ($reference != "" && $status != "") {
                                            $this->db->flush_cache();
                                            $pagseguro_dados_obj = $this->compram->get_pagseguro_dados_info_consulta_avulsa_from_reference(trim($reference));
                                            if ($pagseguro_dados_obj != null) {
                                                $this->db->flush_cache();
                                                $dados_info_obj = $this->compram->get_dados_info_consulta_avulsa_from_id($pagseguro_dados_obj->id_dados_info_consulta_avulsa);
                                                if ($dados_info_obj != null) {
                                                    //Verifica o status para tomar a ação apropriada
                                                    if ($status == '3') { //Compra paga
                                                        //Envia email para o comprador da consulta
                                                        $this->envia_email("no-reply@info-convenios.com", $dados_info_obj->email, "manoel.carvalho.neto@gmail.com", "Resultado completo da sua consulta Info Convênios", $dados_info_obj->resultado);
                                                        //Atualiza data de confirmação do pagamento
                                                        $this->db->flush_cache();
                                                        $this->compram->update_data_pagamento($pagseguro_dados_obj->id);
                                                    } else if ($status == '7') { //Compra cancelada
                                                        $this->db->flush_cache();
                                                        $this->compram->delete_dados_info_avulsa_from_id($dados_info_obj->id);
                                                        $this->envia_email("contato@physisbrasil.com.br", "manoel.carvalho.neto@gmail.com", "manoel.carvalho.neto@live.com", "Compra cancelada", "Email: " . $dados_info_obj->email . "Referencia: " . $pagseguro_dados_obj->codigo_ref_compra);
                                                        $this->db->flush_cache();
                                                    }
                                                }
                                            }
                                        } else {
                                            $this->envia_email("contato@physisbrasil.com.br", "manoel.carvalho.neto@gmail.com", "manoel.carvalho.neto@live.com", "Erro dados em branco", "Erro ao ler dados do xml");
                                        }
                                    } else {
                                        $this->envia_email("contato@physisbrasil.com.br", "manoel.carvalho.neto@gmail.com", "manoel.carvalho.neto@live.com", "Erro dados nulos", "Erro ao ler dados do xml");
                                    }
                                } else {
                                    $this->envia_email("contato@physisbrasil.com.br", "manoel.carvalho.neto@gmail.com", "manoel.carvalho.neto@live.com", "Erro load xml", "Falha ao criar o objeto do xml");
                                }
                            } catch (Exception $ex) {
                                $this->envia_email("contato@physisbrasil.com.br", "manoel.carvalho.neto@gmail.com", "manoel.carvalho.neto@live.com", "Tratamento de mudança de status no pagseguro", trim("Exception: " . $ex->getMessage()));
                            }
                        }
                    }
                } catch (Exception $e) {
                    $this->envia_email("contato@physisbrasil.com.br", "manoel.carvalho.neto@gmail.com", "manoel.carvalho.neto@live.com", "Confirmação mudança de status pagseguro", trim("Exception: " . $e->getMessage()));
                }
            }
        }
    }

    public function retorno() {
        echo '<meta charset="UTF-8">';

        $this->alert("Compra realizada com sucesso. Após a confirmação do pagamento, o email com o resultado completo da sua consulta será enviado para o email informado. Um email com as informações da compra foi enviado para o seu email pelo pagseguro.");
        $this->encaminha("http://www.info-convenios.com/");
    }

    function alert($text) {
        echo "<script type='text/javascript'>alert('" . $text . "');</script>";
    }

    function encaminha($url) {
        echo "<script type='text/javascript'>window.location='" . $url . "';</script>";
    }

    public function openPage($url) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $page = curl_exec($ch);
        curl_close($ch);

        if ($page === false) {
            //die(json_encode(array("error" => "Could not open the form.")));
            $page = $this->openPage($url);
        }

        return $page;
    }

    function envia_email($origem, $destino, $copia, $assunto, $mensagem) {
        $this->load->model('usuariomodel');

        $this->load->library('email');

        $mensagem = $mensagem;

        $this->email->initialize($this->usuariomodel->inicializa_config_email($origem));

        $this->email->set_mailtype('html');
        $this->email->from($origem, "Info Convênios -- Info Convênios");
        $this->email->to($destino);
        if ($copia != null) {
            $this->email->cc($copia);
        }
        $this->email->subject($assunto);
        $this->email->message($mensagem);
        $this->email->send();
    }

    public function soma_emendas($array) {
        $total = doubleval(0);
        foreach ($array as $emenda) {
            $temp = trim($emenda->valor);
            $temp_double = trim(str_replace("R$", "", $temp));
            $temp_double = trim(str_replace(".", "", $temp_double));
            $temp_double = trim(str_replace(",", ".", $temp_double));
            $temp = doubleval($temp_double);
            $total = $total + $temp;
        }

        return number_format(doubleval($total), 2, ',', '.');
    }

}
