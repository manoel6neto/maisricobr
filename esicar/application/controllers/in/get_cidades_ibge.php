<?php

class get_cidades_ibge extends CI_Controller {

    function __construct() {
        parent::__construct();

        $this->cookie_file_path = tempnam("/tmp", "CURLCOOKIE" . rand());
    }

    public function index() {
        ini_set("max_execution_time", 0);
        ini_set("memory_limit", "-1");

        $this->load->model('cidade_tag');

        // ---- Carregando a lista das cidades do banco de dados ---- //
        $cidades = NULL;
        $this->db->flush_cache();
        $this->db->select('id_cidade_tag,cod_ibge,cidade,estado');
        $this->db->where('atualizado', 0);
        $query = $this->db->get('cidade_tag');
        if ($query->num_rows > 0) {
            $cidades = $query->result();
            $count = 0;

            foreach ($cidades as $city) {
                //Controle para o limite de conexões do site
                $count = $count + 1;
                if ($count == 20) {
                    $count = 0;
                    sleep(120);
                }
                $url_panorama = $this->retorna_url_panorama($city->estado, $city->cidade);
                if ($url_panorama != NULL) {
                    //Gera a url do PIB
                    $url_pesquisa_pib = $this->retorna_url_pesquisa_pib($city->estado, $city->cidade);
                    if ($url_pesquisa_pib != NULL) {
                        // Inicializando o array de atualização e buscando os campos no documento
                        $options = array(
                            'id_cidade_tag' => $city->id_cidade_tag,
                            'gentilico' => NULL,
                            'area' => NULL,
                            'densidade' => NULL,
                            'populacao' => NULL,
                            'pib_corrente' => NULL,
                            'pib_per_capita' => NULL,
                            'ano_estimativa' => 2017,
                            'prefeito' => NULL,
                            'atualizado' => 1,
                            'cod_ibge_completo' => NULL
                        );

                        //Parse das páginas
                        $documento = NULL;
                        $this->cookie_file_path = tempnam("/tmp", "CURLCOOKIE" . rand());
                        $documento = $this->openPage($url_panorama, $this->cookie_file_path);
                        $documento = $this->removeSpaceSurplus($documento);
                        if ($documento != NULL && $documento != "" && strlen($documento) > 0) {
                            //Iniciando a leitura dos campos
                            //cod_ibge_completo
                            $cod_ibge_completo = explode('Código do Município', $documento);
                            $cod_ibge_completo = explode('class="lista__unidade">', trim($cod_ibge_completo[1]));
                            $cod_ibge_completo = explode('class="topo__valor">', trim($cod_ibge_completo[0]));
                            $cod_ibge_completo = explode('</p>', trim($cod_ibge_completo[1]));
                            if (!empty($cod_ibge_completo)) {
                                $cod_ibge_completo = trim($cod_ibge_completo[0]);
                                $options['cod_ibge_completo'] = $cod_ibge_completo;
                            }

                            //gentilico
                            $gentilico = explode('Gentílico', $documento);
                            $gentilico = explode('class="lista__unidade">', trim($gentilico[1]));
                            $gentilico = explode('class="topo__valor">', trim($gentilico[0]));
                            $gentilico = explode('</p>', trim($gentilico[1]));
                            if (!empty($gentilico)) {
                                $gentilico = trim($gentilico[0]);
                                $options['gentilico'] = $gentilico;
                            }

                            //area
                            $area = explode('Área da unidade territorial', $documento);
                            $area = explode('class="lista__unidade">', trim($area[1]));
                            $area = explode('class="lista__valor" colspan="2">', trim($area[0]));
                            $area = explode("<span", trim($area[1]));
                            if (!empty($area)) {
                                $area = trim($area[0]);
                                $options['area'] = $area;
                            }

                            //densidade
                            $densidade = explode('Densidade demográfica', $documento);
                            $densidade = explode('class="lista__unidade">', trim($densidade[1]));
                            $densidade = explode('class="lista__valor" colspan="2">', trim($densidade[0]));
                            $densidade = explode("<span", trim($densidade[1]));
                            if (!empty($densidade)) {
                                $densidade = trim($densidade[0]);
                                $options['densidade'] = $densidade;
                            }

                            //populacao
                            $populacao = explode('População estimada', $documento);
                            $populacao = explode('class="lista__unidade">', trim($populacao[1]));
                            $populacao = explode('class="lista__valor" colspan="2">', trim($populacao[0]));
                            $populacao = explode("<span", trim($populacao[1]));
                            if (!empty($populacao)) {
                                $populacao = trim($populacao[0]);
                                $options['populacao'] = str_replace('.', '', $populacao);
                            }

                            //pib_per_capita
                            $pib_per_capita = explode('PIB per capita', $documento);
                            $pib_per_capita = explode('class="lista__unidade">', trim($pib_per_capita[1]));
                            $pib_per_capita = explode('class="lista__valor" colspan="2">', trim($pib_per_capita[0]));
                            $pib_per_capita = explode("<span", trim($pib_per_capita[1]));
                            if (!empty($pib_per_capita)) {
                                $pib_per_capita = trim($pib_per_capita[0]);
                                $options['pib_per_capita'] = str_replace(',', '.', str_replace('.', '', $pib_per_capita));
                            }

                            //prefeito
                            $prefeito = explode('Prefeito', $documento);
                            $prefeito = explode('class="lista__unidade">', trim($prefeito[1]));
                            $prefeito = explode('class="topo__valor">', trim($prefeito[0]));
                            $prefeito = explode('</p>', trim($prefeito[1]));
                            if (!empty($prefeito)) {
                                $prefeito = trim($prefeito[0]);
                                $options['prefeito'] = $prefeito;
                            }

                            //pib_corrente
                            $documento = NULL;
                            $documento = $this->openPage($url_pesquisa_pib, $this->cookie_file_path);
                            $documento = $this->removeSpaceSurplus($documento);
                            if ($documento != NULL && $documento != "" && strlen($documento) > 0) {
                                $pib_corrente = explode('PIB a preços correntes', $documento);
                                $pib_corrente = explode('class="valor s">', trim($pib_corrente[1]));
                                $pib_corrente = explode('</td>', trim($pib_corrente[1]));
                                if (!empty($pib_corrente)) {
                                    $pib_corrente = trim($pib_corrente[0]);
                                    $options['pib_corrente'] = str_replace(',', '.', str_replace('.', '', $pib_corrente));
                                }
                            }

                            //Inserção dos dados
                            try {
                                $rows_changed = $this->cidade_tag->update_cidade_tag($options);
                                if ($rows_changed != 1) {
                                    echo "<h3 style='color: red;'>Erro ao atualizar a cidade: {$city->cidade}, no estado: {$city->estado}</h3>" . "<br>";
                                } else {
                                    echo "<h3 style='color: green;'>Atualizada com sucesso a cidade: {$city->cidade}, no estado: {$city->estado}</h3>" . "<br>";
                                }
                            } catch (Exception $e) {
                                echo "Exception na inserção/atualização: {$e->getMessage()}" . "<br>";
                            }
                        }
                    }
                }
            }
        }
    }

    public function remove_acentos_converte_to_lower($cidade) {
        $cidade = trim($cidade);
        $cidade = str_replace("á", "a", $cidade);
        $cidade = str_replace("é", "e", $cidade);
        $cidade = str_replace("í", "i", $cidade);
        $cidade = str_replace("ó", "o", $cidade);
        $cidade = str_replace("ú", "u", $cidade);
        $cidade = str_replace("â", "a", $cidade);
        $cidade = str_replace("ê", "e", $cidade);
        $cidade = str_replace("î", "i", $cidade);
        $cidade = str_replace("ô", "o", $cidade);
        $cidade = str_replace("û", "u", $cidade);
        $cidade = str_replace("ã", "a", $cidade);
        $cidade = str_replace("õ", "o", $cidade);
        $cidade = str_replace("à", "a", $cidade);
        $cidade = str_replace("è", "e", $cidade);
        $cidade = str_replace("ì", "i", $cidade);
        $cidade = str_replace("ò", "o", $cidade);
        $cidade = str_replace("ù", "u", $cidade);
        $cidade = str_replace("ç", "c", $cidade);
        $cidade = str_replace("ü", "u", $cidade);
        $cidade = str_replace("Á", "A", $cidade);
        $cidade = str_replace("Í", "I", $cidade);
        $cidade = str_replace("É", "E", $cidade);
        $cidade = str_replace("Â", "A", $cidade);
        $cidade = str_replace("Ó", "O", $cidade);
        $cidade = strtolower($cidade);

        return $cidade;
    }

    public function retorna_url_panorama($estado, $cidade) {
        // ----- URL Base das buscas dos panoramas ----- //
        $url_panorama = "https://cidades.ibge.gov.br/brasil/" . $this->get_sigla_estado($estado) . "/" . $this->get_cidade_formato_ibge($this->remove_acentos_converte_to_lower($cidade)) . "/panorama/";

        if ($url_panorama != NULL) {
            return $url_panorama;
        }

        return NULL;
    }

    public function retorna_url_pesquisa_pib($estado, $cidade) {
        // ----- URL Base das buscas das pesquisas do PIB ----- //
        $url_pesquisa_pib = "https://cidades.ibge.gov.br/brasil/" . $this->get_sigla_estado($estado) . "/" . $this->get_cidade_formato_ibge($this->remove_acentos_converte_to_lower($cidade)) . "/pesquisa/38/1/";

        if ($url_pesquisa_pib != NULL) {
            return $url_pesquisa_pib;
        }

        return NULL;
    }

    public function get_sigla_estado($estado) {
        $estados_com_sigla = array(
            "Goiás" => "go",
            "Minas Gerais" => "mg",
            "Pará" => "pa",
            "Ceará" => "ce",
            "Bahia" => "ba",
            "Paraná" => "pr",
            "Santa Catarina" => "sc",
            "Pernambuco" => "pe",
            "Tocantins" => "to",
            "Maranhão" => "ma",
            "Rio Grande do Norte" => "rn",
            "Piauí" => "pi",
            "Rio Grande do Sul" => "rs",
            "Mato Grosso" => "mt",
            "Acre" => "ac",
            "São Paulo" => "sp",
            "Espírito Santo" => "es",
            "Paraíba" => "pb",
            "Alagoas" => "al",
            "Mato Grosso do Sul" => "ms",
            "Rondônia" => "ro",
            "Roraima" => "rr",
            "Amazonas" => "am",
            "Amapá" => "ap",
            "Sergipe" => "se",
            "Rio de Janeiro" => "rj",
            "Distrito Federal" => "df"
        );

        return trim($estados_com_sigla[$estado]);
    }

    public function get_cidade_formato_ibge($cidade) {
        $cidade = str_replace(" ", "-", trim($cidade));
        if ($cidade != "belford roxo") {
            $cidade = str_replace("d-", "d", $cidade);
        }

        return trim($cidade);
    }

    public function get_codigo_cidade_from_codigo_completo_ibge($codigo) {
        return substr(trim($codigo), 2);
    }

    public function openPage($url, $cookie) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_COOKIESESSION, true);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
        curl_setopt($ch, CURLOPT_FAILONERROR, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $page = curl_exec($ch);
        curl_close($ch);
        if ($page === false) {
            $page = $this->openPage($url, $cookie);
        }

        return $page;
    }

    function removeSpaceSurplus($str) {
        return preg_replace("/\s+/", ' ', trim($str));
    }

    function getTextBetweenTags($string, $tag1, $tag2) {
        $pattern = "/$tag1([\w\W]*?)$tag2/";
        preg_match_all($pattern, $string, $matches);
        return $matches[1];
    }

}
